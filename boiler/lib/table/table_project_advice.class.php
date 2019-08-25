<?php

/**
 * table_project_advice.class.php 数据库表:项目建议表
 *
 * @version       v0.01
 * @createtime    2018/8/23
 * @updatetime    2018/8/23
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_advice extends Table {


    private static  $pre = "advice_";

    static protected function struct($data){
        $r = array();
        $r['id']          = $data['advice_id'];
        $r['projectid']   = $data['advice_projectid'];
        $r['content']     = $data['advice_content'];
        $r['user']        = $data['advice_user'];
        $r['addtime']     = $data['advice_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "projectid"=>"number",
            "content"=>"string",
            "user"=>"number",
            "addtime"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_advice', $params);
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
            "advice_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_advice', $params, $where);
        return $r;
    }

    /**
     * Table_project_advice::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_advice where advice_id = $id limit 1";

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
     * Table_project_advice::getPageList() 根据条件列出所有信息
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
    static public function getPageList($page, $pageSize, $count, $projectid, $content){
        global $mypdo;

        $where = " where 1=1 ";
        if($projectid){
            $projectid = $mypdo->sql_check_input(array('number', $projectid));
            $where .= " and advice_projectid = $projectid ";
        }

        if(!empty($content))
        {
            $where .= " and (advice_target like'%{$content}%' or advice_tel  like'%{$content}%')";

        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project_advice".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project_advice".$where;
            $sql .=" order by advice_id desc";

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
     * Table_project_advice::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "advice_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_advice', $where);
    }
}
?>