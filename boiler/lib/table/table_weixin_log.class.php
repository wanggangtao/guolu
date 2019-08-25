<?php

/**
 * table_weixin_log.class.php
 *
 * @version       v0.01
 * @createtime    2017/8/3
 * @updatetime
 * @author        TQ
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_weixin_log extends Table {

    const PRE = "log_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['log_id'];
        $r['request']     = $data['log_request'];
        $r['response']     = $data['log_response'];
        $r['addtime']     = $data['log_addtime'];
        $r['adddate']     = $data['log_adddate'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAtt = array(
            "id"=>"number",
            "request"=>"string",
            "response"=>"string",
            "type"=>"number",
            "addtime"=>"number",
            "adddate"=>"string",

        );

        return isset($typeAtt[$attr])?$typeAtt[$attr]:$typeAtt;
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
        return $mypdo->sqlinsert($mypdo->prefix.'weixin_log', $params);
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
        $r = $mypdo->sqlupdate($mypdo->prefix.'weixin_log', $params, $where);
        return $r;
    }



    /**

    /**
     * @param $type
     * @param $status
     * @return array|int
     */
    static public function getList()
    {
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."weixin_log ";
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


    static public function getCount()
    {
        global $mypdo;

        $sql = "select count(1) as act from ".$mypdo->prefix."weixin_log";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }
    }


}
?>
