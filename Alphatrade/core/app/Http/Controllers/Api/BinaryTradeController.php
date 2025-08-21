<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\BinaryTrade;
use App\Models\CoinPair;

class BinaryTradeController extends Controller
{
    public function binary($id = 0)
    {
        $coinPairs = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('coin:name,id,symbol,image,rate', 'market:id,name,currency_id', 'market.currency:id,symbol,image', 'marketData:id,pair_id,price,html_classes,percent_change_1h,last_percent_change_1h');

        if (!$coinPairs->count()) {
            return responseError('pair_not_found', 'No coin pair found');
        }

        $topBarCoinPairId     = (clone $coinPairs)->withCount('binaryTrade')->orderBy('binary_trade_count', 'desc')->limit(8)->pluck('id')->toArray();
        $topBarCoinId         = array_slice($topBarCoinPairId, 0, 5);
        $dropdownCoinId       = array_slice($topBarCoinPairId, -3);
        $topBarCoinPairs      = (clone $coinPairs)->whereIn('id', $topBarCoinId)->orderByRaw('FIELD(id, ' . implode(',', $topBarCoinId) . ')')->take(5)->get();
        $dropDownMaxCoinPairs = (clone $coinPairs)->whereIn('id', $dropdownCoinId)->orderByRaw('FIELD(id, ' . implode(',', $dropdownCoinId) . ')')->get();
        $minTradeCoinPairs    = (clone $coinPairs)->withCount('binaryTrade')->orderBy('binary_trade_count', 'asc')->orderBy('id', 'asc')->take(3)->get();
        $allCoins             = (clone $coinPairs)->orderBy('symbol', 'asc')->get();

        if ($id) {
            $activeCoin = $coinPairs->where('id', $id)->first();
            if (!$activeCoin) {
                return responseError('pair_not_found', 'No coin pair found');
            }
        } else {
            $activeCoin = $topBarCoinPairs->first();
        }

        if (!$activeCoin) {
            return responseError('something_wrong', 'Something went wrong');
        }

        $durations         = $activeCoin->binary_trade_duration;
        $maxTradeCoinPairs = $topBarCoinPairs;

        $runningTrades = null;
        $closedTrades  = null;
        if (auth()->check()) {
            $runningTrades = BinaryTrade::with('coinPair.coin')->where('user_id', auth()->id())->inactive()->latest()->take(5)->get();
            $closedTrades  = BinaryTrade::with('coinPair.coin')->where('user_id', auth()->id())->active()->latest()->take(5)->get();
        }

        return responseSuccess('binary_trade', 'Binary Trade', [
            'active_coin'              => $activeCoin,
            'running_trades'           => $runningTrades,
            'closed_trades'            => $closedTrades,
            'max_trade_coin_pairs'     => $maxTradeCoinPairs,
            'min_trade_coin_pairs'     => $minTradeCoinPairs,
            'all_coins'                => $allCoins,
            'drop_down_max_coin_pairs' => $dropDownMaxCoinPairs,
            'durations'                => $durations,
        ]);

    }
}
