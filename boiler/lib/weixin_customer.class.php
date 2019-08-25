<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/25
 * Time: 12:11
 */


class weixin_customer{

    const BASECONFIG_KEY = 'weixin_miyu';
    const BASECONFIG_NAME = '微信客服秘语';


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_weixin_customer::add($attrs);

        return $id;
    }


    static public function getInfoById($id){
        return Table_weixin_customer::getInfoById($id);
    }


    static public function update($id, $attrs){
        return Table_weixin_customer::update($id, $attrs);
    }


    static public function del($id){
        return Table_weixin_customer::del($id);
    }

    static public function dels($id){
        return Table_weixin_customer::dels($id);
    }

    static public function getList($params){
        return Table_weixin_customer::getList($params);
    }

    static public function getCount($params){
        return Table_weixin_customer::getCount($params);
    }

    static public function getInfoByOpenid($openid){
        return Table_weixin_customer::getInfoByOpenid($openid);
    }

    static public  function _doText($weixin , $content,$password){

        //接受文本信息
        if($password  === $weixin->msg['Content']){
            $reply = $weixin->makeText($content);
            $weixin->reply($reply);

            $userOpenId = $weixin->msg['FromUserName'];
            $customerInfo =  self::getInfoByOpenid($userOpenId);
            if(empty($customerInfo)){
                $personal_info = $weixin->getUserInfo($userOpenId);
                $attrs =array("nickname" => $personal_info['nickname'] ,
                    "openid" => $userOpenId,
                    "addtime"=>time());

                self::add($attrs);
            }
        }
    }
}


?>