<?php

/**
 * table_project_bid_company.class.php 数据库表:项目第四阶段招标公司表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_bid_company extends Table {


    private static  $pre = "company_";

    static protected function struct($data){
        $r = array();
        $r['id']             = $data['company_id'];
        $r['pf_id']          = $data['company_pf_id'];
        $r['name']           = $data['company_name'];
        $r['price']          = $data['company_price'];
        $r['brand']          = $data['company_brand'];
        $r['isimportant']          = $data['company_isimportant'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "pf_id"=>"number",
            "name"=>"string",
            "brand"=>"string",
            "price"=>"number",
            "isimportant"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_bid_company', $params);
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
            "company_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_bid_company', $params, $where);
        return $r;
    }

    /**
     * Table_project_bid_company::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_bid_company where company_id = $id limit 1";

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

    /**
     * Table_project_bid_company::getInfoByPfId() 根据项目ID获取详情
     *
     * @param Integer $pfid  第四阶段关联项目ID
     *
     * @return
     */
    static public function getInfoByPfId($pfid){
        global $mypdo;

        $pfid = $mypdo->sql_check_input(array('number', $pfid));

        $sql = "select * from ".$mypdo->prefix."project_bid_company where company_pf_id = $pfid order by company_id desc ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else
            return 0;

    }

    /**
     * Table_project_bid_company::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "company_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_bid_company', $where);
    }

    /**
     * Table_project_bid_company::delByPfId() 根据项目ID删除数据
     *
     * @param Integer delByPtId  第四阶段关联项目ID
     *
     * @return
     */
    static public function delByPfId($pfid){

        global $mypdo;

        $where = array(
            "company_pf_id" => array('number', $pfid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_bid_company', $where);
    }
}
?>