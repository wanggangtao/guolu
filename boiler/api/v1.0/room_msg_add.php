<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/12
 * Time: 上午9:52
 */


$uid =  isset($_POST['uid'])?safeCheck($_POST['uid'],0):"";
$room_id =  isset($_POST['room_id'])?safeCheck($_POST['room_id'],0):"";
$content =  isset($_POST['content'])?safeCheck($_POST['content'],0):"";
$extra =  isset($_POST['extra'])?safeCheck($_POST['extra'],0):"";
$size =  isset($_POST['size'])?safeCheck($_POST['size'],0):"";
$isShowTime =  !empty($_POST['isShowTime'])?safeCheck($_POST['isShowTime'],0):0;

$msg_type =  isset($_POST['msg_type'])?safeCheck($_POST['msg_type'],0):"";

try {

    $userInfo = user::getInfoById($uid);

    if(empty($userInfo))
    {
        throw new MyException("用户信息不存在!",1041);
    }

    $msgId = socket_message::sendMsgForRoomId($room_id,$content,0,$msg_type,$uid,$userInfo,$size,$extra,$isShowTime);

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,array("msg_id"=>$msgId));

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>