<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "user";
    protected $fillable = ["openid","nickname","avatar","introduction","grade"];
    public function card(){
        return $this->hasOne(Card::class,"user_id","id");
    }
    /**
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     * 小程序授权登录保存用户信息
     */
    public static function getinfo($code){
        $config = config('wechat');
        $url    = "https://api.weixin.qq.com/sns/jscode2session?appid=".$config['appid'].
            "&secret=".$config['secret']."&js_code=".$code."&grant_type=authorization_code";
        $return_data = json_decode(file_get_contents($url));
        if(!isset($return_data->errcode)){
            // 正常获取了用户的id
            $addSuccess = self::addUser($return_data);
            if($addSuccess == 200){
                return response()->json(['code'=>200,'msg'=>"保存成功",'data'=>$return_data->openid]);
            }else if($addSuccess == 404){
                return response()->json(['code'=>403,'msg'=>"保存异常"]);
            }else{
                return response()->json(['code'=>200,'msg'=>"登录成功",'data'=>$return_data->openid]);
            }
        }else{
            return response()->json(['code'=>403,'msg'=>"code异常"]);
        }
    }

    /**
     * @param $userInfo
     * @return int 状态码
     */
    public static function addUser($userInfo){
        // 判断是否已经存在用户
        $userIsHave = User::where('openid',$userInfo->openid)->value('id');
        if(!$userIsHave){
            // 不存在新增
            $create['openid']   = $userInfo->openid;
            $create['nickname'] = $userInfo->nickname;
            $create['avatar']   = $userInfo->avatar;
            $addSuccess  = User::create($create);
            if($addSuccess){
                return 200;
            }else{
                return 404;
            }
        }
    }

    /**
     * @param $update
     * @return \Illuminate\Http\JsonResponse
     * 修改昵称、简介
     */
    public static function updateInfo($update){
        if(isset($update['nickname']) && isset($update['introduction'])){
            // 请求异常
            return response()->json(['code'=>403,'msg'=>"请求异常"]);
        }else if(isset($update['introduction'])){
            // 简介
            $fillable = "introduction";
        }else if(isset($update['nickname'])){
            // 修改昵称
            $fillable = "nickname";
        }else{
            return response()->json(['code'=>403,'msg'=>"请求异常"]);
        }
        $updateSuccess = User::where('openid',$update['id'])->update([$fillable=>$update["$fillable"]]);
        if($updateSuccess){
            return response()->json(['code'=>200,'msg'=>"保存成功"]);
        }else{
            return response()->json(['code'=>403,'msg'=>"保存失败"]);
        }
    }


    /**
     * @param mixed ...$data  $data[0]=>openid  $data[1]=>file路径
     * @return \Illuminate\Http\JsonResponse
     * 修改头像
     */
    public static function updateAvatar(...$data){
        if($data[0]){
            $updateSuccess = User::where('openid',$data[0])->update(['avatar'=>APPURL.$data[1]]);
            if($updateSuccess){
                return response()->json(['code'=>200,'msg'=>"保存成功","data"=>APPURL.$data[1]]);
            }
            return response()->json(['code'=>403,'msg'=>"保存失败"]);
        }else{
            return response()->json(['code'=>403,'msg'=>"保存失败"]);
        }
    }


    public static function testWechat(){
        $data = json_decode("{}");
        $data->openid   = "0xxmi8sqzzxhq2oak2231hsn2x";
        $data->nickname = "test";
        $data->avatar   = "http://www.xxxxx.com";
        return $data;
    }
}
