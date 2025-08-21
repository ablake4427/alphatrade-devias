<?php

namespace App\Http\Controllers\User\P2P;

use App\Http\Controllers\Controller;
use App\Models\P2P\Ad;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeFeedBack;

class HomeController extends Controller
{

    public function index()
    {
        $user  = auth()->user();
        $trade = Trade::myTrade($user->id);
        $ad    = Ad::where('user_id',$user->id);

        $widget['total_trade']     = (clone $trade)->count();
        $widget['running_trade']    = (clone $trade)->running()->count();
        $widget['completed_trade'] = (clone $trade)->completed()->count();

        $widget['total_ad']     = (clone $ad)->count();
        $widget['active_ad']    = (clone $ad)->active()->count();
        $widget['in_active_ad'] = (clone $ad)->inActive()->count();

        $pageTitle         = "P2P Center";
        $trades            = $trade->latest('id')->take(10)->with('ad.asset', 'ad.fiat')->get();
        $widget['feedback'] = userFeedback($user->id);

        return view('Template::user.p2p.index', compact('pageTitle', 'widget', 'trades', 'user'));
    }

    public function feedbackList()
    {
        $feedbacks = TradeFeedBack::where('user_id', auth()->id())->latest('id')->paginate(getPaginate());
        $pageTitle = "P2P Center";
        return view('Template::user.p2p.feedback', compact('pageTitle', 'feedbacks'));
    }
}
