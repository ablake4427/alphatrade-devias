<?php

namespace App\Http\Controllers\P2P;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\P2P\Ad;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\Trade;
use App\Models\Page;
use App\Models\User;
use Exception;

class HomeController extends Controller
{
    public function p2p($type = "buy", $coin = null, $currency = 'all', $paymentMethod = 'all', $region = 'all', $amount = 0)
    {
        $types = ['buy', 'sell'];
        abort_if(!in_array($type, $types), 404);

        $scope = $type == 'buy' ? 'sell' : 'buy';
        $coins = Currency::active()->crypto()->P2POrdering()->take(15)->get();
        $query = Ad::$scope()->select('p2p_ads.*', "wallets.balance")
            ->publishStatus()
            ->latest('p2p_ads.id')
            ->having('publish_status', 1);

        if ($coin) {
            $query->whereHas('asset', function ($q) use ($coin) {
                $q->where('symbol', $coin)->crypto()->active();
            });
        } else {
            if ($coins->count()) {
                $query->whereHas('asset', function ($q) use ($coins) {
                    $q->where('symbol', $coins->first()->symbol)->crypto()->active();
                });
            }
        }

        if ($currency !=  'all') {
            $query->whereHas('fiat', function ($q) use ($currency) {
                $q->where('symbol', $currency)->fiat()->active();
            });
            $requestedCurrencyPaymentMethods = PaymentMethod::whereJsonContains("supported_currency", $currency)->active()->get();
        } else {
            $requestedCurrencyPaymentMethods = collect();
        }

        $countries      = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        if ($paymentMethod !=  'all') {
            $query->whereHas('paymentMethods', function ($q) use ($paymentMethod) {
                $q->whereHas('paymentMethod', function ($q) use ($paymentMethod) {
                    $q->where('slug', $paymentMethod)->active();
                });
            })->orderBy('price', $type == 'buy'  ?  'asc' : 'desc');
        }

        if ($region !=  'all') {
            $requestedCountry = $countries->$region;
            $query->whereHas('user', function ($q) use ($requestedCountry) {
                $q->where('address->country', $requestedCountry->country)->active();
            });
        }

        if ($amount) {
            $query->where('minimum_amount', '<=', $amount)->where('maximum_amount', '>=', $amount);
        }

        $query    = $query->active();
        $totalAds = (clone $query)->count();
        $ads      = $query->with('user', 'asset', 'fiat', 'paymentMethods.paymentMethod', 'paymentWindow')
            ->skip(request()->skip ?? 0)
            ->take(request()->take ?? 20)
            ->withCount(['trades as total_trade', 'trades' => function ($q) {
                $q->where('status', Status::P2P_TRADE_COMPLETED);
            }])->get();


        if (request()->ajax()) {
            $html = view("Template::p2p.ad.list", compact('ads', 'type'))->render();
            return response()->json([
                'success' => true,
                'html'    => $html,
                'total'   => $totalAds,
            ]);
        }

        $pageTitle      = "P2P Trade";
        $currencies     = Currency::active()->fiat()->orderBy('name')->get();
        $sections       = Page::where('tempname', activeTemplate())->where('slug', 'p2p')->first();
        $paymentMethods = PaymentMethod::select('supported_currency', 'id', 'slug', 'name', 'branding_color')->active()->get();

        return view('Template::p2p.trade.index', compact('pageTitle', 'coins', 'ads', 'sections', 'type', 'countries', 'currencies', 'paymentMethods', 'requestedCurrencyPaymentMethods', 'totalAds'));
    }

    public function advertiser($id)
    {
        try {
            $id = decrypt($id);
        } catch (Exception $ex) {
            $notify[] = ['error', "Invalid URL."];
            return to_route('home')->withNotify($notify);
        }

        $advertiser = User::active()->where('id', $id)->firstOrFail();
        $pageTitle  = "Advertiser: " . $advertiser->full_name;
        $feedback    = userFeedback($advertiser->id);
        $trade      = Trade::myTrade($id);

        $widget['total_trade']     = (clone $trade)->count();
        $widget['running_trade']   = (clone $trade)->running()->count();
        $widget['completed_trade'] = (clone $trade)->completed()->count();
        $widget['reported_trade']  = (clone $trade)->reported()->count();
        $widget['last_trade']      = @$trade->latest('id')->completed()->first()->created_at;

        $adsQuery = Ad::where('p2p_ads.user_id', $advertiser->id)->select('p2p_ads.*', "wallets.balance")
            ->publishStatus()
            ->latest('p2p_ads.id')
            ->having('publish_status', 1)
            ->with('user', 'asset', 'fiat', 'paymentMethods.paymentMethod', 'paymentWindow')
            ->withCount(['trades as total_trade', 'trades' => function ($q) {
                $q->where('status', Status::P2P_TRADE_COMPLETED);
            }]);

        $ads['buy']  = (clone $adsQuery)->sell()->get();
        $ads['sell'] = (clone $adsQuery)->buy()->get();

        $paymentMethods = PaymentMethod::select('supported_currency', 'id', 'slug', 'name', 'branding_color')->active()->get();
        return view('Template::p2p.advertiser_details', compact('pageTitle', 'advertiser', 'feedback', 'widget', 'ads', 'paymentMethods'));
    }
}
