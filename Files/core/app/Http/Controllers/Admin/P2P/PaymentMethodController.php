<?php

namespace App\Http\Controllers\Admin\P2P;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\P2P\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $pageTitle      = 'P2P Payment Method';
        $paymentMethods = PaymentMethod::searchable(['name'])->orderBy('name')->paginate(getPaginate());
        return view('admin.p2p.payment_method.index', compact('pageTitle', 'paymentMethods'));
    }

    public function create()
    {
        $pageTitle = 'Create P2P Payment Method';
        return view('admin.p2p.payment_method.create', compact('pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit P2P Payment Method';
        $gateway   = PaymentMethod::findOrFail($id);
        return view('admin.p2p.payment_method.create', compact('pageTitle', 'gateway'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name'                 => "required|max:255|unique:p2p_payment_methods,name,$id",
            'slug'                 => "required|max:255|unique:p2p_payment_methods,slug,$id",
            'supported_currency'   => "required|array|min:1",
            'supported_currency.*' => "required|exists:currencies,symbol",
            'form_generator'       => "required|array|min:1",
        ], [
            'form_generator.min'      => 'You need to provide at least one set of user data information.',
            'form_generator.required' => 'You need to provide at least one set of user data information.'
        ]);

        $formGenerator           = new FormProcessor();
        $formGeneratorValidation = @$formGenerator->generatorValidation();
        $request->validate($formGeneratorValidation['rules'], $formGeneratorValidation['messages']);

        if ($id) {
            $gateway  = PaymentMethod::findOrFail($id);
            $message  = "P2P Payment Method updated successfully";
            $userData = $request->form_generator ? $formGenerator->generate('p2p_payment_method') : null;
        } else {
            $gateway  = new PaymentMethod();
            $message  = "P2P Payment Method added successfully";
            $userData = $request->form_generator ? $formGenerator->generate('p2p_payment_method', true, 'act', $gateway->form_id) : null;
        }

        $gateway->name               = $request->name;
        $gateway->supported_currency = $request->supported_currency;
        $gateway->branding_color     = $request->branding_color;
        $gateway->slug               = slug($request->name);
        $gateway->form_id            = @$userData->id ?? 0;
        $gateway->save();

        return returnBack($message, "success");
    }

    public function status($id)
    {
        return PaymentMethod::changeStatus($id);
    }

    
}
