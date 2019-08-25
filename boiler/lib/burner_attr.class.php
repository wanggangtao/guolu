<?php

/**
 * burner_attr.class.php 燃烧器类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Burner_attr {

    public function __construct() {

    }

    /**
     * Burner_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_burner_attr::add($attrs);

        return $id;
    }

    /**
     * Burner_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_burner_attr::getInfoById($id);
    }

    /**
     * Burner_attr::getInfoByProid() 根据产品ID获取详情
     * @param $proid
     * @return mixed
     */
    static public function getInfoByProid($proid){
        return Table_burner_attr::getInfoByProid($proid);
    }

    /**
     * Burner_attr::getInfoByGuoluid() 根据锅炉ID获取详情
     * @param $guoluid
     * @return mixed
     */
    static public function getInfoByGuoluid($guoluid){
        return Table_burner_attr::getInfoByGuoluid($guoluid);
    }
    /**
     * Burner_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_burner_attr::update($id, $attrs);
    }

    /**
     * Burner_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_burner_attr::del($id);
    }

    /**
     * Burner_attr::getList() 根据条件列出所有信息
     * @param number $page              起始页
     * @param number $pagesize          页面大小
     * @param number $count             0数量1列表
     * @param string $vender            厂家
     * @param number $is_lownigtrogen   是否低氮
     * @return mixed
     */
    static public function getList($page, $pageSize, $count, $vender, $is_lownigtrogen){
        return Table_burner_attr::getList($page, $pageSize, $count, $vender, $is_lownigtrogen);
    }
}
?>