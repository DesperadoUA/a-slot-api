<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::post('admin/login', 'LoginController@index');
    Route::post('admin/logout', 'LoginController@logout');
    Route::post('admin/check-user', 'LoginController@checkUser');
});