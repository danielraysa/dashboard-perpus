<?php

use Illuminate\Support\Facades\Route;

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
    return view('chart');
});

Route::get('/graph-data', 'DashboardController@graph_data')->name('graph-data');
Route::get('/graph-detail', 'DashboardController@detail_graph')->name('graph-detail');
