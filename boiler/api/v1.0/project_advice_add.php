<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */
try {

    $project_id       =  isset($_POST['project_id'])?safeCheck($_POST['project_id']):0;
    $advice           = HTMLEncode($_POST['advice']);

    if(empty($project_id)) throw new MyException("缺少项目ID参数",101);

    $projectinfo = Project::getInfoById($project_id);

    if(empty($projectinfo) || $projectinfo['del_flag'] == 1)
        throw new MyException("项目不存在",101);

    $userinfo = User::getInfoById($projectinfo['user']);

    $loginuser = User::getInfoById($uid);
    if (!($projectinfo['user'] == $uid || $userinfo['parent'] == $uid || $loginuser['role'] == 3 || ($loginuser['role'] == 4 && $userinfo['department'] == $loginuser['department']))) {
        throw new MyException("没有权限！",102);
    }

    $attrsAdvice = array(
        "content"=>$advice,
        "projectid"=>$project_id,
        "user"=>$uid,
        "addtime"=>time()
    );
    Project_advice::add($attrsAdvice);
    //给报备人发提醒
    $msgcontent = $loginuser['name'].'给您提交的'.$projectinfo['name'].'提出了建议，建议内容是'.$advice.'，请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$project_id.'">马上处理>></a>';
    $msgAttrs = array(
        "recipients" => $projectinfo['user'],
        "sender" => $uid,
        "title" => "项目建议提醒-".$projectinfo['name'],
        "content" => $msgcontent,
        "addtime"=>time()
    );
    $msgid = Message_info::add($msgAttrs);

    $jpushuser = User::getInfoById($projectinfo['user']);

    $roomId = Chat_room::getRoomIdByProject($project_id);
    if(!empty($roomId)){
        $content = $loginuser['name'].'给'.$jpushuser['name'].'提交的'.$projectinfo['name'].'提出了建议，建议内容是'.$advice;
        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_SIMILAR);

    }
    $roomInfo = Chat_room::getInfoByProject($project_id);
    if (!empty($roomInfo)){
        $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
        $extra = $project_id.','.$projectinfo['user'];
        if (!empty($reportRoomId)){
            socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_SIMILAR,1,$extra);
        }
    }


//    if($jpushuser['register_id'] && $msgid > 0){
//        $jpushcontent = $loginuser['name'].'给您提交的'.$projectinfo['name'].'提出了建议，建议内容是'.$advice.'。';
//        JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目建议提醒-".$projectinfo['name'], $project_id, $msgid);
//    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, null);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
?>