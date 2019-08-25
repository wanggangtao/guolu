<?php
/**
 * 项目处理  project_do.php
 *
 * @version       v0.01
 * @create time   2018/6/29
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'changeProuser'://修改负责人
        $id                        = safeCheck($_POST['id']);
        $project_user              = safeCheck($_POST['project_user']);

        try {
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

            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'saveBonus'://修改提出比例
        $id                 = safeCheck($_POST['id']);
        $bonus              = safeCheck($_POST['bonus']);
        $bonus = $bonus / 100;

        $bonus_stage              = safeCheck($_POST['bonus_stage'], 0);
        $bonus_stage = rtrim($bonus_stage, '|');
        try {
            $attrsProject = array(
                "bonus"=>$bonus,
                "bonus_stage"=>$bonus_stage,
                "lastupdate"=>time()
            );
            Project::update($id,$attrsProject);
            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'saveNotice'://修改提醒时间
        $id                 = safeCheck($_POST['id']);
        $notice_one         = safeCheck($_POST['notice_one']);
        $notice_two         = safeCheck($_POST['notice_two']);
        $notice_three       = safeCheck($_POST['notice_three']);

        try {
            $attrsProject = array(
                "notice_one"=>$notice_one,
                "notice_two"=>$notice_two,
                "notice_three"=>$notice_three,
                "lastupdate"=>time()
            );
//            $projectInfo = Project::getInfoById($id);
//            $roomId = Chat_room::getRoomIdByProject($id);
//            if(!empty($roomId)){
//                $content = $userInfo['name'].'对项目提醒时间做了设置,';
//                if ($projectInfo['notice_one']==$notice_one&&$projectInfo['notice_two']==$notice_two&&$projectInfo['notice_three']==$notice_three){
//                    $message='';
//                }else{
//                    $message = '一级提醒时间为:'.$notice_one.';二级提醒时间为:'.$notice_two.';三级提醒时间为:'.$notice_three;
//                }
//
//                if (!empty($message)){
//                    $content.=$message;
//                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_SIMILAR);
//                }
//            }

            Project::update($id,$attrsProject);
            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'saveExport'://修改提醒时间
        $id                 = safeCheck($_POST['id']);
        $export_flag        = safeCheck($_POST['export_flag']);

        try {
            $attrsProject = array(
                "export_flag"=>$export_flag,
                "lastupdate"=>time()
            );
            Project::update($id,$attrsProject);
            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'saveAdvice'://保存项目建议
        $id                 = safeCheck($_POST['id']);
        $advice_content     =HTMLEncode($_POST['advice_content']);

        try {
            $projectinfo = Project::getInfoById($id);
            $attrsAdvice = array(
                "content"=>$advice_content,
                "projectid"=>$id,
                "user"=>$USERId,
                "addtime"=>time()
            );
            Project_advice::add($attrsAdvice);
            //给报备人发提醒

            $msgcontent = $USERINFO['name'].'给您提交的'.$projectinfo['name'].'提出了建议，建议内容是'.$advice_content.'，请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$id.'">马上处理>></a>';
            $msgAttrs = array(
                "recipients" => $projectinfo['user'],
                "sender" => $USERId,
                "title" => "项目建议提醒-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>time()
            );
            $msgid = Message_info::add($msgAttrs);

            $jpushuser = User::getInfoById($projectinfo['user']);

            $mid = User::getSession();
            $my_info = User::getInfoById($mid);
            //机器人
            $content = $my_info['name'].'给'.$jpushuser['name'].'提交的'.$projectinfo['name'].'提出了建议，建议内容是'.$advice_content;
            $roomId = Chat_room::getRoomIdByProject($id);
            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_SIMILAR);
            $roomInfo = Chat_room::getInfoByProject($id);
            if (!empty($roomInfo)){
                $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                $extra = $id.','.$projectinfo['user'];
                if (!empty($reportRoomId)){
                    socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_SIMILAR,1,$extra);
                }
            }
//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = $USERINFO['name'].'给您提交的'.$projectinfo['name'].'提出了建议，建议内容是'.$advice_content.'。';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目建议提醒-".$projectinfo['name'], $id, $msgid);
//            }

            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_review'://阶段审核
        $id                 = safeCheck($_POST['id']);
        $project_review     = safeCheck($_POST['project_review']);
        $reviewopinion      = HTMLEncode($_POST['reviewopinion']);
        $postion            = safeCheck($_POST['postion'], 0);
        $level = 0;

        try {
            $projectinfo = Project::getInfoById($id);
            $status = $projectinfo['status'];
            $one_status = $projectinfo['one_status'];
            $two_status = $projectinfo['two_status'];
            $three_status = $projectinfo['three_status'];
            $four_status = $projectinfo['four_status'];
            if($projectinfo['stop_flag'] != 1){
                if ($postion == "one"){
                    if($one_status == 3){
                        echo action_msg('该星级已审核通过！', 107);
                        die();
                    }
                }elseif ($postion == "two"){
                    if($one_status != 3){
                        echo action_msg('低星级还有未审核的内容，请先审核低级内容！', 101);
                        die();
                    }
                    if($two_status == 3){
                        echo action_msg('该星级已审核通过！', 104);
                        die();
                    }
                }elseif ($postion == "three"){
                    if($one_status != 3 || $two_status != 3){
                        echo action_msg('低星级还有未审核的内容，请先审核低级内容！', 102);
                        die();
                    }
                    if($three_status == 3){
                        echo action_msg('该星级已审核通过！', 105);
                        die();
                    }
                }elseif ($postion == "four"){
                    if($one_status != 3 || $two_status != 3 || $three_status != 3){
                        echo action_msg('低星级还有未审核的内容，请先审核低级内容！', 103);
                        die();
                    }
                    if($four_status == 3){
                        echo action_msg('该星级已审核通过！', 106);
                        die();
                    }
                }
                if($project_review == 3){
                    //if($postion == "one" && $two_status == 2){
                        //$level = $projectinfo['level'] + 2;
                    //}else{
                        $level = $projectinfo['level'] + 1;
                    //}
                    if($postion == "one"){
                        if($projectinfo['level'] == 0){
                            $one_status = 1;
                        }else{
                            $one_status = $project_review;
                        }
                        if($two_status != 2){
                            $status = $project_review;
                        }
                    }elseif ($postion == "two"){
                        $two_status = $project_review;
                        if($three_status != 2){
                            $status = $project_review;
                        }
                    }elseif ($postion == "three"){
                        $three_status = $project_review;
                        if($four_status != 2){
                            $status = $project_review;
                        }
                    }elseif ($postion == "four"){
                        $four_status = $project_review;
                        $status = $project_review;
                    }
                }else{//驳回
                    $level = $projectinfo['level'];
                    $status = $project_review;
                    if($postion == "one"){
                        $one_status = $project_review;
                        if($two_status == 2){
                            $two_status = $project_review;
                        }
                        if($three_status == 2){
                            $three_status = $project_review;
                        }
                        if($four_status == 2){
                            $four_status = $project_review;
                        }
                    }elseif ($postion == "two"){
                        $two_status = $project_review;
                        if($three_status == 2){
                            $three_status = $project_review;
                        }
                        if($four_status == 2){
                            $four_status = $project_review;
                        }
                    }elseif ($postion == "three"){
                        $three_status = $project_review;
                        if($four_status == 2){
                            $four_status = $project_review;
                        }
                    }elseif ($postion == "four"){
                        $four_status = $project_review;
                    }
                }
                $attrsProject = array(
                    "reviewopinion"=>$reviewopinion,
                    "status"=>$status,
                    "one_status"=>$one_status,
                    "two_status"=>$two_status,
                    "three_status"=>$three_status,
                    "four_status"=>$four_status,
                    "level"=>$level,
                    "lastupdate"=>time()
                );
            }else{
                $attrsProject = array(
                    "reviewopinion"=>$reviewopinion,
                    "status"=>$project_review,
                    "lastupdate"=>time()
                );
                if ($project_review==3){
                    $roomId = Chat_room::getRoomIdByProject($id);
                    if (!empty($roomId)){
                        $attr = array(
                            'status'=>-1
                        );
                        Chat_room::update($roomId,$attr);
                    }
                }
                if($project_review == 4){
                    $attrsProject['stop_flag'] = 0;
                }
            }
            $rs = Project::update($id,$attrsProject);

            $info = Project::getInfoById($id);
           if($info['level']==1&&$info['status']==3){
               $info_one = Chat_room::getRoomIdByProject($id);
               if(empty($info_one)) {
                   Chat_room::AddRoomByProject($id);
               }
           }
           $roomId = Chat_room::getRoomIdByProject($id);

            if($rs > 0){
                $jpushuser = User::getInfoById($projectinfo['user']);

                if($project_review == 4){
                    $msgcontent = '您提交的'.$projectinfo['name'].'已被驳回，驳回原因是'.$reviewopinion.'，请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$id.'">马上处理>></a>';
                    $msgAttrs = array(
                        "recipients" => $projectinfo['user'],
                        "sender" => $USERId,
                        "title" => "项目驳回提醒-".$projectinfo['name'],
                        "content" => $msgcontent,
                        "addtime"=>time()
                    );
                    $msgid = Message_info::add($msgAttrs);

                    //机器人
                    if(!empty($roomId)){
                        $content =$jpushuser['name'].'提交的'.$projectinfo['name'].'已被驳回，'.'驳回原因是'.$reviewopinion.'。';
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_REJECT);
                        $roomInfo = Chat_room::getInfoByProject($id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)){
                                socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_STEP_REJECT,1,$extra);
                            }
                        }
                    }

//                    if($jpushuser['register_id'] && $msgid > 0){
//                        $jpushcontent = '您提交的'.$projectinfo['name'].'已被驳回，驳回原因是'.$reviewopinion.'。';
//                        JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目驳回提醒-".$projectinfo['name'], $id, $msgid);
//                    }

                }elseif($project_review == 3){
                    $msgcontent = '您提交的'.$projectinfo['name'].'已审核通过，';
                    if(!empty($reviewopinion)){
                        $msgcontent .= '通过批注是'.$reviewopinion.'，';
                    }

                    //机器人
                    if(!empty($roomId)) {
                        $content = $jpushuser['name'] . '提交的' . $projectinfo['name'] . '已审核通过，' . '通过批注是' . $reviewopinion . '。';
                        socket_message::sendMsgForRoomId($roomId, $content, socket_message::MSG_TYPE_STEP_CHECKED);
                        $roomInfo = Chat_room::getInfoByProject($id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)){
                                socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_STEP_CHECKED,1,$extra);
                            }

                        }
                    }
                    $jpushcontent = $msgcontent;
                    $msgcontent .= '请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$id.'">马上处理>></a>';
                    $msgAttrs = array(
                        "recipients" => $projectinfo['user'],
                        "sender" => $USERId,
                        "title" => "项目审核通过提醒-".$projectinfo['name'],
                        "content" => $msgcontent,
                        "addtime"=>time()
                    );
                    $msgid = Message_info::add($msgAttrs);

//                    if($jpushuser['register_id'] && $msgid > 0){
//                        JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目审核通过提醒-".$projectinfo['name'], $id, $msgid);
//                    }
                }

            }

            echo action_msg('审核成功！', 1);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_review_check'://阶段审核
        $id                 = safeCheck($_POST['id']);
        $postion            = safeCheck($_POST['postion'], 0);
        $level = 0;
        try {
            $projectinfo = Project::getInfoById($id);
            $status = $projectinfo['status'];
            $one_status = $projectinfo['one_status'];
            $two_status = $projectinfo['two_status'];
            $three_status = $projectinfo['three_status'];
            $four_status = $projectinfo['four_status'];

            if ($postion == "one"){
                if($one_status == 3){
                    echo action_msg('该星级已审核通过！', 107);
                    die();
                }
            }elseif ($postion == "two"){
                if($one_status != 3){
                    echo action_msg('低星级还有未审核的内容，请先审核低级内容！', 101);
                    die();
                }
                if($two_status == 3){
                    echo action_msg('该星级已审核通过！', 104);
                    die();
                }
            }elseif ($postion == "three"){
                if($one_status != 3 || $two_status != 3){
                    echo action_msg('低星级还有未审核的内容，请先审核低级内容！', 102);
                    die();
                }
                if($three_status == 3){
                    echo action_msg('该星级已审核通过！', 105);
                    die();
                }
            }elseif ($postion == "four"){
                if($one_status != 3 || $two_status != 3 || $three_status != 3){
                    echo action_msg('低星级还有未审核的内容，请先审核低级内容！', 103);
                    die();
                }
                if($four_status == 3){
                    echo action_msg('该星级已审核通过！', 106);
                    die();
                }
            }elseif ($postion == "stop"){
                if($projectinfo['stop_flag'] != 1){
                    echo action_msg('该项目未提交终止申请！', 107);
                    die();
                }
            }
            echo action_msg('可以审核', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>