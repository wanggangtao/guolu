<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:25
 */

class Repair_person {

    public function __construct() {

    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_repair_person::add($attrs);

        return $id;
    }

    /**
     * Category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_repair_person::getInfoById($id);
    }

    /**
     * Category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_repair_person::update($id, $attrs);
    }

    /**
     * Category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_repair_person::dels($id);
    }



    static public function getList($params=array()){
        return Table_repair_person::getList($params);
    }

    static public function getCount($params=array()){
        return Table_repair_person::getCount($params);
    }

    static public function getIdByLikeName($name){
        return Table_repair_person::getIdByLikeName($name);
    }

    static public function getPhoneByLikeName($name){
        return Table_repair_person::getPhoneByLikeName($name);
    }

    }