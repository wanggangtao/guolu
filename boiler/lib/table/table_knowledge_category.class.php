<?php

/**
 * table_category.class.php 数据库表:管理员
 *
 * @version       $Id$ v0.01
 * @createtime    2014/9/3
 * @updatetime    2016/2/27
 * @author        dxl
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_knowledge_category extends Table {


    private static  $pre = "category_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['category_id'];
        $r['name']       = $data['category_name'];
        $r['addtime']   = $data['category_addtime'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "addtime"=>"number",

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'knowledge_category', $params);
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
            "category_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'knowledge_category', $params, $where);
        return $r;
    }
    /**
     * Table_category::getInfoById() 根据ID获取详情
     *
     * @param Integer $adminId  管理员ID
     *
     * @return
     */
    static public function getInfoById($categoryId){
        global $mypdo;

        $categoryId = $mypdo->sql_check_input(array('number', $categoryId));

        $sql = "select * from ".$mypdo->prefix."knowledge_category where category_id = $categoryId limit 1";

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
        $sql = "select * from ".$mypdo->prefix."knowledge_category";
        $sql .=" order by category_id desc";
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
        $sql = "select count(1) as act from ".$mypdo->prefix."knowledge_category";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){

            return $rs[0]["act"];
        }else{
            return 0;
        }
    }



    static public function getPageList($page,$pageSize){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."knowledge_category";

        $sql .=" order by category_id desc";

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
     * @param $categoryId
     * @return mixed
     */
    static public function del($categoryId){

        global $mypdo;

        $where = array(
            "category_id" => array('number', $categoryId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'knowledge_category', $where);
    }

}
?>
