<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function methods()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();

        $currencies = Currency::active()->get();
        $notify[]   = 'Payment Methods';

        return responseSuccess('deposit_methods', $notify, [
            'methods'    => $gatewayCurrency,
            'currencies' => $currencies,
        ]);
    }

    public function depositInsert(Request $request)
    {
        $walletTypes = gs('wallet_types');

        $validator = Validator::make($request->all(), [
            'amount'      => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency'    => 'required',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            $notify[] = 'The requested deposit currency not found';
            return responseError('validation_error', $notify);
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'deposit', $walletTypes)) {
            $notify[] = "Deposit to $walletType wallet currently disabled.";
            return responseError('validation_error', $notify);
        }

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = 'Invalid gateway';
            return responseError('validation_error', $notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = 'Please follow deposit limit';
            return responseError('validation_error', $notify);
        }

        $charge      = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable     = $request->amount + $charge;
        $finalAmount = $payable;
        $user        = auth()->user();

        $wallet = Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->$walletType()->first();

        if (!$wallet) {
            $wallet              = new Wallet();
            $wallet->user_id     = $user->id;
            $wallet->currency_id = $currency->id;
            $wallet->wallet_type = $walletTypes->$walletType->type_value;
            $wallet->save();
        }

        $data                  = new Deposit();
        $data->from_api        = 1;
        $data->is_web          = $request->is_web ? 1 : 0;
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
        $data->success_url     = $request->success_url ?? route('user.deposit.history');
        $data->failed_url      = $request->failed_url ?? route('user.deposit.history');
        $data->trx             = getTrx();
        $data->save();

        $notify[] = 'Deposit inserted';
        if ($request->is_web && $data->gateway->code < 1000) {
            $dirName = $data->gateway->alias;
            $new     = 'App\\Http\\Controllers\\Gateway\\' . $dirName . '\\ProcessController';

            $gatewayData = $new::process($data);
            $gatewayData = json_decode($gatewayData);

            // for Stripe V3
            if (@$data->session) {
                $data->btc_wallet = $gatewayData->session->id;
                $data->save();
            }

            return responseSuccess('deposit_inserted', $notify, [
                'deposit'      => $data,
                'gateway_data' => $gatewayData,
            ]);
        }

        $data->load('gateway', 'gateway.form');

        return responseSuccess('deposit_inserted', $notify, [
            'deposit'      => $data,
            'redirect_url' => route('deposit.app.confirm', encrypt($data->id)),
        ]);
    }

    public function manualDepositConfirm(Request $request)
    {
        $track = $request->track;
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();

        if (!$data) {
            $notify[] = 'Invalid request';
            return responseError('invalid_request', $notify);
        }

        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amount, currencyFormat: false),
            'amount'          => showAmount($data->amount, currencyFormat: false),
            'charge'          => showAmount($data->charge, currencyFormat: false),
            'rate'            => showAmount($data->rate, currencyFormat: false),
            'trx'             => $data->trx,
        ]);

        $notify[] = ['You have deposit request has been taken'];
        return responseSuccess('deposit_request_taken', $notify);
    }

}
