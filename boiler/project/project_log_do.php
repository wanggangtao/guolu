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
    case 'visitadd'://添加拜访记录
        $project_id        = safeCheck($_POST['id']);
        $content           = HTMLEncode($_POST['content']);
        $effect            = HTMLEncode($_POST['effect']);
        $plan              = HTMLEncode($_POST['plan']);
        $visitway          = safeCheck($_POST['visitway']);
        $positionstr       = safeCheck($_POST['positionstr'], 0);
        $tel               = safeCheck($_POST['tel'], 0);
        $target            = safeCheck($_POST['target'], 0);

        try {
            $attrslog = array(
                "projectid"=>$project_id,
                "target"=>$target,
                "tel"=>$tel,
                "position"=>$positionstr,
                "visitway"=>$visitway,
                "content"=>$content,
                "effect"=>$effect,
                "plan"=>$plan,
                "visittime"=>time(),
                "updatetime"=>time()
            );
            $rs = Project_visitlog::add($attrslog);
            if($rs > 0){
                //给销售经理发送站内信
                $projectinfo = Project::getInfoById($project_id);
                $msgcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的拜访记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_visitlog_check.php?id='.$project_id.'">马上处理>></a>';
                $msgAttrs = array(
                    "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                    "sender" => $USERId,
                    "title" => "项目更新提醒-".$projectinfo['name'],
                    "content" => $msgcontent,
                    "addtime"=>time()
                );
                $msgid = Message_info::add($msgAttrs);

                //机器人
                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
                    $content = $USERINFO['name'].'添加了'.$projectinfo['name'].'的拜访记录';
                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_UPDATE);
                    $roomInfo = Chat_room::getInfoByProject($project_id);
                    if (!empty($roomInfo)){
                        $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                        $extra = $project_id.','.$projectinfo['user'];
                        if (!empty($reportRoomId)){
                            socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_UPDATE,1,$extra);
                        }
                    }
                }

                $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);
