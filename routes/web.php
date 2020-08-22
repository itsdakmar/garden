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
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/{type}', 'HomeController@index')->name('chart');
Route::get('/firebase/{type}', 'FirebaseController@index');
Route::get('/firebase/realtime/{type}', 'FirebaseController@realtime');
Route::get('/data-table', 'Datatable@Controllerindex');
