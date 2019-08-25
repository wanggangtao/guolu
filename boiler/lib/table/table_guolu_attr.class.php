<?php

/**
 * table_guolu_attr.class.php 数据库表:锅炉
 *
 * @version       v0.01
 * @createtime    2018/5/26
 * @updatetime    2018/5/26
 * @author        dlk wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_guolu_attr extends Table {


    private static  $pre = "guolu_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['guolu_id'];
        $r['version']               = $data['guolu_version'];
        $r['vender']                = $data['guolu_vender'];
        $r['type']                  = $data['guolu_type'];
        $r['is_condensate']         = $data['guolu_is_condensate'];
        $r['is_lownitrogen']        = $data['guolu_is_lownitrogen'];
        $r['ratedpower']            = $data['guolu_ratedpower'];
        $r['inwater_t']             = $data['guolu_inwater_t'];
        $r['outwater_t']            = $data['guolu_outwater_t'];
        $r['pressure']              = $data['guolu_pressure'];
        $r['fueltype']              = $data['guolu_fueltype'];
        $r['gas_consumption']       = $data['guolu_gas_consumption'];
        $r['fuel_consumption']      = $data['guolu_fuel_consumption'];
        $r['flue_caliber']          = $data['guolu_flue_caliber'];
        $r['hauled_weight']         = $data['guolu_hauled_weight'];
        $r['hot_flow']              = $data['guolu_hot_flow'];
        $r['interface_diameter']    = $data['guolu_interface_diameter'];
        $r['pressure_drop']         = $data['guolu_pressure_drop'];
        $r['length']                = $data['guolu_length'];
        $r['width']                 = $data['guolu_width'];
        $r['height']                = $data['guolu_height'];
        $r['smoke_height']          = $data['guolu_smoke_height'];
        $r['proid']                 = $data['guolu_proid'];
        $r['water']                 = floatval($data['guolu_water']);
        $r['min_flow']              = floatval($data['guolu_min_flow']);
        $r['max_flow']              = floatval($data['guolu_max_flow']);
        $r['heatout_60']            = floatval($data['guolu_heatout_60']);
        $r['heatout_30']            = floatval($data['guolu_heatout_30']);
        $r['heatout_range']         = $data['guolu_heatout_range'];
        $r['heateffi_80']           = floatval($data['guolu_heateffi_80']);
        $r['heateffi_50']           = floatval($data['guolu_heateffi_50']);
        $r['heateffi_30']           = floatval($data['guolu_heateffi_30']);
        $r['syswater_pre']          = $data['guolu_syswater_pre'];
        $r['heat_capacity']         = floatval($data['guolu_heat_capacity']);
        $r['fluegas_80']            = $data['guolu_fluegas_80'];
        $r['fluegas_50']            = $data['guolu_fluegas_50'];
        $r['emission_co']           = $data['guolu_emission_co'];
        $r['emission_nox']          = $data['guolu_emission_nox'];
        $r['condensed_max']         = $data['guolu_condensed_max'];
        $r['condensed_ph']          = floatval($data['guolu_condensed_ph']);
        $r['flue_interface']        = $data['guolu_flue_interface'];
        $r['gas_interface']         = $data['guolu_gas_interface'];
        $r['iowater_interface']     = $data['guolu_iowater_interface'];
        $r['gas_type']              = $data['guolu_gas_type'];
        $r['gas_press']             = $data['guolu_gas_press'];
        $r['gaspre_range']          = $data['guolu_gaspre_range'];
        $r['energy_level']          = $data['guolu_energy_level'];
        $r['air_filter']            = $data['guolu_air_filter'];
        $r['net_weight']            = $data['guolu_net_weight'];
        $r['refer_heatarea']        = $data['guolu_refer_heatarea'];
        $r['power_supply']          = $data['guolu_power_supply'];
        $r['noise']                 = $data['guolu_noise'];
        $r['electric_power']        = $data['guolu_electric_power'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "type"=>"number",
            "is_condensate"=>"number",
            "is_lownitrogen"=>"number",
            "ratedpower"=>"number",
            "inwater_t"=>"number",
            "outwater_t"=>"number",
            "pressure"=>"number",
            "fueltype"=>"string",
            "gas_consumption"=>"number",
            "fuel_consumption"=>"number",
            "flue_caliber"=>"number",
            "hauled_weight"=>"number",
            "hot_flow"=>"number",
            "interface_diameter"=>"number",
            "pressure_drop"=>"number",
            "length"=>"number",
            "width"=>"number",
            "height"=>"number",
            "smoke_height"=>"number",
            "proid"=>"number",
            "water"=>"number",
            "min_flow"=>"number",
            "max_flow"=>"number",
            "heatout_60"=>"number",
            "heatout_30"=>"number",
            "heatout_range"=>"string",
            "heateffi_80"=>"number",
            "heateffi_50"=>"number",
            "heateffi_30"=>"number",
            "syswater_pre"=>"string",
            "heat_capacity"=>"number",
            "fluegas_80"=>"string",
            "fluegas_50"=>"string",
            "emission_co"=>"string",
            "emission_nox"=>"string",
            "condensed_max"=>"number",
            "condensed_ph"=>"number",
            "flue_interface"=>"number",
            "gas_interface"=>"string",
            "iowater_interface"=>"string",
            "gas_type"=>"string",
            "gas_press"=>"number",
            "gaspre_range"=>"string",
            "energy_level"=>"string",
            "air_filter"=>"string",
            "net_weight"=>"number",
            "refer_heatarea"=>"number",
            "power_supply"=>"string",
            "noise"=>"string",
            "electric_power"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'guolu_attr', $params);
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
            "guolu_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'guolu_attr', $params, $where);
        return $r;
    }

    /**
     * Table_guolu_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $guoluId  锅炉ID
     *
     * @return
     */
    static public function getInfoById($guoluId){
        global $mypdo;

        $guoluId = $mypdo->sql_check_input(array('number', $guoluId));

        $sql = "select * from ".$mypdo->prefix."guolu_attr where guolu_id = $guoluId limit 1";

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
     * Table_guolu_attr::getInfoByVersionId() 根据厂家ID获取详情
     *
     * @param Integer $venderId  厂家ID
     *
     * @return
     */
    static public function getInfoByVenderId($venderId){
        global $mypdo;

        $venderId = $mypdo->sql_check_input(array('number', $venderId));

        $sql = "select * from ".$mypdo->prefix."guolu_attr where guolu_vender = $venderId";
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

    /**
     * Table_guolu_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            1数量0列表
     * @param string $vender            厂家
     * @param number $type              类型
     * @param number $is_condensate     是否冷凝
     * @param number $is_lownigtrogen   是否低氮
     * @return
     */
    static public function getList($page, $pageSize, $count, $vender, $type, $is_condensate, $is_lownigtrogen){
        global $mypdo;

        $where = " from ".$mypdo->prefix."guolu_attr left join boiler_products on products_id = guolu_proid and products_modelid = 1 where 1 = 1";
        if(!empty($vender)){
            $vender = $mypdo->sql_check_input(array('number', $vender));
            $where .= " and guolu_vender = $vender";
        }

        if(!empty($type)){
            $type = $mypdo->sql_check_input(array('number', $type));
            if($type > 0){ //$type>0时，获取相应类型的锅炉，$type=0时 获取所有锅炉类型
                $where .= " and guolu_type = $type ";
            }elseif($type < 0 ){ //$type<0，获取非$type类型的锅炉
                $type = abs($type);
                $where .= " and guolu_type <> $type ";
            }
        }

        if(!empty($is_condensate)){
            $is_condensate = $mypdo->sql_check_input(array('number', $is_condensate));
            $where .= " and guolu_is_condensate = $is_condensate ";
        }

        if(!empty($is_lownigtrogen)){
            $type = $mypdo->sql_check_input(array('number', $is_lownigtrogen));
            $where .= " and guolu_is_lownitrogen = $is_lownigtrogen ";
        }



        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, guolu_id desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
//echo $sql;
            $rs = $mypdo->sqlQuery($sql);
            return $rs;
        }
    }
    
    /**
     * Table_guolu_attr::del() 根据ID删除数据
     *
     * @param Integer $guoluId  锅炉ID
     *
     * @return
     */
    static public function del($guoluId){

        global $mypdo;

        $where = array(
            "guolu_id" => array('number', $guoluId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'guolu_attr', $where);
    }



    static public function checkForChange($guoluStr){
        global $mypdo;

        $sql = "select guolu_id from ".$mypdo->prefix."guolu_attr where guolu_id in({$guoluStr}) group by guolu_inwater_t,guolu_outwater_t";

        $rs = $mypdo->sqlQuery($sql);

        if($rs)
        {
            return $rs;
        }
        else
        {
            return 0;
        }

    }

    static public function getInfoByName($name){
        global $mypdo;

        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select * from ".$mypdo->prefix."guolu_attr where guolu_version = $name";

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