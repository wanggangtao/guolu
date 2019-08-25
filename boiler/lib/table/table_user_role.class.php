<?php

/**
 * table_user_role.class.php 数据库表:角色表
 *
 * @version       v0.01
 * @createtime    2018/6/27
 * @updatetime    2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_user_role extends Table {


    private static  $pre = "role_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['role_id'];
        $r['name']          = $data['role_name'];
        $r['addtime']       = $data['role_addtime'];

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'user_role', $params);
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
            "role_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_role', $params, $where);
        return $r;
    }

    /**
     * Table_user_role::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."user_role where role_id = $id limit 1";

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
     * Table_user_role::getAllList() 列出所有信息
     * @return
     */
    static public function getAllList(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."user_role";
        $sql .=" order by role_id asc";

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
     * Table_user_role::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "role_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'user_role', $where);
    }
}
?>