<?php
//小区

class Table_community extends Table {


    private static  $pre = "community_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['community_id'];
        $r['name']               = $data['community_name'];
        $r['brand']                = $data['community_brand'];
        $r['provice_id']        = $data['community_provice_id'];
        $r['provice_name']                 = $data['community_provice_name'];
        $r['city_id']           = $data['community_city_id'];
        $r['city_name']                 = $data['community_city_name'];
        $r['area_id']               = $data['community_area_id'];
        $r['area_name']               = $data['community_area_name'];
        $r['status']                = $data['community_status'];
        $r['type']                = $data['community_type'];//类型 1 客服添加 0 后台添加
        $r['first_charter']                = $data['community_first_charter'];//类型 1 客服添加 0 后台添加\
        $r['period']                = $data['community_period'];


        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "brand"=>"string",
            "provice_id"=>"number",
            "provice_name"=>"string",
            "city_id"=>"number",
            "city_name"=>"string",
            "area_id"=>"number",
            "area_name"=>"string",
            "status"=>"number",
            "type"=>"number",
            "first_charter"=>"string",
            "period"=>"string",


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

        $r = $mypdo->sqlinsert($mypdo->prefix.'community', $params);
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
            "community_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'community', $params, $where);
        return $r;
    }

    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."community where community_id = $id and community_status =1 limit 1";

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


    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."community ";
        $where=" where community_status =1 ";

        if(isset($params["name"])){
            $name = $mypdo->sql_check_input(array('string', "%".$params["name"]."%"));
            $where .= " and community_name like $name";
        }

        if(isset($params["brand"])){
            $brand = $mypdo->sql_check_input(array('number', $params["brand"]));
            $where .= " and  find_in_set($brand , community_brand) ";
        }
        if(isset($params["area_id"])){
            $area_id = $mypdo->sql_check_input(array('area_id', $params["area_id"]));
            $where .= " and community_area_id like $area_id";
        }
        if(isset($params["type"])){
            $where .= " and community_type ={$params["type"]}";
        }
        $sql.=$where;
        $sql.=" order by community_id desc ";
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


    static public function dels($id){

        global $mypdo;


        $params = array(
            "community_status" => array('number', -1)
        );

        $where = array(
            "community_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'community', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."community ";
        $where=" where community_status =1 ";

        if(isset($params["name"])){
            $name = $mypdo->sql_check_input(array('string', "%".$params["name"]."%"));
            $where .= " and community_name like $name";
        }

        if(isset($params["brand"])){
            $brand = $mypdo->sql_check_input(array('number', $params["brand"]));
            $where .= " and  find_in_set($brand , community_brand) ";
        }
        if(isset($params["type"])){
            $where .= " and community_type ={$params["type"]}";
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
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getCommunityById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."community where community_area_id = $id  and community_status = 1";

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
     * @param $params
     * @return array
     *
     */
    static public function getNameList($params){
        global $mypdo;
        $sql = "select community_name from ".$mypdo->prefix."community ";
        $where=" where community_status =1 ";

        if(isset($params["name"])){
            $name = $mypdo->sql_check_input(array('string', "%".$params["name"]."%"));
            $where .= " and community_name like $name";
        }

        if(isset($params["brand"])){
            $brand = $mypdo->sql_check_input(array('number', $params["brand"]));
            $where .= " and  find_in_set($brand , community_brand) ";
        }
        if(isset($params["area_id"])){
            $area_id = $mypdo->sql_check_input(array('area_id', $params["area_id"]));
            $where .= " and community_area_id like $area_id";
        }
        if(isset($params["type"])){
            $where .= " and community_type ={$params["type"]}";
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
                $r[$key] = $val;
            }
            return $r;
        }else{
            return $r;
        }

    }

    /**
     * @param $params
     * @return array
     * 首字母排序
     */
    static public function getListByFC($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."community ";
        $where=" where community_status =1 ";

        if(isset($params["name"])){
            $name = $mypdo->sql_check_input(array('string', "%".$params["name"]."%"));
            $where .= " and community_name like $name";
        }

        if(isset($params["brand"])){
            $brand = $mypdo->sql_check_input(array('number', $params["brand"]));
            $where .= " and  find_in_set($brand , community_brand) ";
        }
        if(isset($params["area_id"])){
            $area_id = $mypdo->sql_check_input(array('area_id', $params["area_id"]));
            $where .= " and community_area_id like $area_id";
        }

        if(isset($params["first_charter"])){
            $where .= " and community_first_charter = '{$params['first_charter']}'";
        }
        if(isset($params["type"])){
            $where .= " and community_type ={$params["type"]}";
        }
        $where .= " order by community_first_charter asc";
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

    /**
     * @param $params
     * @return array
     * 得到第一个字符的列表
     */
    static public function geFCtListByFC($params){
        global $mypdo;
        $sql = "select distinct(community_first_charter) from ".$mypdo->prefix."community ";
        $where=" where community_status =1 ";
        if(isset($params["type"])){
            $where .= " and community_type ={$params["type"]}";
        }
        $where .= " order by community_first_charter asc";
        $sql.=$where;

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
     * 得到第一个字符的列表
     */
    static public function getCommunityByAddress($provice,$city,$area,$type = 1 ){
        global $mypdo;

        $sql = "SELECT * FROM boiler_community ";
        $where=" where community_status =1 ";
        if($type == 1){
            $where .= "AND community_provice_name LIKE '".$provice."' 
                AND community_city_name LIKE '".$city."' AND community_area_name LIKE '".$area."' ";
        }elseif ($type == 2){
            $where .= "AND community_provice_id = ".$provice." 
                AND community_city_id = ".$city." AND community_area_id = ".$area ;
        }



        $sql.=$where;
//echo $sql;
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
    static public function updateType($id){

        global $mypdo;

        $params = array();

        $params['community_type']= array('number', -1);
        //where条件
        $where = array(
            "community_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'community', $params, $where);
        return $r;
    }

    /**
     * @param $id
     * @return array|mixed
     */
    static public function getInfoByName($name){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."community where community_status =1 and community_name = '".$name."' limit 1";

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
?>