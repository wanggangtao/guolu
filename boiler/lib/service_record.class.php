<?php

/**
 * Service_record.class.php 聊天信息表类
 *
 * @version       v0.01
 * @create time   2019/3/20
 * @update time   2019/3/20
 * @author        GuanXin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Service_record {

    public function __construct() {

    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_service_record::add($attrs);

        return $id;
    }

    /**
     * Service_record::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_service_record::getInfoById($id);
    }

    /**
     * Service_record::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_service_record::update($id, $attrs);
    }

    /**
     * Service_record::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_service_record::del($id);
    }



    static public function getList($params=array()){
        return Table_service_record::getList($params);
    }

    static public function getCount($params=array()){
        return Table_service_record::getCount($params);
    }
//
//    static public function getInfoByBarCode($info_code){
//        return Table_service_record::getInfoByBarCode($info_code);
//    }
    static public function getListByOpenId($params){
        return Table_service_record::getListByOpenId($params);
    }

    static public function getCountByOpenId ($params){
        return Table_service_record::getCountByOpenId($params);
    }
}
?>