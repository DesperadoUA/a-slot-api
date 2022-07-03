<?php

use Illuminate\Http\Request;

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

Route::namespace('Api')->group(function () {
/*
    Route::get('casino/{id}', 'CasinoController@show')->middleware('cash');
    Route::get('casinos/{id}', 'CasinoController@category')->middleware('cash');
    Route::get('poker/{id}', 'PokerController@show')->middleware('cash');
    Route::get('pokers/{id}', 'PokerController@category')->middleware('cash');
    Route::get('bonus/{id}', 'BonusController@show')->middleware('cash');
    Route::get('bonuses/{id}', 'BonusController@category')->middleware('cash');
    Route::get('game/{id}', 'GameController@show')->middleware('cash');
    Route::get('games/{id}', 'GameController@category')->middleware('cash');
    Route::get('vendor/{id}', 'VendorController@show')->middleware('cash');
    Route::get('vendors/{id}', 'VendorController@category')->middleware('cash');
    Route::get('payment/{id}', 'PaymentController@show')->middleware('cash');
    Route::get('payments/{id}', 'PaymentController@category')->middleware('cash');
    Route::get('country/{id}', 'CountryController@show')->middleware('cash');
    Route::get('countries/{id}', 'CountryController@category')->middleware('cash');
    Route::get('currency/{id}', 'CurrencyController@show')->middleware('cash');
    Route::get('currencies/{id}', 'CurrencyController@category')->middleware('cash');
    Route::get('language/{id}', 'LanguageController@show')->middleware('cash');
    Route::get('languages/{id}', 'LanguageController@category')->middleware('cash');
    Route::get('license/{id}', 'LicenseController@show')->middleware('cash');
    Route::get('licenses/{id}', 'LicenseController@category')->middleware('cash');
    Route::get('technology/{id}', 'TechnologyController@show')->middleware('cash');
    Route::get('technologies/{id}', 'TechnologyController@category')->middleware('cash');
    Route::get('type-payment/{id}', 'TypePaymentController@show')->middleware('cash');
    Route::get('type-payments/{id}', 'TypePaymentController@category')->middleware('cash');
    Route::get('type-bonus/{id}', 'TypeBonusController@show')->middleware('cash');
    Route::get('type-bonuses/{id}', 'TypeBonusController@category')->middleware('cash');
*/
    // ----  Admin ---- //
    Route::post('admin/search', 'AdminSearchController@index')->middleware('api_auth');
});

