<?php

/**
 * table_project_five.class.php 数据库表:项目第五阶段表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_five extends Table {


    private static  $pre = "pf_";

    static protected function struct($data){
        $r = array();
        $r['id']                 = $data['pf_id'];
        $r['project_id']         = $data['pf_project_id'];
        $r['after_solve']        = $data['pf_after_solve'];
        $r['money']              = $data['pf_money'];
        $r['pay_condition']      = $data['pf_pay_condition'];

        $r['contract_file']      = $data['pf_contract_file'];
        $r['contract_ac_file']      = $data['pf_contract_ac_file'];

        $r['cost_plan']          = $data['pf_cost_plan'];
        $r['first_reward']       = $data['pf_first_reward'];
        $r['second_reward']      = $data['pf_second_reward'];
        $r['third_reward']       = $data['pf_third_reward'];
        $r['pre_build_time']     = $data['pf_pre_build_time'];
        $r['pre_check_time']     = $data['pf_pre_check_time'];
        $r['addtime']            = $data['pf_addtime'];
        $r['lastupdate']         = $data['pf_lastupdate'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "project_id"=>"number",
            "after_solve"=>"string",
            "money"=>"number",
            "pay_condition"=>"string",
            "contract_file"=>"string",
            "contract_ac_file"=>"string",

            "cost_plan"=>"string",
            "first_reward"=>"number",
            "second_reward"=>"number",
            "third_reward"=>"number",
            "pre_build_time"=>"number",
            "pre_check_time"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_five', $params);
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
            "pf_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_five', $params, $where);
        return $r;
    }

    /**
     * Table_project_five::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_five where pf_id = $id limit 1";

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
     * Table_project_five::getInfoByProjectId() 根据项目ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoByProjectId($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_five where pf_project_id = $prid limit 1";

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
     * Table_project_five::del() 根据ID删除数据
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

        return $mypdo->sqldelete($mypdo->prefix.'project_five', $where);
    }

    /**
     * Table_project_five::delByProjectId() 根据项目ID删除数据
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

        return $mypdo->sqldelete($mypdo->prefix.'project_five', $where);
    }
}
?>