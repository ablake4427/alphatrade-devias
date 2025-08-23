<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Lib\RequiredConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CurrencyDataProvider;

class CurrencyDataProviderController extends Controller
{
    public function index()
    {
        $pageTitle = 'Currency Data Provider';
        $providers = CurrencyDataProvider::orderBy('name')->get();
        return view('admin.currency_data_provider.index', compact('pageTitle', 'providers'));
    }

    public function update(Request $request, $id)
    {

        $provider       = CurrencyDataProvider::findOrFail($id);
        $validationRule = [];

        foreach ($provider->configuration as $key => $val) {
            $validationRule = array_merge($validationRule, [$key => 'required']);
        }
        $request->validate($validationRule);

        $configurations = json_decode(json_encode($provider->configuration), true);

        foreach ($configurations as $key => $value) {
            $configurations[$key]['value'] = $request->$key;
        }

        $provider->configuration = $configurations;
        $provider->save();

        RequiredConfig::configured('currency_data_provider');


        $notify[] = ['success', "Configuration updated successfully"];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return CurrencyDataProvider::changeStatus($id);
    }
    
    public function default($id)
    {
        CurrencyDataProvider::where('is_default', Status::YES)->update(['is_default' => Status::NO]);
        CurrencyDataProvider::where('id', $id)->update(['is_default' => Status::YES]);
        return returnBack("Default updated successfully", 'success');
    }
}
