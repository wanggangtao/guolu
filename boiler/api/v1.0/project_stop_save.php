<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$project_id              = safeCheck($_POST['project_id']);
$stopreason              = HTMLEncode($_POST['stopreason']);

try {
    if(empty($stopreason)){
        throw new MyException("终止原因不能为空!",1351);
    }
    $attrsProject = array(
        "stopreason"=>$stopreason,
        "lastupdate"=>time()
    );
    $projectinfo = Project::getInfoById($project_id);
    $roomId = Chat_room::getRoomIdByProject($project_id);
    if(!empty($roomId)){
        $content = $userInfo['name'].'对项目终止原因做了修改';
        if ($projectinfo['stopreason']!=$stopreason){
            $message = '修改内容是'.$stopreason;
        }
        if (!empty($message)){
            $content.=$message;
            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
            $roomInfo = Chat_room::getInfoByProject($project_id);
            if (!empty($roomInfo)){
                $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                $extra = $project_id.','.$projectinfo['user'];
                if (!empty($reportRoomId)){
                    socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_MODIFY,1,$extra);
                }

            }
        }
    }
    if(!Project::checkProjectForUser($uid,$project_id)) throw new MyException("无权限操作该项目",1352);
    Project::update($project_id,$attrsProject);
    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>