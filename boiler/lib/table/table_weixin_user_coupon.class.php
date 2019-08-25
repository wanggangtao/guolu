<?php

/**
 * table_weixin_user_coupon.class.php 数据库表:用户优惠券表
 *
 * @version       $Id$ v0.01
 * @create time   2014/09/04
 * @update time   2016/02/18
 * @author        dxl,wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_weixin_user_coupon extends Table {

    private static  $pre = "coupon_";

    static protected function struct($data){
        $r = array();

        $r['id']              = $data['coupon_id'];
        $r['uid']             = $data['coupon_uid'];
        $r['cid']             = $data['coupon_cid'];
        $r['addtime']         = $data['coupon_addtime'];
        $r['starttime']       = $data['coupon_starttime'];
        $r['endtime']         = $data['coupon_endtime'];
        $r['updatetime']      = $data['coupon_updatetime'];
        $r['status']          = $data['coupon_status'];

        return $r;
    }


    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "uid"=>"number",
            "cid"=>"number",
            "addtime"=>"number",
            "starttime"=>"number",
            "endtime"=>"number",
            "updatetime"=>"number",
            "status"=>"number",

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getList($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."user_coupon ";
        $where=" where coupon_status in ('1','2','3') ";

        if(isset($params['accountName'])){
            $where .= " and  coupon_uid in (".$params['accountName'].") ";
        }
        if(!empty($params['addtime'])){
            $starttime = strtotime($params['addtime']);
            $endtime = strtotime($params['addtime'])+ 86399;
            // $where .=" and order_addtime > $starttime and  order_addtime < $endtime";
            $where .=" and coupon_addtime >= $starttime and coupon_addtime <= $endtime ";
        }
        if(isset($params['coupon_name']) && !empty($params['coupon_name'])){
            $where .=" and coupon_cid = ".$params['coupon_name'] ;
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

    static public function getListALLInfo($id,$status,$params){
        global $mypdo;


        $sql = "select boiler_user_coupon.coupon_starttime,boiler_user_coupon.coupon_endtime,boiler_coupon.coupon_money,boiler_coupon.coupon_name from ".$mypdo->prefix."user_coupon";
        $sql .= " LEFT JOIN boiler_coupon on coupon_cid = boiler_coupon.coupon_id ";
        $sql .=" where boiler_user_coupon.coupon_uid = {$id} and boiler_user_coupon.coupon_status = {$status}";

        if($params['no_expired']){
            $sql .= " and  (boiler_user_coupon.coupon_endtime > {$params['no_expired']}) ";
        }

        if($params['expired']){
            $sql .= " and ( boiler_user_coupon.coupon_endtime <= {$params['expired']} )";
        }


        $sql .= " ORDER BY boiler_user_coupon.coupon_id desc";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            return $rs;
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
        return $mypdo->sqlinsert($mypdo->prefix.'user_coupon', $params);
    }

    static public function getInfoById($id,$type ){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."user_coupon where coupon_id = $id ";
        if($type == 1){
            $sql .= " and coupon_status = 1 ";
        }
        $sql .= " limit 1 ";
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

    static public function getInfoByUid($uid){
        global $mypdo;
        $nowTime = time();

        $uid = $mypdo->sql_check_input(array('number', $uid));

        $sql = "select * from ".$mypdo->prefix."user_coupon where coupon_uid = $uid  and coupon_status = 1 and ( {$nowTime} >= coupon_starttime and coupon_endtime > {$nowTime})";
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

    static public function getInfoByCid($cid){
        global $mypdo;

        $cid = $mypdo->sql_check_input(array('number', $cid));

        $sql = "select * from ".$mypdo->prefix."user_coupon where coupon_cid = $cid limit 1";
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
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_coupon', $params, $where);
        return $r;
    }

    static public function dels($id){

        global $mypdo;


        $params = array(
            "coupon_status" => array('number', 4)
        );

        $where = array(
            "coupon_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_coupon', $params, $where);
        return $r;
    }
    //使用
    static public function used($id){

        global $mypdo;


        $params = array(
            "coupon_status" => array('number', 2)
        );

        $where = array(
            "coupon_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_coupon', $params, $where);
        return $r;
    }
    //禁用
    static public function forbidden($id){

        global $mypdo;


        $params = array(
            "coupon_status" => array('number', 3)
        );

        $where = array(
            "coupon_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_coupon', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."user_coupon ";
        $where=" where coupon_status in ('1','2','3') ";

        if(isset($params['accountName'])){
            $where .= " and  coupon_uid in (".$params['accountName'].") ";
        }
        if(!empty($params['addtime'])){
            $starttime = strtotime($params['addtime']);
            $endtime = strtotime($params['addtime'])+ 86399;
           // $where .=" and order_addtime > $starttime and  order_addtime < $endtime";
            $where .=" and coupon_addtime >= $starttime and coupon_addtime <= $endtime ";
        }
        if(isset($params['coupon_name']) && !empty($params['coupon_name'])){
            $where .=" and coupon_cid = ".$params['coupon_name'] ;
        }
//        echo $sql;
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

        $sql = "select * from ".$mypdo->prefix."user_coupon where coupon_cid = $id and FIND_IN_SET ({$type},coupon_type)";
        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        echo $sql;
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getInfoByCidAndUid($uid, $cid){
        global $mypdo;

        $cid = $mypdo->sql_check_input(array('number', $cid));
        $uid = $mypdo->sql_check_input(array('number', $uid));

        $sql = "select * from ".$mypdo->prefix."user_coupon where coupon_cid = $cid and coupon_uid = $uid limit 1";
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

    /***
     * @param int $type
     *
     * $params['uid'] 查找当前用户所有优惠券
     * 其他 查找当前时间用户可使用优惠券
     *
     */
    static public function getMyCouponInfo($params){


        global  $mypdo;

        $nowTime = time();
        $sql = "SELECT u.coupon_id as myid, u.coupon_cid as id, c.coupon_name as name ,c.coupon_money as money,u.coupon_starttime as starttime ,u.coupon_endtime as endtime FROM 
                boiler_user_coupon as u LEFT JOIN boiler_coupon as c on u.coupon_cid = c.coupon_id   
                WHERE  u.coupon_status =1 ";

        if(isset($params['uid'])){
            $sql .= " and  u.coupon_uid = ".$params['uid'];
        }
        if(isset($params['type'])){
            $sql .= " and  FIND_IN_SET (".$params['type'].",c.coupon_type) and  ( {$nowTime} >= u.coupon_starttime and u.coupon_endtime > {$nowTime})";
        }
//        echo $sql;

        $rs  = $mypdo->sqlQuery($sql);

        return $rs;


    }

}
?>