<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/8/31
 * Time: 下午3:04
 */

class common{



    static public function getOpenId()
    {
        global  $isWeixin;

        $openId = "";

        if($isWeixin)
        {

            if(isset($_SESSION["openId"]))
            {
                $openId = $_SESSION["openId"];
            }
            else
            {
                $weixin = new weixin();//生成心得==新的对象

                if(isset($_GET["code"]))
                {
                    $weixinUserInfo = $weixin->getInfoByCode();

                    if(!empty($weixinUserInfo))
                    {
                        $openId = $weixinUserInfo["openid"];

                        $_SESSION["openId"] = $openId;
                    }
                }
                else
                {
                    $loginUrl = $weixin->login_url(getPageUrl());
                    header("Location:$loginUrl");
                    exit;
                }
            }


        }


        return $openId;

    }



}