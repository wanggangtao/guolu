<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */
try {

    $projectid         =  isset($_POST['projectid'])?safeCheck($_POST['projectid']):0;
    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);
    $projectinfo = Project::getInfoById($projectid) ;
    if(empty($projectinfo))
        throw new MyException("项目信息不存在",101);

    $content           = HTMLEncode($_POST['content']);
    $effect            = HTMLEncode($_POST['effect']);
    $plan              = HTMLEncode($_POST['plan']);
    $visitway          = safeCheck($_POST['visitway']);
    $positionstr       = safeCheck($_POST['positionstr'], 0);
    $tel               = safeCheck($_POST['tel'], 0);
    $target            = safeCheck($_POST['target'], 0);
    if(empty($target)){
        throw new MyException("拜访对象不能为空",101);
    }
    if(empty($tel)){
        throw new MyException("联系方式不能为空",101);
    }
    if(empty($positionstr)){
        throw new MyException("职位不能为空",101);
    }
    if(empty($visitway)){
        throw new MyException("拜访方式不能为空",101);
    }
    if(empty($content)){
        throw new MyException("拜访内容不能为空",101);
    }
    if(empty($effect)){
        throw new MyException("拜访效果不能为空",101);
    }
    if(empty($plan)){
        throw new MyException("下步计划不能为空",101);
    }
    $attrslog = array(
        "projectid"=>$projectid,
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
    $thisid = Project_visitlog::add($attrslog);
    //给销售经理发送站内信
    $userInfo = User::getInfoById($projectinfo['user']);
    $msgcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的拜访记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_visitlog_check.php?id='.$projectid.'">查看详情>></a>';
    $msgAttrs = array(
        "recipients" => $userInfo['parent']?$userInfo['parent']:$projectinfo['user'],
        "sender" => $projectinfo['user'],
        "title" => "项目更新提醒-".$projectinfo['name'],
        "content" => $msgcontent,
        "addtime"=>time()
    );
    $msgid = Message_info::add($msgAttrs);

    $jpushuser = User::getInfoById($userInfo['parent']?$userInfo['parent']:$projectinfo["user"]);
    $roomId = Chat_room::getRoomIdByProject($projectid);
    if(!empty($roomId)){
        $content = $userInfo['name'].'添加了'.$projectinfo['name'].'的拜访记录';
        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_UPDATE);
        $roomInfo = Chat_room::getInfoByProject($projectid);
        if (!empty($roomInfo)){
            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
            if (!empty($reportRoomId)){
                $extra = $projectid.','.$projectinfo['user'];
                socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_UPDATE,1,$extra);
            }
        }
    }

//    if($jpushuser['register_id'] && $msgid > 0 ){
//        $jpushcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的拜访记录。';
//        JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目更新提醒-".$projectinfo['name'], $projectid, $msgid);
//    }

    //给观察员发送站内信
    if($userInfo['department']){
        $gmsgcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的拜访记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_visitlog_show.php?id='.$projectid.'">马上处理>></a>';
        $guanuser = User::getPageList( 0, 10, 1, '', 1, $userInfo['department'], 4);
        if($guanuser){
            foreach ($guanuser as $thisuser){
                $gmsgAttrs = array(
                    "recipients" => $thisuser['id'],
                    "sender" => $projectinfo['user'],
                    "title" => "项目更新提醒-".$projectinfo['name'],
                    "content" => $gmsgcontent,
                    "addtime"=>time()
                );
                $gmsgid = Message_info::add($gmsgAttrs);

//                if($thisuser['register_id'] && $gmsgid > 0){
//                    $gjpushcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的拜访记录。';
//                    JPUSH_send($thisuser['register_id'], $gjpushcontent, "项目更新提醒-".$projectinfo['name'], $projectid, $gmsgid);
//                }
            }
        }
    }
    $resData = array(
        'id' => $thisid
    );
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
?>