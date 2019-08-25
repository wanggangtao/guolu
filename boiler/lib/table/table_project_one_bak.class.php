<?php

/**
 * table_project_one_bak.class.php 数据库表:项目第一阶段备份表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_one_bak extends Table {


    private static  $pre = "po_";

    static protected function struct($data){
        $r = array();
        $r['id']                           = $data['po_id'];
        $r['project_id']                   = $data['po_project_id'];
        $r['project_name']                 = $data['po_project_name'];
        $r['project_detail']               = $data['po_project_detail'];
        $r['project_lat']                  = $data['po_project_lat'];
        $r['project_long']                 = $data['po_project_long'];
        $r['project_type']                 = $data['po_project_type'];
        $r['project_partya']               = $data['po_project_partya'];
        $r['project_partya_address']       = $data['po_project_partya_address'];
        $r['project_partya_desc']          = $data['po_project_partya_desc'];
        $r['project_partya_pic']           = $data['po_project_partya_pic'];
        $r['project_linkman']              = $data['po_project_linkman'];
        $r['project_linktel']              = $data['po_project_linktel'];
        $r['project_linkposition']         = $data['po_project_linkposition'];
        $r['project_boiler_num']           = $data['po_project_boiler_num'];
        $r['project_boiler_tonnage']       = $data['po_project_boiler_tonnage'];
        $r['project_wallboiler_num']       = $data['po_project_wallboiler_num'];
        $r['project_brand']                = $data['po_project_brand'];
        $r['project_xinghao']              = $data['po_project_xinghao'];
        $r['project_build_type']           = $data['po_project_build_type'];
        $r['project_isnew']                = $data['po_project_isnew'];
        $r['project_pre_buildtime']        = $data['po_project_pre_buildtime'];
        $r['project_competitive_brand']    = $data['po_project_competitive_brand'];
        $r['project_competitive_desc']     = $data['po_project_competitive_desc'];
        $r['project_desc']                 = $data['po_project_desc'];
        $r['project_addtime']              = $data['po_project_addtime'];
        $r['project_lastupdate']           = $data['po_project_lastupdate'];
        $r['project_history']              = $data['po_project_history'];
        $r['project_history_attr']         = $data['po_project_history_attr'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "project_id"=>"number",
            "project_name"=>"string",
            "project_detail"=>"string",
            "project_lat"=>"string",
            "project_long"=>"string",
            "project_type"=>"number",
            "project_partya"=>"string",
            "project_partya_address"=>"string",
            "project_partya_desc"=>"string",
            "project_partya_pic"=>"string",
            "project_linkman"=>"string",
            "project_linktel"=>"string",
            "project_linkposition"=>"string",
            "project_boiler_num"=>"number",
            "project_boiler_tonnage"=>"number",
            "project_wallboiler_num"=>"number",
            "project_brand"=>"string",
            "project_xinghao"=>"string",
            "project_build_type"=>"number",
            "project_isnew"=>"number",
            "project_pre_buildtime"=>"number",
            "project_competitive_brand"=>"string",
            "project_competitive_desc"=>"string",
            "project_desc"=>"string",
            "project_addtime"=>"number",
            "project_lastupdate"=>"number",
            "project_history"=>"string",
            "project_history_attr"=>"string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_one_bak', $params);
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
            "po_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_one_bak', $params, $where);
        return $r;
    }

    /**
     * Table_project_one::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_one_bak where po_id = $id limit 1";

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
     * Table_project_one::getInfoByProjectId() 根据项目ID获取详情
     *
     * @param Integer $prid  项目ID
     *
     * @return
     */
    static public function getInfoByProjectId($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_one_bak where po_project_id = $prid limit 1";

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
     * Table_project_one::getInfoNewRecodeByPrid() 根据项目ID获取最新记录详情
     *
     * @param Integer $prid  项目ID
     *
     * @return
     */
    static public function getInfoNewRecodeByPrid($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_one_bak where po_project_id = $prid order by po_project_addtime desc limit 1";

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
     * Table_project_one::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "po_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_one_bak', $where);
    }

    /**
     * Table_project_one::delByProjectId() 根据项目ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function delByProjectId($prid){

        global $mypdo;

        $where = array(
            "po_project_id" => array('number', $prid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_one_bak', $where);
    }
}
?>