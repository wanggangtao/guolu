<?php

/**
 * category.class.php 产品类别类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Community {

    public function __construct() {

    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_community::add($attrs);

        return $id;
    }

    /**
     * Category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_community::getInfoById($id);
    }

    /**
     * Category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_community::update($id, $attrs);
    }

    /**
     * Category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_community::dels($id);
    }



    static public function getList($params=array()){
        return Table_community::getList($params);
    }


    static public function getNameList($params=array()){
        return Table_community::getNameList($params);
    }

    static public function getCount($params=array()){
        return Table_community::getCount($params);
    }
    static public function getCommunityById($id){
        return Table_community::getCommunityById($id);
    }

    static public function getListByFC($params=array()){
        return Table_community::getListByFC($params);
    }

    static public function geFCtListByFC($params=array()){
        return Table_community::geFCtListByFC($params);
    }
    static public function getCommunityByAddress($provice,$city,$area,$type = 1){
        return Table_community::getCommunityByAddress($provice,$city,$area,$type);
    }
    static public function updateType($id){
        return Table_community::updateType($id);
    }

    static public function getInfoByName($name){
        return Table_community::getInfoByName($name);
    }

}
?>