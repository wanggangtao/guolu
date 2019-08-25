<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 16:45
 */
class Table_case_tplcontent extends Table {


    private static  $pre = "tplcontent_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['tplcontent_id'];
        $r['attrid']       = $data['tplcontent_attrid'];
        $r['tplid']    = $data['tplcontent_tplid'];
        $r['content']        = $data['tplcontent_content'];
        $r['lastupdate']      = floatval($data['tplcontent_lastupdate']);
        $r['addtime']      = floatval($data['tplcontent_addtime']);

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "attrid"=>"number",
            "tplid"=>"number",
            "content"=>"string",
            "lastupdate"=>"number",
            "addtime"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getInfoByTplid($tplid){
        global $mypdo;

        $tplid = $mypdo->sql_check_input(array('number', $tplid));

        $sql = "select * from ".$mypdo->prefix."case_tplcontent where tplcontent_tplid = $tplid limit 1";

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }
    static public function getInfoByAttrid($attrid){
        global $mypdo;

        $attrid = $mypdo->sql_check_input(array('number', $attrid));

        $sql = "select * from ".$mypdo->prefix."case_tplcontent where tplcontent_attrid = $attrid limit 1";

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function getListByAttrid($attrid, $page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($attrid){
            $where .= " and tplcontent_attrid = {$attrid}";
        }

        $where .= " order by tplcontent_lastupdate desc";

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."case_tplcontent".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }

        $sql = "select * from ".$mypdo->prefix."case_tplcontent".$where;
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }

        $sql .= $limit;
        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }


    static public function getInfoByAttridAndTplid($attrid, $tplid){
        global $mypdo;

        $attrid = $mypdo->sql_check_input(array('number', $attrid));
        $tplid = $mypdo->sql_check_input(array('number', $tplid));

        $sql = "select * from ".$mypdo->prefix."case_tplcontent where tplcontent_tplid = $tplid and tplcontent_attrid = $attrid limit 1";


        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function add($attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);
        }

        $r=$mypdo->sqlinsert($mypdo->prefix.'case_tplcontent', $params);

        return $r;

        }


    static public function updateByTplid($tplid,$attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "tplcontent_tplid" => array('number', $tplid)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'case_tplcontent', $params, $where);
        return $r;
    }

    static public function updateByAttrid($attrid,$attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "tplcontent_attrid" => array('number', $attrid)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'case_tplcontent', $params, $where);
        return $r;
    }

    static public function updateByAttridandtplid($attrid,$tplid,$attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "tplcontent_attrid" => array('number', $attrid),
            "tplcontent_tplid" => array('number',$tplid)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'case_tplcontent', $params, $where);
        return $r;
    }


    static public function delByTplid($id){

        global $mypdo;

        $where = array(
            "tplcontent_tplid" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'case_tplcontent', $where);
    }

    static public function delByAttrid($id){

        global $mypdo;

        $where = array(
            "tplcontent_attrid" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'case_tplcontent', $where);
    }
    static public function getListByTplid($tplid){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($tplid){
            $where .= " and tplcontent_tplid = {$tplid}";
        }

        $where .= " order by tplcontent_id asc";



        $sql = "select * from ".$mypdo->prefix."case_tplcontent".$where;

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    static public function getContentByTplcontentId($tplcontentId){
        global $mypdo;

        $contentId = $mypdo->sql_check_input(array('number', $tplcontentId));

        $sql = "select * from ".$mypdo->prefix."case_tplcontent where tplcontent_id = $contentId  limit 1";

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;


    }
    static public function getListByDictId($content_id){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."case_tplcontent where tplcontent_attrid = ".Case_tplcontent::DICT_CONTENT_ATTRID.
                    " and tplcontent_content = ".$content_id;


        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;


    }



}