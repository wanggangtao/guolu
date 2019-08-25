<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2019/3/5
 * Time: 下午3:33
 */

class socket_message{



    const MSG_TYPE_UPDATE = 1;//项目更新提醒
    const MSG_TYPE_COMMENT = 2;//项目评论提醒

    const MSG_TYPE_STEP_APPLY = 3;//项目阶段申请提醒
    const MSG_TYPE_STEP_CHECKED = 4;//项目阶段申请审核通过提醒
    const MSG_TYPE_STEP_REJECT = 5;//项目阶段申请驳回提醒
    const MSG_TYPE_SIMILAR = 6;//项目建议提醒
    const MSG_TYPE_MODIFY = 7;//项目建议提醒
    const MSG_TYPE_SET_TIME = 8;//提醒时间设置


    /**
     *
     * 获取消息类型列表
     * @return array
     */
    static public function getMsgTypeList()
    {
        return array(
            self::MSG_TYPE_UPDATE =>'项目更新提醒',
            self::MSG_TYPE_COMMENT =>'项目评论提醒',
            self::MSG_TYPE_STEP_APPLY =>'项目阶段申请提醒',
            self::MSG_TYPE_STEP_CHECKED =>'项目阶段申请审核通过提醒',
            self::MSG_TYPE_STEP_REJECT =>'项目阶段申请驳回提醒',
            self::MSG_TYPE_SIMILAR =>'项目建议提醒',
        );
    }





    /**
     * 当收到消息时，判断如果不是在聊天界面的，需要发送全局通知
     * @param $room_id
     */
    public static function sendGlobalForClient($room_id,$message)
    {

//获取全局链接的所有客户端
        $clientIds = Gateway::getAllClientSessions(-1);



//获取当前房间所有的客户端
        $currentUidArr = Gateway::getUidListByGroup($room_id);

        $uidKeyData = array();
        if(!empty($clientIds))
        {
            foreach ($clientIds as $key =>$itemData)
            {
                //给当前发消息用户不广播
                if(in_array($itemData["uid"],$currentUidArr)) continue;

                $uidKeyData[$itemData["uid"]] = $key;
            }


            if(empty($uidKeyData)) return;


            $uidStr = implode(",",array_keys($uidKeyData));


            $params = array(
                "room_id"=>$room_id,
                "uidStr"=>$uidStr
            );

            $result = chat_room_msg_config::getInfoByParam($params);


            if(!empty($result))
            {

                foreach ($result as $item)
                {
                    $currentClientId = $uidKeyData[$item["uid"]];


                    Gateway::sendToClient($currentClientId,json_encode($message));

                }

            }
        }
    }



    /**
     * 处在聊天框的人更新最后一条阅读消息
     * @param $room_id
     * @param $insert_id
     */
    public static function updateLastForUser($room_id,$msgId)
    {

        $currentTime = time();
        $uidArr = Gateway::getUidListByGroup($room_id);

        if(!empty($uidArr))
        {

            foreach ($uidArr as $currentUid)
            {
                chat_room_msg_config::updateByUidAndRoom($currentUid,$room_id,array("last_msg_id"=>$msgId,"lastupdate"=>$currentTime));
            }

        }
    }



    /**
     * 处在聊天框的人更新最后一条阅读消息
     * @param $room_id
     * @param $insert_id
     */
    public static function updateLastForRoom($room_id,$msg_id,$uid,$uname,$content)
    {

        $currentTime = time();


        $attrs = array(
          "last_msg_id"=>$msg_id,
            "last_msg_uid"=>$uid,
            "last_msg_uname"=>$uname,
            "last_msg_content"=>$content,
            "last_msg_addtime"=>$currentTime
        );
        chat_room::update($room_id,$attrs);
    }


    /**
     * @param $roomId
     * @param $content
     * @param $system_type
     * @param int $msg_type
     * @param int $uid
     * @param array $userInfo
     * @return mixed
     */
    static public function sendMsgForRoomId($roomId,$content,$system_type,$msg_type=1,$uid=0,$userInfo=array(),$size=0,$extra=0,$isShowTime=0)
    {

        global $HTTP_PATH;

        $roomInfo = chat_room::getInfoById($roomId);


        if(empty($roomInfo)) return -1;



//默认是机器人发消息
        $time = time();
        $currentUid = -1;
        $currentName = "机器人";
        $currentHead = $HTTP_PATH."static/images/rabot.png";



//用户发消息
        if(!empty($uid))
        {
            if(!empty($userInfo))
            {
                $userInfo = user::getInfoById($uid);
            }
            $currentUid = $uid;
            $currentName = $userInfo["name"];
            $currentHead = $HTTP_PATH.$userInfo["headimg"];
        }


//消息入库
        $attrs = array(
            "room_id"=>$roomId,
            "uid"=>$currentUid,
            "uname"=>$currentName,
            "content"=>$content,
            "system_type"=>$system_type,
            "type"=>$msg_type,
            "extra"=>$extra,
            "size"=>$size,
            "addtime"=>$time,
            "isShowTime"=>$isShowTime
        );
        $msgId = chat_room_msg::add($attrs);




//推送消息体
        $data = array(
            "type"=>"say",
            "msg_id"=>$msgId,
            "msg_type"=>$msg_type,
            "system_type"=>$system_type,
            "from_room_id"=>$roomInfo["id"],
            'from_headimg'=>$currentHead,
            "project_id"=>$roomInfo['project'],
            "from_uid"=>$currentUid,
            "from_client_name"=>$currentName,
            "to_client_id"=>'all',
            "to_client_name"=>'all',
            "content"=>$content,
            'extra'=>$extra,
            'size'=>$size,
            "time"=>date("Y-m-d H:i:s")
        );

        Gateway::sendToGroup($roomInfo["id"],json_encode($data));



//给全局发
        self::sendGlobalForClient($roomInfo["id"],$data);


        self::updateLastForUser($roomInfo["id"],$msgId);


        //最后一条消息要进行转化
        if($msg_type==Chat_room_msg::MSG_VIDEO)
        {
            $content = "[视频]";
        }
        else if($msg_type==Chat_room_msg::MSG_PICTURE)
        {
            $content = "[图片]";
        }
        else if($msg_type==Chat_room_msg::MSG_FILE)
        {
            $content = "[文件]";
        }

        self::updateLastForRoom($roomInfo["id"],$msgId,$currentUid,$currentName,$content);





        return $msgId;

    }


    static public function sendMsgForReport($roomId,$content,$system_type,$msg_type=1,$extra){

        $time = time();
        $currentUid = -1;
        $currentName = "机器人";
        $attrs = array(
            "room_id"=>$roomId,
            "uid"=>$currentUid,
            "uname"=>$currentName,
            "content"=>$content,
            "system_type"=>$system_type,
            "type"=>$msg_type,
            "extra"=>$extra,
            "addtime"=>$time,
        );
       $msgId = chat_room_msg::add($attrs);
       self::updateLastForRoom($roomId,$msgId,$currentUid,$currentName,$content);
       return $msgId;
    }

}