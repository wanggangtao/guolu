<?php

/**
 * table_project_inspectlog.class.php 数据库表:项目考察记录表
 *
 * @version       v0.01
 * @createtime    2018/6/24
 * @updatetime    2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_inspectlog extends Table {


    private static  $pre = "inspectlog_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['inspectlog_id'];
        $r['projectid']     = $data['inspectlog_projectid'];
        $r['inspecttime']   = $data['inspectlog_inspecttime'];
        $r['member']        = $data['inspectlog_member'];
        $r['company']       = $data['inspectlog_company'];
        $r['brand']         = $data['inspectlog_brand'];
        $r['address']       = $data['inspectlog_address'];
        $r['situation']     = $data['inspectlog_situation'];
        $r['updatetime']    = $data['inspectlog_updatetime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "projectid"=>"number",
            "inspecttime"=>"number",
            "member"=>"string",
            "company"=>"string",
            "brand"=>"string",
            "address"=>"string",
            "situation"=>"string",
            "updatetime"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_inspectlog', $params);
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
            "inspectlog_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_inspectlog', $params, $where);
        return $r;
    }

    /**
     * Table_project_inspectlog::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_inspectlog where inspectlog_id = $id limit 1";

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
     * Table_project_inspectlog::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @param string $company          考察单位
     * @param number $stday            开始日期
     * @param number $endday           结束日期
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $projectid, $company, $stday, $endday,$content){
        global $mypdo;

        $where = " where 1=1 ";
        if($company){
            $company = $mypdo->sql_check_input(array('string', "%".$company."%"));
            $where .= " and inspectlog_company like $company ";
        }
        if($projectid){
            $projectid = $mypdo->sql_check_input(array('number', $projectid));
            $where .= " and inspectlog_projectid = $projectid ";
        }
        if($stday){
            $stday = $mypdo->sql_check_input(array('number', $stday));
            $where .= " and inspectlog_inspecttime >= $stday ";
        }
        if($endday){
            $endday = $mypdo->sql_check_input(array('number', $endday));
            $where .= " and inspectlog_inspecttime <= $endday ";
        }

        if(!empty($content))
        {
            $where .= " and (inspectlog_company like'%{$content}%')";

        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project_inspectlog".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project_inspectlog".$where;
            $sql .=" order by inspectlog_id desc";

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
     * Table_project_inspectlog::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "inspectlog_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_inspectlog', $where);
    }
}
?>