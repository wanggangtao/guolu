<?php

/**
 * table_dirt_separator_attr.class.php 数据库表:除污器表
 *
 * @version       v0.01
 * @createtime    2018/5/29
 * @updatetime    2018/5/29
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_dirt_separator_attr extends Table {


    private static  $pre = "separator_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['separator_id'];
        $r['version']               = $data['separator_version'];
        $r['diameter']              = floatval($data['separator_diameter']);
        $r['proid']                 = $data['separator_proid'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "diameter"=>"number",
            "proid"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'dirt_separator_attr', $params);
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
            "separator_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'dirt_separator_attr', $params, $where);
        return $r;
    }

    /**
     * Table_dirt_separator_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  除污器ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."dirt_separator_attr where separator_id = $id limit 1";

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
     * Table_dirt_separator_attr::getInfoByDN() 根据直径获取详情
     *
     * @param Integer $dn  除污器直径
     *
     * @return
     */
    static public function getInfoByDN($dn){
        global $mypdo;

        $dn = $mypdo->sql_check_input(array('number', $dn));

        $sql = "select * from ".$mypdo->prefix."dirt_separator_attr where separator_diameter >= $dn order by separator_diameter asc limit 1";

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
     * Table_dirt_separator_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return
     */
    static public function getList($page, $pageSize, $count){
        global $mypdo;

        $where = " from ".$mypdo->prefix."dirt_separator_attr left join boiler_products on products_id = separator_proid and products_modelid = 9 where 1 = 1";

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, separator_id desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;

            $rs = $mypdo->sqlQuery($sql);
            return $rs;
        }
    }
    
    /**
     * Table_dirt_separator_attr::del() 根据ID删除数据
     *
     * @param Integer $id  除污器ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "separator_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'dirt_separator_attr', $where);
    }

}
?>