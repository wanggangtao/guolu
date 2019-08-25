<?php

/**
 * products.class.php 产品类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Products {

    public function __construct() {

    }

    /**
     * Products::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_products::add($attrs);

        return $id;
    }

    /**
     * Products::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_products::getInfoById($id);
    }

    /**
     * Products::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_products::update($id, $attrs);
    }

    /**
     * Products::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_products::del($id);
    }

    /**
     * Products::getList() 根据条件列出所有信息
     * @param number $start             起始行
     * @param number $pagesize          页面大小
     * @param number $count             0数量1列表
     * @param number $modelid           模型ID
     * @return mixed
     */
    static public function getList($start, $pageSize, $count, $modelid, $attrname, $vender=0, $is_lownigtrogen=0){
        return Table_products::getPageList($start, $pageSize, $count, $modelid, $attrname, $vender, $is_lownigtrogen);
    }
}
?>