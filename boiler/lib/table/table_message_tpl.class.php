<?php

/**
 * table_message_tpl.class.php 数据库表:站内信模板表
 *
 * @version       v0.01
 * @createtime    2018/6/27
 * @updatetime    2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_message_tpl extends Table {


    private static  $pre = "tpl_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['tpl_id'];
        $r['type']         = $data['tpl_type'];
        $r['content']      = $data['tpl_content'];
        $r['lasttime']     = $data['tpl_lasttime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "type"=>"number",
            "content"=>"string",
            "lasttime"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'message_tpl', $params);
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
            "tpl_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'message_tpl', $params, $where);
        return $r;
    }

    /**
     * Table_message_tpl::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."message_tpl where tpl_id = $id limit 1";

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
     * Table_message_tpl::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoByType($type){
        global $mypdo;

        $type = $mypdo->sql_check_input(array('number', $type));

        $sql = "select * from ".$mypdo->prefix."message_tpl where tpl_type = $type limit 1";

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
     * Table_message_tpl::getAllList() 列出所有信息
     * @return
     */
    static public function getAllList(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."message_tpl";
        $sql .=" order by tpl_id asc";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }

        }
        return $r;
    }
    
    /**
     * Table_message_tpl::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "tpl_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'message_tpl', $where);
    }
}
?>