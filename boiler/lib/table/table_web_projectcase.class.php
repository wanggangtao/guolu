<?php

/**
 * table_web_projectcase.class.php 数据库表:项目案例表
 *
 * @version       v0.01
 * @createtime    2018/11/21
 * @updatetime    2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_web_projectcase extends Table {


    private static  $pre = "projectcase_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['projectcase_id'];
        $r['title']      = $data['projectcase_title'];
        $r['type']       = $data['projectcase_type'];
        $r['picurl']     = $data['projectcase_picurl'];
        $r['content']    = $data['projectcase_content'];
        $r['http']       = $data['projectcase_http'];
        $r['order']      = $data['projectcase_order'];
        $r['count']      = $data['projectcase_count'];
        $r['addtime']    = $data['projectcase_addtime'];
        $r['is_good']    = $data['projectcase_is_good'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "title"=>"string",
            "type"=>"number",
            "picurl"=>"string",
            "http"=>"string",
            "content"=>"string",
            "count"=>"number",
            "order"=>"number",
            "addtime"=>"number",
            "is_good"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_projectcase', $params);
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
            "projectcase_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_projectcase', $params, $where);
        return $r;
    }

    /**
     * Table_web_projectcase::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."web_projectcase where projectcase_id = $id limit 1";

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
     * Table_web_projectcase::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $type){
        global $mypdo;

        $where = " where 1 = 1 ";

        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and projectcase_type = $type ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_projectcase".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."web_projectcase".$where;
            $sql .=" order by projectcase_order desc, projectcase_addtime asc";

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
     * Table_web_projectcase::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "projectcase_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'web_projectcase', $where);
    }

    /**
     *

     获取所有数据

     */
    static public function getList($params){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_projectcase";

        $where=" where 1=1";
        if(!empty($params['id'])){
            $where.=" and projectcase_order={$params['id']}";
        }
        if(!empty($params['type'])){
        $where.=" and projectcase_type={$params['type']}";
        }
        $sql .= $where;

        $sql .=" order by projectcase_order desc,projectcase_addtime desc";


        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

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
    static public function getOtherList(){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_projectcase";

        $where=" where projectcase_is_good = -1";

        $sql .= $where;

        $sql .=" order by projectcase_order desc,projectcase_addtime desc";


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
    /**
     *
    获取页面总数据条数
     *
     */
    static public function getAllCount($params){
        global $mypdo;

        $where = " where 1=1";
        $sql="select count(1) as act from ".$mypdo->prefix."web_projectcase";
        if(!empty($params['id'])){
            $where.=" and projectcase_order={$params['id']}";
        }
        if(!empty($params['type'])){
            $where.=" and projectcase_type={$params['type']}";
        }




        $sql .= $where;




        $rs=$mypdo->sqlQuery($sql);
        $r=array();
        if($rs){

            return $rs[0]["act"];
        }else{
            return $r;
        }

    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    static public function updateCountInfo($id){

        global $mypdo;


        $param = array(
            'projectcase_count'    => array('expression','projectcase_count+1')
        );
        $where = array('projectcase_id'=>array('number',$id));

        return $mypdo->sqlupdate($mypdo->prefix.'web_projectcase', $param, $where);
    }

    static public function getListQianDuan($params){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_projectcase";

        $where=" where projectcase_is_good=1";
        if(!empty($params['id'])){
            $where.=" and projectcase_order={$params['id']}";
        }

        $sql .= $where;

        $sql .=" order by projectcase_order desc,projectcase_addtime desc";


        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

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
}
?>