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

Route::get('/', 'DashboardController@index')->name('home');
Route::get('/koleksi', 'DashboardController@koleksi')->name('koleksi');
Route::get('/pinjaman', 'DashboardController@pinjaman')->name('pinjaman');
Route::get('/kunjungan', 'DashboardController@kunjungan')->name('kunjungan');
Route::get('/graph-data', 'DashboardController@graph_data')->name('graph-data');
Route::get('/graph-data', 'DashboardController@graph_data')->name('graph-data');
Route::get('/graph-kunjungan', 'DashboardController@graph_kunjungan')->name('graph-kunjungan');
Route::get('/graph-pinjaman', 'DashboardController@graph_pinjaman')->name('graph-pinjaman');
