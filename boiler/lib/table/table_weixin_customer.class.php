<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/25
 * Time: 12:11
 */

class Table_weixin_customer extends Table
{
    private static  $pre = "customer_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['customer_id'];
        $r['nickname']                  = $data['customer_nickname'];
        $r['openid']                  = $data['customer_openid'];
        $r['status']            = $data['customer_status'];
        $r['remark']               = $data['customer_remark'];
        $r['addtime']              = $data['customer_addtime'];
        $r['update_time']                = $data['customer_update_time'];
        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "nickname"=>"string",
            "openid"=>"string",
            "status"=>"number",
            "remark"=>"string",
            "addtime"=>"number",
            "update_time"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'weixin_customer', $params);
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
            "customer_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'weixin_customer', $params, $where);
        return $r;
    }


    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."weixin_customer where customer_id = $id limit 1";
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

    static public function getInfoByOpenid($openid){
        global $mypdo;

        $openid = $mypdo->sql_check_input(array('string', $openid));
        $sql = "select * from ".$mypdo->prefix."weixin_customer where customer_openid = $openid and customer_status = 1 limit 1";
       echo $sql;
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

    static public function del($id){

        global $mypdo;

        $where = array(
            "customer_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'weixin_customer', $where);
    }


    static public function dels($id){

        global $mypdo;
        $params = array(
            "customer_status" => array("number",-1)
        );

        $where = array(
            "customer_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'weixin_customer', $params, $where);
        return $r;
    }

    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."weixin_customer ";
        $where=" where customer_status =1 order by  customer_addtime desc";

        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

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



    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."weixin_customer ";
        $where=" where customer_status =1 ";



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