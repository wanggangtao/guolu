<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$project_id              = safeCheck($_POST['project_id']);
$del_same_id             = safeCheck($_POST['del_same_id']);

try {

    $loginuser = User::getInfoById($uid);
    if(!($loginuser["role"] == 3 || $loginuser["role"] == 2))
    {
        throw new MyException("没有删除权限!",1060);
    }

    $projectinfo = Project::getInfoById($project_id);
    $notsame_id = $projectinfo['notsame_id'];
    if(empty($notsame_id)){
        $notsame_id = $del_same_id;
    }else{
        $notsame_id .= ','.$del_same_id;
    }
    $attrsProject = array(
        "notsame_id"=>$notsame_id,
        "lastupdate"=>time()
    );
    Project::update($project_id,$attrsProject);

    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>