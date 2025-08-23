<?php

namespace App\Http\Controllers\Api\P2P;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\P2P\Ad;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeFeedBack;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $user  = auth()->user();
        $trade = Trade::myTrade($user->id);
        $ad    = Ad::where('user_id', $user->id);

        $widget['total_trade']     = (clone $trade)->count();
        $widget['running_trade']   = (clone $trade)->running()->count();
        $widget['completed_trade'] = (clone $trade)->completed()->count();

        $widget['total_ad']     = (clone $ad)->count();
        $widget['active_ad']    = (clone $ad)->active()->count();
        $widget['in_active_ad'] = (clone $ad)->inActive()->count();

        $trades             = $trade->latest('id')->take(10)->with('ad.asset', 'ad.fiat')->get();
        $widget['feedback'] = userFeedback($user->id);

        $notify[] = 'P2P Dashboard';
        return responseSuccess('p2p_dashboard', $notify, [
            'widget' => $widget,
            'trades' => $trades,
        ]);

    }

    public function feedbackList()
    {
        $feedbacks = TradeFeedBack::with('feedbackProvider')->where('user_id', auth()->id())->latest('id')->apiQuery();

        $notify[] = 'P2P Center';
        return responseSuccess('p2p_center', $notify, [
            'feedbacks' => $feedbacks,
        ]);
    }

    public function list(Request $request, $type = "buy", $coin = null, $currency = 'all', $paymentMethod = 'all', $region = 'all', $amount = 0)
    {
        $type          = $request->type ?? 'buy';
        $coin          = $request->coin ?? null;
        $currency      = $request->currency ?? 'all';
        $paymentMethod = $request->payment_method ?? 'all';
        $region        = $request->region ?? 'all';
        $amount        = $request->amount ?? 0;

        $types = ['buy', 'sell'];

        if (!in_array($type, $types)) {
            $notify[] = 'Something went to the wrong';
            return responseError('something_wrong', $notify);
        }

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

        if ($currency != 'all') {
            $query->whereHas('fiat', function ($q) use ($currency) {
                $q->where('symbol', $currency)->fiat()->active();
            });
            $requestedCurrencyPaymentMethods = PaymentMethod::whereJsonContains("supported_currency", $currency)->active()->get();
        } else {
            $requestedCurrencyPaymentMethods = collect();
        }

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        if ($paymentMethod != 'all') {
            $query->whereHas('paymentMethods', function ($q) use ($paymentMethod) {
                $q->whereHas('paymentMethod', function ($q) use ($paymentMethod) {
                    $q->where('slug', $paymentMethod)->active();
                });
            })->orderBy('price', $type == 'buy' ? 'asc' : 'desc');
        }

        if ($region != 'all') {
            $requestedCountry = @$countries->$region;
            $query->whereHas('user', function ($q) use ($requestedCountry) {
                $q->where('address->country', @$requestedCountry->country)->active();
            });
        }

        if ($amount) {
            $query->where('minimum_amount', '<=', $amount)->where('maximum_amount', '>=', $amount);
        }

        $query    = $query->active();
        $totalAds = (clone $query)->count();
        $ads      = $query->with('user', 'asset', 'fiat', 'paymentMethods.paymentMethod', 'paymentWindow')
            ->withCount(['trades as total_trade', 'trades' => function ($q) {
                $q->where('status', Status::P2P_TRADE_COMPLETED);
            }])->apiQuery();

        $currencies     = Currency::active()->fiat()->orderBy('name')->get();
        $sections       = Page::where('tempname', activeTemplate())->where('slug', 'p2p')->first();
        $paymentMethods = PaymentMethod::select('supported_currency', 'id', 'slug', 'name', 'branding_color')->active()->get();

        $modifiedCountry = collect($countries)
            ->map(function ($value, $key) {
                return array_merge((array) $value, ['country_code' => $key]);
            })->values()->all();

        $data = [
            'total_ads'                          => $totalAds,
            'coins'                              => $coins,
            'ads'                                => $ads,
            'sections'                           => $sections,
            'type'                               => $type,
            'countries'                          => $modifiedCountry,
            'currencies'                         => $currencies,
            'payment_methods'                    => $paymentMethods,
            'requested_currency_payment_methods' => $requestedCurrencyPaymentMethods,
        ];

        $notify[] = 'P2P Trade List';
        return responseSuccess('trade_list', $notify, $data);
    }

    public function advertiser($id)
    {
        $advertiser = User::active()->where('id', $id)->first();
        if (!$advertiser) {
            $notify[] = 'Advertiser not found';
            return responseError('not_found', $notify);
        }

        $feedback = userFeedback($advertiser->id);
        $trade    = Trade::myTrade($id);

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

        $ads['buy']         = (clone $adsQuery)->sell()->get();
        $ads['sell']        = (clone $adsQuery)->buy()->get();
        $ads['total_ad']    = Ad::where('user_id', $advertiser->id)->count();
        $ads['active_ad']   = count($ads['buy']) + count($ads['sell']);
        $ads['inactive_ad'] = $ads['total_ad'] - $ads['active_ad'];

        $paymentMethods = PaymentMethod::select('supported_currency', 'id', 'slug', 'name', 'branding_color')->active()->get();

        $tradeFeedbacks = TradeFeedback::with('feedbackProvider')->where('user_id', $id)->get();

        $notify[] = "Advertiser: " . $advertiser->full_name;
        return responseSuccess('advertiser_profile', $notify, [
            'advertiser'          => $advertiser,
            'feedback'            => $feedback,
            'trade_feedbacks'     => $tradeFeedbacks,
            'widget'              => $widget,
            'ads'                 => $ads,
            'payment_methods'     => $paymentMethods,
            'provider_image_path' => getFilePath('userProfile'),
        ]);
    }

}
