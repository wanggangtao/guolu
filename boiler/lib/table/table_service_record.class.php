<?php

/**
 * table_service_record.class.php 数据库表:聊天信息表
 *
 * @version       v0.01
 * @createtime    2019/3/20
 * @updatetime    2019/3/20
 * @author        GuanXin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_service_record extends Table {


    private static  $pre = "record_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['record_id'];
        $r['content']          = $data['record_content'];
        $r['time']           = $data['record_time'];
        $r['answer']         = $data['record_answer'];
        $r['status']          = $data['record_status'];
        $r['user_openid']       = $data['record_user_openid'];

        $r['account']           = $data['record_account'];
        $r['community']         = $data['record_community'];
        $r['brand']          = $data['record_brand'];
        $r['del_status']       = $data['record_del_status'];
        $r['max']       = $data['max(record_time)'];


        $r['role']           = $data['record_role'];
        $r['type']         = $data['record_type'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"         =>  "number",
            "content"       =>  "string",
            "time"        =>  "number",
            "answer"      =>  "string",
            "status"       =>  "number",
            "user_openid"    =>  "string",
            "account"        =>  "string",
            "community"      =>  "string",
            "brand"       =>  "string",
            "role"        =>  "number",
            "type"        =>  "number",
            "del_status"    =>  "number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'service_record', $params);
        return $r;
    }

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
            "record_user_openid" => array('string', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_record', $params, $where);
        return $r;
    }

    static public function getList($params){
        global $mypdo;
//        $sql = "select * ,max(record_time) from ".$mypdo->prefix."service_record ";
//
//        $sql.=" group by record_user_openid order by max(record_time) desc";
       $sql=" select * from boiler_service_record WHERE record_time in (";
       $where=" select max(record_time) from boiler_service_record ";
       $where.=" where 1=1 ";
        if($params['value']){
            $where.= "  and (record_account like '%".$params['value']."%' or record_community like '%".$params['value']."%' or record_brand like '%".$params['value']."%')";
        }
        if($params['starttime']){
            $where.= "  and record_time between {$params['starttime']} and {$params['endtime']}";
        }
        $where.= "  group by record_user_openid ) order by record_time desc";
        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;
        $rs = $mypdo->sqlQuery($sql);

        print_r($sql);
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
     * Table_service_record::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."service_record where record_user_openid = $id limit 1";

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
    static public function getCount ($params){
        global $mypdo;
//        $sql = "select count(record_user_openid) as act  from ".$mypdo->prefix."service_record ";
//
//        $sql.=" group by record_user_openid order by max(record_time) desc";
//        print_r($sql);
        $sql=" select count(1) as act from boiler_service_record WHERE record_time in (";
        $where=" select max(record_time) from boiler_service_record group by record_user_openid )";
        if($params['value']){
            $where.= "  and record_account like '%".$params['value']."%' or record_community like '%".$params['value']."%' ";
        }
        $where.= "  order by record_time desc";
        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);

        if($rs){
            return $rs[0]['act'];
        }else{
            return $rs;
        }

    }

    /**
     *
     *
     */
    static public function getListByOpenId($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."service_record ";
        $sql.=" where record_user_openid ='{$params['openid']}' order by record_time desc";
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
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

    static public function getCountByOpenId ($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."service_record ";
        $sql.=" where record_user_openid ='{$params['openid']}' order by record_time desc";
        $rs = $mypdo->sqlQuery($sql);

        if($rs){
            return $rs[0]['act'];
        }else{
            return $rs;
        }

    }

}
?>