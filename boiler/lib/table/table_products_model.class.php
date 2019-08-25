<?php

/**
 * table_products_model.class.php 数据库表:产品表
 *
 * @version       v0.01
 * @createtime    2018/5/30
 * @updatetime    2018/5/30
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_products_model extends Table {


    private static  $pre = "model_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['model_id'];
        $r['name']          = $data['model_name'];
        $r['category']      = $data['model_category'];
        $r['attrname']      = $data['model_attrname'];
        $r['addtime']       = $data['model_addtime'];

        return $r;
    }

    /**
 * Table_products_model::getInfoById() 根据ID获取详情
 *
 * @param Integer $id  类别ID
 *
 * @return
 */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."products_model where model_id = $id limit 1";

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
     * Table_products_model::getInfoByCategory() 根据类别ID获取详情
     *
     * @param Integer $categoryid  类别ID
     *
     * @return
     */
    static public function getInfoByCategory($categoryid){
        global $mypdo;

        $categoryid = $mypdo->sql_check_input(array('number', $categoryid));

        $sql = "select * from ".$mypdo->prefix."products_model where model_category = $categoryid limit 1";

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
?>