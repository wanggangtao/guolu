<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$id                 = safeCheck($_POST['project_id']);
$export_flag        = safeCheck($_POST['export_flag']);

try {
    if(!Project::checkProjectForUser($uid,$id)) throw new MyException("无权限查看该项目",1501);
    $attrsProject = array(
        "export_flag"=>$export_flag,
        "lastupdate"=>time()
    );
    Project::update($id,$attrsProject);
    $resultData = array("projectid"=>$id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>