<?php

/**
 * table_project_four_bak.class.php 数据库表:项目第四阶段备份表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_four_bak extends Table {


    private static  $pre = "pf_";

    static protected function struct($data){
        $r = array();
        $r['id']                           = $data['pf_id'];
        $r['project_id']                   = $data['pf_project_id'];
        $r['project_cid_company']          = $data['pf_project_cid_company'];
        $r['project_cid_linkman']          = $data['pf_project_cid_linkman'];
        $r['project_cid_linkphone']        = $data['pf_project_cid_linkphone'];
        $r['project_cid_file']             = $data['pf_project_cid_file'];
        $r['project_bid_file']             = $data['pf_project_bid_file'];
        $r['project_cid_ac_file']          = $data['pf_project_cid_ac_file'];
        $r['project_bid_ac_file']          = $data['pf_project_bid_ac_file'];
        $r['project_cbid_situation']       = $data['pf_project_cbid_situation'];
        $r['addtime']              = $data['pf_addtime'];
        $r['lastupdate']           = $data['pf_lastupdate'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "project_id"=>"number",
            "project_cid_company"=>"string",
            "project_cid_linkman"=>"string",
            "project_cid_linkphone"=>"string",
            "project_cid_file"=>"string",
            "project_bid_file"=>"string",
            "project_cid_ac_file"=>"string",
            "project_bid_ac_file"=>"string",
            "project_cbid_situation"=>"string",
            "addtime"=>"number",
            "lastupdate"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_four_bak', $params);
        return $r;
    }

    static public function getInfoNewRecodeByPrid($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_four_bak where pf_project_id = $prid order by pf_addtime desc limit 1";

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
            "pf_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_four_bak', $params, $where);
        return $r;
    }

    /**
     * Table_project_four_bak::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_four_bak where pf_id = $id limit 1";

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
     * Table_project_four_bak::getInfoByProjectId() 根据项目ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoByProjectId($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_four_bak where pf_project_id = $prid limit 1";

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
     * Table_project_four_bak::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "pf_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_four_bak', $where);
    }

    /**
     * Table_project_four_bak::delByProjectId() 根据项目ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function delByProjectId($prid){

        global $mypdo;

        $where = array(
            "pf_project_id" => array('number', $prid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_four_bak', $where);
    }
}
?>