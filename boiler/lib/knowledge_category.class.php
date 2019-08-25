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

class knowledge_category {

    public function __construct() {

    }


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_knowledge_category::add($attrs);

        return $id;
    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_knowledge_category::getInfoById($id);
    }



    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_knowledge_category::update($id, $attrs);
    }


    static public function del($id){
        return Table_knowledge_category::del($id);
    }

    static public function getList(){
        return Table_knowledge_category::getList();
    }


    static public function getCount(){
        return Table_knowledge_category::getCount();
    }


    static public function getPageList($page,$pageSize){
        return Table_knowledge_category::getPageList($page,$pageSize);
    }


    static public function getFactCode($id){
        $var=sprintf("%05d", $id);
        return "F".$var;
    }

}
?>
