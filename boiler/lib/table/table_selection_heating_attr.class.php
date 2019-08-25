<?php

/**
 * table_selection_heating_attr.class.php 数据库表:暖属性表
 *
 * @version       v0.01
 * @createtime    2018/7/18
 * @updatetime    2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_selection_heating_attr extends Table {


    private static  $pre = "heating_";

    static protected function struct($data){
        $r = array();
        $r['id']                 = $data['heating_id'];
        $r['history_id']         = $data['heating_history_id'];
        $r['build_type']         = $data['heating_build_type'];
        $r['floor_low']          = $data['heating_floor_low'];
        $r['floor_high']         = $data['heating_floor_high'];
        $r['floor_height']       = $data['heating_floor_height'];
        $r['area']               = $data['heating_area'];
        $r['type']               = $data['heating_type'];
        $r['usetime_type']       = $data['heating_usetime_type'];
        $r['addtime']            = $data['heating_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "history_id"=>"number",
            "build_type"=>"number",
            "floor_low"=>"number",
            "floor_high"=>"number",
            "floor_height"=>"number",
            "area"=>"number",
            "type"=>"number",
            "usetime_type"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_heating_attr', $params);
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
            "heating_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_heating_attr', $params, $where);
        return $r;
    }

    /**
     * Table_selection_heating_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_heating_attr where heating_id = $id limit 1";

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
     * Table_selection_heating_attr::getInfoByHistoryId() 根据历史ID获取详情
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function getInfoByHistoryId($hisid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));

        $sql = "select * from ".$mypdo->prefix."selection_heating_attr where heating_history_id = $hisid order by heating_id desc";

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
     * Table_selection_heating_attr::del() 根据ID删除数据
     *
     * @param Integer $id  历史ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "heating_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_heating_attr', $where);
    }

    /**
     * Table_selection_heating_attr::delByHistoryId() 根据历史ID删除数据
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function delByHistoryId($hisid){

        global $mypdo;

        $where = array(
            "heating_history_id" => array('number', $hisid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_heating_attr', $where);
    }
}
?>