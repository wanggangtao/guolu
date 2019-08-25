<?php

/**
 * table_water_box_attr.class.php 数据库表:水箱表
 *
 * @version       v0.01
 * @createtime    2018/5/29
 * @updatetime    2018/5/29
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_water_box_attr extends Table {


    private static  $pre = "box_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['box_id'];
        $r['version']               = $data['box_version'];
        $r['nominal_capacity']      = floatval($data['box_nominal_capacity']);
        $r['available_capacity']    = floatval($data['box_available_capacity']);
        $r['length']                = floatval($data['box_length']);
        $r['width']                 = floatval($data['box_width']);
        $r['height']                = floatval($data['box_height']);
        $r['weight']                = floatval($data['box_weight']);
        $r['proid']                 = $data['box_proid'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "nominal_capacity"=>"number",
            "available_capacity"=>"number",
            "length"=>"number",
            "width"=>"number",
            "height"=>"number",
            "weight"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'water_box_attr', $params);
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
            "box_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'water_box_attr', $params, $where);
        return $r;
    }

    /**
     * Table_water_box_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  水箱ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."water_box_attr where box_id = $id limit 1";

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
     * Table_water_box_attr::getInfoByWeight() 根据公称容积获取详情
     *
     * @param Integer $weight  水箱公称容积
     *
     * @return
     */
    static public function getInfoByCapacity($capacity){
        global $mypdo;

        $capacity = $mypdo->sql_check_input(array('number', $capacity));

        $sql = "select * from ".$mypdo->prefix."water_box_attr where box_nominal_capacity >= $capacity group by box_version order by  box_nominal_capacity asc ";//根据公称容积向上匹配获取水箱

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            $diff = $rs[0]['box_nominal_capacity'] - $capacity;//最小差值
            $i =0;
            foreach($rs as  $val){
                if($val['box_nominal_capacity'] - $capacity == $diff){
                    $r[$i] = self::struct($val);
                    $i++;
                }

            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * Table_water_box_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return
     */
    static public function getList($page, $pageSize, $count){
        global $mypdo;

        $where = " from ".$mypdo->prefix."water_box_attr left join boiler_products on products_id = box_proid and products_modelid = 8 where 1 = 1";

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, box_id desc";

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
     * Table_water_box_attr::del() 根据ID删除数据
     *
     * @param Integer $id  水箱ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "box_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'water_box_attr', $where);
    }

}
?>