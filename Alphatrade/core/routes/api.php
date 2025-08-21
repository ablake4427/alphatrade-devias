<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Api')->name('api.')->group(function () {
    
    Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::post('login', 'login');
            Route::post('check-token', 'checkToken');
            Route::post('social-login', 'socialLogin');
        });

        Route::namespace('Web3')->prefix('web3')->name('web3.')->group(function () {
            Route::controller("MetamaskController")->prefix('metamask-login')->group(function () {
                Route::any('message', 'message');
                Route::post('verify', 'verify');
            });
        });

        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail');
            Route::post('password/verify-code', 'verifyCode');
            Route::post('password/reset', 'reset');
        });
    });


    Route::controller('AppController')->group(function () {
        Route::get('general-setting', 'generalSetting');
        Route::get('get-countries', 'getCountries');
        Route::get('onboarding', 'onboarding');
        Route::get('language/{code?}', 'language');
        Route::get('blogs', 'blogs');
        Route::get('blog/details/{id}', 'blogDetails');
        Route::get('faqs', 'faqs');
        Route::get('policy-pages', 'policies');
        Route::get('policy/{slug}', 'policyContent');

        Route::get('seo', 'seo');
        Route::get('get-extension/{act}','getExtension');
        Route::post('contact', 'submitContact');
        Route::get('cookie', 'cookie');
        Route::post('cookie/accept', 'cookieAccept');
        Route::get('custom-pages', 'customPages');
        Route::get('custom-page/{slug}', 'customPageData');
        Route::get('sections/{key?}', 'allSections');
        Route::get('ticket/{ticket}', 'viewTicket');
        Route::post('ticket/ticket-reply/{id}', 'replyTicket');

        Route::get('market-overview', 'marketOverview');
        Route::get('market-list', 'marketList');
        Route::get('crypto-list', 'cryptoList');
        Route::get('currencies', 'currencies');
    });

    Route::controller("TradeController")->prefix('trade')->group(function () {
        Route::get('order/book/{symbol?}', 'orderBook')->name('trade.order.book');
        Route::get('pairs', 'pairs')->name('trade.pairs');
        Route::get('pair/add-to-favorite', 'addToFavorite');
        Route::get('history/{symbol}', 'history')->name('trade.history');
        Route::get('order/list/{symbol?}', 'orderList')->name('trade.order.list')->middleware('auth:sanctum');
        Route::get('currency', 'currency');
        Route::get('{symbol?}', 'trade')->name('trade');
    });


    Route::middleware('auth:sanctum')->group(function () {

        Route::post('user-data-submit', 'UserController@userDataSubmit');

        //authorization
        Route::middleware('registration.complete')->controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode');
            Route::post('verify-email', 'emailVerification');
            Route::post('verify-mobile', 'mobileVerification');
            Route::post('verify-g2fa', 'g2faVerification');
        });

        Route::middleware(['check.status'])->group(function () {

            Route::get('user-info', 'UserController@userInfo');
            
            Route::middleware('registration.complete')->group(function () {

                Route::controller('UserController')->group(function () {

                    Route::get('dashboard', 'dashboard');

                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');

                    //KYC
                    Route::get('kyc-form', 'kycForm');
                    Route::get('kyc-data','kycData');
                    Route::post('kyc-submit', 'kycSubmit');

                    //Report
                    Route::any('deposit/history', 'depositHistory');
                    Route::get('transactions', 'transactions');

                    Route::get('referrals', 'referrals');

                    Route::post('add-device-token', 'addDeviceToken');
                    Route::get('push-notifications', 'pushNotifications');
                    Route::post('push-notifications/read/{id}', 'pushNotificationsRead');

                    //2FA
                    Route::get('twofactor', 'show2faForm');
                    Route::post('twofactor/enable', 'create2fa');
                    Route::post('twofactor/disable', 'disable2fa');

                    Route::post('delete-account', 'deleteAccount');

                    Route::post('validate/password', 'validatePassword');
                    Route::get('pair/add/to/favorite/{pairSym}', 'addToFavorite')->name('add.pair.to.favorite');

                    Route::get('notifications', 'notifications');

                    Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
                });

                Route::controller('OrderController')->group(function () {
                    Route::prefix('order')->group(function () {
                        Route::get('open', 'open');
                        Route::get('completed', 'completed');
                        Route::get('canceled', 'canceled');
                        Route::post('cancel/{id}', 'cancel');
                        Route::post('update/{id}', 'update');
                        Route::get('history', 'history');
                        Route::post('save/{symbol}', 'save')->name('save');
                    });
                    Route::get('trade-history', 'tradeHistory')->name('trade.history');
                });

                //wallet
                Route::controller('WalletController')->name('wallet.')->prefix('wallet')->group(function () {
                    Route::get('list/{type?}', 'list')->name('list');
                    Route::post('transfer', 'transfer')->name('transfer');
                    Route::post('transfer/to/wallet', 'transferToWallet')->name('transfer.to.other.wallet');
                    Route::get('{type}/{currencySymbol}', 'view')->name('view');
                });

                // Withdraw
                Route::controller('WithdrawController')->group(function () {
                    Route::middleware('kyc')->group(function () {
                        Route::get('withdraw-method', 'withdrawMethod');
                        Route::post('withdraw-request', 'withdrawStore');
                        Route::post('withdraw-request/confirm', 'withdrawSubmit');
                    });
                    Route::get('withdraw/history', 'withdrawLog');
                });

                // Payment
                Route::controller('PaymentController')->group(function () {
                    Route::get('deposit/methods', 'methods');
                    Route::post('deposit/insert', 'depositInsert');
                    Route::post('manual/confirm', 'manualDepositConfirm');
                });

                Route::controller('TicketController')->prefix('ticket')->group(function () {
                    Route::get('/', 'supportTicket');
                    Route::post('create', 'storeSupportTicket');
                    Route::get('view/{ticket}', 'viewTicket');
                    Route::post('reply/{id}', 'replyTicket');
                    Route::post('close/{id}', 'closeTicket');
                    Route::get('download/{attachment_id}', 'ticketDownload');
                });

                // p2p trade
                Route::namespace('P2P')->prefix('p2p')->group(function () {
                    Route::controller('HomeController')->group(function () {
                        Route::get('/', "index");
                        Route::get('/feedback/list', "feedbackList");
    
                        Route::get('list', 'list');
                        Route::get('advertiser/{id}', 'advertiser');
                    });
                
                    Route::controller("UserP2PPaymentMethodController")->prefix('payment-method')->group(function () {
                        Route::get('', "list");
                        Route::get('create', "create");
                        Route::post('save/{id?}', "save");
                        Route::get('edit/{id}', "edit");
                        Route::post('delete/{id}', "delete");
                    });
                
                    Route::controller("AdvertisementController")->prefix('advertisement')->group(function () {
                        Route::get('/', 'index');
                        Route::get('/create/{id?}', 'create');
                        Route::post('/save/{id?}', 'save');
                        Route::post('/change/status/{id}', 'changeStatus');
                    });
                
                    Route::prefix('trade')->group(function () {
                        Route::controller("TradeController")->group(function () {
                            Route::get('/request/{id}', 'request');
                            Route::post('/request/save/{id}', 'requestSave');
                            Route::get('/details/{id}', 'details');
                            Route::post('/cancel/{id}', 'cancel');
                            Route::post('/paid/{id}', 'paid');
                            Route::post('/release/{id}', 'release');
                            Route::post('/dispute/{id}', 'dispute');
                            Route::post('/delete/feedback/{id}', 'feedbackDelete');
                            Route::post('/feedback/{id}', 'feedback');
                            Route::get('{scope}', 'list');
                        });
                        Route::controller("MessageController")->prefix("message")->group(function () {
                            Route::post('/save/{tradeId}', 'save');
                        });
                    });
                });


                Route::controller('BinaryTradeOrderController')->prefix('binary')->group(function () {
                    Route::post('trade/order', 'binaryTradeOrder');
                    Route::post('trade/complete', 'binaryTradeComplete');
                    Route::get('trade/all', 'allTrade');
                    Route::get('trade/win', 'winTrade');
                    Route::get('trade/lose', 'loseTrade');
                    Route::get('trade/refund', 'refundTrade');
                });

                Route::controller("BinaryTradeController")->prefix('binary')->group(function () {
                    Route::get('trade/{id?}', 'binary')->name('binary');
                });

            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });
});
