<?php

/**
 * Class Chat_room
 */
class Chat_room_msg {


    const  MSG_TEXT = 1;
    const  MSG_VOICE = 2;

    const  MSG_VIDEO = 3;
    const  MSG_PICTURE = 4;
    const  MSG_FILE = 5;
    const  MSG_TIME = 6;

    public function __construct() {

    }

    /**
     * @param $attrs
     * @return mixed
     * @throws Exception
     * zx
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);

        $id =  Table_chat_room_msg::add($attrs);

        return $id;
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     * zx
     */
    static public function update($id, $attrs){
        return Table_chat_room_msg::update($id, $attrs);
    }

    /**
     * 获取项目最新消息
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function getLastMsgs($uid,$room_id){
        return Table_chat_room_msg::getLastMsgs($uid,$room_id);
    }

    /**
     * 返回消息记录
     * @param $projectid
     * @return mixed
     * @throws Exception
     */
    static public function getMsgRecord($room_id,$current_msg_id,$direction,$page,$pageSize){
        return Table_chat_room_msg::getMsgRecord($room_id,$current_msg_id,$direction,$page,$pageSize);
    }
    /**
     * Chat_room_msg::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $roomid        roomID
     * @param string $keywords           内容关键词
     * @param number $stday            开始日期
     * @param number $endday           结束日期
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $roomid, $keywords,$stday, $endday){
        return Table_chat_room_msg::getPageList($page, $pageSize, $count, $roomid, $keywords,  $stday, $endday);
    }

    /**
     * 获取room_id,uid对应的群新消息数量
     * @param $room_id
     * @param $uid
     * @return mixed
     */
    static public function getNewCount($room_id,$uid){
//        $attrs = array();
//        $attrs['room_id'] = $room_id;
//        $attrs['uid'] = $uid;
        $config_info = Chat_room_msg_config::getConfigByRoomUid($room_id,$uid);
        $last_msg_id = 0;
        if(!empty($config_info)){
            $last_msg_id = $config_info['last_msg_id'];
        }
        return Table_chat_room_msg::getNewCount($room_id,$uid,$last_msg_id);
    }


    /**
     * 获取这个群的最后一条消息
     * @param $room_id
     * @return array
     */
    static public function getLastMsg($room_id)
    {
        return Table_chat_room_msg::getLastMsg($room_id);
    }
    /**
     * 根据id获取信息
     * @param $room_id
     * @return array
     */
    static public function getInfoById($id)
    {
        return Table_chat_room_msg::getInfoById($id);
    }

    static public function getCurrentIdByQuery($keyword,$time,$page,$pageSize,$room_id)
    {
        return Table_chat_room_msg::getCurrentIdByQuery($keyword,$time,$page,$pageSize,$room_id);
    }


    static public function getResource($page,$pageSize,$room_id)
    {
        return Table_chat_room_msg::getResource($page,$pageSize,$room_id);
    }

}