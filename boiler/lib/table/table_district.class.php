<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2017/7/15
 * Time: 9:47
 */
class Table_district extends Table
{
    const PRE = "district_";
    static protected function struct($data)
    {
        $r=array();
        $r["id"] = $data["district_id"];
        $r["name"] = $data["district_name"];
        $r["upid"] = $data["district_upid"];
        $r["type"] = $data["district_type"];
        $r["displayorder"] = $data["district_displayorder"];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAtt = array(
            "id"=>"number",
            "name"=>"string",
            "upid"=>"number",
            "type"=>"number",
            "displayorder"=>"number",
        );

        return isset($typeAtt[$attr])?$typeAtt[$attr]:$typeAtt;
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."district where district_id = {$id} limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }


    static public function getInfoLikeName($name,$type){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."district where district_name like '%{$name}%' and district_type={$type} limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }

    static function getInfoByType($type)
    {
        global $mypdo;
        $type = $mypdo->sql_check_input(array('number', $type));
        $sql = "select * from ".$mypdo->prefix."district where district_type = {$type}";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r;
        }else{
            return 0;
        }
    }

    static function getInfoByUpid($upid)
    {

        global $mypdo;
        $upid = $mypdo->sql_check_input(array('number', $upid));
        $sql = "select * from ".$mypdo->prefix."district where district_upid = {$upid}";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r;
            print_r($r);exit;

        }else{
            return 0;
        }
    }
    static public function getAddressType($type,$upid){
        global $mypdo;


        $sql="select * from ".$mypdo->prefix."district";
        $where = "  where  district_type= $type";
        if($type !=3){
            $where.=" and district_upid = $upid";
        }
        $sql .= $where;
        $sql .=" order by district_displayorder asc";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();

        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
            return $r;

        } else {
            return $r;
        }
    }



    static public function getAddressNameById($id){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."district where district_id = $id limit 1";


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

    static public function getAddressInfoByName($name,$upid){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."district where 1 = 1";

        if($upid != 0){
            $sql .= " and district_upid = ".$upid ." ";
        }
        if(!empty($name)){
            $sql .= " and district_name like '".$name ."' ";

        }
//        echo $sql;
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
}