<?php

namespace App\Http\Controllers\Admin;

use App\Models\Market;
use App\Models\Currency;
use App\Lib\RequiredConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
    public function list()
    {
        $pageTitle  = "Market List";
        $markets    = Market::with('currency')->searchable(['name', 'currency:name,symbol'])->orderBy('name', 'ASC')->paginate(getPaginate());
        return view('admin.market.list', compact('pageTitle', 'markets'));
    }

    public function save(Request $request, $id = 0)
    {
        $validationRule = $id ? 'nullable' : 'required';
        
        $request->validate([
            'name'     => "required|max:255|unique:markets,name,$id",
            'currency' => "$validationRule|integer",
        ]);

        if(!$id){
            $currency = Currency::active()->where('id', $request->currency)->first();
            if (!$currency) return returnBack("Currency not found");
            $hasAnotherMarketWithCurrency = Market::where('id', '!=', $id)->where('currency_id', $currency->id)->exists();
            if ($hasAnotherMarketWithCurrency) return returnBack("Can't create one more market with the same currency.");
            
            $market              = new Market();
            $message             = "Market added successfully";
            $market->currency_id = $request->currency;
        }else{
            $market  = Market::findOrFail($id);
            $message = "Market updated successfully";
        }

        $market->name        = $request->name;
        $market->save();

        RequiredConfig::configured('add_market');
        
        return returnBack($message,'success');
    }
    public function status($id)
    {
        return Market::changeStatus($id);
    }
}
