<?php

/**
 * selection_hotwater_attr.class.php 采暖属性类
 *
 * @version       v0.01
 * @create time   2018/7/18
 * @update time   2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Selection_hotwater_attr {

    public function __construct() {

    }

    /**
     * Selection_hotwater_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_selection_hotwater_attr::add($attrs);

        return $id;
    }

    /**
     * Selection_hotwater_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_selection_hotwater_attr::getInfoById($id);
    }

    /**
     * Selection_hotwater_attr::getInfoByHistoryId() 根据项目ID获取详情
     * @param $hisid  历史ID
     * @return mixed
     */
    static public function getInfoByHistoryId($hisid, $parmid){
        return Table_selection_hotwater_attr::getInfoByHistoryId($hisid, $parmid);
    }

    static public function getCopyByHistoryId($hisid){
        return Table_selection_hotwater_attr::getCopyByHistoryId($hisid);
    }

    /**
     * Selection_hotwater_attr::getInfoByHistoryId() 根据项目ID获取详情
     * @param $hisid  历史ID
     * @return mixed
     */
    static public function getParamByHistoryId($hisid){
        return Table_selection_hotwater_attr::getParamByHistoryId($hisid);
    }

    /**
     * Selection_hotwater_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_selection_hotwater_attr::update($id, $attrs);
    }

    /**
     * Selection_hotwater_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_selection_hotwater_attr::del($id);
    }

    /**
     * Selection_hotwater_attr::delByHistoryId() 根据项目ID删除数据
     * @param $hisid  历史ID
     * @return mixed
     */
    static public function delByHistoryId($hisid){
        return Table_selection_hotwater_attr::delByHistoryId($hisid);
    }

    /**
     * @param $hisid
     * @return int
     * 根据历史id找最大的paramid，控制循环次数
     */
    static public function getMaxParamId($hisid){
        return Table_selection_hotwater_attr::getMaxParamId($hisid);
    }

    /**
     * @param $hisid
     * @param $parmid
     * @return array
     * 找属性个数
     */
    static public function getAttrCount($hisid, $parmid){
        return Table_selection_hotwater_attr::getAttrCount($hisid,$parmid);
    }
}

?>