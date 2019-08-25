<?php

/**
 * table_project_linkman_bak.class.php 数据库表:项目第二阶段联系人备份表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_linkman_bak extends Table {


    private static  $pre = "linkman_";

    static protected function struct($data){
        $r = array();
        $r['id']             = $data['linkman_id'];
        $r['pt_id']          = $data['linkman_pt_id'];
        $r['name']           = $data['linkman_name'];
        $r['phone']          = $data['linkman_phone'];
        $r['duty']           = $data['linkman_duty'];
        $r['department']     = $data['linkman_department'];
        $r['position']       = $data['linkman_position'];
        $r['isimportant']    = $data['linkman_isimportant'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "pt_id"=>"number",
            "name"=>"string",
            "phone"=>"string",
            "department"=>"string",
            "duty"=>"string",
            "position"=>"string",
            "isimportant"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_linkman_bak', $params);
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
            "linkman_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_linkman_bak', $params, $where);
        return $r;
    }

    /**
     * Table_project_linkman_bak::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_linkman_bak where linkman_id = $id limit 1";

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
     * Table_project_linkman_bak::getInfoByPtId() 根据项目ID获取详情
     *
     * @param Integer $ptid  第二阶段关联项目ID
     *
     * @return
     */
    static public function getInfoByPtId($ptid){
        global $mypdo;

        $ptid = $mypdo->sql_check_input(array('number', $ptid));

        $sql = "select * from ".$mypdo->prefix."project_linkman_bak where linkman_pt_id = $ptid  order by linkman_id desc ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * Table_project_linkman_bak::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "linkman_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_linkman_bak', $where);
    }

    /**
     * Table_project_linkman_bak::delByPtId() 根据项目ID删除数据
     *
     * @param Integer delByPtId  第二阶段关联项目ID
     *
     * @return
     */
    static public function delByPtId($ptid){

        global $mypdo;

        $where = array(
            "linkman_pt_id" => array('number', $ptid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_linkman_bak', $where);
    }
}
?>