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
    Route::get('pages/'.config('constants.PAGES.MAIN'), 'PageController@main')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.CASINOS'), 'PageController@casinos')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.BONUSES'), 'PageController@bonuses')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.GAMES'), 'PageController@games')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.VENDORS'), 'PageController@vendors')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.PAYMENTS'), 'PageController@payments')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.POKERS'), 'PageController@pokers')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.COUNTRIES'), 'PageController@countries')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.CURRENCIES'), 'PageController@currencies')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.LANGUAGES'), 'PageController@languages')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.LICENSES'), 'PageController@licenses')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.TYPE_PAYMENTS'), 'PageController@typePayments')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.TECHNOLOGIES'), 'PageController@technologies')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.TYPE_BONUSES'), 'PageController@typeBonuses')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.SITE_MAP'), 'PageController@siteMap')->middleware('cash');
    Route::get(config('constants.PAGES.SEARCH'), 'PageController@search');

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

    Route::post('admin/login', 'LoginController@index');
    Route::post('admin/logout', 'LoginController@logout');
    Route::post('admin/check-user', 'LoginController@checkUser');

    Route::post('admin/search', 'AdminSearchController@index')->middleware('api_auth');

    Route::post('admin/uploads', 'AdminUploadsController@index')->middleware('api_auth');
    Route::post('admin/media', 'AdminUploadsController@media')->middleware('api_auth');
    Route::post('admin/delete-media', 'AdminUploadsController@delete')->middleware('api_auth');

    Route::post('admin/options', 'AdminOptionsController@index')->middleware('api_auth');
    Route::post('admin/options/update', 'AdminOptionsController@update')->middleware('api_auth');
    Route::post('admin/options/{id}', 'AdminOptionsController@show')->middleware('api_auth');

    Route::post('admin/settings', 'AdminSettingsController@index')->middleware('api_auth');
    Route::post('admin/settings/update', 'AdminSettingsController@update')->middleware('api_auth');
    Route::post('admin/settings/{id}', 'AdminSettingsController@show')->middleware('api_auth');

    Route::post('admin/pages', 'AdminPageController@index')->middleware('api_auth');
    Route::post('admin/pages/update', 'AdminPageController@update')->middleware('api_auth');
    Route::post('admin/pages/{id}', 'AdminPageController@show')->middleware('api_auth');

    Route::post('admin/casinos', 'AdminCasinoController@index')->middleware('api_auth');
    Route::post('admin/casino/update', 'AdminCasinoController@update')->middleware('api_auth');
    Route::post('admin/casino/delete', 'AdminCasinoController@delete')->middleware('api_auth');
    Route::post('admin/casino/store', 'AdminCasinoController@store')->middleware('api_auth');

    Route::post('admin/casino/category', 'AdminCasinoCategoryController@index')->middleware('api_auth');
    Route::post('admin/casino/category/update', 'AdminCasinoCategoryController@update')->middleware('api_auth');
    Route::post('admin/casino/category/delete', 'AdminCasinoCategoryController@delete')->middleware('api_auth');
    Route::post('admin/casino/category/store', 'AdminCasinoCategoryController@store')->middleware('api_auth');
    Route::post('admin/casino/category/{id}', 'AdminCasinoCategoryController@show')->middleware('api_auth');

    Route::post('admin/casino/{id}', 'AdminCasinoController@show')->middleware('api_auth');

    Route::post('admin/pokers', 'AdminPokerController@index')->middleware('api_auth');
    Route::post('admin/poker/update', 'AdminPokerController@update')->middleware('api_auth');
    Route::post('admin/poker/delete', 'AdminPokerController@delete')->middleware('api_auth');
    Route::post('admin/poker/store', 'AdminPokerController@store')->middleware('api_auth');

    Route::post('admin/poker/category', 'AdminPokerCategoryController@index')->middleware('api_auth');
    Route::post('admin/poker/category/update', 'AdminPokerCategoryController@update')->middleware('api_auth');
    Route::post('admin/poker/category/delete', 'AdminPokerCategoryController@delete')->middleware('api_auth');
    Route::post('admin/poker/category/store', 'AdminPokerCategoryController@store')->middleware('api_auth');
    Route::post('admin/poker/category/{id}', 'AdminPokerCategoryController@show')->middleware('api_auth');

    Route::post('admin/poker/{id}', 'AdminPokerController@show')->middleware('api_auth');

    Route::post('admin/games', 'AdminGameController@index')->middleware('api_auth');
    Route::post('admin/game/update', 'AdminGameController@update')->middleware('api_auth');
    Route::post('admin/game/delete', 'AdminGameController@delete')->middleware('api_auth');
    Route::post('admin/game/store', 'AdminGameController@store')->middleware('api_auth');

    Route::post('admin/game/category', 'AdminGameCategoryController@index')->middleware('api_auth');
    Route::post('admin/game/category/update', 'AdminGameCategoryController@update')->middleware('api_auth');
    Route::post('admin/game/category/delete', 'AdminGameCategoryController@delete')->middleware('api_auth');
    Route::post('admin/game/category/store', 'AdminGameCategoryController@store')->middleware('api_auth');
    Route::post('admin/game/category/{id}', 'AdminGameCategoryController@show')->middleware('api_auth');

    Route::post('admin/game/{id}', 'AdminGameController@show')->middleware('api_auth');

    Route::post('admin/bonuses', 'AdminBonusController@index')->middleware('api_auth');
    Route::post('admin/bonus/update', 'AdminBonusController@update')->middleware('api_auth');
    Route::post('admin/bonus/delete', 'AdminBonusController@delete')->middleware('api_auth');
    Route::post('admin/bonus/store', 'AdminBonusController@store')->middleware('api_auth');

    Route::post('admin/bonus/category', 'AdminBonusCategoryController@index')->middleware('api_auth');
    Route::post('admin/bonus/category/update', 'AdminBonusCategoryController@update')->middleware('api_auth');
    Route::post('admin/bonus/category/delete', 'AdminBonusCategoryController@delete')->middleware('api_auth');
    Route::post('admin/bonus/category/store', 'AdminBonusCategoryController@store')->middleware('api_auth');
    Route::post('admin/bonus/category/{id}', 'AdminBonusCategoryController@show')->middleware('api_auth');

    Route::post('admin/bonus/{id}', 'AdminBonusController@show')->middleware('api_auth');

    Route::post('admin/vendors', 'AdminVendorController@index')->middleware('api_auth');
    Route::post('admin/vendor/update', 'AdminVendorController@update')->middleware('api_auth');
    Route::post('admin/vendor/delete', 'AdminVendorController@delete')->middleware('api_auth');
    Route::post('admin/vendor/store', 'AdminVendorController@store')->middleware('api_auth');

    Route::post('admin/vendor/category', 'AdminVendorCategoryController@index')->middleware('api_auth');
    Route::post('admin/vendor/category/update', 'AdminVendorCategoryController@update')->middleware('api_auth');
    Route::post('admin/vendor/category/delete', 'AdminVendorCategoryController@delete')->middleware('api_auth');
    Route::post('admin/vendor/category/store', 'AdminVendorCategoryController@store')->middleware('api_auth');
    Route::post('admin/vendor/category/{id}', 'AdminVendorCategoryController@show')->middleware('api_auth');

    Route::post('admin/vendor/{id}', 'AdminVendorController@show')->middleware('api_auth');

    Route::post('admin/news', 'AdminNewsController@index')->middleware('api_auth');
    Route::post('admin/news/update', 'AdminNewsController@update')->middleware('api_auth');
    Route::post('admin/news/delete', 'AdminNewsController@delete')->middleware('api_auth');
    Route::post('admin/news/store', 'AdminNewsController@store')->middleware('api_auth');

    Route::post('admin/news/category', 'AdminNewsCategoryController@index')->middleware('api_auth');
    Route::post('admin/news/category/update', 'AdminNewsCategoryController@update')->middleware('api_auth');
    Route::post('admin/news/category/delete', 'AdminNewsCategoryController@delete')->middleware('api_auth');
    Route::post('admin/news/category/store', 'AdminNewsCategoryController@store')->middleware('api_auth');
    Route::post('admin/news/category/{id}', 'AdminNewsCategoryController@show')->middleware('api_auth');

    Route::post('admin/news/{id}', 'AdminNewsController@show')->middleware('api_auth');

    Route::get('settings', 'SettingsController@index');
    Route::get('options', 'OptionsController@index');

});

