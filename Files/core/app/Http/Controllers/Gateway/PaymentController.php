<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function depositInsert(Request $request)
    {
        $walletTypes = gs('wallet_types');

        $request->validate([
            'amount'      => 'required|numeric|gt:0',
            'gateway'     => 'required',
            'currency'    => 'required',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
        ]);

        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            return returnBack("The requested deposit currency not found.");
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'deposit', $walletTypes)) {
            return returnBack("Deposit to $walletType wallet currently disabled.");
        }

        $gate = GatewayCurrency::where('currency', $currency->symbol)->whereHas('method', function ($gate) {
            $gate->active();
        })->where('method_code', $request->gateway)->first();

        if (!$gate) {
            return returnBack("Invalid gateway");
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            return returnBack("Please follow deposit limit");
        }

        $charge      = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable     = $request->amount + $charge;
        $finalAmount = $payable;

        $user   = auth()->user();
        $wallet = Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->$walletType()->first();

        if (!$wallet) {
            $wallet              = new Wallet();
            $wallet->user_id     = $user->id;
            $wallet->currency_id = $currency->id;
            $wallet->wallet_type = $walletTypes->$walletType->type_value;
            $wallet->save();
        }

        $data                  = new Deposit();
        $data->wallet_id       = $wallet->id;
        $data->currency_id     = $wallet->currency_id;
        $data->user_id         = $user->id;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = 1;
        $data->final_amount    = $finalAmount;
        $data->btc_amount      = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->success_url = route('user.deposit.history');
        $data->failed_url = route('user.deposit.history');
        $data->save();

        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            abort(404);
        }
        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        session()->put('app', true);
        return to_route('user.deposit.confirm');
    }


    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view("Template::$data->view", compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit, $isManual = null)
    {

        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {

            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $wallet = Wallet::find($deposit->wallet_id);
            $wallet->balance += $deposit->amount;
            $wallet->save();

            $user = User::find($deposit->user_id);

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->wallet_id    = $wallet->id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $wallet->balance;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Deposit Via ' . $deposit->gatewayCurrency()->name;
            $transaction->trx          = $deposit->trx;
            $transaction->remark       = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = 'Deposit successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name'     => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amount, currencyFormat: false),
                'amount'          => showAmount($deposit->amount, currencyFormat: false),
                'charge'          => showAmount($deposit->charge, currencyFormat: false),
                'rate'            => showAmount($deposit->rate, currencyFormat: false),
                'trx'             => $deposit->trx,
                'post_balance'    => showAmount($wallet->balance, currencyFormat: false),
                'wallet_name'     => @$wallet->currency->symbol,
            ]);

            if (gs('deposit_commission')) {
                levelCommission($user, $deposit->amount, 'deposit_commission', $deposit->trx, $deposit->currency_id);
            }
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        if ($data->method_code > 999) {
            $pageTitle = 'Confirm Deposit';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view('Template::user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);


        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();


        $walletName = @$data->wallet->currency->symbol;

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username . " to wallet name " . $walletName;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amount, currencyFormat: false),
            'amount'          => showAmount($data->amount, currencyFormat: false),
            'charge'          => showAmount($data->charge, currencyFormat: false),
            'rate'            => showAmount($data->rate, currencyFormat: false),
            'trx'             => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withNotify($notify);
    }
}
