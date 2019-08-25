<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 16:30
 */
class Table_service_fee extends Table{
    private static  $pre = "fee_";
    public function __construct(){

    }
    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['fee_id'];
        $r['number']                = $data['fee_number'];
        $r['status']                = $data['fee_status'];

        return $r;
    }
    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "number"=>"number",
            "status"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    public static function add($attr){

        global $mypdo;
        $param = array();

        foreach ($attr as $key=>$value){
            $type = self::getTypeByAttr($key);
            $param[self::$pre.$key] =  array($type,$value);
        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'service_fee', $param);
    }

    public static function getList(){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."service_fee";
        $where=" where fee_status =1 ";
        $sql.=$where;
        $sql.=" order by fee_id asc";

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

    public static function getInfoById($id){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."service_fee where fee_id = $id limit 1";

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

    public function getInfoByAccount($name){
        global $mypdo;
        $name = $mypdo->sql_check_input(array('number', $name));

        $sql = "select * from ".$mypdo->prefix."service_fee where fee_number = $name limit 1";

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

    public static function delete($id){
        global $mypdo;
        $params = array(
            "fee_status" => array('number', -1)
        );

        $where = array(
            "fee_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_fee', $params, $where);
        return $r;
    }

    public static function update($id,$fee_number){
        global $mypdo;
        $params = array(
            "fee_number" => array('number', $fee_number)
        );

        $where = array(
            "fee_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_fee', $params, $where);
        return $r;
    }
}