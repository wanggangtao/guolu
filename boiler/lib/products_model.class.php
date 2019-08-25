<?php

/**
 * products_model.class.php 产品对应模型类
 *
 * @version       v0.01
 * @create time   2018/5/30
 * @update time   2018/5/30
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Products_model {

    public function __construct() {

    }

    /**
     * Products_model::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_products_model::getInfoById($id);
    }

    /**
     * Products_model::getInfoByCategory() 根据ID获取详情
     * @param $categoryid
     * @return mixed
     */
    static public function getInfoByCategory($categoryid){
        return Table_products_model::getInfoByCategory($categoryid);
    }
}
?>