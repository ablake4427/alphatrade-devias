<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{


    public function withdrawStore(Request $request)
    {
        $walletTypes = gs('wallet_types');

        $request->validate([
            'method_code' => 'required',
            'amount'      => 'required|numeric|gt:0',
            'currency'    => 'required',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
        ]);

        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            return returnBack('Requested withdraw currency not found');
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'withdraw', $walletTypes)) {
            return returnBack("Withdraw from $walletType wallet currently disabled.");
        }
        
        $user   = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->$walletType()->first();
        
        if (!$wallet) {
            return returnBack('Requested withdraw currency wallet not found');
        }
        
        $method   = WithdrawMethod::where('id', $request->method_code)->where('currency', $currency->symbol)->where('status', Status::ENABLE)->first();
        
        if (!$method) {
            return returnBack('Requested withdraw method not found');
        }
        
        if ($request->amount < $method->min_limit) {
            return returnBack('Your requested amount is smaller than minimum amount.');
        }
        if ($request->amount > $method->max_limit) {
            return returnBack('Your requested amount is larger than maximum amount.');
        }
        
        if ($request->amount > $wallet->balance) {
            return returnBack('You do not have sufficient wallet balance for withdraw.');
        }


        $charge      = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $request->amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->wallet_id    = $wallet->id;
        $withdraw->save();

        session()->put('wtrx', $withdraw->trx);
        return to_route('user.withdraw.preview');
    }
    public function withdrawPreview()
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';
        return view('Template::user.withdraw.preview', compact('pageTitle', 'withdraw'));
    }

    public function withdrawSubmit(Request $request)
    {
        $withdraw = Withdrawal::with('method', 'user', 'wallet')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();
        $method   = $withdraw->method;
        $wallet   = $withdraw->wallet;

        if ($method->status == Status::DISABLE) {
            abort(404);
        }

        $formData       = $method->form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $user = auth()->user();

        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }

        if ($withdraw->amount > $wallet->balance) {
            $notify[] = ['error', 'You do not have sufficient wallet balance for withdraw'];
            return back()->withNotify($notify)->withInput();
        }

        $withdraw->status               = Status::PAYMENT_PENDING;
        $withdraw->withdraw_information = $userData;
        $withdraw->save();

        $wallet->balance -= $withdraw->amount;
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = $withdraw->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->amount,currencyFormat:false) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->wallet_id    = $wallet->id;
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();


        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount,currencyFormat:false),
            'amount'          => showAmount($withdraw->amount,currencyFormat:false),
            'charge'          => showAmount($withdraw->charge,currencyFormat:false),
            'rate'            => showAmount($withdraw->rate,currencyFormat:false),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance,currencyFormat:false),
            'wallet_name'     => $wallet->name
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::searchable(['trx', 'withdrawCurrency:symbol'])->where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE)->with('method', 'wallet.currency')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.withdraw.log', compact('pageTitle', 'withdraws'));
    }
}
