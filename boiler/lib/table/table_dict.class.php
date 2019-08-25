<?php

/**
 * table_dict.class.php 数据库表:产品属性表
 *
 * @version       v0.01
 * @createtime    2018/5/27
 * @updatetime    2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_dict extends Table {


    private static  $pre = "dict_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['dict_id'];
        $r['name']          = $data['dict_name'];
        $r['model']         = $data['dict_model'];
        $r['code']          = $data['dict_code'];
        $r['status']        = $data['dict_status'];
        $r['parent']        = $data['dict_parent'];
        $r['addtime']       = $data['dict_addtime'];
        $r['operator']      = $data['dict_operator'];
        $r['pic']           = $data['dict_pic'];
        $r['cat']           = $data['dict_cat']; //厂商所属类别 0 无  1 工业 2 壁挂 10 全部

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "model"=>"number",
            "code"=>"string",
            "status"=>"number",
            "parent"=>"number",
            "addtime"=>"number",
            "operator"=>"number",
            "pic"=>"string"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'dict', $params);
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
            "dict_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'dict', $params, $where);
        return $r;
    }

    /**
     * Table_dict::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."dict where dict_id = $id limit 1";

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
     * Table_dict::getInfoByParentid() 根据父类ID获取详情
     *
     * @param Integer $parentid  父类ID
     *
     * @return
     */
    static public function getInfoByParentid($parentid){
        global $mypdo;

        $parentid = $mypdo->sql_check_input(array('number', $parentid));

        $sql = "select * from ".$mypdo->prefix."dict where dict_parent = $parentid limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * Table_dict::getListByParentid() 根据父类ID获取列表 
     *
     * @param Integer $parentid  父类ID
     *
     * @return
     */
    static public function getListByParentid($parentid){
        global $mypdo;
    
        $parentid = $mypdo->sql_check_input(array('number', $parentid));
    
        $sql = "select * from ".$mypdo->prefix."dict where dict_parent = $parentid ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }
    
    /**
     * Table_dict::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $parentid         父类ID
     * @param number $status           1是可使用 0禁用
     * @param number $model            关联的model表的id
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $parentid, $status, $model){
        global $mypdo;

        $where = " where 1=1 ";

        if($parentid > -1){
            $parentid = $mypdo->sql_check_input(array('number', $parentid));
            $where .= " and dict_parent = $parentid ";
        }

        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and dict_status = $status ";
        }
        if(!empty($model)){
            $model = $mypdo->sql_check_input(array('number', $model));
            $where .= " and dict_model = $model ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."dict".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."dict".$where;
            $sql .=" order by dict_id asc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
                return $r;
            }else{
                return $r;
            }
        }
    }
    
    /**
     * Table_dict::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "dict_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'dict', $where);
    }

    /**
     * Table_dict::delByParentid() 根据父类ID删除数据
     *
     * @param Integer $parentid  父类别ID
     *
     * @return
     */
    static public function delByParentid($parentid){

        global $mypdo;

        $where = array(
            "dict_parentid" => array('number', $parentid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'dict', $where);
    }




    /**
     * @param $id
     * @return array|mixed
     */
    static public function getInfoByName($name){
        global $mypdo;

        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select * from ".$mypdo->prefix."dict where dict_name = $name limit 1";

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


    static public function getListByCat($parentid,$cat){
        global $mypdo;

        $cat = $mypdo->sql_check_input(array('number', $cat));

        $sql = "select * from ".$mypdo->prefix."dict where dict_parent = $parentid and dict_cat = $cat or dict_cat = 10";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }

    }

    /**
     * @param $parentid
     * @return array
     */
    static public function getListByPid($parentid,$type){
        global $mypdo;

        $parentid = $mypdo->sql_check_input(array('number', $parentid));

        $sql = "select * from ".$mypdo->prefix."dict where dict_parent = $parentid ";

        if($type){
            $sql.= " and dict_cat =2 or dict_cat=10";
        }
        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

}
?>