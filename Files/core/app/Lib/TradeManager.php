<?php

namespace App\Lib;

use App\Models\Order;
use App\Models\Trade;
use App\Models\Wallet;
use App\Constants\Status;
use App\Events\Trade as EventsTrade;
use App\Models\Transaction;
use Exception;

class TradeManager
{
    private $transactions    = [];
    private $trades          = [];
    private $tradeWithSymbol = [];

    public function trade()
    {

        $buySideOrders = Order::with('pair.coin', 'pair.market', 'user')->buySideOrder()->open()->orderBy('id', 'ASC')->get();

        $tradeSideSell = Status::SELL_SIDE_ORDER;
        $tradeSideBuy  = Status::BUY_SIDE_TRADE;

        foreach ($buySideOrders as $buySideOrder) {

            $pairId    = $buySideOrder->pair_id;
            $rate      = $buySideOrder->rate;

            $sellSideOrders = Order::with('pair', 'user')
                ->where('user_id', '!=', $buySideOrder->user_id)
                ->where('pair_id', $pairId)
                ->where('rate', $rate)
                ->sellSideOrder()
                ->open()
                ->whereDate('created_at', ">=", now()->subMinutes(10))
                ->orderBy('id', 'ASC')
                ->get();

            if ($sellSideOrders->count() <= 0) {
                continue;
            }

            foreach ($sellSideOrders as $sellSideOrder) {

                $buyAmount     = $buySideOrder->amount - $buySideOrder->filled_amount;
                $sellingAmount = $sellSideOrder->amount - $sellSideOrder->filled_amount;

                if ($sellingAmount <= 0 || $buyAmount <= 0) continue;

                $tradeAmount = $sellingAmount >= $buyAmount ? $buyAmount : $sellingAmount;

                $this->createTrade($tradeSideBuy, $buySideOrder, $rate, $tradeAmount, $buySideOrder->user_id);

                $buyerWallet  = Wallet::where('user_id', $buySideOrder->user_id)->where('currency_id', $buySideOrder->pair->coin->id)->spot()->first();
                $buySideOrder = $this->updateOrder($buySideOrder, $tradeAmount);

                $details = showAmount($tradeAmount,currencyFormat:false) . ' ' . $buySideOrder->pair->coin->symbol . ' Buy completed on pair ' . $buySideOrder->pair->symbol;
                $this->createTrx($buySideOrder, $buyerWallet, $tradeAmount, 'trade_buy', 0, $details, $tradeAmount, "Buy");

                $totalSellingAmount = $tradeAmount * $rate;
                $charge             = 0;

                if ($sellSideOrder->charge > 0) {
                    $sellingPercentage   = ($tradeAmount / $sellSideOrder->amount) * 100;
                    $charge              = ($sellSideOrder->charge / 100) * $sellingPercentage;
                }

                $this->createTrade($tradeSideSell, $sellSideOrder, $rate, $tradeAmount, $sellSideOrder->user_id, $charge);
                $sellerWallet = Wallet::where('user_id', $sellSideOrder->user_id)->where('currency_id', $sellSideOrder->pair->market->currency_id)->spot()->first();
                $this->updateOrder($sellSideOrder, $tradeAmount);

                $details = showAmount($tradeAmount,currencyFormat:false) . ' ' . $sellSideOrder->pair->coin->symbol . ' Sell completed on pair ' . $sellSideOrder->pair->symbol;
                $this->createTrx($sellSideOrder, $sellerWallet, $totalSellingAmount, 'trade_sell', $charge, $details, $tradeAmount, "Sell");
            }
        }

        Transaction::insert($this->transactions);
        Trade::insert($this->trades);
        try {
            event(new EventsTrade($this->tradeWithSymbol));
        } catch (Exception $ex) {
            $general                     = gs();
            $general->cron_error_message = $ex->getMessage();
            $general->save();
        }
    }

    private function createTrx($order, $wallet, $amount, $remark, $charge = 0, $details, $tradeAmount, $orderSide)
    {
        $wallet->balance += $amount;
        $wallet->save();

        $this->transactions[] = [
            'user_id'      => $order->user_id,
            'wallet_id'    => $wallet->id,
            'amount'       => $amount,
            'post_balance' => $wallet->balance,
            'charge'       => 0,
            'trx_type'     => '+',
            'details'      => $details,
            'trx'          => getTrx(),
            'remark'       => $remark,
            'created_at'    => now()
        ];

        if ($charge > 0) {

            $wallet->balance -= $charge;
            $wallet->save();

            $this->transactions[] = [
                'user_id'      => $order->user_id,
                'wallet_id'    => $wallet->id,
                'amount'       => $charge,
                'post_balance' => $wallet->balance,
                'charge'       => 0,
                'trx_type'     => '-',
                'details'      => "Charge for" . $details,
                'trx'          => getTrx(),
                'remark'       => $remark,
                'created_at'    => now()
            ];
        }

        notify($order->user, 'ORDER_COMPLETE', [
            'pair'                   => $order->pair->symbol,
            'amount'                 => showAmount($tradeAmount,currencyFormat:false),
            'total'                  => showAmount($order->total,currencyFormat:false),
            'rate'                   => showAmount($order->rate,currencyFormat:false),
            'price'                  => showAmount($order->price,currencyFormat:false),
            'coin_symbol'            => @$order->pair->coin->symbol,
            'order_side'             => $orderSide,
            'market_currency_symbol' => @$order->pair->market->currency->symbol,
            'market'                 => @$order->pair->market->name,
            'filled_amount'          => showAmount(@$order->filled_amount,currencyFormat:false),
            'filled_percentage'      => getAmount(@$order->filed_percentage),
        ]);

        if (gs('trade_commission')) {
            levelCommission($order->user, $tradeAmount, 'trade_commission', $order->trx, $order->coin_id);
        }
    }

    private function createTrade($tradeSide, $order, $rate, $amount, $traderId, $charge = 0)
    {
        $trade = [
            'trader_id'  => $traderId,
            'pair_id'    => $order->pair_id,
            'trade_side' => $tradeSide,
            'order_id'   => $order->id,
            'rate'       => $rate,
            'amount'     => $amount,
            'total'      => $rate * $amount,
            'charge'     => $charge,
            'created_at' => now()
        ];
        $this->tradeWithSymbol[@$order->pair->symbol][] = $trade;
        $this->trades[] = $trade;
    }

    private function updateOrder($order, $amount)
    {
        $filedAmount    = $order->filled_amount + $amount;
        $filePercentage = ($filedAmount / $order->amount) * 100;

        if ($filedAmount == $order->amount) {
            $order->status = Status::ORDER_COMPLETED;
        }
        $order->filled_amount    = $filedAmount;
        $order->filed_percentage = $filePercentage;
        $order->save();
        return $order;
    }
}
