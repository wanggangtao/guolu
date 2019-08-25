<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/3/20
 * Time: 10:50
 */
class Table_service_label extends Table {


    private static  $pre = "label_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['label_id'];
        $r['keyword']               = $data['label_keyword'];
        $r['before']                = $data['label_before'];
        $r['after']        = $data['label_after'];
        $r['status']                 = $data['label_status'];
        $r['addtime']           = $data['label_addtime'];
        $r['operator']                 = $data['label_operator'];
        $r['lastupdatetim']               = $data['label_lastupdatetime'];
        $r['first_charter']               = $data['label_first_charter'];
        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "keyword"=>"string",
            "before"=>"string",
            "after"=>"string",
            "status"=>"number",
            "addtime"=>"number",
            "operator"=>"number",
            "lastupdatetim"=>"number",
            "first_charter"=>"string"

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }


    /**
     * @param $attrs
     * @return mixed
     * zx
     * 添加方法
     */

    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'service_label', $params);
        return $r;
    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     *
     */
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
            "label_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_label', $params, $where);
        return $r;
    }

    /**
     * @param $id
     * @return array|mixed
     *
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."service_label where label_id = $id limit 1";

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


    static public function getFCListByBefore($params){
        global $mypdo;
        $sql = "select distinct(label_first_charter) from ".$mypdo->prefix."service_label ";
        $where=" where label_status =1 ";
        if(isset($params["before"])){
            $where .= " and label_before ='".$params["before"]."'";
        }
        $where .= " order by label_first_charter asc";
        $sql.=$where;
//echo $sql;
        $rs = $mypdo->sqlQuery($sql);

        if($rs){

            return $rs;
        }else{
            return null;
        }

    }

    /**
     * @param $params
     * @return array
     *
     */
    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."service_label ";
        $where=" where label_status =1 ";

        if(isset($params['before']) and $params['before']){
            $where.=" and label_before= '".$params['before']."'";
        }
        if(isset($params['after']) and $params['after']){
            $where.=" and label_after= '".$params['after']."'";
        }
        $sql.=$where;
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

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


    static public function getListByFC($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."service_label ";
        $where=" where label_status =1 ";
        if(isset($params["before"])){
            $where .= " and label_before = '".$params['before']."'";
        }
//
//        if(isset($params["brand"])){
//            $brand = $mypdo->sql_check_input(array('number', $params["brand"]));
//            $where .= " and  find_in_set($brand , community_brand) ";
//        }
//        if(isset($params["area_id"])){
//            $area_id = $mypdo->sql_check_input(array('area_id', $params["area_id"]));
//            $where .= " and community_area_id like $area_id";
//        }

//        if(isset($params["first_charter"])){
//            $where .= " and label_first_charter = '{$params['first_charter']}'";
//        }
//        if(isset($params["type"])){
//            $where .= " and community_type ={$params["type"]}";
//        }
        $where .= " order by label_first_charter asc";
        $sql.=$where;
//        echo $sql;
//        $limit = "";
//        if(!empty(isset($params["page"]))){
//            $start = ($params["page"] - 1)*$params["pageSize"];
//            $limit = " limit {$start},{$params["pageSize"]}";
//        }
//
//        $sql .= $limit;

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
     * @param $id
     * @return mixed
     *
     */

    static public function dels($id){

        global $mypdo;


        $params = array(
            "label_status" => array('number', -1)
        );

        $where = array(
            "label_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_label', $params, $where);
        return $r;
    }

    /**
     * @param $params
     * @return null
     */
    static public function getCount($params){
        global $mypdo;

        $sql = "select count(1) as act from ".$mypdo->prefix."service_label ";

        $where=" where label_status =1 ";
        if(isset($params['before']) and $params['before']){
            $where.=" and label_before= '".$params['before']."'";
        }
        $sql.=$where;

        $rs = $mypdo->sqlQuery($sql);

        if($rs){

            return $rs[0][0];

        }else{

            return null;

        }
    }

    /**
     * @param $keyword
     * @return array|mixed
     * 通过关键字查找
     */
    static public function getInfoByKeyword($keyword){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_label where label_keyword like '%".$keyword."%' limit 1";

//        print_r($sql);
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
     * @param $keyword
     * @return array|mixed
     * 通过before查找
     */
    static public function getInfoByBefore($before){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_label where label_before = '".$before."' limit 1";
//echo $sql;
//        print_r($sql);
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
     * @param $after
     * @return array|mixed
     */
    static public function getInfoByAfter($after){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_label where label_after = '".$after."' limit 1";

//        print_r($sql);
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


}