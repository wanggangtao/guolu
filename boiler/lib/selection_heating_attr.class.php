<?php

/**
 * selection_heating_attr.class.php 采暖属性类
 *
 * @version       v0.01
 * @create time   2018/7/18
 * @update time   2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Selection_heating_attr {

    public function __construct() {

    }

    /**
     * Selection_heating_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_selection_heating_attr::add($attrs);

        return $id;
    }

    /**
     * Selection_heating_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_selection_heating_attr::getInfoById($id);
    }

    /**
     * Selection_heating_attr::getInfoByHistoryId() 根据项目ID获取详情
     * @param $hisid  历史ID
     * @return mixed
     */
    static public function getInfoByHistoryId($hisid){
        return Table_selection_heating_attr::getInfoByHistoryId($hisid);
    }

    /**
     * Selection_heating_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_selection_heating_attr::update($id, $attrs);
    }

    /**
     * Selection_heating_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_selection_heating_attr::del($id);
    }

    /**
     * Selection_heating_attr::delByHistoryId() 根据项目ID删除数据
     * @param $hisid  历史ID
     * @return mixed
     */
    static public function delByHistoryId($hisid){
        return Table_selection_heating_attr::delByHistoryId($hisid);
    }
}
?>