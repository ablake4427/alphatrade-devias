<?php

use Illuminate\Support\Facades\Route;


Route::namespace('User\P2P')->group(function () {
    Route::get('/', "HomeController@index")->name('dashboard');
    Route::get('/feedback/list', "HomeController@feedbackList")->name('feedback.list');

    Route::controller("UserP2PPaymentMethodController")->name('payment.method.')->prefix('payment-method')->group(function () {
        Route::get('', "list")->name('list');
        Route::get('create', "create")->name('create');
        Route::post('save/{id?}', "save")->name('save');
        Route::get('edit/{id}', "edit")->name('edit');
        Route::post('delete/{id}', "delete")->name('delete');
    });

    Route::controller("AdvertisementController")->name('advertisement.')->prefix('advertisement')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create/{id?}', 'create')->name('create');
        Route::post('/save/{id?}', 'save')->name('save');
        Route::post('/change/status/{id?}', 'changeStatus')->name('change.status');
    });

    Route::name('trade.')->prefix('trade')->group(function () {
        Route::controller("TradeController")->group(function () {
            Route::get('/request/{id}', 'request')->name('request');
            Route::post('/request/save/{id}', 'requestSave')->name('request.save');
            Route::get('/details/{id}', 'details')->name('details');
            Route::post('/cancel/{id}', 'cancel')->name('cancel');
            Route::post('/paid/{id}', 'paid')->name('paid');
            Route::post('/release/{id}', 'release')->name('release');
            Route::post('/dispute/{id}', 'dispute')->name('dispute');
            Route::post('/delete/feedback/{id}', 'feedbackDelete')->name('feedback.delete');
            Route::post('/feedback/{id}', 'feedback')->name('feedback');
            Route::get('{scope}', 'list')->name('list');
        });
        Route::controller("MessageController")->name("message.")->prefix("message")->group(function () {
            Route::post('/save/{tradeId}', 'save')->name('save');
        });
    });
});
