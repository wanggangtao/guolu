<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/12
 * Time: 上午9:52
 */

$oldpwd =  isset($_POST['oldpwd'])?safeCheck($_POST['oldpwd'],0):"";
$newpwd =  isset($_POST['newpwd'])?safeCheck($_POST['newpwd'],0):"";
$confirmpwd =  isset($_POST['confirmpwd'])?safeCheck($_POST['confirmpwd'],0):"";

try {

    $userInfo = user::getInfoById($uid);

    if(empty($userInfo))
    {
        throw new MyException("用户信息不存在!",1041);
    }

    $buildPwd = user::buildPassword($oldpwd,$userInfo["salt"]);
    if($buildPwd[0] == $userInfo["password"])
    {
        if($newpwd != $confirmpwd)
        {
            throw new MyException("两次密码不一致!",1042);

        }
        user::resetPwd($uid,$newpwd);

    }
    else
    {
        throw new MyException("旧密码错误!",1043);

    }



    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,null);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>