<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\BinaryTrade;
use App\Models\CoinPair;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BinaryTradeOrderController extends Controller
{
    public function binaryTradeOrder(Request $request)
    {
        $coinPair = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with(['coin', 'market', 'marketData'])->where('id', $request->coin_pair_id)->first();

        if (!$coinPair) {
            return responseError('not_found', 'Coin pair not found');
        }

        $minInvest = $coinPair->min_binary_trade_amount;
        $maxInvest = $coinPair->max_binary_trade_amount;
        $duration  = implode(',', $coinPair->binary_trade_duration);

        $validator = Validator::make($request->all(), [
            'amount'    => "required|numeric|gte:$minInvest|lte:$maxInvest",
            'duration'  => "required|in:$duration",
            'direction' => 'required|string|in:higher,lower',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $user       = auth()->user();
        $existTrade = BinaryTrade::where('user_id', $user->id)->inactive()->where('trade_ended_at', '>=', now())->exists();
        if ($existTrade) {
            return responseError('ongoing_trade', 'You need to wait until the ongoing trade is completed');
        }

        $userWallet = $user->wallets()->where('wallet_type', Status::WALLET_TYPE_FUNDING)->where('currency_id', $coinPair->coin_id)->first();
        if (!$userWallet) {
            return responseError('wallet_not_found', 'You have no ' . @$coinPair->coin->symbol . ' funding wallet');
        }

        if ($request->amount > $userWallet->balance) {
            return responseError('insufficient_balance', 'Insufficient balance in your ' . @$coinPair->coin->symbol . ' funding wallet');
        }

        $symbol   = str_replace('_', '', @$coinPair->symbol);

        $try = 1;

        while($try <= 5) {
            $url      = 'https://api.binance.com/api/v3/ticker/price?symbol=' . $symbol;
            $response = CurlRequest::curlContent($url);
            $response = json_decode($response);

            if (@$response->price) {
                break;
            }
            sleep(1);
            $try++;
        }

        if (!@$response->price) {
            return response()->json(['error' => 'Something went wrong']);
        }

        $currentPrice = $response->price;
        $userWallet->balance -= $request->amount;
        $userWallet->save();

        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $userWallet->id;
        $transaction->amount       = $request->amount;
        $transaction->charge       = 0;
        $transaction->post_balance = $userWallet->balance;
        $transaction->trx          = $trx;
        $transaction->trx_type     = '-';
        $transaction->details      = $request->amount . ' ' . @$coinPair->coin->symbol . ' ' . 'binary trade order';
        $transaction->remark       = 'binary_trade';
        $transaction->save();

        $currency       = $coinPair->coin;
        $currency->rate = $currentPrice;
        $currency->save();

        $binaryTrade                 = new BinaryTrade();
        $binaryTrade->user_id        = $user->id;
        $binaryTrade->coin_pair_id   = $request->coin_pair_id;
        $binaryTrade->amount         = $request->amount;
        $binaryTrade->last_price     = $currentPrice;
        $binaryTrade->duration       = (int) $request->duration;
        $binaryTrade->direction      = $request->direction;
        $binaryTrade->trx            = $trx;
        $binaryTrade->trade_ended_at = Carbon::now()->addSeconds((int) $request->duration);
        $binaryTrade->save();

        return responseSuccess('order_created', 'Order created successfully', ['binary_trade' => $binaryTrade]);
    }

    public function binaryTradeComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'binary_trade_id' => "required|integer|exists:binary_trades,id",
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $binaryTrade = BinaryTrade::inactive()->pending()->where('user_id', auth()->id())->withWhereHas('coinPair', function ($query) {
            $query->active()->activeMarket()->activeCoin()->where(function ($q) {
                $q->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
            });
        })->where('id', $request->binary_trade_id)->first();

        if (!$binaryTrade) {
            return responseError('trade_not_found', 'Binary trade not found');
        }

        if (now()->parse($binaryTrade->trade_ended_at)->addSeconds(15) < now()) {
            return responseError('something_wrong', 'Something went wrong');
        }

        if (now()->isBefore($binaryTrade->trade_ended_at)) {
            $binaryTrade->status = Status::ENABLE;
            $binaryTrade->save();
            return responseError('something_wrong', 'Something went wrong');
        }

        $coinPair = $binaryTrade->coinPair;

        $time = now()->parse($binaryTrade->trade_ended_at)->timestamp;
        $time = $time * 1000;

        $symbol = str_replace('_', '', @$coinPair->symbol);

        $try = 1;

        while($try <= 5) {
            $response = CurlRequest::curlContent("https://api.binance.com/api/v3/klines?symbol=$symbol&interval=1s&startTime=$time&endTime=$time&limit=1");
            $response = json_decode($response, true);

            if (@$response[0][1]) {
                break;
            }

            sleep(1);
            $try++;
        }
        
        if (!@$response[0][1]) {
            return response()->json(['error' => 'Something went wrong']);
        }

        $currentPrice = $response[0][1];

        $currency       = $coinPair->coin;
        $currency->rate = $currentPrice;
        $currency->save();

        $currencySymbol = $binaryTrade->coinPair->coin->symbol;
        $result         = $currentPrice > $binaryTrade->last_price;

        if (($binaryTrade->direction == "higher" && $result) || ($binaryTrade->direction == "lower" && !$result)) {
            $binaryTrade->win_status = Status::BINARY_TRADE_WIN;
            $binaryTrade->win_amount = $binaryTrade->amount + ($binaryTrade->amount * $binaryTrade->coinPair->binary_trade_profit / 100);
            $notification            = 'Congratulations! You have got ' . $binaryTrade->win_amount . ' ' . $currencySymbol . ' from binary trade';
        } else {
            $binaryTrade->win_status = Status::BINARY_TRADE_LOSE;
            $notification            = 'You lost ' . $binaryTrade->amount . ' ' . $currencySymbol;
        }

        $binaryTrade->result_price = $currentPrice;
        $binaryTrade->profit       = $binaryTrade->coinPair->binary_trade_profit;
        $binaryTrade->status       = Status::ENABLE;
        $binaryTrade->save();

        if ($binaryTrade->win_status == Status::BINARY_TRADE_WIN) {
            $user       = auth()->user();
            $userWallet = $user->wallets()->where('wallet_type', Status::WALLET_TYPE_FUNDING)->where('currency_id', $binaryTrade->coinPair->coin_id)->first();
            if (!$userWallet) {
                return responseError('wallet_not_found', 'You have no ' . @$currencySymbol . ' funding wallet');
            }

            $userWallet->balance += $binaryTrade->win_amount;
            $userWallet->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->wallet_id    = $userWallet->id;
            $transaction->amount       = $binaryTrade->win_amount;
            $transaction->charge       = 0;
            $transaction->post_balance = $userWallet->balance;
            $transaction->trx          = getTrx();
            $transaction->trx_type     = '+';
            $transaction->details      = $binaryTrade->win_amount . ' ' . $currencySymbol . ' binary trade win';
            $transaction->remark       = 'binary_trade';
            $transaction->save();
        }

        $trades = BinaryTrade::where('user_id', auth()->id())->with('coinPair')->active()->latest()->take(5)->get();
        return responseSuccess('order_completed', $notification, ['trades' => $trades, 'binary_trade' => $binaryTrade]);
    }

    public function allTrade()
    {
        $trades = $this->getBinaryTrade('');
        return responseSuccess('all_trade', 'All binary trade', ['trades' => $trades]);
    }
    public function winTrade()
    {
        $trades = $this->getBinaryTrade('win');
        return responseSuccess('win_trade', 'Win Binary Trade', ['trades' => $trades]);
    }
    public function loseTrade()
    {
        $trades = $this->getBinaryTrade('lose');
        return responseSuccess('lose_trade', 'Lose binary trade', ['trades' => $trades]);
    }
    public function refundTrade()
    {
        $trades = $this->getBinaryTrade('refund');
        return responseSuccess('refund_trade', 'Refund binary trade', ['trades' => $trades]);
    }

    protected function getBinaryTrade($scope)
    {
        if ($scope) {
            $trades = BinaryTrade::$scope();
        } else {
            $trades = BinaryTrade::query();
        }
        return $trades->where('user_id', auth()->id())->searchable(['trx', 'coinPair:symbol', 'coinPair.coin:symbol'])->with('coinPair.coin')->orderBy('id', 'desc')->apiQuery();
    }

}
