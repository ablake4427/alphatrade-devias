<?php

namespace App\Http\Controllers\Admin;

use App\Lib\FormProcessor;
use App\Lib\RequiredConfig;
use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class WithdrawMethodController extends Controller
{
    public function methods()
    {
        $pageTitle = 'Withdrawal Methods';
        $methods   = WithdrawMethod::orderBy('name')->orderBy('id')->get();
        return view('admin.withdraw.index', compact('pageTitle', 'methods'));
    }

    public function create()
    {
        $pageTitle = 'New Withdrawal Method';
        return view('admin.withdraw.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validation = [
            'name'           => 'required',
            'currency'       => 'required',
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'min_limit'      => 'required|numeric|gt:fixed_charge',
            'max_limit'      => 'required|numeric|gt:min_limit',
            'instruction'    => 'required',
        ];


        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $validation          = array_merge($validation,$generatorValidation['rules']);
        $request->validate($validation,$generatorValidation['messages']);
        $generate = $formProcessor->generate('withdraw_method');


        $method                 = new WithdrawMethod();
        $method->name           = $request->name;
        $method->form_id        = @$generate->id ?? 0;
        $method->rate           = 1;
        $method->min_limit      = $request->min_limit;
        $method->max_limit      = $request->max_limit;
        $method->fixed_charge   = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->currency       = strtoupper($request->currency);
        $method->description    = $request->instruction;
        $method->save();

        RequiredConfig::configured('withdrawal_method');

        $notify[] = ['success', 'Withdrawal method added successfully'];
        return to_route('admin.withdraw.method.index')->withNotify($notify);
    }


    public function edit($id)
    {
        $pageTitle = 'Update Withdrawal Method';
        $method    = WithdrawMethod::with('form')->findOrFail($id);
        $form      = $method->form;
        return view('admin.withdraw.edit', compact('pageTitle', 'method','form'));
    }

    public function update(Request $request, $id)
    {
        $validation = [
            'name'           => 'required',
            'fixed_charge'   => 'required|numeric|gte:0',
            'min_limit'      => 'required|numeric|gt:fixed_charge',
            'max_limit'      => 'required|numeric|gt:min_limit',
            'percent_charge' => 'required|numeric|between:0,100',
            'currency'       => 'required',
            'instruction'    => 'required'
        ];

        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $validation          = array_merge($validation,$generatorValidation['rules']);
        $request->validate($validation,$generatorValidation['messages']);

        $method = WithdrawMethod::findOrFail($id);

        $generate               = $formProcessor->generate('withdraw_method',true,'id',$method->form_id);
        $method->form_id        = @$generate->id ?? 0;
        $method->name           = $request->name;
        $method->rate           = 1;
        $method->min_limit      = $request->min_limit;
        $method->max_limit      = $request->max_limit;
        $method->fixed_charge   = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->description    = $request->instruction;
        $method->currency       = $request->currency;
        $method->save();

        $notify[] = ['success', 'Withdrawal method updated successfully'];
        return back()->withNotify($notify);
    }


    public function status($id)
    {
        return WithdrawMethod::changeStatus($id);
    }

}
