<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('poker/{id}', 'PokerController@show')->middleware('cash');
    Route::get('pokers/{id}', 'PokerController@category')->middleware('cash');

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
});