<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$id                 = safeCheck($_POST['project_id']);
$notice_one         = safeCheck($_POST['notice_one']);
$notice_two         = safeCheck($_POST['notice_two']);
$notice_three       = safeCheck($_POST['notice_three']);
if(empty($uid)) throw new MyException("缺少用户uid参数",401);
try {
    $userInfo = user::getInfoById($uid);
    if(!Project::checkProjectForUser($uid,$id)) throw new MyException("无权限查看该项目",1491);
    $attrsProject = array(
        "notice_one"=>$notice_one,
        "notice_two"=>$notice_two,
        "notice_three"=>$notice_three,
        "lastupdate"=>time()
    );

    Project::update($id,$attrsProject);
    $resultData = array("projectid"=>$id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>