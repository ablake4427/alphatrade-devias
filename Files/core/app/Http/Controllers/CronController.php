<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Controllers\User\BinaryTradeOrderController;
use App\Lib\CurlRequest;
use App\Lib\TradeManager;
use App\Models\AdminNotification;
use App\Models\BinaryTrade;
use App\Models\CoinPair;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\FutureOrder;
use App\Models\FutureQueueOrder;
use App\Models\FutureTrade;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds((int) $cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function crypto()
    {
        try {
            return defaultCurrencyDataProvider()->updateCryptoPrice();
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function market()
    {
        try {
            return defaultCurrencyDataProvider()->updateMarkets();
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function trade()
    {
        try {
            $trade = new TradeManager();
            return $trade->trade();
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
    public function stopLimitOrder()
    {
        try {
            $orders = Order::where('is_draft', Status::YES)->where('status', Status::ORDER_PENDING)->get();
            foreach ($orders as $order) {
                $pair = $order->pair;
                if (!$pair) {
                    continue;
                }

                $amount      = @$order->amount;
                $rate        = @$order->rate;
                $marketPrice = @$pair->marketData->price;
                if ($marketPrice <= 0 || $rate <= 0 || $amount <= 0) {
                    continue;
                }

                $totalAmount    = $amount * $rate;
                $coin           = @$pair->coin;
                $marketCurrency = @$pair->market->currency;
                $user           = @$order->user;

                if (!$user || !$marketCurrency || !$coin) {
                    continue;
                }

                if ($order->order_side == Status::BUY_SIDE_ORDER) {
                    if ($marketPrice >= $order->stop_rate) {
                        $userMarketCurrencyWallet = Wallet::where('user_id', $user->id)->where('currency_id', $marketCurrency->id)->spot()->first();
                        $charge                   = ($totalAmount / 100) * $pair->percent_charge_for_buy;
                        if (($charge + $totalAmount) > $userMarketCurrencyWallet->balance) {
                            continue;
                        }

                        $orderSide = "Buy";
                    } else {
                        continue;
                    }
                }

                if ($order->order_side == Status::SELL_SIDE_ORDER) {
                    if ($marketPrice <= $order->stop_rate) {
                        $userCoinWallet = Wallet::where('user_id', $user->id)->where('currency_id', $coin->id)->spot()->first();
                        $charge         = ($totalAmount / 100) * $pair->percent_charge_for_sell;
                        if ($order->amount > $userCoinWallet->balance) {
                            continue;
                        }

                        $orderSide = "Sell";
                    } else {
                        continue;
                    }
                }

                $order->is_draft   = Status::NO;
                $order->status     = Status::ORDER_OPEN;
                $order->order_type = Status::ORDER_TYPE_LIMIT;
                $order->save();

                if ($order->order_side == Status::BUY_SIDE_ORDER) {
                    $details = "Open order for buy coin on " . $pair->symbol . " pair. [From stop limit order]";
                    $this->createTrx($userMarketCurrencyWallet, 'order_buy', $totalAmount, $charge, $details, $user);
                } else {
                    $details = "Open order for sell coin on " . $pair->symbol . " pair. [From stop limit order]";
                    $this->createTrx($userCoinWallet, 'order_sell', $amount, 0, $details, $user);
                }

                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = $user->username . $details;
                $adminNotification->click_url = urlPath('admin.order.history');
                $adminNotification->save();

                notify($user, 'ORDER_OPEN', [
                    'pair'                   => $pair->symbol,
                    'amount'                 => showAmount($order->amount, currencyFormat: false),
                    'total'                  => showAmount($order->total, currencyFormat: false),
                    'rate'                   => showAmount($order->rate, currencyFormat: false),
                    'price'                  => showAmount($order->price, currencyFormat: false),
                    'coin_symbol'            => @$coin->symbol,
                    'order_side'             => $orderSide,
                    'market_currency_symbol' => @$marketCurrency->symbol,
                    'market'                 => $pair->market->name,
                ]);
            }

            $futureQueueOrders = FutureQueueOrder::queueStopLimitOrder()->limit(50)->get();

            foreach ($futureQueueOrders as $futureQueueOrder) {
                $marketPrice = @$futureQueueOrder->pair->marketData->price;

                if (($futureQueueOrder->order_side == Status::BUY_SIDE_ORDER && $marketPrice >= $futureQueueOrder->stop_rate) || ($futureQueueOrder->order_side == Status::SELL_SIDE_ORDER && $marketPrice <= $futureQueueOrder->stop_rate)) {
                    $futureQueueOrder->type = Status::QUEUE_LIMIT_ORDER;
                    $futureQueueOrder->save();
                }

            }

        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    private function createTrx($wallet, $remark, $amount, $charge, $details, $user, $type = "-")
    {
        if ($type == '-') {
            $wallet->balance -= $amount;
        } else {
            $wallet->balance += $amount;
        }

        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $type;
        $transaction->details      = $details;
        $transaction->trx          = getTrx();
        $transaction->remark       = $remark;
        $transaction->save();

        if (getAmount($charge) <= 0) {
            return $wallet->balance;
        }

        if ($type == '-') {
            $wallet->balance -= $charge;
        } else {
            $wallet->balance += $charge;
        }

        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $charge;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $type;
        $transaction->details      = "Charge for " . $details;
        $transaction->trx          = getTrx();
        $transaction->remark       = "charge_" . $remark;
        $transaction->save();

        return $wallet->balance;
    }

    public function incompleteBinary()
    {
        try {
            $incompleteTrades = BinaryTrade::inactive()->pending()->where('trade_ended_at', '<=', now()->subMinute(1))->with(['coinPair'])->orderBy('id', 'asc')->limit(20)->get();

            BinaryTrade::inactive()->pending()->where('trade_ended_at', '<=', now()->subMinute(1))->limit(20)->update(['win_status' => Status::BINARY_TRADE_PROCESSING]);

            BinaryTrade::inactive()->processing()->where('updated_at', '<=', now()->subMinute(10))->limit(20)->update(['win_status' => Status::BINARY_TRADE_PENDING]);

            $binaryTradeOrder = new BinaryTradeOrderController();

            foreach ($incompleteTrades as $trade) {
                $time = now()->parse($trade->trade_ended_at)->timestamp;
                $time = $time * 1000;

                $symbol = str_replace('_', '', @$trade->coinPair->symbol);

                $try = 1;

                while ($try <= 10) {
                    $response = CurlRequest::curlContent("https://api.binance.com/api/v3/klines?symbol=$symbol&interval=1s&startTime=$time&endTime=$time&limit=1");
                    $response = json_decode($response, true);

                    if (@$response[0][1]) {
                        break;
                    }

                    sleep(1);
                    $try++;
                }

                if (!@$response[0][1]) {
                    $this->refundBinaryTrade($trade);
                    continue;
                }

                $price = $response[0][1];
                $binaryTradeOrder->binaryTradeWinLoss($trade, $price);
            }

        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    private function refundBinaryTrade($trade)
    {
        $trade->status     = Status::ENABLE;
        $trade->win_status = Status::BINARY_TRADE_REFUND;
        $trade->save();

        $user           = $trade->user;
        $currencySymbol = $trade->coinPair->market->currency->symbol;
        $userWallet     = $user->wallets()->where('currency_id', $trade->coinPair->market->currency_id)->first();

        $userWallet->balance += $trade->amount;
        $userWallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $userWallet->id;
        $transaction->amount       = $trade->amount;
        $transaction->charge       = 0;
        $transaction->post_balance = $userWallet->balance;
        $transaction->trx          = getTrx();
        $transaction->trx_type     = '+';
        $transaction->details      = $trade->amount . ' ' . $currencySymbol . 'binary trade refunded';
        $transaction->remark       = 'binary_trade';
        $transaction->save();
    }

    public function futureLiquidationOrder()
    {
        $coinPairs = CoinPair::whereHas('futureTradeConfig', function ($query) {
            $query->whereHas('futureOrders', function ($query) {
                $query->positioned();
            });
        })->orderBy('last_liquidation_check_at')->get();

        foreach ($coinPairs as $coinPair) {

            $startTime = now()->parse($coinPair->last_liquidation_check_at)->timestamp * 1000;
            $endTime   = now()->timestamp * 1000;

            $symbol = str_replace('_', '', $coinPair->symbol);
            
            $response = CurlRequest::curlContent("https://api.binance.com/api/v3/klines?symbol=$symbol&interval=1m&startTime=$startTime&endTime=$endTime&limit=1000");

            $response = json_decode($response, true);

            if (!@$response[0][1]) {
                continue;
            }

            $lowestPrice  = PHP_FLOAT_MAX;
            $highestPrice = 0;

            foreach ($response as $candle) {
                $highPrice = (float) $candle[2];
                if ($highPrice > $highestPrice) {
                    $highestPrice = $highPrice;
                }
            }

            foreach ($response as $candle) {
                $lowPrice = (float) $candle[3];
                if ($lowPrice < $lowestPrice) {
                    $lowestPrice = $lowPrice;
                }
            }
           
            $positions = FutureOrder::positioned()->whereHas('futureTradeConfig', function ($config) use ($coinPair) {
                $config->whereHas('coinPair', function ($pair) use ($coinPair) {
                    $pair->where('symbol', $coinPair->symbol);
                });
            })->get();

            foreach ($positions as $position) {
                if (($position->order_side == Status::BUY_SIDE_ORDER && $lowestPrice < $position->liquidation_rate) || ($position->order_side == Status::SELL_SIDE_ORDER && $highestPrice > $position->liquidation_rate)) {
                    $position->status = Status::FUTURE_ORDER_LIQUIDATED;
                    $position->save();

                    if($position->margin_mode == Status::MARGIN_MODE_CROSS){
                        $wallets = Wallet::future()->where('user_id', $position->user_id)->where('balance', '>', 0)->get();
                        foreach($wallets as $wallet){
                            $transaction               = new Transaction();
                            $transaction->user_id      = $position->user_id;
                            $transaction->wallet_id    = $wallet->id;
                            $transaction->amount       = $wallet->balance;
                            $transaction->post_balance = 0;
                            $transaction->charge       = 0;
                            $transaction->trx_type     = '-';
                            $transaction->details      = "Position liquidation " . $position->futureTradeConfig->coinPair->symbol . " pair";
                            $transaction->trx          = getTrx();
                            $transaction->remark       = "position_liquidation";
                            $transaction->save();

                            $wallet->balance = 0;
                            $wallet->save();
                        }
                    }
                }
            }

            $coinPair->last_liquidation_check_at = now();
            $coinPair->save();
        }

        $coinPairs = CoinPair::where(function ($query) {
            $query->whereDoesntHave('futureTradeConfig')
                ->orWhereHas('futureTradeConfig', function ($q) {
                    $q->whereDoesntHave('futureOrders', function ($futureOrder) {
                        $futureOrder->positioned();
                    });
                });
        })->update(['last_liquidation_check_at' => now()]);
    }

    public function futureQueueOrder()
    {
        try {
            $queueBuyOrders = FutureQueueOrder::open()->buySide()->notQueueStopLimitOrder()->orderBy('last_update', 'desc')->limit(20)->get();

            foreach ($queueBuyOrders as $queueBuyOrder) {
                if ($queueBuyOrder->futureOrder && $queueBuyOrder->futureOrder->status == Status::FUTURE_ORDER_LIQUIDATED) {
                    $queueBuyOrder->status = Status::FUTURE_QUEUE_CANCELED;
                    $queueBuyOrder->save();
                    continue;
                }
                $queueBuyOrder->last_update = time();
                $queueBuyOrder->save();

                $buyAmount = $queueBuyOrder->remaining_coin_amount;
                $buySize   = $queueBuyOrder->remaining_size;

                $queueSellOrders = FutureQueueOrder::open()->sellSide()->notQueueStopLimitOrder()->where('future_trade_config_id', $queueBuyOrder->future_trade_config_id)->where('rate', $queueBuyOrder->rate)->where('user_id', '!=', $queueBuyOrder->user_id)->orderBy('last_update', 'desc')->limit(20)->get();

                foreach ($queueSellOrders as $queueSellOrder) {
                    if ($queueSellOrder->futureOrder && $queueSellOrder->futureOrder->status == Status::FUTURE_ORDER_LIQUIDATED) {
                        $queueSellOrder->status = Status::FUTURE_QUEUE_CANCELED;
                        $queueSellOrder->save();
                        continue;
                    }

                    $queueSellOrder->last_update = time();
                    $queueSellOrder->save();

                    $sellAmount  = $queueSellOrder->remaining_coin_amount;
                    $sellSize    = $queueSellOrder->remaining_size;
                    $tradeAmount = $sellAmount >= $buyAmount ? $buyAmount : $sellAmount;
                    $tradeSize   = $sellSize >= $buySize ? $buySize : $sellSize;

                    $queueBuyOrder->remaining_coin_amount -= $tradeAmount;
                    $buyMarginUsed = $tradeAmount / $queueBuyOrder->coin_amount * $queueBuyOrder->margin;
                    $queueBuyOrder->remaining_margin -= $buyMarginUsed;
                    $queueBuyOrder->remaining_size -= $tradeSize;

                    if ($queueBuyOrder->remaining_coin_amount <= 0) {
                        $queueBuyOrder->status = Status::FUTURE_QUEUE_COMPLETED;
                    }

                    $queueBuyOrder->save();

                    $queueSellOrder->remaining_coin_amount -= $tradeAmount;
                    $sellMarginUsed = $tradeAmount / $queueSellOrder->coin_amount * $queueSellOrder->margin;
                    $queueSellOrder->remaining_margin -= $sellMarginUsed;
                    $queueSellOrder->remaining_size -= $tradeSize;

                    if ($queueSellOrder->remaining_coin_amount <= 0) {
                        $queueSellOrder->status = Status::FUTURE_QUEUE_COMPLETED;
                    }
                    if ($queueSellOrder->remaining_size <= 0) {
                        $queueSellOrder->status = Status::FUTURE_QUEUE_COMPLETED;
                    }
                    $queueSellOrder->save();

                    if ($queueBuyOrder->future_order_id) {
                        $this->updateFutureOrder($queueBuyOrder, $tradeAmount, $tradeSize, $buyMarginUsed, Status::BUY_SIDE_ORDER);
                        $futureBuyOrder = $queueBuyOrder->futureOrder;
                    } else {
                        $futureBuyOrder = $this->placeFutureOrder($queueBuyOrder, $tradeAmount, $tradeSize, $buyMarginUsed, Status::BUY_SIDE_ORDER);
                    }

                    if ($queueSellOrder->future_order_id) {
                        $this->updateFutureOrder($queueSellOrder, $tradeAmount, $tradeSize, $sellMarginUsed, Status::SELL_SIDE_ORDER);
                        $futureSellOrder = $queueSellOrder->futureOrder;
                    } else {
                        $futureSellOrder = $this->placeFutureOrder($queueSellOrder, $tradeAmount, $tradeSize, $sellMarginUsed, Status::SELL_SIDE_ORDER);
                    }

                    $this->createFutureTrade($queueBuyOrder, $futureBuyOrder, $tradeAmount, Status::BUY_SIDE_ORDER);
                    $this->createFutureTrade($queueSellOrder, $futureSellOrder, $tradeAmount, Status::SELL_SIDE_ORDER);
                }

            }
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    private function placeFutureOrder($queueOrder, $tradeAmount, $tradeSize, $marginUsed, $orderSide)
    {
        $user = $queueOrder->user;

        if ($orderSide == Status::BUY_SIDE_ORDER) {
            $scope = 'buySideOrder';
        } else {
            $scope = 'sellSideOrder';
        }

        $previousFutureOrder = FutureOrder::$scope()->positioned()->where('user_id', $user->id)->where('future_trade_config_id', $queueOrder->future_trade_config_id)->orderBy('id', 'desc')->first();
        if ($previousFutureOrder && $previousFutureOrder->margin_mode == Status::MARGIN_MODE_CROSS) {
            $previousFutureOrder->margin = $previousFutureOrder->margin + $marginUsed;
            $previousFutureOrder->coin_amount += $tradeAmount;

            $previousFutureOrder->rate = (($previousFutureOrder->rate * $previousFutureOrder->size) + ($queueOrder->rate * $tradeSize)) / ($previousFutureOrder->size + $tradeSize);

            $previousFutureOrder->leverage = $queueOrder->leverage;
            $previousFutureOrder->size += $tradeSize;
            $previousFutureOrder->save();
            $previousFutureOrder->liquidation_rate = getLiquidationRate($previousFutureOrder);
            $previousFutureOrder->save();
            return $previousFutureOrder;
        } else {

            $futureOrder = FutureOrder::where('future_queue_order_id', $queueOrder->id)
                ->whereDoesntHave('futureQueueOrders', function ($query) {
                    $query->open()->queueTakeProfitLossOrder();
                })
                ->first();

            if ($futureOrder) {
                $futureOrder->size += $tradeSize;
                $futureOrder->margin = $futureOrder->size / $queueOrder->leverage;
                $futureOrder->coin_amount += $tradeAmount;
            } else {
                $futureOrder                         = new FutureOrder();
                $futureOrder->user_id                = $queueOrder->user_id;
                $futureOrder->future_trade_config_id = $queueOrder->future_trade_config_id;
                $futureOrder->future_queue_order_id  = $queueOrder->id;
                $futureOrder->size                   = $tradeSize;
                $futureOrder->margin                 = $tradeSize / $queueOrder->leverage;
                $futureOrder->margin_mode            = $queueOrder->margin_mode;
                $futureOrder->coin_amount            = $tradeAmount;
                $futureOrder->rate                   = $queueOrder->rate;
                $futureOrder->leverage               = $queueOrder->leverage;
                $futureOrder->coin_id                = $queueOrder->coin_id;
                $futureOrder->market_currency_id     = $queueOrder->market_currency_id;
                $futureOrder->order_side             = $orderSide;
                $futureOrder->status                 = Status::FUTURE_ORDER_POSITIONED;
                $futureOrder->save();
            }

            $futureOrder->liquidation_rate = getLiquidationRate($futureOrder);
            $futureOrder->save();

            return $futureOrder;
        }

    }

    private function updateFutureOrder($queueOrder, $tradeAmount, $tradeSize, $marginUsed, $orderSide)
    {
        $futureOrder = $queueOrder->futureOrder;

        if ($futureOrder->status != Status::FUTURE_ORDER_POSITIONED) {
            return false;
        }

        $futureOrder->coin_amount -= $tradeAmount;
        $futureOrder->margin -= $marginUsed;
        $futureOrder->size -= $tradeSize;
        $futureOrder->save();

        if ($futureOrder->coin_amount <= 0) {
            $futureOrder->status = Status::FUTURE_ORDER_CLOSED;
        } else {
            $futureOrder->liquidation_rate = getLiquidationRate($futureOrder);
        }

        $futureOrder->save();

        $this->updateWalletForProfitLoss($queueOrder, $tradeAmount, $marginUsed, $orderSide);
    }

    private function updateWalletForProfitLoss($queueOrder, $tradeAmount, $marginUsed, $orderSide)
    {
        $user        = $queueOrder->user;
        $futureOrder = $queueOrder->futureOrder;
        $wallet      = Wallet::future()->where('user_id', $user->id)->where('currency_id', $futureOrder->market_currency_id)->first();

        $margin = $marginUsed;

        if ($orderSide == Status::BUY_SIDE_ORDER) {
            $profitLoss    = ($futureOrder->rate - $queueOrder->rate) * $tradeAmount;
            $chargePercent = $futureOrder->futureTradeConfig->buy_charge;
        } else {
            $profitLoss    = ($queueOrder->rate - $futureOrder->rate) * $tradeAmount;
            $chargePercent = $futureOrder->futureTradeConfig->sell_charge;
        }

        $charge = $tradeAmount * $queueOrder->rate * $chargePercent / 100;
        $profitLoss -= $charge;

        $futureOrder->pnl += $profitLoss;
        $futureOrder->save();

        $totalAmount = $margin + $profitLoss;

        $wallet->balance += $totalAmount;
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = abs($totalAmount);
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $totalAmount >= 0 ? '+' : '-';
        $transaction->details      = "Profit/Loss for " . $queueOrder->pair->symbol . " pair";
        $transaction->trx          = getTrx();
        $transaction->remark       = "profit_loss";
        $transaction->save();
    }

    private function createFutureTrade($queueOrder, $futureOrder, $tradeAmount, $orderSide)
    {
        if ($orderSide == Status::BUY_SIDE_ORDER) {
            $chargePercent = $futureOrder->futureTradeConfig->buy_charge;
        } else {
            $chargePercent = $futureOrder->futureTradeConfig->sell_charge;
        }
        $charge = $tradeAmount * $queueOrder->rate * $chargePercent / 100;

        $futureTrade                         = new FutureTrade();
        $futureTrade->user_id                = $queueOrder->user_id;
        $futureTrade->future_order_id        = $futureOrder->id;
        $futureTrade->future_trade_config_id = $queueOrder->future_trade_config_id;
        $futureTrade->trade_side             = $orderSide;
        $futureTrade->rate                   = $queueOrder->rate;
        $futureTrade->amount                 = $tradeAmount;
        $futureTrade->charge                 = $charge;
        $futureTrade->save();
    }

}
