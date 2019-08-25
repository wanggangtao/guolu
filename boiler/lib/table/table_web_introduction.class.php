<?php
/**
 * Created by kjb.
 * Date: 2018/12/6
 * Time: 20:59
 */

class Table_web_introduction
{
    private static  $pre = "company_introduction_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['company_introduction_id'];
        $r['kind']      = $data['company_introduction_kind'];
        $r['content']       = $data['company_introduction_content'];
        $r['tel']       = $data['company_introduction_tel'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "kind"=>"string",
            "content"=>"string",
            "tel"=>"string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_company_introduction', $params);
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
            "company_introduction_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_company_introduction', $params, $where);
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

        $sql = "select * from ".$mypdo->prefix."web_company_introduction where company_introduction_id = $id limit 1";

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
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_company_introduction".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."web_company_introduction".$where;
            $sql .=" order by company_introduction_id desc";

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

    static public function getList(){
        global $mypdo;

        $where = " where 1 = 1 ";

        $sql = "select * from ".$mypdo->prefix."web_company_introduction".$where;
        $sql .=" order by company_introduction_id desc";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);}
        }

        return $r;
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



}