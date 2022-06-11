<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
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
});