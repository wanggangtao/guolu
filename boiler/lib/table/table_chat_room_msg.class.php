<?php

/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/2/26
 * Time: 13:23
 */
class Table_chat_room_msg extends Table {


    private static  $pre = "msg_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['msg_id'];
        $r['room_id']       = $data['msg_room_id'];
        $r['uid']  = $data['msg_uid'];
        $r['uname']  = $data['msg_uname'];
        $r['type']  = $data['msg_type'];
        $r['system_type']  = $data['msg_system_type'];

        $r['content']    = $data['msg_content'];
        $r['addtime']      = $data['msg_addtime'];
        $r['extra']      = $data['msg_extra'];
        $r['size']      = $data['msg_size'];
        $r['isShowTime']      = $data['msg_isShowTime'];

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
            "uname"=>"string",
            "type"=>"number",
            "system_type"=>"number",
            "content"=>"string",
            "addtime"=>"number",
            "extra"=>"string",
            "size"=>"string",
            "isShowTime"=>"number"
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

        $r=$mypdo->sqlinsert($mypdo->prefix.'chat_room_msg', $params);

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
            "msg_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'chat_room_msg', $params, $where);
        return $r;
    }
    /**
     * 根据id获取信息
     * @param $id
     * @return mixed
     * @throws Exception
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."chat_room_msg where msg_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * 获取最新消息
     * @param $id
     * @param $attrs
     * @return mixed
     * @throws Exception
     */
    static public function getLastMsgs($uid,$room_id){
        global $mypdo;

        $uid = $mypdo->sql_check_input(array('number', $uid));

        $room_id = $mypdo->sql_check_input(array('number', $room_id));

        $sql = "SELECT *
                FROM boiler_chat_room_msg as room_msg
                where msg_room_id={$room_id} and msg_id>(select config_last_msg_id from boiler_chat_room_msg_config where config_uid={$uid} and config_room_id={$room_id})
                order by msg_id asc";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
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
     * 获取消息记录
     * @param $projectid
     * @param $page
     * @param $pageSize
     * @return array
     * @throws Exception
     */
    static public function getMsgRecord($room_id,$current_msg_id,$direction,$page,$pageSize){
        global $mypdo;
        $room_id = $mypdo->sql_check_input(array('number', $room_id));


        $where = " WHERE m.msg_room_id = ".$room_id;



        $order = '';


        if(!empty($current_msg_id)) {
            $sql = "SELECT m.*,u.user_headimg,u.user_name 
                FROM boiler_chat_room_msg  as m left join boiler_user as u on(m.msg_uid = u.user_id)      
                ";

            if ($direction == 'down') {
                $where .= " and m.msg_id<{$current_msg_id} ";
                $order .= "ORDER BY m.msg_id desc";
            } else {
                $where .= " and m.msg_id>{$current_msg_id} ";
                $order .= " ORDER BY m.msg_id asc";
            }
        }else{
            $sql = "SELECT m.*,u.user_headimg,u.user_name 
                FROM boiler_chat_room_msg as m  left join boiler_user as u on(m.msg_uid = u.user_id)   
                ";
            $order .= " ORDER BY m.msg_id desc";
        }

        $sql .= $where;
        $sql .= $order;


        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }

        $sql .= $limit;








        $rs = $mypdo->sqlQuery($sql);


        if($rs){

            if ($direction != 'up') {

                $rs = array_reverse($rs);
            }

            return $rs;
        }else{
            return array();
        }
    }

    /**
     * 获取room_id,uid对应的群新消息数量
     * @param $room_id
     * @param $uid
     * @return array
     * @throws Exception
     */
    static public function getNewCount($room_id,$uid,$last_msg_id){
        global $mypdo;

        $where = " where  1=1 ";

        $room_id = $mypdo->sql_check_input(array('number', $room_id));
        $uid = $mypdo->sql_check_input(array('number', $uid));
        $last_msg_id = $mypdo->sql_check_input(array('number', $last_msg_id));

        if(isset($room_id)){
            $where.= " and msg_room_id = ".$room_id ;
        }
        if(isset($uid)){
            $where.= " and msg_uid = ".$uid ;
        }
        if(isset($last_msg_id)){
            $where.= " and msg_id > ".$last_msg_id ;
        }

        $sql = "select count(*) as ct from ".$mypdo->prefix."chat_room_msg ";
        $sql.=$where;
        $r = $mypdo->sqlQuery($sql);
        if($r){
            return $r[0]['ct'];
        }else{
            return -1;
        }
    }

    /**
     * Table_chat_room_msg::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $roomid        项目ID
     * @param string $keywords           内容关键词
     * @param number $stday            开始日期
     * @param number $endday           结束日期
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $roomid, $keywords,  $stday, $endday){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($roomid){
            $where .= " and msg_room_id = {$roomid}";
        }
        if($keywords){
            $keywords = $mypdo->sql_check_input(array('string', "%".$keywords."%"));
            $where .= " and msg_content like $keywords ";
        }
//        if($roomid){
//            $roomid = $mypdo->sql_check_input(array('number', $roomid));
//            $where .= " and chat_room_msg_room_id = $roomid ";
//        }
        if($stday){
            $stday = $mypdo->sql_check_input(array('number', $stday));
            $where .= " and msg_addtime >= $stday ";
        }
        if($endday){
            $endday = $mypdo->sql_check_input(array('number', $endday));
            $where .= " and msg_addtime <= $endday ";
        }
        $where .= " order by msg_id desc";

        if($count == 1){
            $sql = "select count(1) as ct from ".$mypdo->prefix."chat_room_msg".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }
//        else{
            $sql = "select * from ".$mypdo->prefix."chat_room_msg".$where;
//            $sql .=" order by msg_id desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            //print_r(sql);
            $rs = $mypdo->sqlQuery($sql);
//            echo $sql;
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
            }

            return $r;
//        }
    }





    /**
     * 获取群消息最后一条记录
     * @param $projectid
     * @param $page
     * @param $pageSize
     * @return array
     * @throws Exception
     */
    static public function getLastMsg($room_id){
        global $mypdo;
        $room_id = $mypdo->sql_check_input(array('number', $room_id));

        $sql = "SELECT *
                FROM boiler_chat_room_msg 
                WHERE msg_room_id = ".$room_id." 
                ORDER BY msg_id desc limit 1";


        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }


    static public function getCurrentIdByQuery($keyword,$time,$page,$pageSize,$room_id){
        global $mypdo;

        if(!empty($time)){
            $time = strtotime($time);
            $sql = "select * from ".$mypdo->prefix."chat_room_msg ";
            $sql .= " where msg_addtime >={$time} and msg_room_id = {$room_id} limit 1";

        }else{
            $sql = "select * from ".$mypdo->prefix."chat_room_msg";
            $sql .= " where msg_content like '%{$keyword}%' and msg_room_id={$room_id} ";
            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
        }
        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function getResource($page,$pageSize,$room_id){
        global $mypdo;



        $sql = "select * from ".$mypdo->prefix."chat_room_msg";
        $sql .= " WHERE (msg_type =3 OR msg_type =4) and msg_room_id={$room_id}";
        $sql .=" order by msg_id desc";
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }

        $sql .= $limit;

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

}