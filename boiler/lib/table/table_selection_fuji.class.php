<?php

/**
 * table_selection_fuji.class.php 数据库表:辅机表
 *
 * @version       v0.01
 * @createtime    2018/7/18
 * @updatetime    2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_selection_fuji extends Table {


    private static  $pre = "fuji_";

    static protected function struct($data){
        $r = array();
        $r['id']                          = $data['fuji_id'];
        $r['history_id']                  = $data['fuji_history_id'];
        $r['use_type']                    = $data['fuji_use_type'];
        $r['data_type']                   = $data['fuji_data_type'];
        $r['add_type']                    = $data['fuji_add_type'];
        $r['value']                       = $data['fuji_value'];
        $r['name']                        = $data['fuji_name'];
        $r['param']                       = $data['fuji_param'];
        $r['num']                         = $data['fuji_num'];
        $r['modelid']                     = $data['fuji_modelid'];
        $r['addtime']                     = $data['fuji_addtime'];
        $r['version_show']                = $data['fuji_version_show'];
        $r['context']                     = $data['fuji_context'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "history_id"=>"number",
            "use_type"=>"number",
            "data_type"=>"number",
            "add_type"=>"number",
            "name"=>"string",
            "param"=>"string",
            "num"=>"number",
            "value"=>"string",
            "version_show"=>"string",
            "modelid"=>"number",
            "addtime"=>"number",
            "context"=>"string"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_fuji', $params);
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
            "fuji_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_fuji', $params, $where);
        return $r;
    }

    /**
     * Table_selection_fuji::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_fuji where fuji_id = $id limit 1";

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
     * Table_selection_fuji::getInfoByHistoryId() 根据历史ID获取详情
     *
     * @param Integer $hisid  历史ID
     * @param Integer $type  使用类型
     * @return
     */
    static public function getInfoByHistoryId($hisid, $type){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $type = $mypdo->sql_check_input(array('number', $type));

        $sql = "select * from ".$mypdo->prefix."selection_fuji where fuji_history_id = $hisid and fuji_use_type = $type order by fuji_id asc";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getInfoByHistory_Id($hisid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $sql = "select * from ".$mypdo->prefix."selection_fuji where fuji_history_id = $hisid";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }


    /**
     * Table_selection_fuji::getInfoByHistoryId() 根据历史ID获取详情
     *
     * @param Integer $hisid  历史ID
     * @param Integer $type  使用类型
     * @return
     */
    static public function getInfoByHistoryIdandAddtype($hisid, $type,$addtype){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $type = $mypdo->sql_check_input(array('number', $type));
        $addtype = $mypdo->sql_check_input(array('number', $addtype));

        $sql = "select * from ".$mypdo->prefix."selection_fuji where fuji_history_id = $hisid and fuji_use_type = $type and fuji_add_type=$addtype order by fuji_id asc";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }
    /**
     * Table_selection_fuji::getListByHistoryId() 根据历史ID获取列表
     *
     * @param Integer $hisid  历史ID
     * @param Integer $addtype  是否添加到方案中
     * @return
     */
    static public function getListByHistoryId($hisid, $addtype){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));
        $addtype = $mypdo->sql_check_input(array('number', $addtype));

        $sql = "select * from ".$mypdo->prefix."selection_fuji where fuji_history_id = $hisid and fuji_add_type = $addtype order by fuji_id asc";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }
    /**
     * Table_selection_fuji::del() 根据ID删除数据
     *
     * @param Integer $id  历史ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "fuji_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_fuji', $where);
    }

    /**
     * Table_selection_fuji::delByHistoryId() 根据历史ID删除数据
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function delByHistoryId($hisid){

        global $mypdo;

        $where = array(
            "fuji_history_id" => array('number', $hisid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_fuji', $where);
    }
    static public function getInfoByName($id,$name,$type){
        global $mypdo;

        $name = $mypdo->sql_check_input(array('string', $name));
        $id = $mypdo->sql_check_input(array('number', $id));
        $type = $mypdo->sql_check_input(array('number', $type));


        $sql = "select * from ".$mypdo->prefix."selection_fuji where fuji_name = $name and fuji_history_id=$id  and fuji_use_type=$type" ;


        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }



}
?>