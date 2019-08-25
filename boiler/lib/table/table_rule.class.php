<?php

/**
 * table_rule.class.php 数据库表:管理员
 *
 * @version       $Id$ v0.01
 * @createtime    2014/9/3
 * @updatetime    2016/2/27
 * @author        dxl
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_rule extends Table {


    private static  $pre = "rule_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['rule_id'];
        $r['keyword']       = $data['rule_keyword'];
        $r['before']       = $data['rule_before'];

        $r['after']    = $data['rule_after'];
        $r['status']   = $data['rule_status'];
        $r['addtime']   = $data['rule_addtime'];

        $r['lastupdate']       = $data['rule_lastupdate'];
        $r['operator']       = $data['rule_operator'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "keyword"=>"string",
            "before"=>"string",
            "after"=>"string",
            "status"=>"number",

            "addtime"=>"number",
            "lastupdate"=>"string",
            "operator"=>"number",

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'rule', $params);
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
            "rule_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'rule', $params, $where);
        return $r;
    }
    /**
     * Table_fact::getInfoById() 根据ID获取详情
     *
     * @param Integer $adminId  管理员ID
     *
     * @return
     */
    static public function getInfoById($factId){
        global $mypdo;

        $factId = $mypdo->sql_check_input(array('number', $factId));

        $sql = "select * from ".$mypdo->prefix."rule where rule_id = $factId limit 1";

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



    static public function getList(){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."rule";
        $sql .=" order by rule_id desc";
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


    static public function getCount(){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."rule";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){

            return $rs[0]["act"];
        }else{
            return 0;
        }
    }



    static public function getPageList($page,$pageSize,$before,$after){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."rule";

        $where = " where 1=1 ";
        if(!empty($before))
        {
            $where .= " and rule_before = '{$before}'";
        }


        if(!empty($after))
        {
            $where .= " and rule_after = '{$after}'";
        }

        $sql .= $where;
        $sql .=" order by rule_id desc";

        $limit = "";
        if(!empty($page))
        {

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
            return $r;
        }else{
            return $r;
        }
    }


    /**
     * @param $factId
     * @return mixed
     */
    static public function del($factId){

        global $mypdo;

        $where = array(
            "rule_id" => array('number', $factId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'rule', $where);
    }


    static public function getInfoByBeforeAndKeyword($before,$keyword)
    {
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."rule";

        $where = " where 1=1 ";
        if(!empty($before))
        {
            $where .= " and rule_before = '{$before}'";
        }


        if(!empty($keyword))
        {
            $where .= " and rule_keyword like'%{$keyword}%'";
        }

        $sql .= $where;
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

}
?>