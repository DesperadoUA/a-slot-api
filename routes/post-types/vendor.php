<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('vendor/{id}', 'VendorController@show')->middleware('cash');

    Route::post('admin/vendors', 'AdminVendorController@index')->middleware('api_auth');
    Route::post('admin/vendor/update', 'AdminVendorController@update')->middleware('api_auth');
    Route::post('admin/vendor/delete', 'AdminVendorController@delete')->middleware('api_auth');
    Route::post('admin/vendor/store', 'AdminVendorController@store')->middleware('api_auth');

    Route::post('admin/vendor/category', 'AdminVendorCategoryController@index')->middleware('api_auth');
    Route::post('admin/vendor/category/update', 'AdminVendorCategoryController@update')->middleware('api_auth');
    Route::post('admin/vendor/category/delete', 'AdminVendorCategoryController@delete')->middleware('api_auth');
    Route::post('admin/vendor/category/store', 'AdminVendorCategoryController@store')->middleware('api_auth');
    Route::post('admin/vendor/category/{id}', 'AdminVendorCategoryController@show')->middleware('api_auth');

    Route::post('admin/vendor/{id}', 'AdminVendorController@show')->middleware('api_auth');
});