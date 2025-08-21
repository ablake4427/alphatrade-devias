<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\BinaryTrade;
use App\Models\CoinPair;

class BinaryTradeController extends Controller {
    public function binary($id = 0) {
        $pageTitle = 'Binary Trade';

        $coinPairs = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('coin:name,id,symbol,image,rate', 'market:id,name,currency_id', 'market.currency:id,symbol,image', 'marketData:id,pair_id,price,html_classes,percent_change_1h,last_percent_change_1h');

        if (!$coinPairs->count()) {
            return to_route('home');
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
                return to_route('home');
            }
            $tradeTabs  = session('coinPairsBinary');
            if($tradeTabs){
                $coinExists = $tradeTabs->contains(function ($item) use ($id) {
                    return $item['id'] == $id;
                });
                if (!$coinExists) {
                    if (count($tradeTabs) >= 6) {
                        $tradeTabs->pop();
                    }
                    $tradeTabs[] = $activeCoin;
                    session()->put('coinPairsBinary', $tradeTabs);
                }
            }
        } else {
            $activeCoin = $topBarCoinPairs->first();
        }

        if (!$activeCoin) {
            return to_route('home');
        }

        $durations = $activeCoin->binary_trade_duration;

        $maxTradeCoinPairs = session('coinPairsBinary');
        if (!$maxTradeCoinPairs) {
            $maxTradeCoinPairs = $topBarCoinPairs;
            session()->put('coinPairsBinary', $maxTradeCoinPairs);
        }

        $runningTrades = null;
        $closedTrades  = null;
        if (auth()->check()) {
            $runningTrades = BinaryTrade::where('user_id', auth()->id())->inactive()->latest()->take(5)->get();
            $closedTrades  = BinaryTrade::where('user_id', auth()->id())->active()->latest()->take(5)->get();
        }

        return view('Template::binary.trade', compact('pageTitle', 'activeCoin', 'runningTrades', 'closedTrades', 'maxTradeCoinPairs', 'minTradeCoinPairs', 'allCoins', 'dropDownMaxCoinPairs', 'durations'));
    }

    public function tradeTabClose($id, $firstCoinId) {
        $activeCoin = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('coin:name,id,symbol,image,rate', 'market:id,name,currency_id', 'market.currency:id,symbol,image', 'marketData:id,pair_id,price,html_classes,percent_change_1h,last_percent_change_1h')->where('id', $firstCoinId)->first();
        if (!$activeCoin) {
            return response()->json(['error' => 'Coin Pair not found.']);
        }

        $tradeTabs = session('coinPairsBinary');
        if ($tradeTabs) {
            $tradeTabs = $tradeTabs->filter(function ($item) use ($id) {
                return $item['id'] != $id;
            });
        }
        session()->put('coinPairsBinary', $tradeTabs);
        $firstDuration = convertToMinutesSeconds($activeCoin->binary_trade_duration[0]) ?? '0:00';
        $durations     = '';
        foreach ($activeCoin->binary_trade_duration as $duration) {
            $durations .= '<li class="trade-duration-presets__item">' . convertToMinutesSeconds($duration) . '</li>';
        }
        return response()->json(['activeCoin' => $activeCoin, 'durations' => $durations, 'firstDuration' => $firstDuration]);
    }

    public function tradeTabAdd($id = 0) {
        $activeCoin = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('coin:name,id,symbol,image,rate', 'market:id,name,currency_id', 'market.currency:id,symbol,image', 'marketData:id,pair_id,price,html_classes,percent_change_1h,last_percent_change_1h')->where('id', $id)->first();
        if (!$activeCoin) {
            return response()->json(['error' => 'Coin Pair not found.']);
        }

        $tradeTabs = session('coinPairsBinary');
        if (count($tradeTabs) > 6) {
            $tradeTabs = $tradeTabs->pop();
        }
        $tradeTabs[] = $activeCoin;
        session()->put('coinPairsBinary', $tradeTabs);

        $firstDuration = convertToMinutesSeconds($activeCoin->binary_trade_duration[0]) ?? '0:00';
        $durations     = '';
        foreach ($activeCoin->binary_trade_duration as $duration) {
            $durations .= '<li class="trade-duration-presets__item">' . convertToMinutesSeconds($duration) . '</li>';
        }
        return response()->json(['activeCoin' => $activeCoin, 'durations' => $durations, 'firstDuration' => $firstDuration]);
    }
    public function tradeTabUpdate($id = 0) {
        $activeCoin = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::BINARY_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('coin:name,id,symbol,image,rate', 'market:id,name,currency_id', 'market.currency:id,symbol,image', 'marketData:id,pair_id,price,html_classes,percent_change_1h,last_percent_change_1h')->where('id', $id)->first();
        if (!$activeCoin) {
            return response()->json(['error' => 'Coin Pair not found.']);
        }
        $firstDuration = convertToMinutesSeconds($activeCoin->binary_trade_duration[0]) ?? '0:00';
        $durations     = '';
        foreach ($activeCoin->binary_trade_duration as $duration) {
            $durations .= '<li class="trade-duration-presets__item">' . convertToMinutesSeconds($duration) . '</li>';
        }
        return response()->json(['activeCoin' => $activeCoin, 'durations' => $durations, 'firstDuration' => $firstDuration]);
    }
}
