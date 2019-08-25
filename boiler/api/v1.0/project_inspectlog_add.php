<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */
try {

    $projectid =  isset($_POST['projectid'])?safeCheck($_POST['projectid']):0;
    if(empty($projectid)) throw new MyException("缺少项目ID参数",101);
    $projectinfo = Project::getInfoById($projectid) ;
    if(empty($projectinfo))
        throw new MyException("项目信息不存在",101);

    $situation         = HTMLEncode($_POST['situation']);
    $inspecttime       = safeCheck($_POST['inspecttime'], 0);
    $inspecttime       = strtotime($inspecttime);
    $member            = safeCheck($_POST['member'], 0);
    $company           = safeCheck($_POST['company'], 0);
    $brand             = safeCheck($_POST['brand'], 0);
    $address            = safeCheck($_POST['address'], 0);

    if(empty($situation)){
        throw new MyException("考察情况不能为空",101);
    }
    if(empty($inspecttime)){
        throw new MyException("考察时间不能为空",101);
    }
    if(empty($member)){
        throw new MyException("考察人员为空",101);
    }
    if(empty($company)){
        throw new MyException("考察单位不能为空",101);
    }
    if(empty($brand)){
        throw new MyException("考察品牌不能为空",101);
    }
    if(empty($address)){
        throw new MyException("考察地点不能为空",101);
    }

    $attrslog = array(
        "projectid"=>$projectid,
        "member"=>$member,
        "inspecttime"=>$inspecttime,
        "company"=>$company,
        "brand"=>$brand,
        "address"=>$address,
        "situation"=>$situation,
        "updatetime"=>time()
    );
    $thisid = Project_inspectlog::add($attrslog);


    if($thisid > 0){
        //给销售经理发送站内信
        $userInfo = User::getInfoById($projectinfo['user']);
        $msgcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的考察记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_inspectlog_check.php?id='.$projectid.'">查看详情>></a>';
        $msgAttrs = array(
            "recipients" => $userInfo['parent']?$userInfo['parent']:$projectinfo["user"],
            "sender" => $projectinfo['user'],
            "title" => "项目更新提醒-".$projectinfo['name'],
            "content" => $msgcontent,
            "addtime"=>time()
        );
        $msgid = Message_info::add($msgAttrs);

        $jpushuser = User::getInfoById($userInfo['parent']?$userInfo['parent']:$projectinfo["user"]);

        $roomId = Chat_room::getRoomIdByProject($projectid);
        if(!empty($roomId)){
            $content = $userInfo['name'].'添加了'.$projectinfo['name'].'的考察记录';
            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_UPDATE);
            $roomInfo = Chat_room::getInfoByProject($projectid);
            if (!empty($roomInfo)){
                $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                $extra = $projectid.','.$projectinfo['user'];
                if (!empty($reportRoomId)){
                    socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_UPDATE,1,$extra);
                }
            }
        }

//        if($jpushuser['register_id'] && $msgid > 0){
//            $jpushcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的考察记录。';
//            JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目更新提醒-".$projectinfo['name'], $projectid, $msgid);
//        }


        //给观察员发送站内信
        if($userInfo['department']){
            $gmsgcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的考察记录。请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_inspectlog_show.php?id='.$projectid.'">查看详情>></a>';
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

//                    if($thisuser['register_id'] && $gmsgid > 0){
//                        $gjpushcontent = $userInfo['name'].'添加了'.$projectinfo['name'].'的考察记录。';
//                        JPUSH_send($thisuser['register_id'], $gjpushcontent, "项目更新提醒-".$projectinfo['name'], $projectid, $gmsgid);
//                    }
                }
            }
        }
    }

    $resData = array(
        'id' => $thisid
    );
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);
}catch (MyException $e){
    echo $e->jsonMsg();
}
?>