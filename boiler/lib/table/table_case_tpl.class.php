<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 13:42
 */
class Table_case_tpl extends Table {


    private static  $pre = "tpl_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['tpl_id'];
        $r['name']        = $data['tpl_name'];
        $r['attrid']      = $data['tpl_attrid'];
        $r['vender']      = $data['tpl_vender'];
        $r['usertype']      = $data['tpl_usertype'];
        $r['userstate']      = $data['tpl_userstate'];
        $r['operator']      = $data['tpl_operator'];
        $r['lastupdate']      = $data['tpl_lastupdate'];
        $r['code']      = $data['tpl_code'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "attrid"=>"number",
            "vender"=>"number",
            "usertype"=>"number",
            "userstate"=>"number",
            "operator"=>"number",
            "lastupdate"=>"number",
            "code"=>"string"

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."case_tpl where tpl_id = $id limit 1";

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'case_tpl', $params);
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
            "tpl_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'case_tpl', $params, $where);
        return $r;
    }

    static public function del($id){

        global $mypdo;

        $where = array(
            "tpl_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'case_tpl', $where);
    }

    static public function getListByAttrid($attrid,$page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($attrid){
            $where .= " and tpl_attrid = {$attrid}";
        }
        $where .= " order by tpl_lastupdate desc";

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."case_tpl".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }

        $sql = "select * from ".$mypdo->prefix."case_tpl".$where;
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
    static public function getListByAttridandSel($attrid,$sel_vender,$sel_usertype,$sel_userstate,$page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($attrid){
            $where .= " and tpl_attrid = {$attrid}";
        }
        if($sel_vender){
            $vender = $mypdo->sql_check_input(array('number', $sel_vender));
            $where .= " and tpl_vender = $vender ";
        }
        if($sel_usertype){
            $usertype = $mypdo->sql_check_input(array('number', $sel_usertype));
            $where .= " and tpl_usertype = $usertype ";
        }
        if($sel_userstate){
            $userstate = $mypdo->sql_check_input(array('number', $sel_userstate));
            $where .= " and tpl_userstate = $userstate ";
        }
        $where .= " order by tpl_lastupdate desc";

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."case_tpl".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }

        $sql = "select * from ".$mypdo->prefix."case_tpl".$where;
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }

        $sql .= $limit;
        $rs = $mypdo->sqlQuery($sql);
//        echo $sql;

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * 根据检索条件数组查询列表数据
     * @param $params
     * @return array
     * @throws Exception
     */
    static public function getListBySelect($params)
    {
        global $mypdo;

        $sql = "select * from " . $mypdo->prefix . "case_tpl";
        $where = " where 1 = 1 ";


        //attr_id
        if (!empty($params["tpl_attrid"])) {
            $where .= " and tpl_attrid={$params["tpl_attrid"]}";
        }

        //tpl_name
        if (!empty($params["tpl_name"])) {

            $where .= " and tpl_name like '%{$params["tpl_name"]}%'";
        }
        //tpl_vender
        if (!empty($params["tpl_vender"])) {

            $where .= " and tpl_vender ={$params["tpl_vender"]}";
        }
        //tpl_usertype
        if (!empty($params["tpl_usertype"])) {

            $where .= " and tpl_usertype ={$params["tpl_usertype"]}";
        }

        $sql .= $where;

        $sql .= " order by tpl_userstate asc";


        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $id = $val['tpl_id'];
                $r[$id] = self::struct($val);
            }
        }
        return $r;
    }
    /**
     * 获取模板表最后插入ID
     * @return mixed
     */
    static public function getLastId(){
        global $mypdo;
        $sql = "select MAX(tpl_id) as last_id from ".$mypdo->prefix."case_tpl ";

        $rs = $mypdo->sqlQuery($sql);

        return $rs[0]['last_id'];
    }

}