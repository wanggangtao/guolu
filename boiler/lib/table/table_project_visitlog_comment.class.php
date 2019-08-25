<?php

/**
 * table_project_visitlog_comment.class.php 数据库表:项目拜访记录评论表
 *
 * @version       v0.01
 * @createtime    2018/8/16
 * @updatetime    2018/8/16
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_visitlog_comment extends Table {


    private static  $pre = "comment_";

    static protected function struct($data){
        $r = array();
        $r['id']          = $data['comment_id'];
        $r['visitlog_id'] = $data['comment_visitlog_id'];
        $r['content']     = $data['comment_content'];
        $r['comuser']     = $data['comment_comuser'];
        $r['time']        = $data['comment_time'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "visitlog_id"=>"number",
            "content"=>"string",
            "comuser"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project_visitlog_comment', $params);
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
            "comment_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_visitlog_comment', $params, $where);
        return $r;
    }

    /**
     * Table_project_visitlog_comment::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_visitlog_comment where comment_id = $id limit 1";

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
     * Table_project_visitlog_comment::getInfoByVisitlogid() 根据拜访记录ID获取详情
     *
     * @param Integer $visitlog_id  拜访记录ID
     *
     * @return
     */
    static public function getInfoByVisitlogid($visitlog_id){
        global $mypdo;

        $visitlog_id = $mypdo->sql_check_input(array('number', $visitlog_id));

        $sql = "select * from ".$mypdo->prefix."project_visitlog_comment where comment_visitlog_id = $visitlog_id order by comment_time desc";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }

        return $r;
    }

    /**
     * Table_project_visitlog_comment::del() 根据ID删除数据
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

        return $mypdo->sqldelete($mypdo->prefix.'project_visitlog_comment', $where);
    }
}
?>