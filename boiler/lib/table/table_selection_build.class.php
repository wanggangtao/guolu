<?php

/**
 * table_selection_build.class.php 数据库表:建筑类型表
 *
 * @version       v0.01
 * @createtime    2018/7/18
 * @updatetime    2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_selection_build extends Table {


    private static  $pre = "build_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['build_id'];
        $r['name']          = $data['build_name'];
        $r['status']        = $data['build_status'];
        $r['parent']        = $data['build_parent'];
        $r['addtime']       = $data['build_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "status"=>"number",
            "parent"=>"number",
            "addtime"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_build', $params);
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
            "build_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_build', $params, $where);
        return $r;
    }

    /**
     * Table_selection_build::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_build where build_id = $id limit 1";

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
     * Table_selection_build::getInfoByParentid() 根据父类ID获取详情
     *
     * @param Integer $parentid  父类ID
     *
     * @return
     */
    static public function getInfoByParentid($parentid){
        global $mypdo;

        $parentid = $mypdo->sql_check_input(array('number', $parentid));

        $sql = "select * from ".$mypdo->prefix."selection_build where build_parent = $parentid limit 1";

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
     * Table_selection_build::getListByParentid() 根据父类ID获取列表 
     *
     * @param Integer $parentid  父类ID
     *
     * @return
     */
    static public function getListByParentid($parentid){
        global $mypdo;
    
        $parentid = $mypdo->sql_check_input(array('number', $parentid));
    
        $sql = "select * from ".$mypdo->prefix."selection_build where build_parent = $parentid and build_status = 1 ";
    
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
     * Table_selection_build::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "build_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_build', $where);
    }

    /**
     * Table_selection_build::delByParentid() 根据父类ID删除数据
     *
     * @param Integer $parentid  父类别ID
     *
     * @return
     */
    static public function delByParentid($parentid){

        global $mypdo;

        $where = array(
            "build_parentid" => array('number', $parentid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_build', $where);
    }

}
?>