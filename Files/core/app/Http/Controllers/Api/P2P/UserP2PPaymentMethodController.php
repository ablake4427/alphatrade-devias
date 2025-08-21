<?php

namespace App\Http\Controllers\Api\P2P;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\UserPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserP2PPaymentMethodController extends Controller
{
    public function list()
    {
        $paymentMethods = UserPaymentMethod::where('user_id', auth()->id())->latest('id')->with('paymentMethod')->apiQuery();

        $notify[] = 'User Payment Method';
        return responseSuccess('user_payment_method', $notify, [ 'payment_method' => $paymentMethods]);
    }

    public function create()
    {
        $methods = PaymentMethod::with('userData')->active()->orderBy('name')->get();

        $notify[] = 'New Payment P2P Method';
        return responseSuccess('payment_method', $notify, [
            'payment_method' => $methods,
        ]);        
    }

    public function edit($id)
    {
        $methods       = PaymentMethod::with('userData')->active()->orderBy('name')->get();
        $paymentMethod = UserPaymentMethod::where('user_id', auth()->id())->where('id', $id)->first();

        if (!$paymentMethod) {
            $notify[] = 'User payment method not found';
            return responseError('not_found', $notify);
        }

        $notify[] = 'Update payment P2P method';
        return responseSuccess('update_user_payment_method', $notify, [
            'methods' => $methods,
            'payment_method' => $paymentMethod,
        ]);        
    }

    public function save(Request $request, $id = 0)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|integer',
            'remark'         => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
           return responseError('validation_error', $validator->errors()->all());
        }

        if (UserPaymentMethod::where('user_id', auth()->id())->where('payment_method_id', $request->payment_method)->exists() && !$id) {
            $notify[] = 'You have already added this payment method';
            return responseError('already_added', $notify);
        }

        $paymentMethod = PaymentMethod::active()->where('id', $request->payment_method)->first();
        if (!$paymentMethod) {
            $notify[] = 'Payment method not found.';
            return responseError('not_found', $notify);
        }
        $form     = Form::where('act', 'p2p_payment_method')->where('id', $paymentMethod->form_id)->first();
        $formData = $form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $validator = Validator::make($request->all(), $validationRule);
        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $userData = $formProcessor->processFormData($request, $formData);
        $user     = auth()->user();

        if ($id) {
            $gateway = UserPaymentMethod::where('user_id', $user->id)->find($id);
            if(!$gateway){
                return responseError('not_found', 'Gateway not found');
            }
            $notify[] = "P2P Payment Method updated successfully";
        } else {
            $gateway                    = new UserPaymentMethod();
            $gateway->user_id           = $user->id;
            $gateway->payment_method_id = $paymentMethod->id;
            $notify[]                    = "P2P Payment Method added successfully";
        }
        $gateway->remark    = $request->remark;
        $gateway->user_data = $userData;
        $gateway->save();

        return responseSuccess('method_saved', $notify);
    }

    public function delete($id)
    {
        $paymentMethod = UserPaymentMethod::where('user_id', auth()->id())->where('id', $id)->first();
        if(!$paymentMethod){
            return responseError('not_found', 'Payment method not found');
        }
        $paymentMethod->delete();

        return responseSuccess('payment_method_deleted', 'Payment method deleted successfully');
    }

}
