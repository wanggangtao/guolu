<?php

/**
 * syswater_pump_attr.class.php 管道泵类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Syswater_pump_attr {

    public function __construct() {

    }

    /**
     * Syswater_pump_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_syswater_pump_attr::add($attrs);

        return $id;
    }

    /**
     * Syswater_pump_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_syswater_pump_attr::getInfoById($id);
    }

    /**
     * Syswater_pump_attr::getInfoByProid() 根据产品ID获取详情
     * @param $proid
     * @return mixed
     */
    static public function getInfoByProid($proid){
        return Table_syswater_pump_attr::getInfoByProid($proid);
    }

    /**
     * Syswater_pump_attr::getInfoByFlowLift($flow, $lift) 根据流量和扬程获取详情
     *
     * @param Integer $flow
     *
     * @param Integer $lift
     *
     * @return
     */
    static public function getInfoByFlowLift($flow, $lift){
        return Table_syswater_pump_attr::getInfoByFlowLift($flow, $lift);
    }

    /**
     * Syswater_pump_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_syswater_pump_attr::update($id, $attrs);
    }

    /**
     * Syswater_pump_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_syswater_pump_attr::del($id);
    }

    /**
     * Syswater_pump_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $vender            厂家
     * @return mixed
     */
    static public function getList($page, $pageSize, $count, $vender){
        return Table_syswater_pump_attr::getList($page, $pageSize, $count, $vender);
    }
}
?>