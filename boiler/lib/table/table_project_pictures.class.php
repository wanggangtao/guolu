<?php

/**
 * table_project_pictures.class.php 数据库表:项目图片库表
 *
 * @version       v0.01
 * @createtime    2018/6/24
 * @updatetime    2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_pictures extends Table {


    private static  $pre = "pictures_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['pictures_id'];
        $r['projectid']     = $data['pictures_projectid'];
        $r['type']          = $data['pictures_type'];
        $r['url']           = $data['pictures_url'];
        $r['time']          = $data['pictures_time'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "projectid"=>"number",
            "type"=>"number",
            "url"=>"string",
            "time"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_pictures', $params);
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
            "pictures_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_pictures', $params, $where);
        return $r;
    }

    /**
     * Table_project_pictures::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_pictures where pictures_id = $id limit 1";

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
     * Table_project_pictures::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @param number $type             图片类型
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $projectid, $type){
        global $mypdo;

        $where = " where 1=1 ";
        if($projectid){
            $projectid = $mypdo->sql_check_input(array('number', $projectid));
            $where .= " and pictures_projectid = $projectid ";
        }
        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and pictures_type = $type ";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project_pictures".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project_pictures".$where;
            $sql .=" order by pictures_id desc";

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
     * Table_project_pictures::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "pictures_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_pictures', $where);
    }
}
?>