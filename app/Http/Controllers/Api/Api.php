<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class Api extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 修改昵称、简介
     */
    public function updateInfo(Request $request){
        $get = $request->except(['s']);
        return User::updateInfo($get);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 修改头像
     */
    public function updateAvatar(Request $request){
        $openid = $request->input('id');
        $file   = $request->file('avatar')->store('user','user');
        return User::updateAvatar($openid,$file);
    }
}
