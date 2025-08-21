<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BinaryTrade;

class BinaryTradeController extends Controller {
    public function index() {
        $pageTitle = 'All Binary Trade';
        $trades    = $this->getBinaryTrade('');
        return view('admin.binary.trades', compact('pageTitle', 'trades'));
    }
    public function running() {
        $pageTitle = 'Running Binary Trade';
        $trades    = $this->getBinaryTrade('pending');
        return view('admin.binary.trades', compact('pageTitle', 'trades'));
    }
    public function win() {
        $pageTitle = 'Win Binary Trade';
        $trades    = $this->getBinaryTrade('win');
        return view('admin.binary.trades', compact('pageTitle', 'trades'));
    }
    public function lose() {
        $pageTitle = 'Lose Binary Trade';
        $trades    = $this->getBinaryTrade('lose');
        return view('admin.binary.trades', compact('pageTitle', 'trades'));
    }

    protected function getBinaryTrade($scope) {
        if ($scope) {
            $trades = BinaryTrade::$scope();
        } else {
            $trades = BinaryTrade::query();
        }

        return $trades->searchable(['trx', 'coinPair:symbol', 'coinPair.coin:symbol', 'user:username,firstname,lastname'])->with(['coinPair', 'user'])->orderBy('id', 'desc')->paginate(getPaginate());

    }
}
