<?php

/**
 * guolu_attr.class.php 锅炉类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Guolu_attr {


    public function __construct() {

    }

    /**
     * Guolu_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_guolu_attr::add($attrs);

        return $id;
    }

    /**
     * Guolu_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_guolu_attr::getInfoById($id);
    }

    /**
     * Guolu_attr::getInfoById() 根据厂家ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoByVenderId($id){
        return Table_guolu_attr::getInfoByVenderId($id);
    }

    /**
     * Guolu_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_guolu_attr::update($id, $attrs);
    }

    /**
     * Guolu_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_guolu_attr::del($id);
    }

    /**
     * Guolu_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            1数量0列表
     * @param string $vender            厂家
     * @param number $type              类型
     * @param number $is_condensate     是否冷凝
     * @param number $is_lownigtrogen   是否低氮
     * @return mixed
     */
    static public function getList($page, $pageSize, $count, $vender, $type, $is_condensate, $is_lownigtrogen){
        return Table_guolu_attr::getList($page, $pageSize, $count, $vender, $type, $is_condensate, $is_lownigtrogen);
    }
    static public function getInfoByName($name){
        return Table_guolu_attr::getInfoByName($name);
    }





    static public function checkForChange($guoluStr){
        $rs =  Table_guolu_attr::checkForChange($guoluStr);

        if(count($rs)==1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>