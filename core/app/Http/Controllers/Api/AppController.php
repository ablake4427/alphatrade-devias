<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Order;
use App\Models\Page;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->userType   = 'user';
        $this->column     = 'user_id';
        $this->user       = auth()->user();
        $this->apiRequest = true;
    }

    public function generalSetting()
    {

        if (request()->header('custom_string') != 'vinance*123') {
            $notify[] = 'Invalid String';
            return responseError('invalid_string', $notify);
        }

        $notify[] = 'General setting data';

        return responseSuccess('general_setting', $notify, [
            'general_setting'       => gs(),
            'social_login_redirect' => route('user.social.login.callback', ''),
        ]);
    }

    public function getCountries()
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[]    = 'Country List';
        foreach ($countryData as $k => $country) {
            $countries[] = [
                'country'      => $country->country,
                'dial_code'    => $country->dial_code,
                'country_code' => $k,
            ];
        }

        return responseSuccess('country_data', $notify, ['countries' => $countries]);
    }

    public function language($code)
    {
        $languages     = Language::get();
        $languageCodes = $languages->pluck('code')->toArray();

        if ($code && !in_array($code, $languageCodes)) {
            $notify[] = 'Invalid code given';
            return responseError('validation_error', $notify);
        }

        if (!$code) {
            $code = Language::where('is_default', Status::YES)->first()?->code ?? 'en';
        }

        $jsonFile = file_get_contents(resource_path('lang/' . $code . '.json'));

        $notify[] = 'Language';
        return responseSuccess('language', $notify, [
            'languages'  => $languages,
            'file'       => json_decode($jsonFile) ?? [],
            'image_path' => getFilePath('language'),
        ]);
    }

    public function policyContent($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->first();
        if (!$policy) {
            $notify[] = 'Policy not found';
            return responseError('policy_not_found', $notify);
        }

        $seoContents = $policy->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        $notify[]    = 'Policy content';

        return responseSuccess('policy_content', $notify, [
            'policy'      => $policy,
            'seo_content' => $seoContents,
            'seo_image'   => $seoImage,
        ]);
    }

    public function seo()
    {
        $notify[] = 'Global SEO data';
        $seo      = Frontend::where('data_keys', 'seo.data')->first();
        return responseSuccess('seo', $notify, ['seo_content' => $seo]);
    }

    public function getExtension($act)
    {
        $notify[]  = 'Extension Data';
        $extension = Extension::where('status', Status::ENABLE)->where('act', $act)->first()?->makeVisible('shortcode');
        return responseSuccess('extension', $notify, [
            'extension'      => $extension,
            'custom_captcha' => $act == 'custom-captcha' ? loadCustomCaptcha() : null,
        ]);
    }

    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }

        if (!verifyCaptcha()) {
            $notify[] = 'Invalid captcha provided';
            return responseError('captcha_error', $notify);
        }

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = 0;
        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = 0;
        $adminNotification->title     = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = 'Contact form submitted successfully';
        return responseSuccess('contact_form_submitted', $notify, ['ticket' => $ticket]);
    }

    public function cookie()
    {
        $cookie   = Frontend::where('data_keys', 'cookie.data')->first();
        $notify[] = 'Cookie policy';

        return responseSuccess('cookie_data', $notify, [
            'cookie' => $cookie,
        ]);
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
        $notify[] = 'Cookie accepted';

        return responseSuccess('cookie_accepted', $notify);
    }

    public function customPages()
    {
        $pages = Page::where('tempname', activeTemplate())
            ->where(function ($query) {
                $query->where('is_default', Status::NO)->orWhere('slug', '/'); // home page data went with default
            })
            ->get();

        $notify[] = 'Custom pages';

        return responseSuccess('custom_pages', $notify, [
            'pages' => $pages,
        ]);
    }

    public function customPageData($slug)
    {
        if ($slug == 'home') {
            $slug = '/';
        }

        // default is home page, the where clause for default page is removed
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->first();

        if (!$page) {
            $notify[] = 'Page not found';
            return responseError('page_not_found', $notify);
        }

        $seoContents = $page->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        $notify[]    = 'Custom page';
        return responseSuccess('custom_page', $notify, [
            'page'        => $page,
            'seo_content' => $seoContents,
            'seo_image'   => $seoImage,
        ]);
    }

    public function allSections($key = null)
    {
        $items = Frontend::where('data_keys', 'like', '%.content')
            ->orWhere('data_keys', 'like', '%.element')
            ->orWhere('data_keys', 'like', '%.data')
            ->get();

        $groupedItems = $items->groupBy(function ($item) {
            return explode('.', $item->data_keys)[0]; // Group by section key
        });

        $data = $groupedItems->map(function ($group, $sectionKey) {
            $content   = $group->firstWhere('data_keys', "{$sectionKey}.content");
            $elements  = $group->filter(fn($item) => str_ends_with($item->data_keys, '.element'));
            $dataItems = $group->filter(fn($item) => str_ends_with($item->data_keys, '.data'));

            return [
                'key'      => $sectionKey,
                'content'  => $content->data_values ?? null,
                'elements' => $elements->pluck('data_values')->toArray(),
                'data'     => $dataItems->pluck('data_values')->first(),
            ];
        })->values();

        return $key ? $data->firstWhere('key', $key) : $data;
    }

    public function onboarding()
    {
        $onboardings = Frontend::where('data_keys', 'app_onboarding.element')->get();
        $path        = 'assets/images/frontend/app_onboarding';

        $notify[] = 'Onboarding screen';

        return responseSuccess('onboarding_screen', $notify, [
            'onboardings' => $onboardings,
            'path'        => $path,
        ]);
    }

    public function blogs()
    {
        $blogs = Frontend::where('data_keys', 'blog.element')->apiQuery();
        $path  = 'assets/images/frontend/blog';

        $notify[] = 'Blogs';
        return responseSuccess('blogs', $notify, [
            'blogs' => $blogs,
            'path'  => $path,
        ]);
    }

    public function blogDetails($id)
    {
        $blog = Frontend::where('data_keys', 'blog.element')->find($id);
        $path = 'assets/images/frontend/blog';

        if (!$blog) {
            $notify[] = 'Blog not found';
            return responseError('validation_error', $notify);
        }

        $notify[] = 'Blog Details';
        return responseSuccess('blog_details', $notify, [
            'blog' => $blog,
            'path' => $path,
        ]);
    }

    public function faqs()
    {
        $faqs = Frontend::where('data_keys', 'faq.element')->apiQuery();

        $notify[] = 'FAQs';
        return responseSuccess('faqs', $notify, [
            'faqs' => $faqs,
        ]);
    }

    public function policies()
    {
        $policies = getContent('policy_pages.element', orderById: true);
        $notify[] = 'All policies';

        return responseSuccess('policy_data', $notify, ['policies' => $policies]);
    }

    public function marketOverview()
    {

        $topExchangesCoins = Order::whereHas('coin', function ($q) {
            $q->active()->crypto();
        })->where('status', '!=', Status::ORDER_CANCELED)
            ->selectRaw('*,SUM(filled_amount) as total_exchange_amount')
            ->groupBy('coin_id')
            ->orderBy('total_exchange_amount', 'desc')
            ->take(4)
            ->with('coin', 'coin.marketData')
            ->get();

        $highLightedCoins = Currency::active()
            ->crypto()
            ->where('highlighted_coin', Status::YES)
            ->with('marketData')
            ->rankOrdering()
            ->take(4)
            ->get();

        $newCoins = Currency::active()
            ->crypto()
            ->rankOrdering()
            ->with('marketData')
            ->take(4)
            ->get();

        $notify[] = 'Market Overview';
        return responseSuccess('market_overview', $notify, [
            'top_exchanges_coins' => $topExchangesCoins,
            'high_lighted_coins'  => $highLightedCoins,
            'new_coins'           => $newCoins,
        ]);
    }

    public function marketList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:all,crypto,fiat',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $query = CoinPair::searchable(['symbol'])->select('id', 'market_id', 'coin_id', 'symbol');

        if ($request->type != 'all') {
            $query->whereHas('market', function ($q) use ($request) {
                $q->whereHas('currency', function ($c) use ($request) {
                    if ($request->type == 'crypto') {
                        return $c->crypto();
                    }
                    $c->fiat();
                });
            });
        }

        $query = $query->with('market:id,name,currency_id', 'coin:id,name,symbol,image', 'market.currency:id,name,symbol,image', 'marketData')
            ->withCount('trade as total_trade')
            ->orderBy('total_trade', 'desc');

        $total = (clone $query)->count();
        $pairs = (clone $query)->paginate(getPaginate());

        $notify[] = 'Market list';
        return responseSuccess('market_list', $notify, [
            'pairs' => $pairs,
            'total' => $total,
        ]);

    }

    public function cryptoList(Request $request)
    {
        $query = Currency::active()->crypto()->with('marketData')->rankOrdering()->searchable(['name', 'symbol']);

        $total      = (clone $query)->count();
        $currencies = (clone $query)->skip($request->skip ?? 0)
            ->take($request->limit ?? 20)
            ->get();

        $notify[] = 'Crypto list';
        return responseSuccess('crypto_list', $notify, [
            'currencies' => $currencies,
            'total'      => $total,
        ]);
    }

    public function currencies()
    {
        $currencies = Currency::active()->rankOrdering()->get();

        $notify[] = 'Currencies';
        return responseSuccess('currencies', $notify, ['currencies' => $currencies]);
    }
}
