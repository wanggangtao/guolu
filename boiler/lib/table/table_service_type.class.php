<?php

/**
 * Table_service_type.class.php 数据库表:服务类型表
 *
 * @version       $Id$ v0.01
 * @create time   2014/09/04
 * @update time   2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_service_type extends Table {

    /**
     * Table_service_type::struct()  数组转换
     *
     * @param array $r
     *
     * @return
     */
    private static  $pre = "service_";

    static protected function struct($data){
        $r = array();

        $r['id']             = $data['service_id'];
        $r['name']           = $data['service_name'];
        $r['sort']           = $data['service_sort'];
        $r['add_time']        = $data['service_add_time'];
        $r['update_time']     = $data['service_update_time'];
        $r['status']         = $data['service_status'];

        return $r;
    }


    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "sort"=>"number",
            "add_time"=>"number",
            "update_time"=>"number",
            "status"=>"number",

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getList($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."service_type ";
        $where=" where service_status =1 ";

        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }
        $sql .= " ORDER BY service_sort desc,service_id asc";

        $sql .= $limit;

        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getInfoByName($servicename){
        global $mypdo;

        $servicename = $mypdo->sql_check_input(array('string', $servicename));

        $sql = "select * from ".$mypdo->prefix."service_type where service_name = $servicename limit 1";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }


    static public function add($attrs){
        global $mypdo;

        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        return $mypdo->sqlinsert($mypdo->prefix.'service_type', $params);
    }


    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."service_type where service_id = $id limit 1";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
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
            "service_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_type', $params, $where);
        return $r;
    }

    static public function dels($id){

        global $mypdo;


        $params = array(
            "service_status" => array('number', -1)
        );

        $where = array(
            "service_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_type', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."service_type ";
        $where=" where service_status =1 ";
        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }
}
?>