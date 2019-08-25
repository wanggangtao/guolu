<?php

/**
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Hdys_attr {

    public function __construct() {

    }

    /**
     * Hdys_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_hdys_attr::add($attrs);

        return $id;
    }

    /**
     * Hdys_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_hdys_attr::getInfoById($id);
    }

    /**
     * Hdys_attr::getInfoByWeight() 根据出水量获取详情，选择辅机时用到
     *
     * @param Integer $weight  自动软水机出水量
     *
     * @return
     */
    static public function getInfoByWeight($weight){
        return Table_hdys_attr::getInfoByWeight($weight);
    }

    /**
     * Hdys_attr::getInfoByProid() 根据产品ID获取详情
     * @param $proid
     * @return mixed
     */
    static public function getInfoByProid($proid){
        return Table_hdys_attr::getInfoByProid($proid);
    }

    /**
     * Hdys_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_hdys_attr::update($id, $attrs);
    }

    /**
     * Hdys_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_hdys_attr::del($id);
    }

    /**
     * Hdys_attr::getList() 根据条件列出所有信息
     * @param number $page              起始页
     * @param number $pagesize          页面大小
     * @param number $count             0数量1列表
     * @return mixed
     */
    static public function getList($page, $pageSize, $count){
        return Table_hdys_attr::getList($page, $pageSize, $count);
    }
}
?>