//                if($jpushuser['register_id'] && $msgid > 0){
//                    $jpushcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的拜访记录。';
//                    JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目更新提醒-".$projectinfo['name'], $project_id, $msgid);
//                }

                //给观察员发送站内信
                $puser = User::getInfoById($projectinfo['user']);
                if($puser['department']){
                    $gmsgcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的拜访记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_visitlog_show.php?id='.$project_id.'">马上处理>></a>';
                    $guanuser = User::getPageList( 0, 10, 1, '', 1, $puser['department'], 4);
                    if($guanuser){
                        foreach ($guanuser as $thisuser){
                            $gmsgAttrs = array(
                                "recipients" => $thisuser['id'],
                                "sender" => $USERId,
                                "title" => "项目更新提醒-".$projectinfo['name'],
                                "content" => $gmsgcontent,
                                "addtime"=>time()
                            );
                            $gmsgid = Message_info::add($gmsgAttrs);

//                            if($thisuser['register_id'] && $gmsgid > 0){
//                                $gjpushcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的拜访记录。';
//                                JPUSH_send($thisuser['register_id'], $gjpushcontent, "项目更新提醒-".$projectinfo['name'], $project_id, $gmsgid);
//                            }
                        }
                    }
                }
            }

            echo action_msg('添加成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'visitedit'://修改拜访记录
        $id                = safeCheck($_POST['id']);
        $content           = HTMLEncode($_POST['content']);
        $effect            = HTMLEncode($_POST['effect']);
        $plan              = HTMLEncode($_POST['plan']);
        $visitway          = safeCheck($_POST['visitway']);
        $positionstr       = safeCheck($_POST['positionstr'], 0);
        $tel               = safeCheck($_POST['tel'], 0);
        $target            = safeCheck($_POST['target'], 0);

        try {
            $attrslog = array(
                "target"=>$target,
                "tel"=>$tel,
                "position"=>$positionstr,
                "visitway"=>$visitway,
                "content"=>$content,
                "effect"=>$effect,
                "plan"=>$plan,
                "updatetime"=>time()
            );
            Project_visitlog::update($id, $attrslog);
            echo action_msg('修改成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'visitcomment'://评论拜访记录
        $id                = safeCheck($_POST['id']);
        $comment           = HTMLEncode($_POST['comment']);

        try {
            $attrslog = array(
                "visitlog_id"=>$id,
                "content"=>$comment,
                "comuser"=>$USERId,
                "time"=>time()
            );
            $rs = Project_visitlog_comment::add($attrslog);
            if($rs > 0 ){
                //给报备人发送站内信
                $vloginfo = Project_visitlog::getInfoById($id);
                $projectinfo = Project::getInfoById($vloginfo['projectid']);
                $msgcontent = '您提交的'.$projectinfo['name'].'已被评论，请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_visitlog.php?id='.$vloginfo['projectid'].'">查看详情>></a>';
                $msgAttrs = array(
                    "recipients" => $projectinfo['user'],
                    "sender" => $USERId,
                    "title" => "项目评论提醒-".$projectinfo['name'],
                    "content" => $msgcontent,
                    "addtime"=>time()
                );
                $msgid = Message_info::add($msgAttrs);

                $jpushuser = User::getInfoById($projectinfo['user']);

                //机器人
                $roomId = Chat_room::getRoomIdByProject($vloginfo['projectid']);
                if(!empty($roomId)){
                    $content = $jpushuser['name'].'提交的'.$projectinfo['name'].'已被评论';
                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_COMMENT);
                    $roomInfo = Chat_room::getInfoByProject($vloginfo['projectid']);
                    if (!empty($roomInfo)) {
                        $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                        $extra = $vloginfo['projectid'] . ',' . $projectinfo['user'];
                        if (!empty($reportRoomId)) {
                            socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_COMMENT, 1, $extra);

                        }
                    }
                        }
//                if($jpushuser['register_id'] && $msgid > 0){
//                    $jpushcontent = '您提交的'.$projectinfo['name'].'已被评论';
//                    JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目评论提醒-".$projectinfo['name'], $project_id, $msgid);
//                }
            }
            echo action_msg('评论成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'visitdel'://删除拜访记录
        $id                = safeCheck($_POST['id']);

        try {
            Project_visitlog::del($id);
            echo action_msg('删除成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'inspectadd'://添加考察记录
        $project_id        = safeCheck($_POST['id']);
        $situation         = HTMLEncode($_POST['situation']);
        $inspecttime       = safeCheck($_POST['inspecttime'], 0);
        $inspecttime       = strtotime($inspecttime);
        $member            = safeCheck($_POST['member'], 0);
        $company           = safeCheck($_POST['company'], 0);
        $brand             = safeCheck($_POST['brand'], 0);
        $address            = safeCheck($_POST['address'], 0);

        try {
            $attrslog = array(
                "projectid"=>$project_id,
                "member"=>$member,
                "inspecttime"=>$inspecttime,
                "company"=>$company,
                "brand"=>$brand,
                "address"=>$address,
                "situation"=>$situation,
                "updatetime"=>time()
            );
            $rs = Project_inspectlog::add($attrslog);

            if($rs > 0){
                //给销售经理发送站内信
                $projectinfo = Project::getInfoById($project_id);
                $msgcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的考察记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_inspectlog_check.php?id='.$project_id.'">查看详情>></a>';
                $msgAttrs = array(
                    "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                    "sender" => $USERId,
                    "title" => "项目更新提醒-".$projectinfo['name'],
                    "content" => $msgcontent,
                    "addtime"=>time()
                );
                $msgid = Message_info::add($msgAttrs);

                $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);

                //机器人
                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
                    $content = $jpushuser['name'].'添加了'.$projectinfo['name'].'的考察记录';
                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_UPDATE);
                    $roomInfo = Chat_room::getInfoByProject($project_id);
                    if (!empty($roomInfo)) {
                        $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                        $extra = $project_id . ',' . $projectinfo['user'];
                        if (!empty($reportRoomId)) {
                            socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_UPDATE, 1, $extra);

                        }
                    }
                }

//                if($jpushuser['register_id'] && $msgid > 0){
//                    $jpushcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的考察记录。';
//                    JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目更新提醒-".$projectinfo['name'], $project_id, $msgid);
//                }

                //给观察员发送站内信
                $puser = User::getInfoById($projectinfo['user']);
                if($puser['department']){
                    $guanuser = User::getPageList( 0, 10, 1, '', 1, $puser['department'], 4);
                    $gmsgcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的考察记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_inspectlog_show.php?id='.$project_id.'">查看详情>></a>';
                    if($guanuser){
                        foreach ($guanuser as $thisuser){
                            $gmsgAttrs = array(
                                "recipients" => $thisuser['id'],
                                "sender" => $USERId,
                                "title" => "项目更新提醒-".$projectinfo['name'],
                                "content" => $gmsgcontent,
                                "addtime"=>time()
                            );
                            $gmsgid = Message_info::add($gmsgAttrs);

//                            if($thisuser['register_id'] && $gmsgid > 0){
//                                $gjpushcontent = $USERINFO['name'].'添加了'.$projectinfo['name'].'的考察记录。';
//                                JPUSH_send($thisuser['register_id'], $gjpushcontent, "项目更新提醒-".$projectinfo['name'], $project_id, $gmsgid);
//                            }
                        }
                    }
                }
            }


            echo action_msg('添加成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>