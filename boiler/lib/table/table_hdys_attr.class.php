<?php

/**
 * table_hdys_attr.class.php 数据库表:全自动软水器
 *
 * @version       v0.01
 * @createtime    2018/5/26
 * @updatetime    2018/5/26
 * @author        dlk wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_hdys_attr extends Table {


    private static  $pre = "hdys_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['hdys_id'];
        $r['version']               = $data['hdys_version'];
        $r['vender']                = $data['hdys_vender'];
        $r['outwater']              = floatval($data['hdys_outwater']);
        $r['proid']                 = $data['hdys_proid'];
        $r['weight']                = floatval($data['hdys_weight']);

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "version"=>"string",
            "vender"=>"number",
            "outwater"=>"number",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'hdys_attr', $params);
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
            "hdys_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'hdys_attr', $params, $where);
        return $r;
    }

    /**
     * Table_hdys_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $hdysId  全自动软水器ID
     *
     * @return
     */
    static public function getInfoById($hdysId){
        global $mypdo;

        $hdysId = $mypdo->sql_check_input(array('number', $hdysId));

        $sql = "select * from ".$mypdo->prefix."hdys_attr where hdys_id = $hdysId limit 1";

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
     * Table_hdys_attr::getInfoByProid() 根据产品ID获取详情
     *
     * @param Integer $proid  产品ID
     *
     * @return
     */
    static public function getInfoByProid($proid){
        global $mypdo;

        $proid = $mypdo->sql_check_input(array('number', $proid));

        $sql = "select * from ".$mypdo->prefix."hdys_attr where hdys_proid = $proid limit 1";

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
     * Table_hdys_attr::getInfoByWeight() 根据出水量获取详情，选择辅机时用到
     *
     * @param Integer $weight  软水机出水量
     *
     * @return
     */
    static public function getInfoByWeight($weight){
        global $mypdo;

        $weight = $mypdo->sql_check_input(array('number', $weight));

        $sql = "select * from ".$mypdo->prefix."hdys_attr where hdys_outwater >= $weight order by hdys_outwater asc ";//获取符合要求的软水机

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            $min_outwater = $rs[0]['hdys_outwater'];
            foreach($rs as $key => $val){
                if($val['hdys_outwater'] == $min_outwater){
                    $r[$key] = self::struct($val);
                }

            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * Table_hdys_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $vender            厂家
     * @param number $is_lownigtrogen   是否低氮
     * @return
     */
    static public function getList($page, $pageSize, $count){
        global $mypdo;

        $where = " from ".$mypdo->prefix."hdys_attr left join boiler_products on products_id = hdys_proid and products_modelid = 3 where 1 = 1";

        if($count == 1){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * ".$where;
            $sql .=" order by products_weight desc, hdys_id desc";

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
     * Table_hdys_attr::del() 根据ID删除数据
     *
     * @param Integer $hdysId  全自动软水器ID
     *
     * @return
     */
    static public function del($hdysId){

        global $mypdo;

        $where = array(
            "hdys_id" => array('number', $hdysId)
        );

        return $mypdo->sqldelete($mypdo->prefix.'hdys_attr', $where);
    }

}
?>