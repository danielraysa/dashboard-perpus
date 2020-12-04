<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group(['middleware' => ['auth:api']], function () {
    Route::get('graph-data', 'Api\ChartController@graph_data')->name('graph-data');
    Route::get('graph-kunjungan', 'Api\ChartController@graph_kunjungan')->name('graph-kunjungan');
    Route::get('graph-pinjaman-lama', 'Api\ChartController@graph_pinjaman');
    Route::get('graph-pinjaman', 'Api\ChartController@graph_pinjaman_baru')->name('graph-pinjaman');
// });
