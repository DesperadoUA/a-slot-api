<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::post('admin/pages', 'AdminPageController@index')->middleware('api_auth');
    Route::post('admin/pages/update', 'AdminPageController@update')->middleware('api_auth');
    Route::post('admin/pages/{id}', 'AdminPageController@show')->middleware('api_auth');
    /* Front */
    Route::get('pages/'.config('constants.PAGES.MAIN'), 'PageController@main')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.CASINO'), 'PageController@casinos')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.BONUS'), 'PageController@bonuses')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.GAME'), 'PageController@games')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.VENDOR'), 'PageController@vendors')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.PAYMENT'), 'PageController@payments')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.POKER'), 'PageController@pokers')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.BETTING'), 'PageController@bettings')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.NEWS'), 'PageController@news')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.SITE_MAP'), 'PageController@siteMap')->middleware('cash');
    Route::get(config('constants.PAGES.SEARCH'), 'PageController@search');
});