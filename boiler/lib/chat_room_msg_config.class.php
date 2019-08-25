<?php

/**
 * Class Chat_room
 */
class Chat_room_msg_config {


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

        $id =  Table_Chat_room_msg_config::add($attrs);

        return $id;
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     *zx
     */
    static public function update($id, $attrs){
        return Table_Chat_room_msg_config::update($id, $attrs);
    }



    /**
     * @param $id
     * @param $attrs
     * @return mixed
     *zx
     */
    static public function updateByUidAndRoom($uid,$room_id,$attrs){
        return Table_Chat_room_msg_config::updateByUidAndRoom($uid,$room_id, $attrs);
    }

    /**
     * 根据room以及uid获取config
     * @param $room_id
     * @param $uid
     * @return mixed
     */
    static public function getConfigByRoomUid($room_id,$uid){
        return Table_Chat_room_msg_config::getConfigByRoomUid($room_id,$uid);
    }


    static public function getInfoByParam($params){
        return Table_chat_room_msg_config::getInfoByParams($params);
    }

    static public function getInfoByUid($id,$roomId){
        return Table_chat_room_msg_config::getInfoByUid($id,$roomId);
    }


    /**
     * 判断是否在相亲相爱一家人群里（主要考虑新增加的用户)
     * @param $uid
     * @return array|mixed
     */
    static public function checkFromFirstRoom($uid){

        $configInfo = self::getConfigByRoomUid(1,$uid);

        if(empty($configInfo))
        {

            //获取这个群的最后一条消息


            $currentTime = time();
            $lasgInfo = chat_room_msg::getLastMsg(1);

            $attrs = array("uid"=>$uid,"room_id"=>1,"addtime"=>$currentTime);

            if(!empty($lasgInfo))
            {
                $attrs["last_msg_id"]= $lasgInfo["id"];
                $attrs["lastupdate"]= $currentTime;

            }


            self::add($attrs);
        }
    }

    static public function delById($id){
        return Table_chat_room_msg_config::delById($id);
    }

    static public function delByRoomId($roomId){
        return Table_chat_room_msg_config::delByRoomId($roomId);
    }

    static public function GetByRoomId($roomId){
        return Table_chat_room_msg_config::GetByRoomId($roomId);
    }

}