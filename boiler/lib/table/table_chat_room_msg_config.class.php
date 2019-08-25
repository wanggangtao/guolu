<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/2/26
 * Time: 13:23
 */
class Table_chat_room_msg_config extends Table{
    private static  $pre = "config_";

    static protected function struct($data){
        $r = array();
        $r['id']              = $data['config_id'];
        $r['room_id']         = $data['config_room_id'];
        $r['uid']             = $data['config_uid'];
        $r['addtime']              = $data['config_addtime'];
        $r['last_msg_id']      = $data['config_last_msg_id'];
        $r['lastupdate']      = $data['config_lastupdate'];

        return $r;
    }

    /**
     * @param $attr
     * @return array|mixed
     *
     */
    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "room_id"=>"number",
            "uid"=>"number",
            "addtime"=>"number",
            "last_msg_id"=>"number",
            "lastupdate"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }


    /**
     * @param $attrs
     * @return mixed
     *
     */
    static public function add($attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);
        }

        $r=$mypdo->sqlinsert($mypdo->prefix.'chat_room_msg_config', $params);

        return $r;

    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id,$attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "config_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'chat_room_msg_config', $params, $where);
        return $r;
    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function updateByUidAndRoom($uid,$room_id,$attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "config_uid" => array('number', $uid),
            "config_room_id" => array('number', $room_id)

        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'chat_room_msg_config', $params, $where);
        return $r;
    }


    /**
     * 根据参数获取列表
     * @param $attrs
     * @return array|mixed
     */
    static public function getConfigByRoomUid($room_id,$uid){
        global $mypdo;

        $room_id = $mypdo->sql_check_input(array('number', $room_id));
        $uid = $mypdo->sql_check_input(array('number', $uid));

        $sql = "select * from ".$mypdo->prefix."chat_room_msg_config where config_room_id = $room_id  and config_uid = $uid limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }



    /**
     * @param $project
     * @return array|mixed
     */
    static public function getInfoByParams($params){
        global $mypdo;


        $where = " where 1=1 ";

        if(!empty($params["room_id"]))
        {
            $where .= " and  config_room_id={$params["room_id"]}";
        }


        if(!empty($params["uid"]))
        {
            $where .= " and  config_uid ={$params["uid"]}";
        }

        if(!empty($params["uidStr"]))
        {
            $where .= " and  config_uid in ({$params["uidStr"]})";
        }


        $sql = "select * from ".$mypdo->prefix."chat_room_msg_config ";

        $sql .= $where;

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }


    static public function getInfoByUid($id,$roomId){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."chat_room_msg_config where config_uid = {$id} and config_room_id = {$roomId} ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function delById($id){

        global $mypdo;

        $where = array(
            "config_id" => array('number', $id),
        );

        return $mypdo->sqldelete($mypdo->prefix.'chat_room_msg_config', $where);
    }


    static public function delByRoomId($roomId){

        global $mypdo;

        $where = array(
            'config_room_id'=>array('number', $roomId),
        );

        return $mypdo->sqldelete($mypdo->prefix.'chat_room_msg_config', $where);
    }


    static public function GetByRoomId($roomId){
        global $mypdo;

        $sql = "select config.config_id from ".$mypdo->prefix."chat_room_msg_config config LEFT JOIN `boiler_user` USER ON config.config_uid = USER.user_id where USER.user_role =1 and config.config_room_id={$roomId}";
        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }
}