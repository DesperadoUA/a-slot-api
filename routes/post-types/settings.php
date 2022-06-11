<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::post('admin/settings', 'AdminSettingsController@index')->middleware('api_auth');
    Route::post('admin/settings/update', 'AdminSettingsController@update')->middleware('api_auth');
    Route::post('admin/settings/{id}', 'AdminSettingsController@show')->middleware('api_auth');
    Route::get('settings', 'SettingsController@index');
});