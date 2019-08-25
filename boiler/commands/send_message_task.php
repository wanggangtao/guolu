<?php
/**
 * 自动发站内信处理
 * @version       v0.01
 * @create time   2018/7/4
 * @update time   2018/7/4
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 **/
$CURRENT_PATH = str_replace('\\','/',dirname(__FILE__)).'/'; //网站根目录路径
require_once($CURRENT_PATH."../init.php");

try {
    $nowtime = time();
    $projectlist = Project::getAllSendList();
    if($projectlist){
        foreach ($projectlist as $project){
            $visitinfo = Project_visitlog::getInfoNew($project['id']);
            $diff = 0;
            if($visitinfo){
                $diff = intval(($nowtime - $visitinfo['visittime']) / 86400);
            }
            if($diff > 0 && ($project['notice_one'] > 0 || $project['notice_two'] > 0 || $project['notice_three'] > 0)){
                if($project['notice_one'] == $diff){
                    $msgtpl = Message_tpl::getInfoByType(1);
                    $userinfo = User::getInfoById($project['user']);
                    $content = $msgtpl['content'];
                    $content = str_replace("{username}", $userinfo['name'], $content);
                    $content = str_replace("{time}", $diff, $content);
                    $content = str_replace("{projectname}", $project['name'], $content);
                    $content = str_replace("{url}", '<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn.php?id='.$project['id'].'">马上处理>></a>', $content);
                    $msgAttrs = array(
                        "recipients" => $project['user'],
                        "sender" => 0,
                        "title" => "跟进项目提醒-".$project['name'],
                        "content" => $content,
                        "addtime"=>$nowtime
                    );
                    Message_info::add($msgAttrs);
                    $projectInfo = Project::getInfoById($project['id']);
                    $roomId = Chat_room::getRoomIdByProject($project['id']);
                    if(!empty($roomId)){
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_SIMILAR);
                    }
                }elseif (($project['notice_one'] + $project['notice_two']) == $diff){
                    $msgtpl = Message_tpl::getInfoByType(2);
                    $userinfo = User::getInfoById($project['parent']?$project['parent']:$project['user']);
                    $content = $msgtpl['content'];
                    $content = str_replace("{username}", $userinfo['name'], $content);
                    $content = str_replace("{time}", $diff, $content);
                    $content = str_replace("{projectname}", $project['name'], $content);

                    $msgAttrs = array(
                        "recipients" => $userinfo['id'],
                        "sender" => 0,
                        "title" => "跟进项目提醒-".$project['name'],
                        "content" => $content,
                        "addtime"=>$nowtime
                    );
                    Message_info::add($msgAttrs);
                    $projectInfo = Project::getInfoById($project['id']);
                    $roomId = Chat_room::getRoomIdByProject($project['id']);
                    if(!empty($roomId)){
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_SIMILAR);
                    }
                }elseif (($project['notice_one'] + $project['notice_two'] + $project['notice_three']) == $diff){
                    $msgtpl = Message_tpl::getInfoByType(2);
                    $userinfo = User::getInfoByRole(3);
                    if($userinfo){
                        $content = $msgtpl['content'];
                        $content = str_replace("{username}", $userinfo['name'], $content);
                        $content = str_replace("{time}", $diff, $content);
                        $content = str_replace("{projectname}", $project['name'], $content);

                        $msgAttrs = array(
                            "recipients" => $userinfo[0]['id'],
                            "sender" => 0,
                            "title" => "跟进项目提醒-".$project['name'],
                            "content" => $content,
                            "addtime"=>$nowtime
                        );
                        Message_info::add($msgAttrs);
                        $projectInfo = Project::getInfoById($project['id']);
                        $roomId = Chat_room::getRoomIdByProject($project['id']);
                        if(!empty($roomId)){
                            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_SIMILAR);
                        }
                    }
                }
            }
        }
    }

    print_r('成功');
}catch(MyException $e){
    echo $e->jsonMsg();
}

?>