<?php

/**
 * table_project_type.class.php 数据库表:项目类别表
 *
 * @version       v0.01
 * @createtime    2018/6/27
 * @updatetime    2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_type extends Table {


    private static  $pre = "type_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['type_id'];
        $r['name']          = $data['type_name'];
        $r['addtime']       = $data['type_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_type', $params);
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
            "type_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_type', $params, $where);
        return $r;
    }

    /**
     * Table_project_type::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_type where type_id = $id limit 1";

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
     * Table_project_type::getAllList() 列出所有信息
     * @return
     */
    static public function getAllList(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."project_type";
        $sql .=" order by type_id asc";

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
     * Table_project_type::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "type_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_type', $where);
    }
}
?>