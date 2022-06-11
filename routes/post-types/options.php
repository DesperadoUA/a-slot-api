<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::post('admin/options', 'AdminOptionsController@index')->middleware('api_auth');
    Route::post('admin/options/update', 'AdminOptionsController@update')->middleware('api_auth');
    Route::post('admin/options/{id}', 'AdminOptionsController@show')->middleware('api_auth');
    Route::get('options', 'OptionsController@index');
});