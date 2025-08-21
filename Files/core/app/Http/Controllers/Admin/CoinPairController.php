<?php

namespace App\Http\Controllers\Admin;

use App\Models\Market;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Constants\Status;
use App\Models\MarketData;
use App\Lib\RequiredConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoinPairController extends Controller {
    public function list() {

        $pageTitle = "Coin Pair";
        $pairs     = CoinPair::with('market.currency', 'coin')
            ->searchable(['coin:name,symbol', 'market.currency:name,symbol'])
            ->select('coin_pairs.*')
            ->leftJoin('orders', function ($q) {
                $q->on('coin_pairs.id', 'orders.pair_id')->where('orders.status', Status::ORDER_OPEN);
            })
            ->selectRaw('SUM(CASE WHEN orders.order_side = ? THEN ((orders.amount-orders.filled_amount)*orders.rate) ELSE 0 END) as buy_liquidity', [Status::BUY_SIDE_ORDER])
            ->selectRaw('SUM(CASE WHEN orders.order_side = ?  THEN ((orders.amount-orders.filled_amount)*orders.rate) ELSE 0 END) as sell_liquidity', [Status::SELL_SIDE_ORDER])
            ->orderBy('is_default', 'DESC')
            ->filter(['market_id'])
            ->groupBy('coin_pairs.id')
            ->paginate(getPaginate());

        return view('admin.coin_pair.list', compact('pageTitle', 'pairs'));
    }
    public function create() {
        $pageTitle = "New Coin Pair";
        $markets   = Market::with('currency')->active()->orderBy('name')->get();
        return view('admin.coin_pair.create', compact('pageTitle', 'markets'));
    }

    public function edit($id) {
        $pageTitle = "Edit Coin Pair";
        $coinPair  = CoinPair::where('id', $id)->firstOrFail();
        $markets   = Market::with('currency')->active()->get();
        return view('admin.coin_pair.create', compact('pageTitle', 'markets', 'coinPair'));
    }
    public function save(Request $request, $id = 0) {
        $isRequired = $id ? "nullable" : 'required';

        $rules = [
            'market'                  => "$isRequired|integer",
            'coin'                    => "$isRequired|integer",
            'minimum_buy_amount'      => 'required|numeric|gt:0',
            'maximum_buy_amount'      => ['required', 'numeric', function ($attribute, $value, $fail) use ($request) {
                if ($value <= 0 && $value != -1) {
                    return $fail("Only -1 for no maximum buy limit.");
                }

                if ($value < $request->minimum_buy_amount && $value != -1) {
                    return $fail("The maximum buy amount must be greater then minimum buy amount");
                }

            }],
            'minimum_sell_amount'     => 'required|numeric|gt:0',
            'maximum_sell_amount'     => ['required', 'numeric', function ($attribute, $value, $fail) use ($request) {
                if ($value <= 0 && $value != -1) {
                    return $fail("Only -1 for no maximum sell limit.");
                }

                if ($value < $request->minimum_sell_amount && $value != -1) {
                    return $fail("The maximum sell amount must be greater then minimum sell amount");
                }

            }],
            'percent_charge_for_buy'  => 'required|numeric|gte:0|lt:100',
            'percent_charge_for_sell' => 'required|numeric|gte:0',
            'listed_market_name'      => 'required',
            'binary_trade_profit'     => 'nullable|numeric|gte:0',
            'type'                    => 'required|integer|in:1,2,3',
            
        ];

        if (in_array($request->type, [2, 3])) {
            $rules = array_merge($rules, [
            'min_binary_trade_amount' => 'required|numeric|gt:0',
            'max_binary_trade_amount' => 'required|numeric|gte:min_binary_trade_amount',
            'binary_increment_amount' => 'required|numeric|gte:0',
            'binary_trade_duration'   => 'required|array|min:1',
            'binary_trade_duration.*' => 'required|integer|gt:0',
            ]);
        }

        $request->validate($rules, [
            'binary_trade_duration.*' => 'Duration must be an integer',
        ]);
     
        if (!$id) {
            $market = Market::active()->where('id', $request->market)->whereHas('currency', function ($q) {
                $q->active();
            })->active()->first();

            if (!$market) {
                return returnBack("Selected market is invalid");
            }

            $coin = Currency::where('id', $request->coin)->active()->crypto()->first();
            if (!$coin) {
                return returnBack("Selected coin is invalid.", 'error');
            }

            if (strtoupper($market->currency->symbol) == strtoupper($coin->symbol)) {
                return returnBack("Market currency & coin can't be the same.", "error", true);
            }

            $symbol        = $coin->symbol . '_' . $market->currency->symbol;
            $alreadyExists = CoinPair::where('id', '!=', $id)->where('symbol', $symbol)->exists();

            if ($alreadyExists) {
                return returnBack("Can't make one more coin pair with the same currency & market", "error", true);
            }

            $message             = "CoinPair saved successfully";
            $coinPair            = new CoinPair();
            $coinPair->market_id = $request->market;
            $coinPair->coin_id   = $coin->id;
            $coinPair->symbol    = $symbol;
        } else {
            $message  = "CoinPair updated successfully";
            $coinPair = CoinPair::findOrFail($id);
        }


        $coinPair->minimum_buy_amount      = $request->minimum_buy_amount;
        $coinPair->maximum_buy_amount      = $request->maximum_buy_amount;
        $coinPair->minimum_sell_amount     = $request->minimum_sell_amount;
        $coinPair->maximum_sell_amount     = $request->maximum_sell_amount;
        $coinPair->percent_charge_for_sell = $request->percent_charge_for_sell;
        $coinPair->percent_charge_for_buy  = $request->percent_charge_for_buy;
        $coinPair->listed_market_name      = strtoupper($request->listed_market_name);

        if($request->type == 2 || $request->type == 3){
            $coinPair->binary_trade_profit     = $request->binary_trade_profit;
            $coinPair->binary_trade_duration   = $request->binary_trade_duration;
            $coinPair->min_binary_trade_amount = $request->min_binary_trade_amount;
            $coinPair->max_binary_trade_amount = $request->max_binary_trade_amount;
            $coinPair->binary_increment_amount = $request->binary_increment_amount;
        }

        $coinPair->type                    = $request->type;

        if ($request->is_default) {
            CoinPair::where('id', '!=', $id)->where('is_default', Status::YES)->update(['is_default' => Status::NO]);
            $coinPair->is_default = Status::YES;
        } else {
            $defaultPair = CoinPair::where('id', '!=', $id)->where('is_default', Status::YES)->exists();
            if (!$defaultPair) {
                return returnBack("Default coin pair is required.", "error", true);
            }

            $coinPair->is_default = Status::NO;
        }

        $coinPair->save();

        $marketData = MarketData::where('pair_id', $coinPair->id)->where('currency_id', 0)->first();

        if (!$marketData) {
            $coinPairData          = new MarketData();
            $coinPairData->pair_id = $coinPair->id;
            $coinPairData->symbol  = $coin->symbol;
            $coinPairData->save();
        }

        RequiredConfig::configured('add_coin_pair');

        return returnBack($message, 'success');
    }

    public function status($id) {
        return CoinPair::changeStatus($id);
    }
}
