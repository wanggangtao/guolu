<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$id                        = safeCheck($_POST['id']);
$project_id                = safeCheck($_POST['project_id']);
$after_solve               = HTMLEncode($_POST['after_solve']);
$pay_condition             = HTMLEncode($_POST['pay_condition']);
$cost_plan                 = HTMLEncode($_POST['cost_plan']);
$money                     = safeCheck($_POST['money']);
$pre_build_time            = safeCheck($_POST['pre_build_time'], 0);
$pre_check_time            = safeCheck($_POST['pre_check_time'], 0);
$nowtime = time();
$pre_build_time  = $pre_build_time?strtotime($pre_build_time):0;
$pre_check_time  = $pre_check_time?strtotime($pre_check_time):0;
$projectinfo = Project::getInfoById($project_id);
$first_reward = 0;
$second_reward = 0;
$third_reward = 0;
$first_reward = $money * $projectinfo['bonus'] * $constant_project_first_reward;
$second_reward = $money * $projectinfo['bonus'] * $constant_project_second_reward;
$third_reward = $money * $projectinfo['bonus'] * $constant_project_third_reward;
try {
    $userInfo = user::getInfoById($uid);
    $projectfiveid = 0;
    $attrsProject = array(
        "lastupdate"=>$nowtime
    );
    $attrsPf = array(
        "project_id" => $project_id,
        "after_solve"=>$after_solve,
        "pay_condition"=>$pay_condition,
        "cost_plan"=>$cost_plan,
        "money"=>$money,
        "first_reward"=>$first_reward,
        "second_reward"=>$second_reward,
        "third_reward"=>$third_reward,
        "pre_build_time"=>$pre_build_time,
        "pre_check_time"=>$pre_check_time,
        "lastupdate"=>$nowtime
    );

    Project::update($project_id,$attrsProject);
    if(empty($id)){//首次保存
        $attrsPf['addtime'] = $nowtime;
        $rs = Project_five::add($attrsPf);
        $projectfiveid = $rs;
        $bakid = Project_five_bak::add($attrsPf);
        $attrsRecord = array(
            "user"=>$uid,
            "user_name"=>$userInfo['name'],
            "before_id"=>0,
            "after_id"=>$bakid,
            "project_id"=>$project_id,
            "addtime"=>$nowtime
        );
        Project_five_record::add($attrsRecord);

//        $five_info = Project_five_bak::getInfoById($bakid);
//        $roomId = Chat_room::getRoomIdByProject($project_id);
//        if(!empty($roomId)){
//            $content = $userInfo['name'].'对第五阶段的';
//            foreach ($five_info as $k=>$val){
//                if($k=='id'||$k=='addtime'||$k=='lastupdate'||$k=='project_id'||$k=='contract_file'||$k=='contract_ac_file'){
//                    continue;
//                } else{
//                    if($k=='pre_build_time'||$k=='pre_check_time'){
//                        $content.=$ARRAY_Project_five[$k].'做了修改,'.'修改内容是'.date("Y-m-d",$val).';';
//                    }else{
//                        $content.=$ARRAY_Project_five[$k].'做了修改,'.'修改内容是'.$val.';';
//                    }
//                }
//
//            }
//            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//        }
    }else{//之前保存过
        $projectfiveid = $id;
        Project_five::update($id, $attrsPf);
        $attrsPf['addtime'] = $nowtime;
        $beforeid = Project_five_bak::getInfoNewRecodeByPrid($project_id);
        $bakid = Project_five_bak::add($attrsPf);
        $roomId = Chat_room::getRoomIdByProject($project_id);
        if(!empty($roomId)){
            $data = array();
            $project_five_before = Project_five_bak::getInfoById($beforeid['id']);
            $project_five_after = Project_five_bak::getInfoById($bakid);
            $content = $userInfo['name'].'对第五阶段的';
            foreach ($project_five_after as $key=>$value){
                if ($key=='id'||$key=='addtime'||$key=='lastupdate'||$key=='project_id'||$key=='contract_file'||$key=='contract_ac_file'){
                    continue;
                }else{
                    if ($project_five_before[$key]!=$value){
                        if($key=='pre_build_time'||$key=='pre_check_time'){
                            $message.=$ARRAY_Project_five[$key].'做了修改,'.'修改内容是:'.date("Y-m-d",$value).';';
                        }else{
                            $message.=$ARRAY_Project_five[$key].'做了修改,'.'修改内容是:'.$value.';';
                        }
                    }
                }
            }
            if (!empty($message)){
                $content.=$message;
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);

                $roomInfo = Chat_room::getInfoByProject($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $projectinfo = Project::getInfoById($project_id);
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

                Project_five_record::add($attrsRecord);
            }

        }

    }
    $resultData = array("projectid"=>$project_id,"id"=>$projectfiveid);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>