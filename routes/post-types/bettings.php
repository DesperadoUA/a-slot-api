<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('betting/{id}', 'BettingController@show')->middleware('cash');

    Route::post('admin/bettings', 'AdminBettingController@index')->middleware('api_auth');
    Route::post('admin/betting/update', 'AdminBettingController@update')->middleware('api_auth');
    Route::post('admin/betting/delete', 'AdminBettingController@delete')->middleware('api_auth');
    Route::post('admin/betting/store', 'AdminBettingController@store')->middleware('api_auth');

    Route::post('admin/betting/category', 'AdminBettingCategoryController@index')->middleware('api_auth');
    Route::post('admin/betting/category/update', 'AdminBettingCategoryController@update')->middleware('api_auth');
    Route::post('admin/betting/category/delete', 'AdminBettingCategoryController@delete')->middleware('api_auth');
    Route::post('admin/betting/category/store', 'AdminBettingCategoryController@store')->middleware('api_auth');
    Route::post('admin/betting/category/{id}', 'AdminBettingCategoryController@show')->middleware('api_auth');

    Route::post('admin/betting/{id}', 'AdminBettingController@show')->middleware('api_auth');   
});
