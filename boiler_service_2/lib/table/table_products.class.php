<?php

/**
 * table_products.class.php 数据库表:产品表
 *
 * @version       v0.01
 * @createtime    2018/5/27
 * @updatetime    2018/5/27
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_products extends Table {


    private static  $pre = "products_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['products_id'];
        $r['name']          = $data['products_name'];
        $r['img']           = $data['products_img'];
        $r['brief']         = $data['products_brief'];
        $r['desc']          = $data['products_desc'];
        $r['addtime']       = $data['products_addtime'];
        $r['lastupdate']    = $data['products_lastupdate'];
        $r['weight']        = $data['products_weight'];
        $r['price']         = $data['products_price'];
        $r['modelid']       = $data['products_modelid'];
        $r['detail_imgs']    = $data['products_detail_imgs'];
        $r['detail_video']   = $data['products_detail_video'];
        $r['wxdesc']          = $data['products_wxdesc'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"         =>  "number",
            "name"       =>  "string",
            "img"        =>  "string",
            "brief"      =>  "string",
            "desc"       =>  "string",
            "addtime"    =>  "number",
            "lastupdate" =>  "number",
            "weight"     =>  "number",
            "price"      =>  "number",
            "modelid"    =>  "number",
            "detail_imgs" =>  "string",
            "detail_video" =>  "string",
            "wxdesc"     =>  "string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'products', $params);
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
            "products_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'products', $params, $where);
        return $r;
    }

    /**
     * Table_products::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."products where products_id = $id limit 1";

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
     * Table_products::del() 根据ID删除数据
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "products_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'products', $where);
    }

    /**
     * Table_products::getPageList() 根据条件列出所有信息
     * @param number $start             起始行
     * @param number $pagesize          页面大小
     * @param number $count             0数量1列表
     * @param number $modelid           模型ID
     * @return
     */
    static public function getPageList($start, $pageSize, $count, $modelid, $attrname, $vender, $is_lownigtrogen){
        global $mypdo;

        $proidname ="";
        switch ($modelid){
            case 5:
                $proidname ="pump";
                break;
            case 6:
                $proidname ="exchanger";
                break;
            case 7:
                $proidname ="water";
                break;
            case 8:
                $proidname ="box";
                break;
            case 9:
                $proidname ="separator";
                break;
            default :
                $proidname = str_replace("_attr","", $attrname);
        }

        $where = " from ".$mypdo->prefix."products right join ".$mypdo->prefix.$attrname." on products_id = ".$proidname."_proid ";
        if(!empty($vender)){
            $vender = $mypdo->sql_check_input(array('number', $vender));
            $where .= " and ".$proidname."_vender = $vender ";
        }
        if(!empty($is_lownigtrogen)){
            $is_lownigtrogen = $mypdo->sql_check_input(array('number', $is_lownigtrogen));
            $where .= " and ".$proidname."_is_lownitrogen = $is_lownigtrogen ";
        }

        $where .= "where 1 = 1";
        if(!empty($modelid)){
            $modelid = $mypdo->sql_check_input(array('number', $modelid));
            $where .= " and products_modelid = $modelid ";
        }
        if($count == 0){
            $sql = "select count(1) as ct ".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select *  ".$where;
            $sql .=" order by products_id asc";

            //$limit = "";
            //if(!empty($start)){
            $limit = " limit {$start},{$pageSize}";
            //}

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
                return $r;
            }else{

            }
        }
    }
}
?>