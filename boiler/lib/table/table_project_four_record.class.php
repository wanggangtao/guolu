<?php

/**
 * table_project_four_record.class.php 数据库表:项目第四阶段修改记录表
 *
 * @version       v0.01
 * @createtime    2018/6/24
 * @updatetime    2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_four_record extends Table {


    private static  $pre = "record_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['record_id'];
        $r['user']          = $data['record_user'];
        $r['user_name']     = $data['record_user_name'];
        $r['addtime']       = $data['record_addtime'];
        $r['before_id']     = $data['record_before_id'];
        $r['after_id']      = $data['record_after_id'];
        $r['project_id']    = $data['record_project_id'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "user"=>"number",
            "user_name"=>"string",
            "addtime"=>"number",
            "before_id"=>"number",
            "after_id"=>"number",
            "project_id"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_four_record', $params);
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
            "record_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_four_record', $params, $where);
        return $r;
    }

    /**
     * Table_project_four_record::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_four_record where record_id = $id limit 1";

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
     * Table_project_four_record::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $projectid){
        global $mypdo;

        $where = " where 1=1 ";
        if($projectid){
            $projectid = $mypdo->sql_check_input(array('number', $projectid));
            $where .= " and record_project_id = $projectid ";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project_four_record".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project_four_record".$where;
            $sql .=" order by record_id desc";

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
     * Table_project_four_record::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "record_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_four_record', $where);
    }

    static public function getLastIdByProject($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_four_record where record_project_id = $id order by record_id desc limit 1";

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