<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/3/20
 * Time: 10:58
 */

class Service_label {

    public function __construct() {

    }

    /**
     * @param $attrs
     * @return mixed
     * @throws Exception
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_service_label::add($attrs);

        return $id;
    }

    /**
     * @param $id
     * @return array|mixed
     *
     */
    static public function getInfoById($id){
        return Table_service_label::getInfoById($id);
    }

    /**
     * Service_label::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_service_label::update($id, $attrs);
    }

    /**
     * Service_label::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_service_label::dels($id);
    }


    /**
     * @param array $params
     * @return array
     */
    static public function getList($params=array()){
        return Table_service_label::getList($params);
    }

    static public function getFCListByBefore($params=array()){
        return Table_service_label::getFCListByBefore($params);
    }

    static public function getListByFC($params=array()){
        return Table_service_label::getListByFC($params);
    }
    /**
     * @param array $params
     * @return null
     */
    static public function getCount($params=array()){
        return Table_service_label::getCount($params);
    }

    static public function getInfoByKeyword($keyword){
        return Table_service_label::getInfoByKeyword($keyword);
    }

    static public function getInfoByBefore($before){
        return Table_service_label::getInfoByBefore($before);
    }

    static public function getInfoByAfter($after){
        return Table_service_label::getInfoByAfter($after);
    }


}