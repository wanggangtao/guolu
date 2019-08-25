<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:46
 */

$id                 = safeCheck($_POST['project_id']);
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
                throw new MyException("该星级已审核通过！",1331);
            }
        }elseif ($postion == "two"){
            if($one_status != 3){
                throw new MyException("低星级还有未审核的内容，请先审核低级内容！",1332);
            }
            if($two_status == 3){
                throw new MyException("该星级已审核通过！",1333);
            }
        }elseif ($postion == "three"){
            if($one_status != 3 || $two_status != 3){
                throw new MyException("低星级还有未审核的内容，请先审核低级内容！",1334);
            }
            if($three_status == 3){
                throw new MyException("该星级已审核通过！",1335);
            }
        }elseif ($postion == "four"){
            if($one_status != 3 || $two_status != 3 || $three_status != 3){
                throw new MyException("低星级还有未审核的内容，请先审核低级内容！",1336);
            }
            if($four_status == 3){
                throw new MyException("该星级已审核通过！",1337);
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
            if(empty($reviewopinion)){
                throw new MyException("驳回原因不能为空！",1338);
            }
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
        if(empty($info_one)){
            Chat_room::AddRoomByProject($id);
        }

    }
    $jpushuser = User::getInfoById($projectinfo['user']);
    $roomId = Chat_room::getRoomIdByProject($id);
    if($rs > 0 && $project_review == 4){
        $msgcontent = '您提交的'.$projectinfo['name'].'已被驳回，驳回原因是'.$reviewopinion.'，请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$id.'">马上处理>></a>';
        $msgAttrs = array(
            "recipients" => $projectinfo['user'],
            "sender" => $uid,
            "title" => "项目驳回提醒-".$projectinfo['name'],
            "content" => $msgcontent,
            "addtime"=>time()
        );
        $msgid = Message_info::add($msgAttrs);

        if(!empty($roomId)){
            $content =$jpushuser['name'].'提交的'.$projectinfo['name'].'已被驳回，'.'驳回原因是'.$reviewopinion.'。';
            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_REJECT);
            $roomInfo = Chat_room::getInfoByProject($id);
            if (!empty($roomInfo)){
                $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                $extra = $id.','.$projectinfo['user'];
                if (!empty($reportRoomId)){
                socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_SIMILAR,1,$extra);
                }
            }
        }

//        if($jpushuser['register_id'] && $msgid > 0){
//            $jpushcontent = '您提交的'.$projectinfo['name'].'已被驳回，驳回原因是'.$reviewopinion.'。';
//            JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目驳回提醒-".$projectinfo['name'], $id, $msgid);
//        }
    }
    if($rs > 0 && $project_review == 3){
        $msgcontent = '您提交的'.$projectinfo['name'].'已审核通过，';
        if(!empty($reviewopinion)){
            $msgcontent .= '通过批注是'.$reviewopinion.'，';
        }
        $jpushcontent = $msgcontent;
        $msgcontent .= '请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$id.'">马上处理>></a>';
        $msgAttrs = array(
            "recipients" => $projectinfo['user'],
            "sender" => $uid,
            "title" => "项目审核通过提醒-".$projectinfo['name'],
            "content" => $msgcontent,
            "addtime"=>time()
        );
        $msgid = Message_info::add($msgAttrs);

        if(!empty($roomId)) {
            $content = $jpushuser['name'] . '提交的' . $projectinfo['name'] . '已审核通过，' . '通过批注是' . $reviewopinion . '。';
            socket_message::sendMsgForRoomId($roomId, $content, socket_message::MSG_TYPE_STEP_CHECKED);
            $roomInfo = Chat_room::getInfoByProject($id);
            if (!empty($roomInfo)){
                $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                $extra = $id.','.$projectinfo['user'];
                if (!empty($reportRoomId)) {
                    socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_STEP_CHECKED, 1, $extra);
                }
            }
        }

//            if($jpushuser['register_id'] && $msgid > 0){
//            JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目审核通过提醒-".$projectinfo['name'], $id, $msgid);
//        }
    }
    $resultData = array("projectid"=>$id);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>