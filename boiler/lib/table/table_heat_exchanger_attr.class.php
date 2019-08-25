<?php

/**
 * table_heat_exchanger_attr.class.php 数据库表:换热器
 *
 * @version       v0.01
 * @createtime    2018/5/26
 * @updatetime    2018/5/26
 * @author        dlk wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_heat_exchanger_attr extends Table {


    private static  $pre = "exchanger_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['exchanger_id'];
        $r['version']               = $data['exchanger_version'];
        $r['vender']                = $data['exchanger_vender'];
        $r['heat_surface']          = floatval($data['exchanger_heat_surface']);
        $r['first_r']               = floatval($data['exchanger_first_r']);
        $r['second_r']              = floatval($data['exchanger_second_r']);
        $r['length']                = floatval($data['exchanger_length']);
        $r['width']                 = floatval($data['exchanger_width']);
        $r['weight']                = floatval($data['exchanger_weight']);
        $r['height']                = floatval($data['exchanger_height']);
        $r['proid']                 = $data['exchanger_proid'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "heat_surface"=>"number",
            "first_r"=>"number",
            "second_r"=>"number",
            "length"=>"number",
            "width"=>"number",
            "weight"=>"number",
            "height"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'heat_exchanger_attr', $params);
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
            "exchanger_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'heat_exchanger_attr', $params, $where);
        return $r;
    }

    /**
     * Table_heat_exchanger_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $exchangerId  换热器ID
     *
     * @return
     */
    static public function getInfoById($exchangerId){
        global $mypdo;

        $exchangerId = $mypdo->sql_check_input(array('number', $exchangerId));

        $sql = "select * from ".$mypdo->prefix."heat_exchanger_attr where exchanger_id = $exchangerId limit 1";

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
     * Table_heat_exchanger_attr::getInfoByProid() 根据产品ID获取详情
     *
     * @param Integer $proid  产品ID
     *
     * @return
     */
    static public function getInfoByProid($proid){
        global $mypdo;

        $proid = $mypdo->sql_check_input(array('number', $proid));

        $sql = "select * from ".$mypdo->prefix."heat_exchanger_attr where exchanger_proid = $proid limit 1";

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
     * Table_heat_exchanger_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return
     */
    static public function getList($page, $pageSize, $count){
        global $mypdo;

        $where = " from ".$mypdo->prefix."heat_exchanger_attr left join boiler_products on products_id = exchanger_proid and products_modelid = 6 where 1 = 1";

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, exchanger_id desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;

            $rs = $mypdo->sqlQuery($sql);
            return $rs;
        }
    }
    
    /**
     * Table_heat_exchanger_attr::del() 根据ID删除数据
     *
     * @param Integer $exchangerId  换热器ID
     *
     * @return
     */
    static public function del($exchangerId){

        global $mypdo;

        $where = array(
            "exchanger_id" => array('number', $exchangerId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'heat_exchanger_attr', $where);
    }

}
?>