<?php

use Illuminate\Http\Request;

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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*Route::middleware([''])->group(function (){
    Route::get('lists', 'ExcelController@lists')->name('api.excel.lists');
});*/

Route::middleware([])->group(function (){
    Route::get('lists', 'ExcelController@lists')->name('api.excel.lists');
    Route::post('submit', 'ExcelController@submit')->name('api.excel.submit');//提交试卷
    Route::get('recordAnalysis', 'ExcelController@recordAnalysis')->name('api.excel.recordAnalysis');//分析试卷
    Route::post('getScore', 'ExcelController@getScore')->name('api.excel.getScore');//获取分数





});
