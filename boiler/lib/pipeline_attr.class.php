<?php

/**
 * pipeline_attr.class.php 管道泵类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Pipeline_attr {

    public function __construct() {

    }

    /**
     * Pipeline_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_pipeline_attr::add($attrs);

        return $id;
    }

    /**
     * Pipeline_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_pipeline_attr::getInfoById($id);
    }

    /**
     * Pipeline_attr::getInfoByFlowLift($flow, $lift) 根据流量和扬程获取详情
     *
     * @param Integer $flow
     *
     * @param Integer $lift
     *
     * @return
     */
    static public function getInfoByFlowLift($flow, $lift){
        return Table_pipeline_attr::getInfoByFlowLift($flow, $lift);
    }

    /**
     * Pipeline_attr::getInfoByProid() 根据产品ID获取详情
     * @param $proid
     * @return mixed
     */
    static public function getInfoByProid($proid){
        return Table_pipeline_attr::getInfoByProid($proid);
    }

    /**
     * Pipeline_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_pipeline_attr::update($id, $attrs);
    }

    /**
     * Pipeline_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_pipeline_attr::del($id);
    }

    /**
     * Pipeline_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $vender            厂家
     * @return mixed
     */
    static public function getList($page, $pageSize, $count, $vender){
        return Table_pipeline_attr::getList($page, $pageSize, $count, $vender);
    }
}
?>