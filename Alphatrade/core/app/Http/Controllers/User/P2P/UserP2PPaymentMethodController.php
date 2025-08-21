<?php

namespace App\Http\Controllers\User\P2P;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\UserPaymentMethod;
use Illuminate\Http\Request;

class UserP2PPaymentMethodController extends Controller
{
    public function list()
    {
        $pageTitle      = "Payment Method";
        $paymentMethods = UserPaymentMethod::where('user_id', auth()->id())->latest('id')->with('paymentMethod')->paginate(getPaginate(10));
        return view('Template::user.p2p.payment_method.index', compact('pageTitle', 'paymentMethods'));
    }

    public function create()
    {
        $pageTitle = "New Payment P2P Method";
        $methods   = PaymentMethod::with('userData')->active()->orderBy('name')->get();
        return view('Template::user.p2p.payment_method.create', compact('pageTitle', 'methods'));
    }
    
    public function edit($id)
    {
        $pageTitle     = "Update Payment P2P Method";
        $methods       = PaymentMethod::with('userData')->active()->orderBy('name')->get();
        $paymentMethod = UserPaymentMethod::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        return view('Template::user.p2p.payment_method.create', compact('pageTitle', 'methods', 'paymentMethod'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'payment_method' => 'required|integer',
            'remark'         => 'nullable|string|max:255',
        ]);

        if (UserPaymentMethod::where('user_id', auth()->id())->where('payment_method_id', $request->payment_method)->exists() && !$id) {
            return $this->response("You have already added this payment method");
        }

        $paymentMethod = PaymentMethod::active()->where('id', $request->payment_method)->first();
        if (!$paymentMethod) {
            return $this->response("Payment method not found.");
        }
        $form          = Form::where('act', 'p2p_payment_method')->where('id', $paymentMethod->form_id)->first();
        $formData      = $form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $request->validate($validationRule);

        $userData = $formProcessor->processFormData($request, $formData);
        $user     = auth()->user();

        if ($id) {
            $gateway = UserPaymentMethod::where('user_id', $user->id)->findOrFail($id);
            $message = "P2P Payment Method updated successfully";
        } else {
            $gateway                    = new UserPaymentMethod();
            $gateway->user_id           = $user->id;
            $gateway->payment_method_id = $paymentMethod->id;
            $message                    = "P2P Payment Method added successfully";
        }
        $gateway->remark    = $request->remark;
        $gateway->user_data = $userData;
        $gateway->save();

        return $this->response($message, "success");
    }

    public function delete($id)
    {
        $paymentMethod = UserPaymentMethod::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $paymentMethod->delete();

        return returnBack("Payment method deleted successfully", "success");
    }

    public function response($message, $type = "error")
    {
        if (request()->ajax()) {
            return jsonResponse($message, $type == 'success');
        } else {
            return returnBack($message, $type);
        }
    }
}
