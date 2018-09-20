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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'Api'],function(){
    // 修改昵称、简介
    Route::any("/update","Api@updateInfo");
    // 修改头像
    Route::any("/updateAvatar","Api@updateAvatar");
});


// wechat
Route::group(['namespace'=>'Api'],function(){
    Route::any("/getinfo","Wechat@getinfo");

});