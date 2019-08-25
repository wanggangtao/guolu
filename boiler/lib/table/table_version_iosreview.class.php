<?php

/**
 * table_version_iosreview.class.php 数据库表:ios提审表
 *
 * @version       v0.01
 * @createtime    2018/8/18
 * @updatetime    2018/8/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_version_iosreview extends Table {


    private static  $pre = "iosreview_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['iosreview_id'];
        $r['version']       = $data['iosreview_version'];
        $r['isregister']    = $data['iosreview_isregister'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "isregister"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'version_iosreview', $params);
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
            "iosreview_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'version_iosreview', $params, $where);
        return $r;
    }

    /**
     * Table_version_iosreview::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."version_iosreview where iosreview_id = $id limit 1";

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
     * Table_version_iosreview::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "iosreview_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'version_iosreview', $where);
    }
}
?>