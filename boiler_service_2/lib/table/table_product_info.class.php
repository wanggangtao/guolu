<?php


class Table_product_info extends Table {


    private static  $pre = "info_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['info_id'];
        $r['code']               = $data['info_code'];
        $r['brand']                = $data['info_brand'];
        $r['version']        = $data['info_version'];
        $r['type']        = $data['info_type'];
        $r['period']       = $data['info_period'];
        $r['status']           = $data['info_status'];
        $r['addtime']           = $data['info_addtime'];


        $r['province_id']        = $data['info_province_id'];
        $r['city_id']        = $data['info_city_id'];
        $r['area_id']       = $data['info_area_id'];
        $r['community_id']           = $data['info_community_id'];
        $r['detail_addres']           = $data['info_detail_addres'];
        $r['all_address']           = $data['info_all_address'];



        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "code"=>"string",
            "brand"=>"number",
            "version"=>"number",
            "type"=>"number",
            "period"=>"string",
            "status"=>"number",
            "addtime"=>"number",

            "province_id"=>"number",
            "city_id"=>"number",
            "area_id"=>"number",
            "community_id"=>"number",
            "detail_addres"=>"string",
            "all_address"=>"string",


        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }


    static public function master_submit($code,$period)
    {
        global $mypdo;
        $param = array(
            'info_period'=>array('string', $period),

        );
        $where = array(
            "info_code" => array('string', $code)
        );
        $r = $mypdo->sqlupdate($mypdo->prefix .'product_info', $param, $where);
        return $r;
    }

    static public function getInfoBycode($bar_code){
        global $mypdo;

        $bar_code = $mypdo->sql_check_input(array('string', $bar_code));

        $sql = "select * from ".$mypdo->prefix."product_info where info_code = $bar_code ";
        $sql.= " and info_status=1";
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



    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'product_info', $params);
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
            "info_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'product_info', $params, $where);
        return $r;
    }

    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."product_info where info_id = $id limit 1";

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
        $sql = "select * from ".$mypdo->prefix."product_info ";
        $where=" where info_status =1 ";

        if(isset($params["code"])){
            $where .= " and info_code like '%".$params["code"]."%'";
        }
        if(isset($params["community_id"])){
            $where .= " and info_community_id ={$params["community_id"]}";
        }

        if ((isset($params["starttime"])) && (isset($params["stoptime"]))) {
            $where .= " and info_period between " .$params["starttime"]. " and " .$params["stoptime"];
        }
        $sql.=$where;
        $sql.=" order by info_id desc".
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
            "info_status" => array('number', -1)
        );

        $where = array(
            "info_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'product_info', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."product_info ";
        $where=" where info_status =1 ";

        if(isset($params["code"])){
            $where .= " and info_code like '%".$params["code"]."%'";
        }

        if ((isset($params["starttime"])) && (isset($params["stoptime"]))) {
            $where .= " and info_period between " .$params["starttime"]. " and " . $params["stoptime"];
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
    static public function getInfoByBarCode($info_code){
        global $mypdo;
        $info_code = $mypdo->sql_check_input(array('string', $info_code));

//        $info_code = $mypdo->sql_check_input(array('string', $info_code));

        $sql = "select * from ".$mypdo->prefix."product_info where info_status =1  and info_code = ".$info_code." limit 1";

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
     * @param $info_code
     * @return array|mixed
     */
    static public function getInfoLikeCode($code){
        global $mypdo;


        $sql = "select info_code from ".$mypdo->prefix."product_info where info_status =1  and info_code like '%".$code."%' ";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs;
        }else{
            return $rs;
        }
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function updateByCode($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "info_code" => array('string', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'product_info', $params, $where);
        return $r;
    }

    static public function updateByCommunity($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "info_community_id" => array('string', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'product_info', $params, $where);
        return $r;
    }

}
?>