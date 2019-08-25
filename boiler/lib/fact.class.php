<?php

/**
 * adminlog.class.php 管理员日志类
 *
 * @version       v0.01
 * @create time   2014/09/09
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class fact {

    public function __construct() {

    }


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_fact::add($attrs);
        $code = self::getFactCode($id);
        Table_fact::update($id, array("code"=>$code));
        return $id;
    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_fact::getInfoById($id);
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function getInfoByCode($code){
        return Table_fact::getInfoByCode($code);
    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_fact::update($id, $attrs);
    }


    static public function del($id){
        return Table_fact::del($id);
    }

    static public function getList(){
        return Table_fact::getList();
    }


    static public function getCount(){
        return Table_fact::getCount();
    }


    static public function getPageList($page,$pageSize,$content,$category=0){
        return Table_fact::getPageList($page,$pageSize,$content,$category);
    }


    static public function getFactCode($id){
        $var=sprintf("%05d", $id);
        return "F".$var;
    }

    static public function getBegin(){

        return Table_fact::getBegin();
    }

}
?>