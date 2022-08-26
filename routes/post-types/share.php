<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('share/{id}', 'ShareController@show')->middleware('cash');
    Route::get('shares/{id}', 'ShareController@category')->middleware('cash');

    Route::post('admin/shares', 'AdminShareController@index')->middleware('api_auth');
    Route::post('admin/share/update', 'AdminShareController@update')->middleware('api_auth');
    Route::post('admin/share/delete', 'AdminShareController@delete')->middleware('api_auth');
    Route::post('admin/share/store', 'AdminShareController@store')->middleware('api_auth');

    Route::post('admin/share/category', 'AdminShareCategoryController@index')->middleware('api_auth');
    Route::post('admin/share/category/update', 'AdminShareCategoryController@update')->middleware('api_auth');
    Route::post('admin/share/category/delete', 'AdminShareCategoryController@delete')->middleware('api_auth');
    Route::post('admin/share/category/store', 'AdminShareCategoryController@store')->middleware('api_auth');
    Route::post('admin/share/category/{id}', 'AdminShareCategoryController@show')->middleware('api_auth');

    Route::post('admin/share/{id}', 'AdminShareController@show')->middleware('api_auth');
});