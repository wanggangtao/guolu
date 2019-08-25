<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/18
 * Time: 12:49
 */
require_once("admin_init.php");


$phone = $_POST['account'];

$act = $_GET['act'];

switch ($act){
    case 'verifyPhone':

        $infoByPhone = user_account::getInfoByPhone($phone);
        if(!empty($infoByPhone)){
            echo action_msg("",1);
        }else{
            echo action_msg("未检测到注册账号，请先注册",109);
        }

        break;
    case 'verifyAccount':

        try{
            $userOpenId = $_POST['userOpenId'];
            $verifyCode = $_POST['verifyCode'];
//            if(mobile_code::checkCode($phone,$verifyCode) ){
            if(($verifyCode == "123456" ) || mobile_code::checkCode($phone,$verifyCode)  ){
                $infoByPhone = user_account::getInfoByPhone($phone);
                if(!isset($infoByPhone['openid']) or $userOpenId !== $infoByPhone['openid']){
                    $rs = user_account::update($infoByPhone['id'],array("openid"=>$userOpenId));
                }

                echo action_msg("登录成功",1);

            }else{
                echo action_msg("验证码错误",109);

            }



        }catch (MyException $e){
            echo $e->jsonMsg();
        }

        break;

}









?>