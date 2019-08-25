<?php

/**
 * table_web_contactus.class.php 数据库表:表
 *
 * @version       v0.01
 * @createtime    2018/11/21
 * @updatetime    2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_web_contactus extends Table {


    private static  $pre = "contactus_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['contactus_id'];
        $r['company']      = $data['contactus_company'];
        $r['contacter']     = $data['contactus_contacter'];
        $r['phone']    = $data['contactus_phone'];
        $r['telephone']    = $data['contactus_telephone'];
        $r['website']     = $data['contactus_website'];
        $r['email']       = $data['contactus_email'];
        $r['address']       = $data['contactus_address'];
        $r['lat']       = $data['contactus_lat'];
        $r['lng']       = $data['contactus_lng'];
        $r['picurl1']       = $data['contactus_picurl1'];
        $r['picurl2']       = $data['contactus_picurl2'];
        $r['hotline']       = $data['contactus_hotline'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "company"=>"string",
            "contacter"=>"string",
            "phone"=>"string",
            "telephone"=>"string",
            "website"=>"string",
            "email"=>"string",
            "address"=>"string",

            "lat"=>"number",
            "lng"=>"number",

            "hotline"=>"string",
            "picurl1"=>"string",
            "picurl2"=>"string"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_contactus', $params);
        return $r;
    }


    /**
     *

    获取所有数据

     */
    static public function getList($params){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_contactus";

        $where="";

        $sql .= $where;




        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

        $rs=$mypdo->sqlQuery($sql);
        $r=array();
        if($rs){
            foreach ($rs as $key => $val){
                $r[$key]=self::struct($val);
            }

            return $r;
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
            "contactus_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_contactus', $params, $where);
        return $r;
    }


}
?>