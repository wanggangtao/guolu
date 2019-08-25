<?php

/**
 * table_project_burner_type_bak.class.php 数据库表:项目第一阶段燃烧器类型备份表
 *
 * @version       v0.01
 * @createtime    2018/6/30
 * @updatetime    2018/6/30
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_burner_type_bak extends Table {


    private static  $pre = "burner_";

    static protected function struct($data){
        $r = array();
        $r['id']             = $data['burner_id'];
        $r['po_id']          = $data['burner_po_id'];
        $r['guolu_tonnage']  = $data['burner_guolu_tonnage'];
        $r['guolu_num']      = $data['burner_guolu_num'];
        $r['addtime']        = $data['burner_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "po_id"=>"number",
            "guolu_tonnage"=>"number",
            "guolu_num"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_burner_type_bak', $params);
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
            "burner_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_burner_type_bak', $params, $where);
        return $r;
    }

    /**
     * Table_project_burner_type_bak::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_burner_type_bak where burner_id = $id limit 1";

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
     * Table_project_burner_type_bak::getInfoByPoId() 根据项目ID获取详情
     *
     * @param Integer $poid  第一阶段关联项目ID
     *
     * @return
     */
    static public function getInfoByPoId($poid){
        global $mypdo;

        $poid = $mypdo->sql_check_input(array('number', $poid));

        $sql = "select * from ".$mypdo->prefix."project_burner_type_bak where burner_po_id = $poid order by burner_id desc";

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
     * Table_project_burner_type_bak::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "burner_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_burner_type_bak', $where);
    }

    /**
     * Table_project_burner_type_bak::delByPoId() 根据项目ID删除数据
     *
     * @param Integer $poid  第一阶段关联项目ID
     *
     * @return
     */
    static public function delByPoId($poid){

        global $mypdo;

        $where = array(
            "burner_po_id" => array('number', $poid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_burner_type_bak', $where);
    }
}
?>