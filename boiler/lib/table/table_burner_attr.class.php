<?php

/**
 * table_burner_attr.class.php 数据库表:燃烧器
 *
 * @version       v0.01
 * @createtime    2018/5/26
 * @updatetime    2018/5/26
 * @author        dlk wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_burner_attr extends Table {


    private static  $pre = "burner_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['burner_id'];
        $r['version']               = $data['burner_version'];
        $r['vender']                = $data['burner_vender'];
        $r['is_lownitrogen']        = $data['burner_is_lownitrogen'];
        $r['power']                 = floatval($data['burner_power']);
        $r['boilerpower']           = floatval($data['burner_boilerpower']);
        $r['proid']                 = $data['burner_proid'];
        $r['guoluid']               = $data['burner_guoluid'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "is_lownitrogen"=>"number",
            "power"=>"number",
            "boilerpower"=>"number",
            "proid"=>"number",
            "guoluid"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'burner_attr', $params);
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
            "burner_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'burner_attr', $params, $where);
        return $r;
    }

    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getInfoById($burnerId){
        global $mypdo;

        $burnerId = $mypdo->sql_check_input(array('number', $burnerId));

        $sql = "select * from ".$mypdo->prefix."burner_attr where burner_id = $burnerId limit 1";

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
     * Table_burner_attr::getInfoByProid() 根据产品ID获取详情
     *
     * @param Integer $proid  产品ID
     *
     * @return
     */
    static public function getInfoByProid($proid){
        global $mypdo;

        $proid = $mypdo->sql_check_input(array('number', $proid));

        $sql = "select * from ".$mypdo->prefix."burner_attr where  burner_proid = $proid limit 1";

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
     * Table_burner_attr::getInfoByGuoluid() 根据锅炉ID获取详情
     *
     * @param Integer $guoluid  锅炉ID
     *
     * @return
     */
    static public function getInfoByGuoluid($guoluid){
        global $mypdo;

        $guoluid = $mypdo->sql_check_input(array('number', $guoluid));

        $sql = "select * from ".$mypdo->prefix."burner_attr where  burner_guoluid = $guoluid limit 1";

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
     * Table_burner_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $vender            厂家
     * @param number $is_lownigtrogen   是否低氮
     * @return
     */
    static public function getList($page, $pageSize, $count, $vender, $is_lownigtrogen){
        global $mypdo;

        $where = " from ".$mypdo->prefix."burner_attr left join boiler_products on products_id = burner_proid and products_modelid = 2 where 1 = 1";
        if(!empty($vender)){
            $vender = $mypdo->sql_check_input(array('number', $vender));
            $where .= " and burner_vender = $vender";
        }

        if(!empty($is_lownigtrogen)){
            $type = $mypdo->sql_check_input(array('number', $is_lownigtrogen));
            $where .= " and burner_is_lownitrogen = $is_lownigtrogen ";
        }

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, burner_id desc";

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
     * Table_burner_attr::del() 根据ID删除数据
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function del($burnerId){

        global $mypdo;

        $where = array(
            "burner_id" => array('number', $burnerId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'burner_attr', $where);
    }

}
?>