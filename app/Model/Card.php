<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = "card";
    protected $fillable = ["user_id","card_avatar","card_name","card_height","card_weight","card_shoes","card_bust","card_waist","card_hips","card_citys","card_tips"];

    /**
     * @param $get
     * @return \Illuminate\Http\JsonResponse
     * 模卡增修
     */
    protected static function addCard($get){
        $get['user_id'] = User::where('openid',$get['id'])->value('id');
        $cardIsHave     = Card::where('user_id',$get['user_id'])->value('id');
            if($get['step'] == 1){
                if(!$cardIsHave){
                    // 第一步保存基本信息
                    $addSuccess = Card::create($get);
                }else{
                    // 第一步修改
                    $addSuccess = self::updateCard($get,$cardIsHave);
                }
            }else if($get['step'] == 2){
                // 第二步保存城市、标签
                $update['card_citys']  = json_encode($get['card_citys']);
                $update['card_tips']   = json_encode($get['card_tips']);
                $addSuccess  = Card::where('user_id',$get['user_id'])->update($update);
            }else if($get['step'] == 3){
                // 第三步保存图片，完成
            }

            if($addSuccess){
                return response()->json(["code"=>200,"msg"=>"保存成功"]);
            }else{
                return response()->json(["code"=>403,"msg"=>"保存失败"]);
            }
    }

    /**
     * @param $get 数据
      * @param $cardId 数据id
     * @return int
     * 模卡第一步修改
     */
    protected static function updateCard($get,$cardId){
        // 模卡修改
        $fillable = ["card_avatar","card_name","card_height","card_weight","card_shoes","card_bust","card_waist","card_hips"];
        foreach ( $get as $key=>$item) {
            if(!in_array($key,$fillable)){
                unset($get[$key]);
            }
        }
        $updateSuccess = Card::where('id',$cardId)->update($get);
        if($updateSuccess){
            return 1;
        }
        return 0;
    }
}
