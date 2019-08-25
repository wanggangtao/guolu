<?php

/**
 * heat_exchanger_attr.class.php 换热器类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Heat_exchanger_attr {

    public function __construct() {

    }

    /**
     * Heat_exchanger_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_heat_exchanger_attr::add($attrs);

        return $id;
    }

    /**
     * Heat_exchanger_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_heat_exchanger_attr::getInfoById($id);
    }

    /**
     * Heat_exchanger_attr::getInfoByProid() 根据产品ID获取详情
     * @param $proid
     * @return mixed
     */
    static public function getInfoByProid($proid){
        return Table_heat_exchanger_attr::getInfoByProid($proid);
    }

    /**
     * Heat_exchanger_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_heat_exchanger_attr::update($id, $attrs);
    }

    /**
     * Heat_exchanger_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_heat_exchanger_attr::del($id);
    }

    /**
     * Heat_exchanger_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return mixed
     */
    static public function getList($page, $pageSize, $count){
        return Table_heat_exchanger_attr::getList($page, $pageSize, $count);
    }
}
?>