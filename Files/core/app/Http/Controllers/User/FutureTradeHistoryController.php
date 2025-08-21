<?php

namespace App\Http\Controllers\User;

use App\Models\FutureOrder;
use App\Models\FutureTrade;
use App\Models\FutureQueueOrder;
use App\Http\Controllers\Controller;

class FutureTradeHistoryController extends Controller
{
    public function __construct()
    {
        if(!gs('future_trade')){
            abort(404);
        }
    }

    public function openOrder()
    {
        $pageTitle = 'Open Orders';
        $orders    = $this->orderData('open');
        return view('Template::user.future.orders', compact('pageTitle', 'orders'));
    }

    public function orderHistory()
    {
        $pageTitle = 'Order History';
        $orders    = $this->orderData();
        return view('Template::user.future.orders', compact('pageTitle', 'orders'));
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
            });
        }

        $orders = $orders->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());
        return $orders;
    }

    public function openPosition()
    {
        $pageTitle = 'Open Positions';
        $positions = $this->positionData('open');
        return view('Template::user.future.positions', compact('pageTitle', 'positions'));
    }

    public function positionHistory()
    {
        $pageTitle = 'Open Positions';
        $positions = $this->positionData();
        return view('Template::user.future.positions', compact('pageTitle', 'positions'));
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
            })->withAvg(['futureQueueOrders as avg_closing' => function($queueOrder){
                $queueOrder->completed();
            }], 'rate');
        }

        $search = request()->search;
        if ($search) {
            $positions = $positions->whereHas('futureTradeConfig', function ($config) use ($search) {
                $config->whereHas('coinPair', function ($pair) use ($search) {
                    $pair->where('symbol', 'like', "%$search%");
                });
            });
        }

        $positions = $positions->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());
        return $positions;       
    }

    public function tradeHistory()
    {
        $pageTitle = 'Open trades';
        $trades = FutureTrade::where('user_id', auth()->id());
        
        $search = request()->search;
        if ($search) {
            $trades = $trades->whereHas('futureTradeConfig', function ($config) use ($search) {
                $config->whereHas('coinPair', function ($pair) use ($search) {
                    $pair->where('symbol', 'like', "%$search%");
                });
            });
        }
        
        $trades = $trades->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.future.trades', compact('pageTitle', 'trades'));
    }

}
