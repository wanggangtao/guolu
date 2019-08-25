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

class Category {

    public function __construct() {

    }

    /**
     * Category::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_category::add($attrs);

        return $id;
    }

    /**
     * Category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_category::getInfoById($id);
    }

    /**
     * Category::getInfoByParentid() 根据父类ID获取详情
     * @param $parentid  父类ID
     * @return mixed
     */
    static public function getInfoByParentid($parentid){
        return Table_category::getInfoByParentid($parentid);
    }

    /**
     * Category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_category::update($id, $attrs);
    }

    /**
     * Category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_category::del($id);
    }

    /**
     * Category::delByParentid() 根据父类ID删除数据
     * @param $parentid  父类别ID
     * @return mixed
     */
    static public function delByParentid($parentid){
        return Table_category::delByParentid($parentid);
    }

    /**
     * Category::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $parentid         父类ID
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $parentid){
        return Table_category::getPageList($page, $pageSize, $count, $parentid);
    }
}
?>