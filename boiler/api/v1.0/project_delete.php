<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$project_id              = safeCheck($_POST['project_id']);
$delreason               = HTMLEncode($_POST['delreason']);

try {

    $projectinfo = Project::getInfoById($project_id);

    $loginuser = User::getInfoById($uid);
    if(!($loginuser["role"] == 3 || $loginuser["role"] == 2))
    {
        if ($loginuser['id']!=$projectinfo['user']||$projectinfo['level']!=0){
            throw new MyException("没有删除权限!",1060);
        }
    }

    $attrsProject = array(
        "del_flag"=>1,
        "del_reason"=>$delreason,
        "lastupdate"=>time()
    );

    Project::update($project_id,$attrsProject);
    $msgcontent = '您提交的'.$projectinfo['name'].'已被删除';
    if($delreason){
        $msgcontent .= "，删除原因是".$delreason;
    }
    $msgcontent .= "。";
    $msgAttrs = array(
        "recipients" => $projectinfo['user'],
        "sender" => $uid,
        "title" => "项目删除提醒-".$projectinfo['name'],
        "content" => $msgcontent,
        "addtime"=>time()
    );
    $msgid = Message_info::add($msgAttrs);

    $jpushuser = User::getInfoById($projectinfo['user']);
//    if($jpushuser['register_id'] && $msgid > 0){
//        JPUSH_send($jpushuser['register_id'], $msgcontent, "项目删除提醒-".$projectinfo['name'], $project_id, $msgid);
//    }

    $chart_room_id = Chat_room::getRoomIdByProject($project_id);
    if(!empty($chart_room_id)){
        $roomAttr = array(
            "status"=>-1
        );
        Chat_room::update($chart_room_id,$roomAttr);
    }

    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>