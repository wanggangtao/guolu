<?php

/**
 * table_register.class.php
 *
 * @version       v0.01
 * @createtime    2016/3/30
 * @updatetime
 * @author        TQ
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_mobile_code extends Table {

    static protected function struct($data){
        $r = array();

        $r['id']         = $data['code_id'];
        $r['mobile']     = $data['code_mobile'];
        $r['value']     = $data['code_value'];
        $r['lastupdate']     = $data['code_lastupdate'];

        return $r;
    }


    /**
     * @param $registerId
     * @return int|mixed
     */
    static public function getInfoById($id){
        global $mypdo;
        $registerId = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."mobile_code where code_id = $id limit 1";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }


    /**
     * @param $addrOne
     * @param $addrTwo
     * @param $key
     * @param $registerSize
     * @param $registerDuration
     * @return mixed
     */
    static public function add($mobile,$code){

        global $mypdo;
        //写入数据库
        $param = array (
            'code_mobile'   => array('string', $mobile),
            'code_value'  => array('string', $code),
            'code_lastupdate'  => array('number', time()),


        );
        return $mypdo->sqlinsert($mypdo->prefix.'mobile_code', $param);
    }

    /**
     * @param $registerId
     * @param $registerSize
     * @param $registerDuration
     * @return mixed
     */
    static public function update($id,$code){

        global $mypdo;

        //修改参数i
        $param = array(
            'code_value'  => array('string', $code),
            'code_lastupdate'  => array('number', time()),
        );
        //where条件
        $where = array(
            "code_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'mobile_code', $param, $where);
        return $r;
    }

    /**
     * @param $registerId
     * @return mixed
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "register_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'mobile_code', $where);
    }



    /**
     * @param $key
     * @return int|mixed
     */
    static public function getInfoByMobile($mobile){
        global $mypdo;

        $mobile = $mypdo->sql_check_input(array('string', $mobile));

        $sql = "select * from ".$mypdo->prefix."mobile_code where code_mobile = {$mobile} limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }

    static public function getSearchCount($attrs){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."mobile_code where 1=1";
        if(isset($attrs["starttime"]))
        {
            $sql .=" and code_lastupdate >=".$attrs["starttime"];
        }

        if(isset($attrs["endtime"]))
        {
            $sql .=" and code_lastupdate <=".$attrs["endtime"];
        }

        if(isset($attrs["content"]))
        {
            $sql .=" and code_mobile =".$attrs["content"];
        }

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }
    }

    static public function search($attrs){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."mobile_code where 1=1";
        if(isset($attrs["starttime"]))
        {
            $sql .=" and code_lastupdate >=".$attrs["starttime"];
        }

        if(isset($attrs["endtime"]))
        {
            $sql .=" and code_lastupdate <=".$attrs["endtime"];
        }

        if(isset($attrs["content"]))
        {
            $sql .=" and code_mobile =".$attrs["content"];
        }

        $sql .= " order BY code_lastupdate DESC";
        if(isset($attrs["page"])){
            $itemnow = ($attrs["page"]-1)*$attrs["pageSize"];
            $sql .= " limit $itemnow,".$attrs["pageSize"];
        }
        $rs = $mypdo->sqlQuery($sql);

//        echo "$sql";
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
}
?>
