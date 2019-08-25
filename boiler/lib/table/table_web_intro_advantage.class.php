<?php
/**
 * Table_web_intro_advantage.class.php 数据库表:公司介绍----优势
 * User: Lin
 * Date: 2018/12/27 0027
 * Time: 下午 2:11
 */

class Table_web_intro_advantage
{
    private static  $pre = "advantage_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['advantage_id'];
        $r['number']      = $data['advantage_number'];
        $r['weight']       = $data['advantage_weight'];
        $r['title']       = $data['advantage_title'];
        $r['purl']       = $data['advantage_purl'];
        $r['content']       = $data['advantage_content'];
        $r['type']       = $data['advantage_type'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "number"=>"number",
            "weight"=>"number",
            "title"=>"string",
            "purl"=>"string",
            "content"=>"string",
            "type"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_company_introduction_advantage', $params);
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
            "advantage_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_company_introduction_advantage', $params, $where);
        return $r;
    }

    /**
     * Table_web_introduction::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."web_company_introduction_advantage where advantage_id = $id limit 1";

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
     * Table_web_introduction::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return
     */
    static public function getPageList($page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_company_introduction_advantage".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."web_company_introduction_advantage".$where;
            $sql .=" order by advantage_id desc";

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
     * Table_web_introduction::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "company_introduction_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'web_company_introduction', $where);
    }

    static public function getListByType($type){
        global $mypdo;

        $type = $mypdo->sql_check_input(array('number', $type));

        $sql = "select * from ".$mypdo->prefix."web_company_introduction_advantage where advantage_type = $type";
        $sql .=" order by advantage_weight desc";
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