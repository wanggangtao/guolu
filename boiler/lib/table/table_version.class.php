<?php

/**
 * table_version.class.php
 *
 * @version       v0.01
 * @createtime    2017/7/9
 * @updatetime
 * @author        TQ
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_version extends Table {

    const PRE = "version_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['version_id'];
        $r['name']     = $data['version_name'];
        $r['code']     = $data['version_code'];
        $r['app_type']     = $data['version_app_type'];
        $r['desc']     = $data['version_desc'];
        $r['url']     = $data['version_url'];
        $r['isforce']     = $data['version_isforce'];
        $r['status']     = $data['version_status'];
        $r['addtime']     = $data['version_addtime'];
        $r['operator']     = $data['version_operator'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAtt = array(
            "id"=>"number",
            "name"=>"string",
            "code"=>"number",
            "app_type"=>"number",
            "desc"=>"string",
            "url"=>"string",
            "isforce"=>"number",
            "status"=>"number",
            "addtime"=>"number",
            "operator"=>"number",
        );

        return isset($typeAtt[$attr])?$typeAtt[$attr]:$typeAtt;
    }


    /**
     * @param $id
     * @return int|mixed
     */
    static public function getAvailVersion($app_type){
        global $mypdo;

        $app_type = $mypdo->sql_check_input(array('number', $app_type));


        $sql = "select * from ".$mypdo->prefix."version where 
                 version_status = ".version::ONLINE." 
                 and version_app_type={$app_type} order by version_addtime desc limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }

    /**
     * @param $id
     * @return int|mixed
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));


        $sql = "select * from ".$mypdo->prefix."version where version_id={$id}  limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }

    /**
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){

        global $mypdo;
        //写入数据库
        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::PRE.$key] =  array($type,$value);

        }
        return $mypdo->sqlinsert($mypdo->prefix.'version', $params);
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::PRE.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            self::PRE."id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'version', $params, $where);
        return $r;
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            self::PRE."id" => array('number', $id)
        );
        return $mypdo->sqldelete($mypdo->prefix.'version', $where);
    }


    /**
     * @param $type
     * @param $status
     * @return array|int
     */
    static public function getList($status)
    {

        global $mypdo;
        $where = " where 1=1";



        if(!empty($status))
        {
            $where .= " and ".self::PRE."status={$status}";
        }
        $sql = "select * from ".$mypdo->prefix."version  {$where} order by version_addtime desc";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r;
        }else{
            return 0;
        }
    }

    static public function getPageList($page,$pageSize)
    {

        global $mypdo;


        $start = ($page-1)*$pageSize;
        $sql = "select * from ".$mypdo->prefix."version order by version_addtime desc limit {$start},{$pageSize}";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = @self::struct($val);
            }
            return $r;
        }else{
            return 0;
        }
    }



    static public function getCount($status)
    {
        global $mypdo;
        $where = " where 1=1";



        if(!empty($status))
        {
            $where .= " and ".self::PRE."status={$status}";
        }
        $sql = "select count(1) as act from ".$mypdo->prefix."version  {$where}";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }
    }

    static public function disableAll($app_type)
    {
        global $mypdo;

        $where = " where 1=1";


        if(!empty($app_type))
        {
            $where .= " and version_app_type={$app_type}";
        }

        $sql = "update ".$mypdo->prefix."version set version_status=".version::DOWNLINE."{$where}";
        return $mypdo->pdo->exec($sql);

    }


    /**
     * @param $id
     * @return int|mixed
     */
    static public function getMaxVersion($app_type){
        global $mypdo;

        $app_type = $mypdo->sql_check_input(array('number', $app_type));


        $sql = "select max(version_code) as code from ".$mypdo->prefix."version where 
                version_app_type={$app_type}";
        $rs = $mypdo->sqlQuery($sql);

        if($rs){

            return $rs[0]["code"];
        }else{
            return 0;
        }
    }


}
?>
