<?php

/**
 * table_weixin_coupon_register_rule.class.php 数据库表:优惠券注册规则发放表
 *
 * @version       $Id$ v0.01
 * @create time   2014/09/04
 * @update time   2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_weixin_coupon_register_rule extends Table {

    /**
     *
     *
     * @param array $r
     *
     * @return
     */
    private static  $pre = "rule_";

    static protected function struct($data){
        $r = array();

        $r['id']              = $data['rule_id'];
        $r['name']            = $data['rule_name'];
        $r['starttime']       = $data['rule_starttime'];
        $r['endtime']         = $data['rule_endtime'];
        $r['updatetime']      = $data['rule_updatetime'];
        $r['status']          = $data['rule_status'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "starttime"=>"number",
            "endtime"=>"number",
            "updatetime"=>"number",
            "status"=>"number",

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getList($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."coupon_register_rule ";
        $where=" where rule_status != 2 ";

        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }
        $sql .= " ORDER BY rule_id desc";

        $sql .= $limit;

        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getNormalList($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."coupon_register_rule ";
        $where=" where rule_status = 1 ";

        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }
        $sql .= " ORDER BY rule_id desc";

        $sql .= $limit;

        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getInfoByName($rulename){
        global $mypdo;

        $rulename = $mypdo->sql_check_input(array('string', $rulename));

        $sql = "select * from ".$mypdo->prefix."coupon_register_rule where rule_name = $rulename and rule_status != 2 limit 1";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
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

        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        return $mypdo->sqlinsert($mypdo->prefix.'coupon_register_rule', $params);
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."coupon_register_rule where rule_id = $id limit 1";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
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
            "rule_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon_register_rule', $params, $where);
        return $r;
    }

    static public function dels($id){

        global $mypdo;


        $params = array(
            "rule_status" => array('number', 2)
        );

        $where = array(
            "rule_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon_register_rule', $params, $where);
        return $r;
    }
    //终止发放
    static public function termination($id){

        global $mypdo;


        $params = array(
            "rule_status" => array('number', -1)
        );

        $where = array(
            "rule_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon_register_rule', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."coupon_register_rule ";
        $where=" where rule_status != 2 ";
        $sql.=$where;
//        echo $sql;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }
    static public function isRuleExist($start,$end){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."coupon_register_rule ";
        $where=" where rule_status =1 and (('$start' >= rule_starttime and rule_endtime >= '$start')
                 or ('$end' >= rule_starttime and rule_endtime >= '$end'))";
        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }


    static public function getInfoByTime($nowTime){
        global $mypdo;

        $nowTime = $mypdo->sql_check_input(array('number', $nowTime));

        $sql = "select item_coupon_id ,rule_id from boiler_register_rule_item LEFT JOIN boiler_coupon_register_rule on item_rule_id = rule_id
              where rule_starttime<= {$nowTime} and rule_endtime > {$nowTime} and rule_status = 1";

        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            return $rs;
        }else{
            return $r;
        }
    }

}
?>