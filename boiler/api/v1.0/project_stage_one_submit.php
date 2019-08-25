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


    if($projectinfo["level"]==Project::UN_REPORT && $projectinfo["status"]==Project::SUBMITED)
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


//检验原锅炉功率和数量填写与否
    $projectOneInfo = Project_one::getInfoByProjectId($project_id);

    $historyAttr = explode("|",$projectOneInfo["project_history_attr"]);

    if(empty($historyAttr[0])) throw new MyException("请填写原锅炉功率!",401);

    if(empty($historyAttr[1])) throw new MyException("请填写原锅炉数量!",402);



    $attrsProject = array(
        "status"=>2,
        "one_status"=>2,
        "reviewopinion"=>'',
        "lastupdate"=>$nowtime
    );

    if($projectinfo['level'] == 0){
        $attrsProject['addtime'] = $nowtime;
    }

    if(empty($id)){//首次提交
        throw new MyException("还未保存，请先保存!",1061);
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
    $msgid = Message_info::add($msgAttrs);

    $jpushuser = User::getInfoById($userInfo['parent']?$userInfo['parent']:$projectinfo['user']);
//    if($jpushuser['register_id'] && $msgid > 0){
//        $jpushcontent = $userInfo['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//        JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//    }
    //给总经理发相似项目提醒
    if($projectinfo['level'] == 0) {
        $project_one = Project_one::getInfoByProjectId($project_id);
        $samecount = Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', '');
        if($samecount > 1){
            //$muserlist = User::getPageList( 0, 10, 1, '', 1, '', 3);
            //if($muserlist){
                //foreach ($muserlist as $thism){
                    $mmsgcontent = $userInfo['name'].'提交的'.$projectinfo['name'].'有'.($samecount-1).'个相似项目,请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_show.php?id='.$project_id.'">马上处理>></a>';
                    $mmsgAttrs = array(
                        "recipients" => $userInfo['parent']?$userInfo['parent']:$projectinfo['user'],
                        "sender" => $projectinfo['user'],
                        "title" => "相似项目提醒-".$projectinfo['name'],
                        "content" => $mmsgcontent,
                        "addtime"=>$nowtime
                    );
                    $mmsgid = Message_info::add($mmsgAttrs);


        }
    }
    $resultData = array("projectid"=>$project_id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}