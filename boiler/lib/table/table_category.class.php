<?php

/**
 * table_category.class.php 数据库表:产品类别表
 *
 * @version       v0.01
 * @createtime    2018/5/27
 * @updatetime    2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_category extends Table {


    private static  $pre = "category_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['category_id'];
        $r['parentid']      = $data['category_parentid'];
        $r['name']          = $data['category_name'];
        $r['level']         = $data['category_level'];
        $r['photo']         = $data['category_photo'];
        $r['weight']        = $data['category_weight'];
        $r['addtime']       = $data['category_addtime'];
        $r['operator']      = $data['category_operator'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "parentid"=>"number",
            "name"=>"string",
            "level"=>"number",
            "photo"=>"string",
            "weight"=>"number",
            "addtime"=>"number",
            "operator"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'category', $params);
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
            "category_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'category', $params, $where);
        return $r;
    }

    /**
     * Table_category::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."category where category_id = $id limit 1";

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
     * Table_category::getInfoByParentid() 根据父类ID获取详情
     *
     * @param Integer $parentid  父类ID
     *
     * @return
     */
    static public function getInfoByParentid($parentid){
        global $mypdo;

        $parentid = $mypdo->sql_check_input(array('number', $parentid));

        $sql = "select * from ".$mypdo->prefix."category where category_parentid = $parentid ";

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

    /**
     * Table_category::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $parentid         父类ID
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $parentid){
        global $mypdo;

        $where = " where 1=1 ";

        if($parentid > -1){
            $parentid = $mypdo->sql_check_input(array('number', $parentid));
            $where .= " and category_parentid = $parentid ";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."category".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."category".$where;
            $sql .=" order by category_id asc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
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
     * Table_category::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "category_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'category', $where);
    }

    /**
     * Table_category::delByParentid() 根据父类ID删除数据
     *
     * @param Integer $parentid  父类别ID
     *
     * @return
     */
    static public function delByParentid($parentid){

        global $mypdo;

        $where = array(
            "category_parentid" => array('number', $parentid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'category', $where);
    }

}
?>