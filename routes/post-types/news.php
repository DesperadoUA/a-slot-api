<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('news/{id}', 'NewsController@show')->middleware('cash');
    Route::get('news-category/{id}', 'NewsController@category')->middleware('cash');

    Route::post('admin/news', 'AdminNewsController@index')->middleware('api_auth');
    Route::post('admin/news/update', 'AdminNewsController@update')->middleware('api_auth');
    Route::post('admin/news/delete', 'AdminNewsController@delete')->middleware('api_auth');
    Route::post('admin/news/store', 'AdminNewsController@store')->middleware('api_auth');

    Route::post('admin/news/category', 'AdminNewsCategoryController@index')->middleware('api_auth');
    Route::post('admin/news/category/update', 'AdminNewsCategoryController@update')->middleware('api_auth');
    Route::post('admin/news/category/delete', 'AdminNewsCategoryController@delete')->middleware('api_auth');
    Route::post('admin/news/category/store', 'AdminNewsCategoryController@store')->middleware('api_auth');
    Route::post('admin/news/category/{id}', 'AdminNewsCategoryController@show')->middleware('api_auth');

    Route::post('admin/news/{id}', 'AdminNewsController@show')->middleware('api_auth');
});