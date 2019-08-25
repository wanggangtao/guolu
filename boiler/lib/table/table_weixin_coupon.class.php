<?php

/**
 * table_weixin_coupon.class.php 数据库表:优惠券表
 *
 * @version       $Id$ v0.01
 * @create time   2014/09/04
 * @update time   2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_weixin_coupon extends Table {

    private static  $pre = "coupon_";

    static protected function struct($data){
        $r = array();

        $r['id']                 = $data['coupon_id'];
        $r['name']               = $data['coupon_name'];
        $r['total']              = $data['coupon_total'];
        $r['received']           = $data['coupon_received'];
        $r['money']              = $data['coupon_money'];
        $r['type']               = $data['coupon_type'];
        $r['addtime']            = $data['coupon_addtime'];
        $r['deletetime']         = $data['coupon_deletetime'];
        $r['starttime']          = $data['coupon_starttime'];
        $r['endtime']            = $data['coupon_endtime'];
        $r['validity_period']    = $data['coupon_validity_period'];
        $r['status']             = $data['coupon_status'];

        return $r;
    }


    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "total"=>"number",
            "received"=>"number",
            "money"=>"number",
            "type"=>"string",
            "addtime"=>"number",
            "deletetime"=>"number",
            "starttime"=>"number",
            "endtime"=>"number",
            "validity_period"=>"number",
            "status"=>"number",


        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getList($params){
        global $mypdo;
        $nowTime = time();

        $sql = "select * from ".$mypdo->prefix."coupon ";
        $where=" where coupon_status =1 ";
        if($params["onTime"]){
            $where .= " and if(coupon_validity_period <> 0 , 1 = 1 ,coupon_endtime - {$params['onTime']} > 0 )";
        }

        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }
        $sql .= " ORDER BY coupon_id desc";

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

    static public function getInfoByName($couponname){
        global $mypdo;

        $couponname = $mypdo->sql_check_input(array('string', $couponname));

        $sql = "select * from ".$mypdo->prefix."coupon where coupon_name = $couponname and  coupon_status != -1  limit 1";
//        echo $sql;
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
        return $mypdo->sqlinsert($mypdo->prefix.'coupon', $params);
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."coupon where coupon_id = $id limit 1";
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
            "coupon_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon', $params, $where);
        return $r;
    }

    static public function dels($id){

        global $mypdo;


        $params = array(
            "coupon_status" => array('number', -1)
        );

        $where = array(
            "coupon_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."coupon ";
        $where=" where coupon_status =1 ";
        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }

    static public function getInfoByIdAndType($id,$type){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."coupon where coupon_id = $id and FIND_IN_SET ({$type},coupon_type)";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
//        echo $sql;
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }

//    static public function getTypeNameById($id){
//        global $mypdo;
//
//        $id = $mypdo->sql_check_input(array('number', $id));
//
//        $sql = "select service_name from boiler_service_type LEFT JOIN boiler_coupon on item_rule_id = rule_id
//              where rule_starttime<= {$nowTime} and rule_endtime > {$nowTime} and rule_status = 1";
//
//        $rs  = $mypdo->sqlQuery($sql);
//        $r   = array();
//        if($rs){
//            return $rs;
//        }else{
//            return $r;
//        }
//    }
}
?>