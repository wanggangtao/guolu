<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-03
 * Time: 20:52
 */

class Table_smallguolu_attr extends Table {
    private static  $pre = "smallguolu_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['smallguolu_id'];
        $r['version']               = $data['smallguolu_version'];
        $r['vender']                = $data['smallguolu_vender'];
        $r['heat_temperature']      = $data['smallguolu_heat_temperature'];
        $r['live_temperature']      = $data['smallguolu_live_temperature'];
        $r['power']                 = $data['smallguolu_power'];
        $r['thermal_efficiency']    = $data['smallguolu_thermal_efficiency'];
        $r['efficiency_level']      = $data['smallguolu_efficiency_level'];
        $r['size']                  = $data['smallguolu_size'];
        $r['weight']                = $data['smallguolu_weight'];
        $r['protection_level']      = $data['smallguolu_protection_level'];
        $r['proid']                 = $data['smallguolu_proid'];
        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "heat_temperature"=>"string",
            "live_temperature"=>"string",
            "thermal_efficiency"=>"string",
            "efficiency_level"=>"string",
            "size"=>"string",
            "protection_level"=>"string",
            "weight"=>"number",
            "power"=>"number",
            "proid"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'smallguolu_attr', $params);
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
            "smallguolu_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'smallguolu_attr', $params, $where);
        return $r;
    }


    static public function getInfoById($guoluId){
        global $mypdo;

        $guoluId = $mypdo->sql_check_input(array('number', $guoluId));
        $sql = "select * from ".$mypdo->prefix."smallguolu_attr where smallguolu_id = $guoluId limit 1";
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
            "smallguolu_id" => array('number', $guoluId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'smallguolu_attr', $where);
    }


    static public function getCount(){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."smallguolu_attr";
        $where = " where 1 = 1 ";
        $sql .= $where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }else{
            return 0;
        }
    }

    static public function getList($page, $pageSize, $vender){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."smallguolu_attr";
        $where = " left join boiler_products on products_id = smallguolu_proid and products_modelid = 1 where 1 = 1";
        if(!empty($vender)){
            $vender = $mypdo->sql_check_input(array('number', $vender));
            $where .= " and smallguolu_vender = $vender";
        }
        $sql .= $where;
//        echo $sql;
        $sql .= " order by products_weight desc, smallguolu_id desc";
        $limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }
        $sql .= $limit;
        $rs = $mypdo->sqlQuery($sql);
        return $rs;
    }

    /**
     * @param $name
     * @return array|mixed导入
     */
    static public function getInfoByName($name){
        global $mypdo;

        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select * from ".$mypdo->prefix."smallguolu_attr where smallguolu_version = $name";

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

    static public function getInfoByVenderId($venderId){
        global $mypdo;

        $venderId = $mypdo->sql_check_input(array('number', $venderId));

        $sql = "select * from ".$mypdo->prefix."smallguolu_attr where smallguolu_vender = $venderId";
        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $temp = array();
                $temp[$key] = self::struct($val);
                array_push($r,$temp);
            }
            return $r;
        }else{
            return $r;
        }
    }



}
?>