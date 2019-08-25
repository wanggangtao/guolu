<?php

/**
 * table_picture.class.php 数据库表:图片表
 *
 * @version       v0.01
 * @createtime    2018/6/24
 * @updatetime    2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_picture extends Table {


    private static  $pre = "picture_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['picture_id'];
        $r['url']        = $data['picture_url'];
        $r['status']     = $data['picture_status'];
        $r['http']       = $data['picture_http'];
        $r['order']      = $data['picture_order'];
        $r['type']       = $data['picture_type'];
        $r['count']       = $data['picture_count'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "url"=>"string",
            "status"=>"number",
            "http"=>"string",
            "type"=>"number",
            "order"=>"number",
            "count"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'picture', $params);
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
            "picture_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'picture', $params, $where);
        return $r;
    }

    /**
     * Table_picture::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."picture where picture_id = $id limit 1";

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
     * Table_picture::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @param number $status           状态
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $type, $status){
        global $mypdo;

        $where = " where 1 = 1 ";

        if($type>-1){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and picture_type = $type ";
        }
        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and picture_status = $status ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."picture".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."picture".$where;
            $sql .=" order by picture_order desc";

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
            }

            return $r;
        }
    }

    /**
     * Table_picture::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "picture_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'picture', $where);
    }
}
?>