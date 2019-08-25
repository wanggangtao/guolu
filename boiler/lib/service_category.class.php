<?php

/**
 * Service_category.class.php 问题类别类
 *
 * @version       v0.01
 * @create time   2019/3/20
 * @update time   2019/3/20
 * @author        GuanXin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Service_category {

    public function __construct() {

    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_service_category::add($attrs);

        return $id;
    }

    static public function getCount(){
        return Table_service_category::getCount();
    }

    static public function getPageList($params=array()){
        return Table_service_category::getPageList($params);
    }

    /**
     * Service_category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_service_category::getInfoById($id);
    }

    /**
     * Service_category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_service_category::update($id, $attrs);
    }

    /**
     * Service_category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_service_category::del($id);
    }

}
?>