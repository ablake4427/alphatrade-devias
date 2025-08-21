<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Trade;


class OrderController extends Controller
{
    public function open()
    {
        $pageTitle = "Open Order";
        $orders    = $this->orderData('open');
        return view('admin.order.list', compact('pageTitle', 'orders'));
    }
    public function history()
    {
        $pageTitle = "Order History";
        $orders    = $this->orderData();
        return view('admin.order.list', compact('pageTitle', 'orders'));
    }

    protected function orderData($scope = null)
    {
        $query = Order::filter(['order_side', 'user_id', 'status'])->searchable(['pair:symbol', 'pair.coin:symbol', 'pair.market.currency:symbol'])->with('pair', 'pair.coin', 'pair.market.currency')->orderBy('id', 'desc');
        if ($scope) {
            $query->$scope();
        }
        return $query->paginate(getPaginate());
    }

    public function tradeHistory()
    {
        $pageTitle = "Trade History";
        $trades    = Trade::filter(['trade_side', 'trader_id'])->searchable(['order.pair:symbol', 'order.pair.coin:symbol', 'order.pair.market.currency:symbol'])->with('order.pair', 'order.pair.coin', 'order.pair.market.currency')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.order.trade_history', compact('pageTitle', 'trades'));
    }
}
