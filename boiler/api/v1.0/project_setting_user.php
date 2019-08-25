<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$id                 = safeCheck($_POST['project_id']);
$project_user       = safeCheck($_POST['project_user']);

try {
    if(!Project::checkProjectForUser($uid,$id)) throw new MyException("无权限查看该项目",1501);
    $attrsProject = array(
        "user"=>$project_user,
        "lastupdate"=>time()
    );
    Project::update($id,$attrsProject);
    //更新群负责人
    $room_info = Chat_room::getInfoByProject($id);
    if (!empty($room_info)){
        $user_info = User::getInfoById($project_user);
        $user_old = User::getInfoById($room_info['principal_uid']);
        if($user_old['role']==1){
            Chat_room_msg_config::delByUserId($room_info['principal_uid'],$room_info['id']);
        }
        $roomAttr = array(
            "principal_uid"=>$project_user,
            "principal_uname"=>$user_info['name'],

        );
        Chat_room::update($room_info['id'],$roomAttr);
        $rs = Chat_room_msg_config::getInfoByUid($project_user,$room_info['id']);
        if(empty($rs)){
            $config_attr = array(
                "room_id"=>$room_info['id'],
                "uid"=>$project_user,
                "addtime"=>time()
            );
            Chat_room_msg_config::add($config_attr);
        }
    }

    $resultData = array("projectid"=>$id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>