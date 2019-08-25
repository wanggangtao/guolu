<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/12
 * Time: 上午9:52
 */

$title =  safeCheck($_POST['title'],0);
$content =  safeCheck($_POST['content'],0);
$registerId =  isset($_POST['registerId'])?safeCheck($_POST['registerId'],0):"";
$project_id =  safeCheck($_POST['project_id']);
$msgid =  safeCheck($_POST['msgid']);

try {

    JPUSH_send($registerId, $content, $title, $project_id, $msgid);

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,null);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>