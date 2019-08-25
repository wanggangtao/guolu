<?php

/**
 * table_fact.class.php 数据库表:管理员
 *
 * @version       $Id$ v0.01
 * @createtime    2014/9/3
 * @updatetime    2016/2/27
 * @author        dxl
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_fact extends Table {


    private static  $pre = "fact_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['fact_id'];
        $r['keyword']       = $data['fact_keyword'];
        $r['category']       = $data['fact_category'];

        $r['content']    = $data['fact_content'];
        $r['addtime']   = $data['fact_addtime'];
        $r['lastupdate']       = $data['fact_lastupdate'];
        $r['operator']      = $data['fact_operator'];
        $r['code']    = $data['fact_code'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "keyword"=>"string",
            "content"=>"string",
            "category"=>"number",
            "addtime"=>"number",
            "lastupdate"=>"string",
            "operator"=>"number",
            "code"=>"string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'fact', $params);
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
            "fact_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'fact', $params, $where);
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

        $sql = "select * from ".$mypdo->prefix."fact where fact_id = $factId limit 1";

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
     * Table_fact::getInfoById() 根据ID获取详情
     *
     * @param Integer $adminId  管理员ID
     *
     * @return
     */
    static public function getInfoByCode($code){
        global $mypdo;

        $code = $mypdo->sql_check_input(array('string', $code));

        $sql = "select * from ".$mypdo->prefix."fact where fact_code = $code limit 1";

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
        $sql = "select * from ".$mypdo->prefix."fact";
        $sql .=" order by fact_id desc";
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
        $sql = "select count(1) as act from ".$mypdo->prefix."fact";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){

            return $rs[0]["act"];
        }else{
            return 0;
        }
    }



    static public function getPageList($page,$pageSize,$content,$category){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."fact";

        $where = " where 1=1 ";
        if(!empty($content))
        {
            $where .= " and fact_content like'%{$content}%'";
        }

        if(!empty($category))
        {
            $where .= " and fact_category =$category";
        }
        $sql .=$where;
        $sql .=" order by fact_id desc";

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
            "fact_id" => array('number', $factId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'fact', $where);
    }


    static public function getBegin(){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."fact where fact_category = 1 limit 1";
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