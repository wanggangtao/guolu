<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$id                 = safeCheck($_POST['project_id']);
$bonus              = safeCheck($_POST['bonus']);
$bonus = $bonus / 100;

$bonus_stage              = safeCheck($_POST['bonus_stage'], 0);
$bonus_stage = rtrim($bonus_stage, '|');
try {
    if(!Project::checkProjectForUser($uid,$id)) throw new MyException("无权限查看该项目",1481);
    $attrsProject = array(
        "bonus"=>$bonus,
        "bonus_stage"=>$bonus_stage,
        "lastupdate"=>time()
    );
    Project::update($id,$attrsProject);
    $resultData = array("projectid"=>$id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>