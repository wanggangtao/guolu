<?php

/**
 * table_baseconfig.class.php
 *
 * @version       $Id$ v0.01
 * @createtime    2017/8/17
 * @updatetime
 * @author        xp
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
class Table_baseconfig extends Table
{

    static protected function struct($data)
    {
        $r = array();

        $r['id'] = $data['baseconfig_id'];
        $r['key'] = $data['baseconfig_key'];
        $r['value'] = $data['baseconfig_value'];
        $r['name'] = $data['baseconfig_name'];
        $r['addtime'] = $data['baseconfig_addtime'];
        $r['lastupdate'] = $data['baseconfig_lastupdate'];

        return $r;
    }

    /**
     * 根据id获取详情
     * @param $id
     * @return int|mixed
     */
    static public function getInfoById($id)
    {
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from " . $mypdo->prefix . "baseconfig where baseconfig_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if ($rs) {
            $r = array();
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
            return $r[0];
        } else {
            return 0;
        }
    }

    /**
     * 根据key获取用户详情
     * @param $key
     * @return int|mixed
     */
    static public function getInfoByKey($key)
    {
        global $mypdo;

        $key = $mypdo->sql_check_input(array('string', $key));

        $sql = "select * from " . $mypdo->prefix . "baseconfig where baseconfig_key = $key limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if ($rs) {
            $r = array();
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
            return $r[0];
        } else {
            return 0;
        }
    }


    /**
     * 根据key获取用户详情
     * @param $key
     * @return int|mixed
     */
    static public function getInfoByArr($keyArr)
    {
        global $mypdo;


        $where = " where 1=1 ";
        if(is_array($keyArr))
        {
            $where .= " and  baseconfig_key in ('".implode("','",$keyArr)."')";
        }


        $sql = "select * from " . $mypdo->prefix . "baseconfig {$where}";

        $rs = $mypdo->sqlQuery($sql);
        if ($rs) {
            $r = array();
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
            return $r;
        } else {
            return 0;
        }
    }


    /**
     * 添加
     * @param $key
     * @param $value
     * @param $name
     * @return mixed
     */
    static public function add($key, $value, $name)
    {
        global $mypdo;

        //写入数据库
        $param = array(
            'baseconfig_key' => array('string', $key),
            'baseconfig_value' => array('string', $value),
            'baseconfig_name' => array('string', $name),
            'baseconfig_addtime' => array('number', time())
        );
        return $mypdo->sqlinsert($mypdo->prefix . 'baseconfig', $param);
    }

    /**
     * 更新
     * @param $id
     * @param $update_info
     * @return mixed
     */
    static public function update($id, $update_info)
    {
        global $mypdo;

        $param = array(
            'baseconfig_value' => array('string', $update_info['value']),
            'baseconfig_lastupdate' => array('number', $update_info['lastupdate']),
        );
        $where = array('baseconfig_id' => array('number', $id));

        return $mypdo->sqlupdate($mypdo->prefix . 'baseconfig', $param, $where);
    }


    /**
     * 更新
     * @param $id
     * @param $update_info
     * @return mixed
     */
    static public function updateByKey($key, $update_info)
    {
        global $mypdo;

        $param = array(
            'baseconfig_value' => array('string', $update_info["value"]),
            'baseconfig_lastupdate' => array('number', $update_info['lastupdate']),
        );
        $where = array('baseconfig_key' => array('string', $key));

        return $mypdo->sqlupdate($mypdo->prefix . 'baseconfig', $param, $where);
    }

    static public function edit_minxinswitch($minxinswitch){
       // global $mypdo;
        global $mypdo;
        $param=array(
            'baseconfig_value'            =>array('string',$minxinswitch)
        );

        $where=array(
            'baseconfig_id'          =>array('number',7)
        );

        return $mypdo->sqlupdate($mypdo->prefix. 'baseconfig',$param,$where);
    }

    static public function edit_minxin_shadow_switch($switch){
        // global $mypdo;
        global $mypdo;
        $param=array(
            'baseconfig_value'            =>array('number',$switch)
        );

        $where=array(
            'baseconfig_id'          =>array('number',11)
        );

        return $mypdo->sqlupdate($mypdo->prefix. 'baseconfig',$param,$where);
    }

    static public function getStatus(){
        global $mypdo;

        $sql = "select baseconfig_value as status from ".$mypdo->prefix."baseconfig";
        //      print_r($sql);exit();
        $r = $mypdo->sqlQuery($sql);
        if($r){
            //     print_r($r);exit();
            return $r[6]['status'];
        }else{
            return 0;
        }
    }


}

?>