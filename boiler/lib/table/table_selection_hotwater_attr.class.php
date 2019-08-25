<?php

/**
 * table_selection_hotwater_attr.class.php 数据库表:热水属性表
 *
 * @version       v0.01
 * @createtime    2018/7/18
 * @updatetime    2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_selection_hotwater_attr extends Table {


    private static  $pre = "hotwater_";

    static protected function struct($data){
        $r = array();
        $r['id']               = $data['hotwater_id'];
        $r['history_id']       = $data['hotwater_history_id'];
        $r['param_id']         = $data['hotwater_param_id'];
        $r['heating_area']     = $data['hotwater_heating_area'];
        $r['use_type']         = $data['hotwater_use_type'];
        $r['build_type']       = $data['hotwater_build_type'];
        $r['buildattr_id']     = $data['hotwater_buildattr_id'];
        $r['attr_num']         = $data['hotwater_attr_num'];
        $r['same_use']         = $data['hotwater_same_use'];
        $r['addtime']          = $data['hotwater_addtime'];
        $r['floor_height']         = $data['hotwater_floor_height'];
        $r['floor_low']         = $data['hotwater_floor_low'];
        $r['floor_high']          = $data['hotwater_floor_high'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "history_id"=>"number",
            "param_id"=>"number",
            "heating_area"=>"number",
            "build_type"=>"number",
            "use_type"=>"number",
            "buildattr_id"=>"number",
            "attr_num"=>"number",
            "same_use"=>"number",
            "floor_high"=>"number",
            "floor_low"=>"number",
            "floor_height"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_hotwater_attr', $params);
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
            "hotwater_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_hotwater_attr', $params, $where);
        return $r;
    }

    /**
     * Table_selection_hotwater_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_hotwater_attr where hotwater_id = $id order by hotwater_id asc";

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
     * Table_selection_hotwater_attr::getInfoByHistoryId() 根据历史ID获取详情
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function getInfoByHistoryId($hisid, $parmid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));

        $sql = "select * from ".$mypdo->prefix."selection_hotwater_attr where hotwater_history_id = $hisid ";
        if($parmid){
            $parmid = $mypdo->sql_check_input(array('number', $parmid));
            $sql .= " and hotwater_param_id = $parmid ";
        }
        $sql .= " order by hotwater_id Desc";
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
     * Table_selection_hotwater_attr::getInfoByHistoryId() 根据历史ID获取详情
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function getParamByHistoryId($hisid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));

        $sql = "select distinct hotwater_param_id from ".$mypdo->prefix."selection_hotwater_attr where hotwater_history_id = $hisid order by hotwater_param_id ";

        $rs = $mypdo->sqlQuery($sql);
        return $rs;
    }

    /**
     * Table_selection_hotwater_attr::del() 根据ID删除数据
     *
     * @param Integer $id  历史ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "hotwater_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_hotwater_attr', $where);
    }

    /**
     * Table_selection_hotwater_attr::delByHistoryId() 根据历史ID删除数据
     *
     * @param Integer $hisid  历史ID
     *
     * @return
     */
    static public function delByHistoryId($hisid){

        global $mypdo;

        $where = array(
            "hotwater_history_id" => array('number', $hisid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_hotwater_attr', $where);
    }

    /**
     * @param $hisid
     * @return int
     * 得到最大值，控制循环次数
     */
    static public function getMaxParamId($hisid)
    {
        global $mypdo;

        $sql="select max(hotwater_param_id ) as act from ".$mypdo->prefix."selection_hotwater_attr where hotwater_history_id=$hisid";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }

    }

    /**
     * @param $hisid
     * @param $parmid
     * @return array
     *得到属性的个数，区别建筑类型
     */
    static public function getAttrCount($hisid, $parmid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));

        $sql = "select count(1) as act from ".$mypdo->prefix."selection_hotwater_attr where hotwater_history_id = $hisid ";
        if($parmid){
            $parmid = $mypdo->sql_check_input(array('number', $parmid));
            $sql .= " and hotwater_param_id = $parmid ";
        }

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }
    }


    static public function getCopyByHistoryId($hisid){
        global $mypdo;

        $hisid = $mypdo->sql_check_input(array('number', $hisid));

        $sql = "select * from ".$mypdo->prefix."selection_hotwater_attr where hotwater_history_id = $hisid ";
        $sql .= " order by hotwater_buildattr_id Desc";
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