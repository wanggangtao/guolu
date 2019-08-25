<?php

/**
 * table_code.class.php 数据库表:编号管理表
 *
 * @version       v0.01
 * @createtime    2018/7/7
 * @updatetime    2018/7/7
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_code extends Table {


    private static  $pre = "code_";

    static protected function struct($data){
        $r = array();
        $r['id']          = $data['code_id'];
        $r['type']        = $data['code_type'];
        $r['day']         = $data['code_day'];
        $r['num']         = $data['code_num'];
        $r['lasttime']    = $data['code_lasttime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "day"=>"string",
            "type"=>"number",
            "num"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'code', $params);
        return $r;
    }

    /**
     * Table_code::updateCode() 修改信息
     * @param number $id    编号ID
     * @param array $arr
     * @return
     */
    static public function updateCode($id, $type, $day, $codenum){
        global $mypdo;

        $data = array();

        $data[self::$pre.'num'] = array('number', $codenum + 1);
        $data[self::$pre.'lasttime'] = array('number', time());
        $where = array(
            "code_id" => array('number', $id),
            "code_num"  => array('number', $codenum),
            "code_type"  => array('number', $type),
            "code_day"  => array('number', $day)
        );

        return $mypdo->sqlupdate($mypdo->prefix.'code', $data, $where);
    }

    /**
     * Table_code::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."code where code_id = $id limit 1";

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
     * Table_code::getInfoByTD() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoByTD($type, $day){
        global $mypdo;

        $type = $mypdo->sql_check_input(array('number', $type));
        $day = $mypdo->sql_check_input(array('number', $day));

        $sql = "select * from ".$mypdo->prefix."code where code_type = $type and code_day = $day limit 1";

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