<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Wechat extends Controller
{
    /**
     * @param Request $request code=>小程序获取的用户code码
     * @return \Illuminate\Http\JsonResponse
     * 小程序授权登录保存用户信息
     *
     */
    public function getinfo(Request $request){
        $code = $request->input('code');
        return User::getinfo($code);
    }


}
