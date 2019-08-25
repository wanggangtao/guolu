<?php

/**
 * table_service_category.class.php 数据库表:问题类别表
 *
 * @version       v0.01
 * @createtime    2019/3/20
 * @updatetime    2019/3/20
 * @author        GuanXin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_service_category extends Table {


    private static  $pre = "category_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['category_id'];
        $r['name']          = $data['category_name'];
        $r['addtime']           = $data['category_addtime'];
        $r['status']          = $data['category_status'];
        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"         =>  "number",
            "name"       =>  "string",
            "addtime"        =>  "number",
            "status"       =>  "number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'service_category', $params);
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
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_category', $params, $where);
        return $r;
    }

    /**
     * Table_service_category::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."service_category where category_id = $id limit 1";

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

    static public function getCount(){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."service_category";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){

            return $rs[0]["act"];
        }else{
            return 0;
        }
    }

    static public function getPageList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."service_category where category_status=1";

        $sql .=" order by category_id desc";

        $limit = "";
        if(!empty($params['$page']) && !empty($params['$pageSize']))
        {

            $start = ($params['$page'] - 1)*$params['$pageSize'];
            $limit = " limit {$start},{$params['$pageSize']}";
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
     * Table_service_category::del() 根据ID删除数据
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function del($id){
//
//        global $mypdo;
//
//        $params['category_status'] = 0;
//        //where条件
//        $where = array(
//            "category_id" => array('number', $id)
//        );
//        //返回结果
//        $r = $mypdo->sqlupdate($mypdo->prefix.'service_category', $params, $where);
//        return $r;
        global $mypdo;

        $where = array(
            "category_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'service_category', $where);
    }
}
?>