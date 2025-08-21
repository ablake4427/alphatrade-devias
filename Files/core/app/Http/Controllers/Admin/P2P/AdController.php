<?php

namespace App\Http\Controllers\Admin\P2P;

use App\Http\Controllers\Controller;
use App\Models\P2P\Ad;

class AdController extends Controller
{
    public function index()
    {
        $pageTitle = 'P2P Ad';
        $ads       = Ad::select('p2p_ads.*', "wallets.balance")
            ->publishStatus()
            ->latest('p2p_ads.id')
            ->with('asset', 'fiat', 'paymentWindow', 'paymentMethods.paymentMethod', 'user')
            ->searchable(['user:username'])
            ->filter(['type'])
            ->paginate(getPaginate());
        return view('admin.p2p.ad.index', compact('pageTitle', 'ads'));
    }
}
