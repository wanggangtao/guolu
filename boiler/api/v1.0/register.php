<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/12
 * Time: 上午9:52
 */

$mobile =  safeCheck($_POST['mobile'],0);
$password =  safeCheck($_POST['password'],0);
$repassword =  safeCheck($_POST['repassword'],0);

try {
    if(!ParamCheck::is_mobile($mobile)){
        throw new MyException("手机号格式不正确！",701);
    }
    $userlist = User::getInfoByAccount($mobile);
    if(!empty($userlist)){
        throw new MyException("账号已存在！",705);
    }
    if($password != $repassword){
        throw new MyException("密码不一致！",702);
    }
    if(ParamCheck::is_weakPwd($password)){
        throw new MyException("密码格式不正确！",703);
    }

    $attrs = array(
        "account"=>$mobile,
        "password"=>$password,
        "role"=>1,
        "addtime"=>time(),
        "lastupdate"=>time()
    );

    $rs = User::add($attrs);
    if($rs > 0){
        $userInfo = user::getInfoById($rs);
        if(empty($userInfo))
        {
            throw new MyException("用户信息不存在!",1041);
        }
        unset($userInfo["password"]);

        $userInfo["headpic"] = empty($userInfo["headimg"])?"":$HTTP_PATH.$userInfo["headimg"];
        unset($userInfo["headimg"]);
        unset($userInfo["status"]);
        unset($userInfo["salt"]);

        echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$userInfo);
    }else{
        throw new MyException("注册失败！",704);
    }
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>