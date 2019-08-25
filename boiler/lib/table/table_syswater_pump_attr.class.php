<?php

/**
 * table_syswater_pump_attr.class.php 数据库表:管道泵
 *
 * @version       v0.01
 * @createtime    2018/5/26
 * @updatetime    2018/5/26
 * @author        dlk wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_syswater_pump_attr extends Table {


    private static  $pre = "pump_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['pump_id'];
        $r['version']               = $data['pump_version'];
        $r['vender']                = $data['pump_vender'];
        $r['flow_min']              = floatval($data['pump_flow_min']);
        $r['flow_mid']              = floatval($data['pump_flow_mid']);
        $r['flow_max']              = floatval($data['pump_flow_max']);
        $r['lift_min']              = floatval($data['pump_lift_min']);
        $r['lift_mid']              = floatval($data['pump_lift_mid']);
        $r['lift_max']              = floatval($data['pump_lift_max']);
        $r['speed']                 = floatval($data['pump_speed']);
        $r['motorpower']            = floatval($data['pump_motorpower']);
        $r['npsh']                  = floatval($data['pump_npsh']);
        $r['weight']                = floatval($data['pump_weight']);
        $r['proid']                 = $data['pump_proid'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "flow_min"=>"number",
            "flow_mid"=>"number",
            "flow_max"=>"number",
            "lift_min"=>"number",
            "lift_mid"=>"number",
            "lift_max"=>"number",
            "motorpower"=>"number",
            "npsh"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'syswater_pump_attr', $params);
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
            "pump_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'syswater_pump_attr', $params, $where);
        return $r;
    }

    /**
     * Table_syswater_pump_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $pumpId  管道泵ID
     *
     * @return
     */
    static public function getInfoById($pumpId){
        global $mypdo;

        $pumpId = $mypdo->sql_check_input(array('number', $pumpId));

        $sql = "select * from ".$mypdo->prefix."syswater_pump_attr where pump_id = $pumpId limit 1";

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
     * Table_syswater_pump_attr::getInfoByProid() 根据ID获取详情
     *
     * @param Integer $proid  产品ID
     *
     * @return
     */
    static public function getInfoByProid($proid){
        global $mypdo;

        $proid = $mypdo->sql_check_input(array('number', $proid));

        $sql = "select * from ".$mypdo->prefix."syswater_pump_attr where pump_proid = $proid limit 1";

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
     * Table_syswater_pump_attr::getInfoByFlowLift($flow, $lift) 根据流量和扬程获取详情
     *
     * @param Integer $flow
     *
     * @param Integer $lift
     *
     * @return
     */
    static public function getInfoByFlowLift($flow, $lift){
        global $mypdo;

        $flow = $mypdo->sql_check_input(array('number', $flow));
        $lift = $mypdo->sql_check_input(array('number', $lift));

        $sql = "select * from ".$mypdo->prefix."syswater_pump_attr where (pump_flow_min <= $flow and $flow <= pump_flow_mid and  pump_lift_mid <= $lift and $lift <= pump_lift_max)
                                                  or (pump_flow_mid <= $flow and $flow <= pump_flow_max and  pump_lift_min <= $lift and $lift <= pump_lift_mid) order by pump_flow_mid asc ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            $min_diff = abs($rs[0]['pump_flow_mid']-$flow);//初始化最小值
            foreach ($rs as $val){//计算最小差值
                if(abs($val['pump_flow_mid']-$flow) < $min_diff){
                    $min_diff = abs($val['pump_flow_mid']-$flow);
                }
            }
            $i=0;
            foreach($rs as $val){
                if( abs($val['pump_flow_mid']-$flow)  == $min_diff){//差值等于最小差值的才返回
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
     * Table_syswater_pump_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $vender            厂家
     * @return
     */
    static public function getList($page, $pageSize, $count, $vender){
        global $mypdo;

        $where = " from ".$mypdo->prefix."syswater_pump_attr left join boiler_products on products_id = pump_proid and products_modelid = 5 where 1 = 1";
        if(!empty($vender)){
            $vender = $mypdo->sql_check_input(array('number', $vender));
            $where .= " and pump_vender = $vender";
        }

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, pump_id desc";

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
     * Table_syswater_pump_attr::del() 根据ID删除数据
     *
     * @param Integer $pumpId  管道泵ID
     *
     * @return
     */
    static public function del($pumpId){

        global $mypdo;

        $where = array(
            "pump_id" => array('number', $pumpId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'syswater_pump_attr', $where);
    }

}
?>