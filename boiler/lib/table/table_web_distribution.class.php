<?php

/**
 * table_web_distribution.class.php 数据库表:分销渠道表
 *
 * @version       v0.01
 * @createtime    2018/11/21
 * @updatetime    2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_web_distribution extends Table
{


    private static $pre = "distribution_";

    static protected function struct($data)
    {
        $r = array();
        $r['id'] = $data['distribution_id'];
        $r['title'] = $data['distribution_title'];
        $r['detail'] = $data['distribution_detail'];
        $r['address'] = $data['distribution_address'];
        $r['picurl'] = $data['distribution_picurl'];
        $r['tel'] = $data['distribution_tel'];
        $r['time'] = $data['distribution_time'];
        $r['tel'] = $data['distribution_tel'];
        $r['province'] = $data['distribution_province'];
        $r['city'] = $data['distribution_city'];
        $r['lat'] = $data['distribution_lat'];
        $r['lng'] = $data['distribution_lng'];
        $r['weight'] = $data['distribution_weight'];
        $r['is_good'] = $data['distribution_is_good'];
        $r['http'] = $data['distribution_http'];
        $r['count'] = $data['distribution_count'];
        return $r;
    }


    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id" => "number",
            "title" => "string",
            "detail" => "string",
            "address" => "string",
            "picurl" => "string",
            "time" => "number",
            "lat" => "number",
            "lng" => "number",
            "tel" => "string",
            "province" => "number",
            "city" => "number",
            "weight" => "number",
            "count" => "number"
        );

        return isset($typeAttr[$attr]) ? $typeAttr[$attr] : $typeAttr;
    }


    static public function add($attrs)
    {

        global $mypdo;

        $params = array();
        foreach ($attrs as $key => $value) {
            $type = self::getTypeByAttr($key);
            $params[self::$pre . $key] = array($type, $value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix . 'web_distribution', $params);
        return $r;
    }


    static public function update($id, $attrs)
    {

        global $mypdo;

        $params = array();
        foreach ($attrs as $key => $value) {
            $type = self::getTypeByAttr($key);
            $params[self::$pre . $key] = array($type, $value);

        }
        //where条件
        $where = array(
            "distribution_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix . 'web_distribution', $params, $where);
        return $r;
    }

    /**
     * Table_web_distribution::getInfoById() 根据ID获取详情
     *
     * @param Integer $id 项目ID
     *
     * @return
     */
    static public function getInfoById($id)
    {
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from " . $mypdo->prefix . "web_distribution where distribution_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
            return $r[0];
        } else {
            return $r;
        }
    }


    /**
     * Table_web_distribution::getPageList() 根据条件列出所有信息
     * @param number $page 起始页，$page为0时取出全部
     * @param number $pagesize 页面大小
     * @param number $count 0数量1列表
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $province, $city)
    {
        global $mypdo;

        $where = " where 1 = 1 ";
        if (!empty($province)) {
            $where .= " and distribution_province = $province ";
        }
        if (!empty($city)) {
            $where .= " and distribution_city = $city ";
        }
        if ($count == 0) {
            $sql = "select count(1) as ct from " . $mypdo->prefix . "web_distribution" . $where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        } else {
            $sql = "select * from " . $mypdo->prefix . "web_distribution" . $where;
            $sql .= " order by distribution_weight desc,distribution_time asc ";

            $limit = "";
            if (!empty($page)) {
                $start = ($page - 1) * $pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if ($rs) {
                foreach ($rs as $key => $val) {
                    $r[$key] = self::struct($val);
                }
            }

            return $r;
        }
    }

    /**
     * Table_web_distribution::del() 根据ID删除数据
     *
     * @param Integer $id 项目ID
     *
     * @return
     */
    static public function del($id)
    {

        global $mypdo;

        $where = array(
            "distribution_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix . 'web_distribution', $where);
    }


    /**
     *
     *
     * 获取所有数据
     */
    static public function getList($params)
    {
        global $mypdo;

        $sql = "select * from " . $mypdo->prefix . "web_distribution";

        $where = " where 1=1";

        if (!empty($params['province'])) {
            $where .= " and distribution_province={$params['province']}";
        }
        if (!empty($params['city'])) {
            $where .= " and distribution_city={$params['city']}";
        }

        $sql .= $where;

        $sql .= " order by distribution_weight desc,distribution_id desc";


        $limit = "";

        if (!empty($params["page"])) {
            $start = ($params["page"] - 1) * $params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }

            return $r;
        } else {
            return $r;
        }

    }


    /**
     *
     * 获取页面总数据条数
     *
     */
    static public function getAllCount($params)
    {
        global $mypdo;

        $where = " where 1=1";
        $sql = "select count(1) as act from " . $mypdo->prefix . "web_distribution";
        if (!empty($params['province'])) {
            $where .= " and distribution_province={$params['province']}";
        }
        if (!empty($params['city'])) {
            $where .= " and distribution_city={$params['city']}";
        }

        $sql .= $where;

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {

            return $rs[0]["act"];
        } else {
            return $r;
        }

    }

    static public function getListQianDuan($params)
    {
        global $mypdo;

        $sql = "select * from " . $mypdo->prefix . "web_distribution";

        $where = " where distribution_is_good=1";

        $sql .= $where;

        $sql .= " order by distribution_weight desc, distribution_id desc";


        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }

            return $r;
        } else {
            return $r;
        }

    }

    static public function getOtherList()
    {
        global $mypdo;

        $sql = "select * from " . $mypdo->prefix . "web_distribution";

        $where = " where distribution_is_good = -1";

        $sql .= $where;

        $sql .= " order by distribution_weight desc, distribution_id desc";


        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }

            return $r;

        } else {
            return $r;
        }
    }


    static public function getDistrict($params){
        global $mypdo;

        $sql="select distribution_id as id,distribution_title as title,distribution_lat as lat,distribution_lng as lng from ".$mypdo->prefix."web_distribution";

        $where=" where 1=1";

        if(!empty($params['province'])){
            $where.=" and distribution_province={$params['province']}";
        }
        if(!empty($params['city'])){
            $where.=" and distribution_city={$params['city']}";
        }

        $sql .= $where;

        $sql.=" order by distribution_weight desc,distribution_id desc";



        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

        $rs=$mypdo->sqlQuery($sql);
        return $rs;
//        $r=array();
//        if($rs){
//            foreach ($rs as $key => $val){
//                $r[$key]=self::struct($val);
//            }
//
//            return $r;
//        }else{
//            return $r;
//        }

    }

}
?>