<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */
try {

    $id         =  isset($_POST['id'])?safeCheck($_POST['id']):0;
    if(empty($id)) throw new MyException("缺少记录ID参数",101);
    $info = Project_visitlog::getInfoById($id) ;
    if(empty($info))
        throw new MyException("记录不存在",101);
    /*if(!empty($info['comuser']))
        throw new MyException("该记录已评论",101);*/
    $projectinfo = Project::getInfoById($info['projectid']);
    $userinfo = User::getInfoById($projectinfo['user']);

    $loginuser = User::getInfoById($uid);
    if (!($userinfo['parent'] == $uid || ($loginuser['role'] == 4 && $userinfo['department'] == $loginuser['department'])||$loginuser['role']==3)) {
        throw new MyException("没有权限评论！",102);
    }
    $comment           = HTMLEncode($_POST['comment']);
    $attrslog = array(
        "visitlog_id"=>$id,
        "content"=>$comment,
        "comuser"=>$uid,
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
            "sender" => $uid,
            "title" => "项目评论提醒-".$projectinfo['name'],
            "content" => $msgcontent,
            "addtime"=>time()
        );
        $msgid = Message_info::add($msgAttrs);


        $jpushuser = User::getInfoById($projectinfo['user']);

        $roomId = Chat_room::getRoomIdByProject($vloginfo['projectid']);
        if(!empty($roomId)){
            $content = $jpushuser['name'].'提交的'.$projectinfo['name'].'已被评论';
            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_COMMENT);
            $roomInfo = Chat_room::getInfoByProject($info['projectid']);
            if (!empty($roomInfo)){
                $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                $extra = $info['projectid'].','.$projectinfo['user'];
                if (!empty($reportRoomId)){
                    socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_SIMILAR,1,$extra);
                }
            }
        }
//        if($jpushuser['register_id'] && $msgid > 0 ){
//            $jpushcontent = '您提交的'.$projectinfo['name'].'已被评论。';
//            JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目评论提醒-".$projectinfo['name'], $vloginfo['projectid'], $msgid);
//        }
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, null);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
?>