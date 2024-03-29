<?php

/**
 * table_project_two_bak.class.php 数据库表:项目第二阶段备份表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_two_bak extends Table {


    private static  $pre = "pt_";

    static protected function struct($data){
        $r = array();
        $r['id']                   = $data['pt_id'];
        $r['project_id']           = $data['pt_project_id'];
        $r['addtime']              = $data['pt_addtime'];
        $r['lastupdate']           = $data['pt_lastupdate'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "project_id"=>"number",
            "addtime"=>"number",
            "lastupdate"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    /**
     * Table_project_two_bak::getInfoNewRecodeByPrid() 根据项目ID获取最新记录详情
     *
     * @param Integer $prid  项目ID
     *
     * @return
     */
    static public function getInfoNewRecodeByPrid($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_two_bak where pt_project_id = $prid order by pt_addtime desc limit 1";

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


    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_two_bak', $params);
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
            "pt_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_two_bak', $params, $where);
        return $r;
    }

    /**
     * Table_project_two_bak::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_two_bak where pt_id = $id limit 1";

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
     * Table_project_two_bak::getInfoByProjectId() 根据项目ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoByProjectId($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_two_bak where pt_project_id = $prid limit 1";

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
     * Table_project_two_bak::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "pt_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_two_bak', $where);
    }

    /**
     * Table_project_two_bak::delByProjectId() 根据项目ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function delByProjectId($prid){

        global $mypdo;

        $where = array(
            "pt_project_id" => array('number', $prid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_two_bak', $where);
    }
}
?>