<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $url      = safeCheck($_POST['url'], 0);

    if(empty($url)) throw new MyException("图片路径不能为空",101);

    if(empty($uid)) throw new MyException("缺少用户ID参数",101);

    $userinfo = User::getInfoById($uid);

    if(empty($userinfo))
    {
        throw new MyException("用户信息不存在",101);
    }

    $attrs = array(
        "headimg"=>$url,
        "lastupdate"=>time()
    );
    User::update($uid, $attrs);
    $resData = array(
        'uid' => $uid
    );

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
