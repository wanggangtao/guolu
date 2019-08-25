<?php

/**
 * table_project_visitlog.class.php 数据库表:项目拜访记录表
 *
 * @version       v0.01
 * @createtime    2018/6/24
 * @updatetime    2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_visitlog extends Table {


    private static  $pre = "visitlog_";

    static protected function struct($data){
        $r = array();
        $r['id']          = $data['visitlog_id'];
        $r['projectid']   = $data['visitlog_projectid'];
        $r['visittime']   = $data['visitlog_visittime'];
        $r['target']      = $data['visitlog_target'];
        $r['tel']         = $data['visitlog_tel'];
        $r['position']    = $data['visitlog_position'];
        $r['visitway']    = $data['visitlog_visitway'];
        $r['content']     = $data['visitlog_content'];
        $r['effect']      = $data['visitlog_effect'];
        $r['plan']        = $data['visitlog_plan'];
        $r['comment']     = $data['visitlog_comment'];
        $r['comuser']     = $data['visitlog_comuser'];
        $r['updatetime']  = $data['visitlog_updatetime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "projectid"=>"number",
            "visittime"=>"number",
            "target"=>"string",
            "tel"=>"string",
            "position"=>"string",
            "visitway"=>"number",
            "content"=>"string",
            "effect"=>"string",
            "plan"=>"string",
            "comment"=>"string",
            "comuser"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_visitlog', $params);
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
            "visitlog_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_visitlog', $params, $where);
        return $r;
    }

    /**
     * Table_project_visitlog::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_visitlog where visitlog_id = $id limit 1";

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
     * Table_project_visitlog::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoNew($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_visitlog where visitlog_projectid = $prid order by visitlog_visittime desc limit 1";

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
     * Table_project_visitlog::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @param string $target           拜访对象
     * @param number $way              拜访方式
     * @param number $stday            开始日期
     * @param number $endday           结束日期
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $projectid, $target, $way, $stday, $endday,$content){
        global $mypdo;

        $where = " where 1=1 ";
        if($target){
            $target = $mypdo->sql_check_input(array('string', "%".$target."%"));
            $where .= " and visitlog_target like $target ";
        }
        if($projectid){
            $projectid = $mypdo->sql_check_input(array('number', $projectid));
            $where .= " and visitlog_projectid = $projectid ";
        }
        if($way){
            $way = $mypdo->sql_check_input(array('number', $way));
            $where .= " and visitlog_visitway = $way ";
        }
        if($stday){
            $stday = $mypdo->sql_check_input(array('number', $stday));
            $where .= " and visitlog_visittime >= $stday ";
        }
        if($endday){
            $endday = $mypdo->sql_check_input(array('number', $endday));
            $where .= " and visitlog_visittime <= $endday ";
        }

        if(!empty($content))
        {
            $where .= " and (visitlog_target like'%{$content}%' or visitlog_tel  like'%{$content}%')";

        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project_visitlog".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project_visitlog".$where;
            $sql .=" order by visitlog_id desc";

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
     * Table_project_visitlog::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "visitlog_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_visitlog', $where);
    }
}
?>