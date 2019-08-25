<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:03
 */

$id                           = safeCheck($_POST['id']);
$project_id                   = safeCheck($_POST['project_id']);
$competitive_brand_situation  = HTMLEncode($_POST['competitive_brand_situation']);
$progress_situation           = HTMLEncode($_POST['progress_situation']);
$invitation_situation         = HTMLEncode($_POST['invitation_situation']);
$other_situation              = HTMLEncode($_POST['other_situation']);
$nowtime = time();

try {
    $projectthreeid = 0;
    $userInfo = user::getInfoById($uid);

    $attrsProject = array(
        "lastupdate"=>$nowtime
    );
    $attrsPt = array(
        "project_id" => $project_id,
        "competitive_brand_situation"=>$competitive_brand_situation,
        "progress_situation"=>$progress_situation,
        "invitation_situation"=>$invitation_situation,
        "other_situation"=>$other_situation,
        "lastupdate"=>$nowtime
    );

    Project::update($project_id,$attrsProject);
    if(empty($id)){//首次保存
        $attrsPt['addtime'] = $nowtime;
        $rs = Project_three::add($attrsPt);
        $projectthreeid = $rs;
        $bakid = Project_three_bak::add($attrsPt);
        $attrsRecord = array(
            "user"=>$uid,
            "user_name"=>$userInfo['name'],
            "before_id"=>0,
            "after_id"=>$bakid,
            "project_id"=>$project_id,
            "addtime"=>$nowtime
        );
        Project_three_record::add($attrsRecord);

        //修改信息
//        $three_info = Project_three_bak::getInfoById($bakid);
//        $roomId = Chat_room::getRoomIdByProject($project_id);
//        if(!empty($roomId)){
//            $content = $userInfo['name'].'对第三阶段的';
//            foreach ($three_info as $k=>$val){
//                if($k=='id'||$k=='addtime'||$k=='lastupdate'||$k=='project_id'){
//                    continue;
//                } else{
//                    $content.=$ARRAY_Project_three[$k].'做了修改,'.'修改内容是'.$val.';';
//                }
//
//            }
//            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//        }

    }else{//之前保存过
        $projectthreeid = $id;
        Project_three::update($id, $attrsPt);
        $attrsPt['addtime'] = $nowtime;
        $beforeid = Project_three_bak::getInfoNewRecodeByPrid($project_id);
        $bakid = Project_three_bak::add($attrsPt);

        $roomId = Chat_room::getRoomIdByProject($project_id);
        if(!empty($roomId)){
//                    $project_before = Project_two_bak::getInfoById($beforeid['id']);
//                    $project_after = Project_two_bak::getInfoById($bakid);
            $project_three_before = Project_three_bak::getInfoById($beforeid['id']);
            $project_three_after = Project_three_bak::getInfoById($bakid);

            $content = $userInfo['name'].'对第三阶段的';
            foreach ($project_three_after as $key=>$value){
                if ($key=='id'||$key=='addtime'||$key=='lastupdate'||$key=='project_id'){
                    continue;
                }else if($value!=$project_three_before[$key]){
                    $data[$ARRAY_Project_three[$key]]=$value;
                }
            }

            foreach ($data as $k=>$val){
                $message.=$k.'做了修改,'.'修改内容是:'.$val.';';
            }
            if(!empty($message)){
                $content .=$message;
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                $roomInfo = Chat_room::getInfoByProject($project_id);
                $projectinfo = Project::getInfoById($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $extra = $project_id.','.$projectinfo['user'];
                    if (!empty($reportRoomId)){
                        socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_MODIFY,1,$extra);
                    }

                }
                $attrsRecord = array(
                    "user"=>$uid,
                    "user_name"=>$userInfo['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );
                Project_three_record::add($attrsRecord);
            }

        }
    }
    $resultData = array("projectid"=>$project_id,"id"=> $projectthreeid);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}