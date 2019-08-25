<?php

/**
 * table_web_recruit.class.php 数据库表:人员招聘表
 *
 * @version       v0.01
 * @createtime    2018/11/21
 * @updatetime    2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_web_recruit extends Table {


    private static  $pre = "recruit_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['recruit_id'];
        $r['station']      = $data['recruit_station'];
        $r['education']     = $data['recruit_education'];
        $r['number']    = $data['recruit_number'];
        $r['salary']    = $data['recruit_salary'];
        $r['describe']     = $data['recruit_describe'];
        $r['need']       = $data['recruit_need'];
        $r['detail']       = $data['recruit_detail'];
        $r['experience']       = $data['recruit_experience'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "station"=>"string",
            "education"=>"number",
            "number"=>"number",
            "describe"=>"string",
            "need"=>"string",
            "salary"=>"number",
            "experience"=>"string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_recruit', $params);
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
            "recruit_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_recruit', $params, $where);
        return $r;
    }

    /**
     * Table_web_recruit::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."web_recruit where recruit_id = $id limit 1";

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
     * Table_web_recruit::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return
     */
    static public function getPageList($page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_recruit".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."web_recruit".$where;
            $sql .=" order by recruit_id desc";

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
     * Table_web_recruit::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "recruit_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'web_recruit', $where);
    }

    /**
     *

    获取所有数据

     */
    static public function getList($params){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_recruit";

        $where=" order by recruit_id desc";

        $sql .= $where;




        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;
//print_r($sql);
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

    static public function getCount($params){

        global $mypdo;

        $sql="select count(1) as act from ".$mypdo->prefix."web_recruit";

        $where="";



        $sql .= $where;


        $rs=$mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }
    }
}
?>