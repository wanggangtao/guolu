<?php

/**
 * table_case_pricelog.class.php 数据库表:价格记录表
 *
 * @version       v0.01
 * @createtime    2018/10/20
 * @updatetime    2018/10/20
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_case_pricelog extends Table {


    private static  $pre = "pricelog_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['pricelog_id'];
        $r['type']          = $data['pricelog_type'];
        $r['objectid']      = $data['pricelog_objectid'];
        $r['addtype']       = $data['pricelog_addtype'];
        $r['price']         = floatval($data['pricelog_price']);
        $r['addtime']       = $data['pricelog_addtime'];


        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "type"=>"number",
            "objectid"=>"number",
            "addtype"=>"number",
            "price"=>"number",
            "addtime"=>"number",

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'case_pricelog', $params);
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
            "pricelog_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'case_pricelog', $params, $where);
        return $r;
    }

    /**
     * Table_case_pricelog::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."case_pricelog where pricelog_id = $id limit 1";

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
     * Table_case_pricelog::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @param number $objectid         对象id
     * @param number $addtype          添加方式
     * @param number $sttime           查询开始时间
     * @param number $endtime          查询结束时间
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $type, $objectid, $addtype, $sttime, $endtime){
        global $mypdo;

        $where = " where 1=1 ";

        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and pricelog_type = $type ";
        }

        if($objectid){
            $objectid = $mypdo->sql_check_input(array('number', $objectid));
            $where .= " and pricelog_objectid = $objectid ";
        }
        if($addtype){
            $addtype = $mypdo->sql_check_input(array('number', $addtype));
            $where .= " and pricelog_addtype = $addtype ";
        }
        if($sttime){
            $sttime = $mypdo->sql_check_input(array('number', $sttime));
            $where .= " and pricelog_addtime >= $sttime ";
        }
        if($endtime){
            $endtime = $mypdo->sql_check_input(array('number', $endtime));
            $where .= " and pricelog_addtime <= $endtime ";
        }
        if($count == 0){
            $sql = "select count(1) as ct, MAX(pricelog_price) as maxprice, MIN(pricelog_price) as minprice, FORMAT(AVG(pricelog_price),6) as avgprice from ".$mypdo->prefix."case_pricelog".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0];
        }else{
            $sql = "select * from ".$mypdo->prefix."case_pricelog".$where;
            $sql .=" order by pricelog_id desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
//            echo $sql;
//            exit;
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
    }
    
    /**
     * Table_case_pricelog::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "pricelog_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'case_pricelog', $where);
    }

    /**
     * Table_case_pricelog::delByObject() 根据对象删除数据
     *
     * @param number $type             类型
     * @param number $objectid         对象id
     *
     * @return
     */
    static public function delByObject($type, $objectid){

        global $mypdo;

        $where = array(
            "pricelog_type" => array('number', $type),
            "pricelog_objectid" => array('number', $objectid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'case_pricelog', $where);
    }

}
?>
