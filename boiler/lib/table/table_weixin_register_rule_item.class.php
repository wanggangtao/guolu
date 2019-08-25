<?php

/**
 * table_weixin_register_rule_item.class.php 数据库表:优惠券注册规则发放数量表
 *
 * @version       $Id$ v0.01
 * @create time   2014/09/04
 * @update time   2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_weixin_register_rule_item extends Table {

    /**
     *
     *
     * @param array $r
     *
     * @return
     */
    private static  $pre = "item_";

    static protected function struct($data){
        $r = array();

        $r['id']                = $data['item_id'];
        $r['rule_id']           = $data['item_rule_id'];
        $r['coupon_id']         = $data['item_coupon_id'];
//        $r['coupon_starttime']           = $data['item_coupon_starttime'];
//        $r['coupon_endtime']         = $data['item_coupon_endtime'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "rule_id"=>"number",
            "coupon_id"=>"number",
//            "coupon_starttime"=>"number",
//            "coupon_endtime"=>"number",

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getList($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."register_rule_item ";

        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }
        $sql .= " ORDER BY item_id desc";

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

    static public function add($attrs){
        global $mypdo;

        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        return $mypdo->sqlinsert($mypdo->prefix.'register_rule_item', $params);
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."register_rule_item where item_id = $id limit 1";
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

    static public function getInfoByRuleId($ruleid){
        global $mypdo;

        $ruleid = $mypdo->sql_check_input(array('number', $ruleid));

        $sql = "select * from ".$mypdo->prefix."register_rule_item where item_rule_id = $ruleid ";
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

    static public function getInfoByCouponId($couponid){
        global $mypdo;

        $couponid = $mypdo->sql_check_input(array('number', $couponid));

        $sql = "select * from ".$mypdo->prefix."register_rule_item where item_coupon_id = $couponid limit 1";
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
            "item_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'register_rule_item', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."register_rule_item ";
        $where=" where item_id =1 ";
        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }
}
?>