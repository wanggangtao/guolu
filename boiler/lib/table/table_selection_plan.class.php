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

class Table_selection_plan extends Table {


    private static  $pre = "plan_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['plan_id'];
        $r['history_id']   = $data['plan_history_id'];
        $r['equ_name']       = $data['plan_equ_name'];
        $r['version']         = $data['plan_version'];
        $r['nums']         = $data['plan_nums'];
        $r['remark']         = $data['plan_remark'];
        $r['tab_type']       = $data['plan_tab_type'];
        $r['modelid']         = $data['plan_modelid'];
        $r['attrid']         = $data['plan_attrid'];
        $r['pro_price']       = $data['plan_pro_price'];


        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "history_id"=>"number",
            "equ_name"=>"string",
            "version"=>"string",
            "nums"=>"number",
            "remark"=>"string",
            "tab_type"=>"number",
            "modelid"=>"number",
            "attrid"=>"number",
            "price"=>"number"

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_plan', $params);
        return $r;
    }

    static public function delByHistoryId($hid){

        global $mypdo;

        $where = array(
            "plan_history_id" => array('number', $hid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_plan', $where);
    }

    /**
     * Table_selection_plan::getInfoByHistoryidandTabtype() 根据历史ID和表单类型获取详情
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function getInfoByHistoryidandTabtype($hisid,$tab_type){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $tab_type = $mypdo->sql_check_input(array('number', $tab_type));

        $sql = "select * from ".$mypdo->prefix."selection_plan where plan_history_id = $hisid and plan_tab_type = $tab_type ";

        //print_r($sql."---------------");
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function getInfoByHistoryId($hisid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $sql = "select * from ".$mypdo->prefix."selection_plan where plan_history_id = $hisid";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."selection_plan where plan_id = $id ";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r[0];
    }


    static public function getInfoByHidandTabtypeandAttrid($hisid,$tab_type,$attrid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $tab_type = $mypdo->sql_check_input(array('number', $tab_type));
        $attrid = $mypdo->sql_check_input(array('number', $attrid));

        $sql = "select * from ".$mypdo->prefix."selection_plan where plan_history_id = $hisid and plan_tab_type = $tab_type and plan_attrid = $attrid limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function getListByHidandTabtype($hisid,$tab_type){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($hisid){
            $where .= " and plan_history_id = {$hisid}";
        }
        if(isset($tab_type)){
            $tab_type = $mypdo->sql_check_input(array('number', $tab_type));
            $where .= " and plan_tab_type = $tab_type ";
        }

        $where .= " order by plan_id asc";


        $sql = "select * from ".$mypdo->prefix."selection_plan".$where;
//echo $sql;

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
     * 根据history_id获取plan列表
     * @param $hisid
     * @param $tab_type
     * @return array
     * @throws Exception
     */
    static public function findFujiByHistory($hisid ){
        global $mypdo;

        $where = " WHERE plan.plan_history_id =  $hisid  
	                    AND plan.plan_tab_type IN (".Selection_plan::HEATING_FUJI_TAB_TYPE.",
	                    ".Selection_plan::WATER_FUJI_TAB_TYPE.",".Selection_plan::ADD_FUJI_TAB_TYPE.")";


        $sql = "select * from ".$mypdo->prefix."selection_plan as plan".$where;

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
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
            "plan_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_plan', $params, $where);
        return $r;
    }
}
?>