<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::namespace('Web3')->prefix('web3')->name('web3.')->group(function () {
        Route::controller("MetamaskController")->name('metamask.login.')->prefix('metamask-login')->group(function () {
            Route::any('message', 'message')->name('message');
            Route::post('verify', 'verify')->name('verify');
        });
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
                Route::get('more/wallet/{skip}', 'wallet')->name('more.wallet');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
                Route::get('pair/add/to/favorite/{pairSym}', 'addToFavorite')->name('add.pair.to.favorite');
                Route::get('all/currency', 'allCurrency')->name('currency.all');

                Route::get('referrals', 'referrals')->name('referrals');
            });

            Route::controller('OrderController')->group(function () {
                Route::name('order.')->prefix('order')->group(function () {
                    Route::get('open', 'open')->name('open');
                    Route::get('pending', 'pending')->name('pending');
                    Route::get('completed', 'completed')->name('completed');
                    Route::get('canceled', 'canceled')->name('canceled');
                    Route::post('cancel/{id}', 'cancel')->name('cancel');
                    Route::post('update/{id}', 'update')->name('update');
                    Route::get('history', 'history')->name('history');
                });
                Route::get('trade/history', 'tradeHistory')->name('trade.history');
            });

            Route::controller("OrderController")->prefix('order')->name('order.')->group(function () {
                Route::post('save/{symbol}', 'save')->name('save');
            });

            Route::controller('BinaryTradeOrderController')->prefix('binary')->name('binary.')->group(function () {
                Route::post('trade/order', 'binaryTradeOrder')->name('trade.order');
                Route::post('trade/complete', 'binaryTradeComplete')->name('trade.complete');
                Route::get('trade/all', 'allTrade')->name('trade.all');
                Route::get('trade/win', 'winTrade')->name('trade.win');
                Route::get('trade/lose', 'loseTrade')->name('trade.lose');
                Route::get('trade/refund', 'refundTrade')->name('trade.refund');
                Route::get('trade/history', 'tradeHistory')->name('trade.history');
            });

            Route::controller('FutureTradeHistoryController')->prefix('future')->name('future.')->group(function () {
                Route::get('order/open', 'openOrder')->name('order.open');
                Route::get('order/history', 'orderHistory')->name('order.history');
                Route::get('position/open', 'openPosition')->name('position.open');
                Route::get('position/history', 'positionHistory')->name('position.history');
                Route::get('trade/history', 'tradeHistory')->name('trade.history');
            });

            Route::controller('FutureTradeOrderController')->prefix('future/trade')->name('future.trade.')->group(function () {
                Route::get('available-balance/{currency_id}', 'availableBalance')->name('available.balance');
                Route::post('order', 'order')->name('order.save');

                Route::post('profit/take', 'takeProfitLoss')->name('profit.loss.take');
                Route::get('position/close/all', 'closeAllPosition')->name('position.close.all');

                Route::get('favorite/{pairId}', 'favorite')->name('favorite');

                Route::get('positioned', 'positioned')->name('positioned');
                Route::get('pending-queue', 'pendingQueue')->name('pending.queue');

                Route::get('position/history', 'positionHistory')->name('position.history');

                Route::post('order/cancel/{id}', 'cancelOrder')->name('order.cancel');
                Route::get('order/history', 'orderHistory')->name('order.history');

                Route::get('trade/history', 'tradeHistory')->name('trading.history');
            });

            //wallet
            Route::controller('WalletController')->name('wallet.')->prefix('wallet')->group(function () {
                Route::get('list/{type?}', 'list')->name('list');
                Route::post('transfer', 'transfer')->name('transfer');
                Route::post('transfer/to/wallet', 'transferToWallet')->name('transfer.to.other.wallet');
                Route::get('{type}/{currencySymbol}', 'view')->name('view');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });

        // Payment
        Route::prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
