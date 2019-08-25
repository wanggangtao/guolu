<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$project_id              = safeCheck($_POST['project_id']);
$summarize               = HTMLEncode($_POST['summarize']);

try {
    if(empty($summarize)){
        throw new MyException("项目沉淀内容不能为空!",1341);
    }
    if(!Project::checkProjectForUser($uid,$project_id)) throw new MyException("无权限查看该项目",1039);
    $attrsProject = array(
        "summarize"=>$summarize,
        "lastupdate"=>time()
    );
    Project::update($project_id,$attrsProject);
    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>