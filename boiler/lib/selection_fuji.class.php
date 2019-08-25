<?php

/**
 * selection_fuji.class.php 辅机类
 *
 * @version       v0.01
 * @create time   2018/7/18
 * @update time   2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Selection_fuji {

    public function __construct() {

    }

    /**
     * Selection_fuji::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_selection_fuji::add($attrs);

        return $id;
    }

    /**
     * Selection_fuji::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_selection_fuji::getInfoById($id);
    }

    /**
     * Selection_fuji::getInfoByHistoryId() 根据项目ID获取详情
     * @param $hisid  历史ID
     * @param $type  使用类型
     * @return mixed
     */
    static public function getInfoByHistoryId($hisid, $type){
        return Table_selection_fuji::getInfoByHistoryId($hisid, $type);
    }

    /**
     * @param $hisid
     * @return mixed
     */
    static public function getInfoByHistory_Id($hisid){
        return Table_selection_fuji::getInfoByHistory_Id($hisid);
    }



    /**
     * Selection_fuji::getInfoByHistoryId() 根据项目ID获取详情
     * @param $hisid  历史ID
     * @param Integer $addtype  是否添加到方案中
     * @return mixed
     */
    static public function getListByHistoryId($hisid, $addtype){
        return Table_selection_fuji::getListByHistoryId($hisid, $addtype);
    }

    /**
     * Selection_fuji::getInfoByHistoryId() 根据项目ID获取详情
     * @param $hisid  历史ID
     * @param $type  使用类型
     * @param $addtype  是否添加到方案中
     * @return mixed
     */
    static public function getInfoByHistoryIdandAddtype($hisid, $type,$addtype){
        return Table_selection_fuji::getInfoByHistoryIdandAddtype($hisid, $type,$addtype);
    }

    /**
     * Selection_fuji::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_selection_fuji::update($id, $attrs);
    }

    /**
     * Selection_fuji::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_selection_fuji::del($id);
    }

    /**
     * Selection_fuji::delByHistoryId() 根据项目ID删除数据
     * @param $hisid  历史ID
     * @return mixed
     */
    static public function delByHistoryId($hisid){
        return Table_selection_fuji::delByHistoryId($hisid);
    }

    /**
     * @param $id通过辅机名选择进行回显
     * @param $name
     */
    static public function getInfoByName($id,$name,$type){
        return Table_selection_fuji::getInfoByName($id,$name,$type);
    }
}
?>