<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('migrate', 'MigrateController@index');
Route::get('migrate/test', 'MigrateController@test');
Route::get('migrate/casino', 'MigrateController@casino');
Route::get('migrate/casino/category', 'MigrateController@casinoCategory');
Route::get('migrate/game', 'MigrateController@game');
Route::get('migrate/game/category', 'MigrateController@gameCategory');
Route::get('migrate/bonus/category', 'MigrateController@bonusCategory');
Route::get('migrate/poker/category', 'MigrateController@pokerCategory');