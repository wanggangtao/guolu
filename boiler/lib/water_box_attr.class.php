<?php

/**
 * water_box_attr.class.php 水箱类
 *
 * @version       v0.01
 * @create time   2018/5/29
 * @update time   2018/5/29
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Water_box_attr {

    public function __construct() {

    }

    /**
     * Water_box_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_water_box_attr::add($attrs);

        return $id;
    }

    /**
     * Water_box_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_water_box_attr::getInfoById($id);
    }

    /**
     * Water_box_attr::getInfoByWeight() 根据重量获取详情
     *
     * @param Integer $weight  水箱重量
     *
     * @return
     */
    static public function getInfoByCapacity($capacity){
        return Table_water_box_attr::getInfoByCapacity($capacity);
    }

    /**
     * Water_box_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_water_box_attr::update($id, $attrs);
    }

    /**
     * Water_box_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_water_box_attr::del($id);
    }

    /**
     * Water_box_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return mixed
     */
    static public function getList($page, $pageSize, $count){
        return Table_water_box_attr::getList($page, $pageSize, $count);
    }
}
?>