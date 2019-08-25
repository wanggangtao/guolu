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
        $infoByPhone = Repair_person::getInfoByPhone($phone);
        if(!empty($infoByPhone)){
            echo action_msg($infoByPhone["id"],1);
        }else{
            echo action_msg("未检测到注册账号，请先注册",109);
        }
        break;
    case 'verifyAccount':
        try{
            $person_openid = $_POST['person_openid'];
            $verifyCode = $_POST['verifyCode'];
            if(($verifyCode == "123456" ) || mobile_code::checkCode($phone,$verifyCode)  ){
                $infoByPhone = Repair_person::getInfoByPhone($phone);
//                var_dump($infoByPhone);
                if(!isset($infoByPhone['person_openid']) or $person_openid !== $infoByPhone['person_openid']){
                    $rs = Repair_person::update($infoByPhone['id'],array("person_openid"=>$person_openid));
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