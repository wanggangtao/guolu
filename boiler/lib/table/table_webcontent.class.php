<?php

/**
 * table_webcontent.class.php 数据库表:图片表
 *
 * @version       v0.01
 * @createtime    2018/9/13
 * @updatetime    2018/9/13
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_webcontent extends Table {


    private static  $pre = "content_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['content_id'];
        $r['title']      = $data['content_title'];
        $r['title_supplement']      = $data['content_title_supplement'];

        $r['subtitle']   = $data['content_subtitle'];
        $r['content']    = $data['content_content'];
        $r['time']       = $data['content_time'];
        $r['order']      = $data['content_order'];
        $r['type']       = $data['content_type'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "title"=>"string",
            "title_supplement"=>"string",

            "subtitle"=>"string",
            "content"=>"string",
            "time"=>"number",
            "type"=>"number",
            "order"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'webcontent', $params);
    //    echo $r;
     //   exit();
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
            "content_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'webcontent', $params, $where);
        return $r;
    }

    /**
     * Table_webcontent::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."webcontent where content_id = $id limit 1";

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
     * Table_webcontent::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @param number $status           状态
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $type){
        global $mypdo;

        $where = " where 1 = 1 ";

        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and content_type = $type ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."webcontent".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."webcontent".$where;
            $sql .=" order by content_order desc";

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
     * Table_webcontent::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "content_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'webcontent', $where);
    }
}
?>