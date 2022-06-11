<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
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
});