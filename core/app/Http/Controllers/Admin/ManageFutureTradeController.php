<?php

namespace App\Http\Controllers\Admin;

use App\Models\CoinPair;
use App\Constants\Status;
use App\Models\FutureOrder;
use App\Models\FutureTrade;
use Illuminate\Http\Request;
use App\Models\FutureQueueOrder;
use App\Models\FutureTradeConfig;
use App\Http\Controllers\Controller;

class ManageFutureTradeController extends Controller
{

    public function openOrder()
    {
        $pageTitle = 'Open Orders';
        $orders   = $this->orderData('open');
        return view('admin.future_trade.orders', compact('pageTitle', 'orders'));
    }

    public function orderHistory()
    {
        $pageTitle = 'Order History';
        $orders    = $this->orderData();
        return view('admin.future_trade.orders', compact('pageTitle', 'orders'));
    }

    private function orderData($scope = null)
    {
        $orders = FutureQueueOrder::query();

        if ($scope == 'open') {
            $orders->open();
        } else {
            $orders->where(function ($query) {
                $query->completed()
                    ->orWhere(function ($query) {
                        $query->canceled();
                    });
            });
        }

        $search = request()->search;
        if ($search) {
            $orders = $orders->whereHas('futureTradeConfig', function ($config) use ($search) {
                $config->whereHas('coinPair', function ($pair) use ($search) {
                    $pair->where('symbol', 'like', "%$search%");
                });
            })->orWhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        }

        return $orders->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function openPosition()
    {
        $pageTitle = 'Open Positions';
        $positions = $this->positionData('open');
        return view('admin.future_trade.positions', compact('pageTitle', 'positions'));
    }

    public function positionHistory()
    {
        $pageTitle = 'Position History';
        $positions = $this->positionData();
        return view('admin.future_trade.positions', compact('pageTitle', 'positions'));
    }

    private function positionData($scope = null)
    {
        $positions = FutureOrder::query();

        if ($scope == 'open') {
            $positions->positioned();
        } else {
            $positions->where(function ($query) {
                $query->liquidated()
                    ->orWhere(function ($query) {
                        $query->closed();
                    });
            });
        }

        $search = request()->search;

        if ($search) {
            $positions = $positions->whereHas('futureTradeConfig', function ($config) use ($search) {
                $config->whereHas('coinPair', function ($pair) use ($search) {
                    $pair->where('symbol', 'like', "%$search%");
                });
            })->orWhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        }

        $positions = $positions->orderBy('id', 'desc')->paginate(getPaginate());
        return $positions;       
    }

    public function tradeHistory()
    {
        $pageTitle = 'Trade History';
        $trades    = FutureTrade::query();

        $search = request()->search;
        if ($search) {
            $trades = $trades->whereHas('futureTradeConfig', function ($config) use ($search) {
                $config->whereHas('coinPair', function ($pair) use ($search) {
                    $pair->where('symbol', 'like', "%$search%");
                });
            })->orWhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        }
        
        $trades = $trades->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.future_trade.trades', compact('pageTitle', 'trades'));
    }


    public function pairs()
    {
        $pageTitle          = 'Future Trade Pairs';
        $futureTradeConfigs = FutureTradeConfig::with('coinPair')->paginate(getPaginate());

        return view('admin.future_trade.pairs', compact('pageTitle', 'futureTradeConfigs'));
    }

    public function create()
    {
        $pageTitle = 'Add New Future Trade Pair';
        $coinPairs = CoinPair::active()->whereDoesntHave('futureTradeConfig')->get();
        return view('admin.future_trade.create', compact('pageTitle', 'coinPairs'));
    }

    public function edit($id)
    {
        $futureTradeConfig = FutureTradeConfig::findOrFail($id);
        $pageTitle         = 'Update Future Trade Pair - ' . $futureTradeConfig->coinPair->symbol;
        return view('admin.future_trade.create', compact('pageTitle', 'futureTradeConfig'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'coin_pair_id'            => $id ? 'nullable' : 'required|integer',
            'min_buy_amount'          => 'required|numeric|gt:0',
            'max_buy_amount'          => 'required|numeric|gt:0',
            'min_sell_amount'         => 'required|numeric|gt:0',
            'max_sell_amount'         => 'required|numeric|gt:0',
            'buy_charge'              => 'required|numeric|min:0',
            'sell_charge'             => 'required|numeric|min:0',
            'leverage'                => 'required|integer|min:1',
            'maintenance_margin_rate' => 'required|numeric|min:0',
        ]);

        $initialMarginRate = 1 / $request->leverage;
        if ($request->maintenance_margin_rate >= $initialMarginRate) {
            $notify[] = ['error', 'Maintenance margin rate must be less than initial margin rate '. showAmount($initialMarginRate, currencyFormat:false). ' when set leverage '. $request->leverage];
            return back()->withNotify($notify)->withInput();
        }

        if ($id) {
            $futureTradeConfig = FutureTradeConfig::findOrFail($id);
            $notify[]          = ['success', 'Future trade pair updated successfully.'];
        } else {
            $coinPair = FutureTradeConfig::where('pair_id', $request->coin_pair_id)->exists();
            if ($coinPair) {
                $notify[] = ['error', 'Coin pair already configured for future trade.'];
                return back()->withNotify($notify)->withInput();
            }
            $futureTradeConfig          = new FutureTradeConfig();
            $futureTradeConfig->pair_id = $request->coin_pair_id;
            $notify[]                   = ['success', 'Future trade pair created successfully.'];
        }

        if ($request->is_default) {
            FutureTradeConfig::where('id', '!=', $id)->where('is_default', Status::YES)->update(['is_default' => Status::NO]);
            $futureTradeConfig->is_default = Status::YES;
        }

        $futureTradeConfig->min_buy_amount          = $request->min_buy_amount;
        $futureTradeConfig->max_buy_amount          = $request->max_buy_amount;
        $futureTradeConfig->min_sell_amount         = $request->min_sell_amount;
        $futureTradeConfig->max_sell_amount         = $request->max_sell_amount;
        $futureTradeConfig->buy_charge              = $request->buy_charge;
        $futureTradeConfig->sell_charge             = $request->sell_charge;
        $futureTradeConfig->leverage                = $request->leverage;
        $futureTradeConfig->maintenance_margin_rate = $request->maintenance_margin_rate;
        $futureTradeConfig->save();

        return back()->withNotify($notify);
    }

    public function changeStatus($id)
    {
        return FutureTradeConfig::changeStatus($id);
    }

}
