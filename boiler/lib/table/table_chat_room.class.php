<?php

/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/2/26
 * Time: 13:23
 */
class Table_chat_room extends Table {


    private static  $pre = "room_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['room_id'];
        $r['name']       = $data['room_name'];
        $r['first']    = $data['room_first'];
        $r['principal_uid']        = $data['room_principal_uid'];
        $r['principal_uname']      = $data['room_principal_uname'];
        $r['project']      = $data['room_project'];
        $r['last_msg_id']      = $data['room_last_msg_id'];
        $r['last_msg_uid']      = $data['room_last_msg_uid'];
        $r['last_msg_uname']      = $data['room_last_msg_uname'];
        $r['last_msg_content']      = $data['room_last_msg_content'];
        $r['last_msg_addtime']      = $data['room_last_msg_addtime'];
        $r['addtime']      = $data['room_addtime'];
        $r['status']      = $data['room_status'];

        return $r;
    }



    static protected function structs($data){
        $r = array();
        $r['id']           = $data['room_id'];
        $r['name']       = $data['room_name'];
        $r['first']    = $data['room_first'];
        $r['principal_uid']        = $data['room_principal_uid'];
        $r['principal_uname']      = $data['room_principal_uname'];
        $r['project']      = $data['room_project'];
        $r['last_msg_id']      = $data['room_last_msg_id'];
        $r['last_msg_uid']      = $data['room_last_msg_uid'];
        $r['last_msg_uname']      = $data['room_last_msg_uname'];
        $r['last_msg_content']      = $data['room_last_msg_content'];
        $r['last_msg_addtime']      = $data['room_last_msg_addtime'];
        $r['addtime']      = $data['room_addtime'];
        $r['status']      = $data['room_status'];
        $r['new_count']      = $data['new_count'];

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
            "name"=>"string",
            "first"=>"string",
            "principal_uid"=>"number",
            "principal_uname"=>"string",
            "project"=>"number",
            "last_msg_id"=>"number",
            "last_msg_uid"=>"number",
            "last_msg_uname"=>"string",
            "last_msg_content"=>"string",
            "last_msg_addtime"=>"number",
            "addtime"=>"number",
            "status"=>"number"
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

        $r=$mypdo->sqlinsert($mypdo->prefix.'chat_room', $params);

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
            "room_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'chat_room', $params, $where);
        return $r;
    }

    /**
     * Table_chat_room::getInfoByProId() 根据项目ID获取详情
     *
     * @param Integer $adminId  管理员ID
     *
     * @return
     */
    static public function getInfoByProId($projectId){
        global $mypdo;

        $projectId = $mypdo->sql_check_input(array('number', $projectId));

        $sql = "select * from ".$mypdo->prefix."chat_room where room_project = $projectId limit 1";

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
    static public function getInfoByProject($project){
        global $mypdo;

        $project = $mypdo->sql_check_input(array('number', $project));

        $sql = "select * from ".$mypdo->prefix."chat_room where room_project = {$project} limit 1";

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
     * 根据room_id获取room
     * @param $room_id
     * @return array|mixed
     * @throws Exception
     */
    static public function getInfoByAttrs($attrs,$page,$pageSize){
        global $mypdo;

        $where = "where 1=1";
        if(!empty($attrs)){
            foreach ($attrs as $key => $value){
                $where.=" and ".self::$pre.$key."={$value} ";
            }
        }
        $sql = "select * from ".$mypdo->prefix."chat_room ";

        $sql.=$where;
        $sql.=" ORDER BY room_last_msg_addtime ASC ";
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }
        $sql .= $limit;

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

    /**
     * 根据ID获取详情
     * @param $id
     * @return array|mixed
     * @throws Exception
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."chat_room where room_id = $id limit 1";

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
     * 根据room_id获取room
     * @param $room_id
     * @return array|mixed
     * @throws Exception
     */
    static public function getListByUser($uid,$attrs,$page,$pageSize){
        global $mypdo;

        $where = " where c.config_uid = {$uid}";

        $sql = "select r.*,(select count(1) from ".$mypdo->prefix."chat_room_msg where msg_room_id=c.config_room_id and msg_id>c.config_last_msg_id and msg_type!=".chat_room_msg::MSG_TIME.") as new_count from ".$mypdo->prefix."chat_room_msg_config as c 
                 left join ".$mypdo->prefix."chat_room as r on(c.config_room_id=r.room_id)
                 ";



        if(!empty($attrs["keyword"]))
        {
            $where .= " and (r.room_name like '%{$attrs["keyword"]}%' or r.room_principal_uname like '%{$attrs["keyword"]}%')";
        }


        $sql.=$where;
        $sql.=" and r.room_status !=-1 ORDER BY r.room_is_top desc,r.room_type desc,r.room_last_msg_addtime desc";
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }
        $sql .= $limit;

        $rs = $mypdo->sqlQuery($sql);
        if($rs){

            return $rs;
        }else{
            return array();
        }
    }



    static public function getRoomIdByProject($projectId){
        global $mypdo;

        $projectId = $mypdo->sql_check_input(array('number', $projectId));

        $sql = "select room_id from ".$mypdo->prefix."chat_room where room_project = $projectId limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r[0];
        }else{
            return $r;
        }
    }


    static public function getList(){
        global $mypdo;
        $sql = 'select * from '.$mypdo->prefix.'chat_room where room_status !=-1';

        $rs = $mypdo->sqlQuery($sql);
//echo $sql;
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }


    static public function getRoomList(){
        global $mypdo;

        $sql = "select room_id from ".$mypdo->prefix."chat_room where room_status !=-1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getListByUId($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select room_id from ".$mypdo->prefix."chat_room where room_principal_uid = $id and room_status !=-1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r;
        }else{
            return $r;
        }
    }


    static public function getListByUd($id,$uid,$attrs,$page,$pageSize){
        global $mypdo;

        $where = " where c.config_uid = {$id}";

        $sql = "select r.*,(select count(1) from ".$mypdo->prefix."chat_room_msg where msg_room_id=c.config_room_id and msg_id>c.config_last_msg_id and msg_type!=".chat_room_msg::MSG_TIME.") as new_count from ".$mypdo->prefix."chat_room_msg_config as c 
                 left join ".$mypdo->prefix."chat_room as r on(c.config_room_id=r.room_id)
                 ";



        if(!empty($attrs["keyword"]))
        {
            $where .= " and (r.room_name like '%{$attrs["keyword"]}%' or r.room_principal_uname like '%{$attrs["keyword"]}%')";
        }

        if (!empty($uid)){
            $where.=" and room_principal_uid={$uid} and r.room_status !=-1 ORDER BY r.room_is_top desc,r.room_type desc,r.room_last_msg_addtime desc";

        }else{
            $where.=" and r.room_status !=-1 ORDER BY r.room_is_top desc,r.room_type desc,r.room_last_msg_addtime desc ";

        }
        $sql.=$where;
        $sql.=" ";
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }
        $sql .= $limit;

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::structs($val);
            }
        }
        return $r;
    }



    static public function getRoomByUId($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select room_id from ".$mypdo->prefix."chat_room where room_principal_uid = $id and room_status !=-1 and room_type=2";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r[0];
        }else{
            return $r;
        }
    }
}