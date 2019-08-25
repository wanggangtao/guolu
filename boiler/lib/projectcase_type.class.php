<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 10:20
 */
class Projectcase_type {

    public function __construct() {

    }

    static public function getList(){
        return Table_projectcase_type::getList();
    }

    static public function getCount(){
        return Table_projectcase_type::getCount();
    }
    static public function getListByOrder( $page, $pageSize, $count){
        return Table_projectcase_type::getListByOrder( $page, $pageSize, $count);
    }

    static public function getPageList($page,$pageSize){
        return Table_projectcase_type::getPageList($page,$pageSize);
    }

    static public function getInfoById($id){
        return Table_projectcase_type::getInfoById($id);
    }


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_projectcase_type::add($attrs);

        return $id;
    }

    static public function update($id, $attrs){
        return Table_projectcase_type::update($id, $attrs);
    }

    static public function del($id){
        return Table_projectcase_type::del($id);
    }
}