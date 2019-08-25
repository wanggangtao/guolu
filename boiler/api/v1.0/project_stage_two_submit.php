<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午3:40
 */

$id                           = safeCheck($_POST['id']);
$project_id                   = safeCheck($_POST['project_id']);
$nowtime = time();

try {

    $projectinfo = Project::getInfoById($project_id);



    if($projectinfo["level"]==Project::UN_REPORT&&$projectinfo["status"]==Project::SUBMITED)
    {
        throw new MyException("该项目尚未报备成功,不能提交!",1060);
    }

    if($projectinfo["stop_flag"]==Project::STOP && $projectinfo["status"]==Project::CHECKED)
    {
        throw new MyException("该项目已经停止,不能提交!",1062);
    }

    if($projectinfo["stop_flag"]==Project::STOP && $projectinfo["status"]==Project::SUBMITED)
    {
        throw new MyException("项目终止已提交，暂不能提交!",1062);
    }
    $attrsProject = array(
        "status"=>2,
        "two_status"=>2,
        "reviewopinion"=>'',
        "lastupdate"=>$nowtime
    );
    if($projectinfo['one_status'] != 2 && $projectinfo['one_status'] != 3){
        throw new MyException("低星级还未提交，请先提交低星级!",1101);
    }
    if(empty($id)){//首次提交
        throw new MyException("还未保存，请先保存!",1102);
    }else{//提交前保存过
        Project::update($project_id,$attrsProject);
    }
    $userInfo = User::getInfoById($projectinfo['user']);
    $msgcontent = $userInfo['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';
    $msgAttrs = array(
        "recipients" => $userInfo['parent']?$userInfo['parent']:$projectinfo['user'],
        "sender" => $projectinfo['user'],
        "title" => "项目阶段审核申请-".$projectinfo['name'],
        "content" => $msgcontent,
        "addtime"=>$nowtime
    );
    $msgid = Message_info::add($msgAttrs);

    $roomId = Chat_room::getRoomIdByProject($project_id);
    if(!empty($roomId)){
        $content = $userInfo['name'].'已经提交了'.$projectinfo['name'].'，请尽快处理。';
        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_APPLY);
        $roomInfo = Chat_room::getInfoByProject($project_id);
        if (!empty($roomInfo)){
            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
            $extra = $project_id.','.$projectinfo['user'];
            if (!empty($reportRoomId)){
                socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_STEP_APPLY,1,$extra);
            }
        }

    }
//    $jpushuser = User::getInfoById($userInfo['parent']?$userInfo['parent']:$projectinfo['user']);
//    if($jpushuser['register_id'] && $msgid > 0){
//        $jpushcontent = $userInfo['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//        JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//    }

    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}