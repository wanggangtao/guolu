<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $userInfo = user::getInfoById($uid);
    if(empty($userInfo))
    {
        throw new MyException("用户信息不存在!",1041);
    }



    unset($userInfo["password"]);

    $userInfo["headpic"] = empty($userInfo["headimg"])?"":$HTTP_PATH.$userInfo["headimg"];
    unset($userInfo["headimg"]);
    unset($userInfo["status"]);

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$userInfo);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
