<?php

/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:26
 */
class Table_repair_person
{

    public function __construct() {

    }

    private static  $pre = "repair_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['repair_id'];
        $r['name']       = $data['repair_name'];
        $r['phone']       = $data['repair_phone'];

        $r['addtime']    = $data['repair_addtime'];
        $r['status']   = $data['repair_status'];
        $r['deltime']   = $data['repair_deltime'];
        $r['person_openid']   = $data['repair_person_openid'];


        return $r;
    }

    /***
     * @param $reject
     * @param $id
     * @return mixed
     * 添加取消的原因
     */

    /***
     * @param $id
     * @return array
     * 查询维修人员的信息
     */
    static public function getrepair_person($person ){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $person));
        $sql = "select * from ".$mypdo->prefix."repair_person where repair_status != -1 and repair_id = {$person}  ";
        $sql .=" order by repair_id desc";
//        echo $sql;
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "phone"=>"string",
            "addtime"=>"number",
            "status"=>"number",
            "deltime"=>"number",
            "person_openid"=>"string"


        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }
    static public function getInfoByOpenid($openId){
        global $mypdo;

        $openId = $mypdo->sql_check_input(array('string', $openId));

        $sql = "select * from ".$mypdo->prefix."repair_person where repair_person_openid = $openId and repair_status = 1 limit 1";

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


    static public function login_out($openId){

        global $mypdo;

        $openId = $mypdo->sql_check_input(array('string', $openId));


        $sql = "UPDATE boiler_repair_person SET repair_person_openid = '' WHERE repair_person_openid = {$openId}";
//echo $sql;
        $num = $mypdo->pdo->exec($sql);

        return $num;
    }
        /***
     * @param $phone
     * @return array|mixed
     * 王刚涛
     * 根据电话号码找维修人的相关信息
     */

    static public function getInfoByPhone($phone){
        global $mypdo;

        $phone = $mypdo->sql_check_input(array('number', $phone));

        $sql = "select * from ".$mypdo->prefix."repair_person where repair_phone = $phone limit 1";

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

    /***
     * @param $id
     * @param $attrs
     * @return mixed
     * 修改
     * 王刚涛
     */
    static public function update($id,$attrs){

        global $mypdo;
//        var_dump($attrs);
        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);
        }
//        var_dump($params);
        //where条件
        $where = array(
            "repair_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_person', $params, $where);
        return $r;
    }

    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'repair_person', $params);
        return $r;
    }




    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."repair_person where repair_id = $id limit 1";

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


    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."repair_person ";
        $where=" where repair_status =1 order by  repair_addtime desc";

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


    static public function dels($id){

        global $mypdo;


        $params = array(
            "repair_status" => array('number', -1)
        );

        $where = array(
            "repair_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_person', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."repair_person ";
        $where=" where repair_status =1 ";



        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }

    static public function getIdByLikeName($name){
        global $mypdo;

//        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select repair_id from ".$mypdo->prefix."repair_person where repair_name   LIKE '%".$name."%' ";

        $rs = $mypdo->sqlQuery($sql);

        return $rs;

    }

        static public function getPhoneByLikeName($name){
        global $mypdo;

//        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select repair_phone from ".$mypdo->prefix."repair_person where repair_name   LIKE '%".$name."%' ";

        $rs = $mypdo->sqlQuery($sql);

        return $rs;

    }


}