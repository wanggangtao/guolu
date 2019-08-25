<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/25
 * Time: 11:25
 */


class Table_weixin_coupon_dx_rule extends Table {

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
        $r['addtime']       = $data['rule_addtime'];
        $r['send_time']         = $data['rule_send_time'];
        $r['is_send']      = $data['rule_is_send'];
        $r['status']          = $data['rule_status'];
        $r['send_type']      = $data['rule_send_type'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "addtime"=>"number",
            "send_time"=>"number",
            "is_send"=>"number",
            "status"=>"number",
            "send_type" => "number"

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getList($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."coupon_dx_rule ";
        $where=" where rule_status != -1";

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

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."coupon_dx_rule ";
        $where=" where rule_status != -1 ";
        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]['act'];
        }else{
            return null;
        }
    }


    static public function getInfoByName($rulename){
        global $mypdo;

        $rulename = $mypdo->sql_check_input(array('string', $rulename));

        $sql = "select * from ".$mypdo->prefix."coupon_dx_rule where rule_name = $rulename and rule_status = 1  limit 1";
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
        return $mypdo->sqlinsert($mypdo->prefix.'coupon_dx_rule', $params);
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."coupon_dx_rule where rule_id = $id limit 1";
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
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon_dx_rule', $params, $where);
        return $r;
    }

    static public function dels($id){

        global $mypdo;


        $params = array(
            "rule_status" => array('number', -1)
        );

        $where = array(
            "rule_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon_dx_rule', $params, $where);
        return $r;
    }
    //终止发放
    static public function termination($id){

        global $mypdo;


        $params = array(
            "rule_status" => array('number', 2)
        );

        $where = array(
            "rule_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'coupon_dx_rule', $params, $where);
        return $r;
    }

    static public function getRuleByAttrs($params){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."coupon_dx_rule ";

        $where=" where rule_status = 1 ";
        if(isset($params['no_send'])){
            $where .=  " and rule_send_type = 2 and rule_is_send = 0 limit 1";
        }
        if(isset($params['now_time'])){
            $where .= " and if(rule_is_send = 0 , rule_send_time <= {$params['now_time']} , rule_is_send = 1)";
        }
        $sql.=$where;

        $rs = $mypdo->sqlQuery($sql);
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


}