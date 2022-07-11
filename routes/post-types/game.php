<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('game/{id}', 'GameController@show')->middleware('cash');

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
});