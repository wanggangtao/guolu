<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-03
 * Time: 21:02
 */

class Smallguolu {


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_smallguolu_attr::add($attrs);

        return $id;
    }


    static public function getInfoById($id){
        return Table_smallguolu_attr::getInfoById($id);
    }


    static public function update($id, $attrs){
        return Table_smallguolu_attr::update($id, $attrs);
    }


    static public function del($id){
        return Table_smallguolu_attr::del($id);
    }


    static public function getCount(){
        return Table_smallguolu_attr::getCount();
    }

    static public function getList($page, $pageSize, $vender){
        return Table_smallguolu_attr::getList($page, $pageSize, $vender);
    }

    static public function getInfoByName($name){
        return Table_smallguolu_attr::getInfoByName($name);
    }

    static public function getInfoByVenderId($venderId){
        return Table_smallguolu_attr::getInfoByVenderId($venderId);
    }

}