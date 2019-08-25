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

    $projectinfo = Project::getInfoById($project_id);

    if($projectinfo["level"]==Project::UN_REPORT&&$projectinfo["status"]==Project::SUBMITED)
    {
        throw new MyException("该项目尚未报备成功,不能提交!",1060);
    }



    if(empty($stopreason)){
        throw new MyException("终止原因不能为空!",1341);
    }
    if(!Project::checkProjectForUser($uid,$project_id)) throw new MyException("无权限查看该项目",1039);
    $attrsProject = array(
        "stop_flag"=>1,
        "status"=>2,
        "stopreason"=>$stopreason,
        "lastupdate"=>time()
    );

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

    Project::update($project_id,$attrsProject);
    $userInfo = User::getInfoById($projectinfo['user']);
    $msgcontent = $userInfo['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';
    $msgAttrs = array(
        "recipients" => $userInfo['parent']?$userInfo['parent']:$projectinfo['user'],
        "sender" => $projectinfo['user'],
        "title" => "项目阶段审核申请-".$projectinfo['name'],
        "content" => $msgcontent,
        "addtime"=>time()
    );
    $msgid = Message_info::add($msgAttrs);



    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>