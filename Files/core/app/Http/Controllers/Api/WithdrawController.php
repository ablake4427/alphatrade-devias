<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function withdrawMethod()
    {
        $withdrawMethod = WithdrawMethod::where('status', Status::ENABLE)->get();
        $currencies     = Currency::active()->get();

        $wallets = Wallet::where('user_id', auth()->id())
            ->with('currency:id,name,symbol,image')
            ->select('id', 'balance', 'currency_id')
            ->orderBy('balance', 'desc');

        $spotWallets    = (clone $wallets)->spot()->get();
        $fundingWallets = (clone $wallets)->funding()->get();

        $notify[] = 'Withdrawals methods';
        return responseSuccess('withdraw_methods', $notify, [
            'withdrawMethod'  => $withdrawMethod,
            'currencies'      => $currencies,
            'spot_wallets'    => $spotWallets,
            'funding_wallets' => $fundingWallets,
        ]);
    }

    public function withdrawStore(Request $request)
    {
        $walletTypes = gs('wallet_types');

        $validator = Validator::make($request->all(), [
            'method_code' => 'required',
            'amount'      => 'required|numeric',
            'currency'    => 'required',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            $notify[] = 'Requested withdraw currency not found';
            return responseError('validation_error', $notify);
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'withdraw', $walletTypes)) {
            $notify[] = "Withdraw from $walletType wallet currently disabled.";
            return responseError('validation_error', $notify);
        }

        $user   = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->$walletType()->first();

        if (!$wallet) {
            $notify[] = 'Requested withdraw currency wallet not found';
            return responseError('validation_error', $notify);
        }

        $method = WithdrawMethod::where('id', $request->method_code)->where('status', Status::ENABLE)->first();
        if (!$method) {
            $notify[] = 'Withdraw method not found.';
            return responseError('validation_error', $notify);
        }

        $user = auth()->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = 'Your requested amount is smaller than minimum amount.';
            return responseError('validation_error', $notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = 'Your requested amount is larger than maximum amount.';
            return responseError('validation_error', $notify);
        }

        if ($request->amount > $wallet->balance) {
            $notify[] = 'You do not have sufficient balance for withdraw.';
            return responseError('validation_error', $notify);
        }

        $charge      = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id; // wallet method ID
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

        $notify[] = 'Withdraw request created';

        return responseSuccess('withdraw_request_created', $notify, [
            'trx'           => $withdraw->trx,
            'withdraw_data' => $withdraw,
            'form'          => $method->form->form_data,
            'user'          => auth()->user(),
        ]);
    }

    public function withdrawSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trx' => 'required',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $withdraw = Withdrawal::with('method', 'user')->where('trx', $request->trx)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->first();
        if (!$withdraw) {
            $notify[] = 'Withdrawal request not found';
            return responseError('validation_error', $notify);
        }

        $method = $withdraw->method;

        if ($method->status == Status::DISABLE) {
            $notify[] = 'Withdraw method not found.';
            return responseError('validation_error', $notify);
        }

        $wallet   = $withdraw->wallet;
        $formData = $method->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $validator = Validator::make($request->all(), $validationRule);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $userData = $formProcessor->processFormData($request, $formData);

        $user = auth()->user();
        if ($user->ts) {
            if (!$request->authenticator_code) {
                $notify[] = 'Google authentication is required';
                return responseError('validation_error', $notify);
            }
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = 'Wrong verification code';
                return responseError('validation_error', $notify);
            }
        }

        if ($withdraw->amount > $wallet->balance) {
            $notify[] = 'Your request amount is larger then your current balance.';
            return responseError('validation_error', $notify);
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
        $transaction->details      = showAmount($withdraw->amount, currencyFormat: false) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
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
            'method_amount'   => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount'          => showAmount($withdraw->amount, currencyFormat: false),
            'charge'          => showAmount($withdraw->charge, currencyFormat: false),

            'rate'            => showAmount($withdraw->rate, currencyFormat: false),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance, currencyFormat: false),
        ]);

        $notify[] = 'Withdraw request sent successfully';
        return responseSuccess('withdraw_confirmed', $notify, [
            'withdraw_data' => $withdraw,
        ]);
    }

    public function withdrawLog()
    {
        $withdraws = Withdrawal::searchable(['trx', 'withdrawCurrency:symbol'])->where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE)->with('method', 'wallet')->orderBy('id', 'desc')->apiQuery();
        $notify[]  = 'Withdrawals';

        $withdraws->each(function ($withdrawal) {
            $withdrawal->makeVisible('withdraw_information');
        });

        return responseSuccess('withdrawals', $notify, [
            'withdrawals' => $withdraws,
            'path'        => getFilePath('verify'),
        ]);
    }
}
