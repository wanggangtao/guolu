
<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 10:22
 */
class Table_case_attr extends Table {


    private static  $pre = "attr_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['attr_id'];
        $r['level']       = $data['attr_level'];
        $r['parentid']    = $data['attr_parentid'];
        $r['name']        = $data['attr_name'];
        $r['isshow']      = floatval($data['attr_isshow']);
        $r['operator']      = floatval($data['attr_operator']);
        $r['addtime']      = floatval($data['attr_addtime']);

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "level"=>"number",
            "parentid"=>"number",
            "name"=>"string",
            "isshow"=>"number",
            "operator"=>"number",
            "addtime"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."case_attr where attr_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }



    static public function getListByPid($pid, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($pid){
            $where .= " and attr_parentid = {$pid} and attr_isshow = 1";
        }

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."case_attr".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }
        $where .= " order by attr_id asc";
        $sql = "select * from ".$mypdo->prefix."case_attr".$where;
        $limit = "";


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

    static public function getListByLevel($level, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($level){
            $where .= " and attr_level = {$level}";
        }

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."case_attr".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }
        $where .= " order by attr_id asc";
        $sql = "select * from ".$mypdo->prefix."case_attr".$where;
        $limit = "";


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

    static public function getListByPidandLevel($level,$pid, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($pid){
            $where .= " and attr_parentid = {$pid}";
        }
        if($level){
            $where .= " and attr_level = {$level}";
        }

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."case_attr".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }
        $where .= " order by attr_id asc";
        $sql = "select * from ".$mypdo->prefix."case_attr".$where;
        $limit = "";


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


    static public function add($attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'case_attr', $params);
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
            "attr_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'case_attr', $params, $where);
        return $r;
    }

    static public function del($id){

        global $mypdo;

        $where = array(
            "attr_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'case_attr', $where);
    }

}