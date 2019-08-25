<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-24
 * Time: 10:22
 */

class Table_weixin_video extends Table
{
    private static  $pre = "video_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['video_id'];
        $r['name']                  = $data['video_name'];
        $r['path']                  = $data['video_path'];
        $r['product_id']            = $data['video_product_id'];
        $r['imgpath']               = $data['video_imgpath'];
        $r['add_time']              = $data['video_add_time'];
        $r['weight']                = $data['video_weight'];
        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "path"=>"string",
            "product_id"=>"number",
            "imgpath"=>"string",
            "add_time"=>"number",
            "weight"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'weixin_video', $params);
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
            "video_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'weixin_video', $params, $where);
        return $r;
    }


    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."weixin_video where video_id = $id limit 1";
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


    static public function del($guoluId){

        global $mypdo;

        $where = array(
            "video_id" => array('number', $guoluId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'weixin_video', $where);
    }


    static public  function  getCountByProductId($id,$page,$pageSize){
        global $mypdo;

        $sql = "select count(1) as act from ".$mypdo->prefix."weixin_video";
        $where = " where video_product_id = $id";
        $start = ($page-1)*$pageSize;
        $where .= " limit {$start},{$pageSize}";
        $sql .= $where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }

    }

    static public  function getListByProudctIdAndPage($id,$page,$pageSize){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."weixin_video where video_product_id = $id ORDER BY video_weight DESC ";
        $start = ($page-1)*$pageSize;
        $sql .= " limit {$start},{$pageSize}";
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


    static public function getListByProudctId($id){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."weixin_video where video_product_id = $id ORDER BY video_weight DESC";
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