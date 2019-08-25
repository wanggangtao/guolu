<?php

/**
 * table_diversity_water_attr.class.php 数据库表:分集水器
 *
 * @version       v0.01
 * @createtime    2018/5/31
 * @updatetime    2018/5/31
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_diversity_water_attr extends Table {


    private static  $pre = "water_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['water_id'];
        $r['vender']                = $data['water_vender'];
        $r['version']               = $data['water_version'];
        $r['dgmm']                  = floatval($data['water_dgmm']);
        $r['head_height']           = floatval($data['water_head_height']);
        $r['blowdown_pipe']         = floatval($data['water_blowdown_pipe']);
        $r['proid']                 = $data['water_proid'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "dgmm"=>"number",
            "head_height"=>"number",
            "blowdown_pipe"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'diversity_water_attr', $params);
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
            "water_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'diversity_water_attr', $params, $where);
        return $r;
    }

    /**
     * Table_diversity_water_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  分集水器ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."diversity_water_attr where water_id = $id limit 1";

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
     * Table_diversity_water_attr::getInfoByProid() 根据产品ID获取详情
     *
     * @param Integer $proid  产品ID
     *
     * @return
     */
    static public function getInfoByProid($proid){
        global $mypdo;

        $proid = $mypdo->sql_check_input(array('number', $proid));

        $sql = "select * from ".$mypdo->prefix."diversity_water_attr where water_proid = $proid limit 1";

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
     * Table_diversity_water_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return
     */
    static public function getList($page, $pageSize, $count){
        global $mypdo;

        $where = " from ".$mypdo->prefix."diversity_water_attr left join boiler_products on products_id = water_proid and products_modelid = 7 where 1 = 1";

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, water_id desc";

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
     * Table_diversity_water_attr::del() 根据ID删除数据
     *
     * @param Integer $id  分集水器ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "water_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'diversity_water_attr', $where);
    }

}
?>