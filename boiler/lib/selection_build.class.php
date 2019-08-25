<?php

/**
 * selection_build.class.php 建筑类型类
 *
 * @version       v0.01
 * @create time   2018/7/18
 * @update time   2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Selection_build {

    public function __construct() {

    }

    /**
     * Selection_build::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_selection_build::add($attrs);

        return $id;
    }

    /**
     * Selection_build::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_selection_build::getInfoById($id);
    }

    /**
     * Selection_build::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_selection_build::update($id, $attrs);
    }

    /**
     * Selection_build::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_selection_build::del($id);
    }

    /**
     * Selection_build::delByParentid() 根据父类ID删除数据
     * @param $parentid  父类别ID
     * @return mixed
     */
    static public function delByParentid($parentid){
        return Table_selection_build::delByParentid($parentid);
    }

    /**
     * Selection_build::getInfoByParentid() 根据父类ID获取详情
     * @param $parentid   父类别ID
     * @return mixed
     */
    static public function getInfoByParentid($parentid){
        return Table_selection_build::getInfoByParentid($parentid);
    }

    /**
     * Selection_build::getListByParentid() 根据父类ID获取详情
     * @param $parentid   父类别ID
     * @return mixed
     */
    static public function getListByParentid($parentid){
    	return Table_selection_build::getListByParentid($parentid);
    }

}
?>