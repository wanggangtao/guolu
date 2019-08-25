<?php

/**
 * dirt_separator_attr.class.php 除污器类
 *
 * @version       v0.01
 * @create time   2018/5/29
 * @update time   2018/5/29
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Dirt_separator_attr {

    public function __construct() {

    }

    /**
     * Dirt_separator_attr::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_dirt_separator_attr::add($attrs);

        return $id;
    }

    /**
     * Dirt_separator_attr::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_dirt_separator_attr::getInfoById($id);
    }

    /**
     * Dirt_separator_attr::getInfoByDN() 根据直径获取详情
     *
     * @param Integer $dn  除污器直径
     *
     * @return
     */
    static public function getInfoByDN($dn){
        return Table_dirt_separator_attr::getInfoByDN($dn);
    }

    /**
     * Dirt_separator_attr::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_dirt_separator_attr::update($id, $attrs);
    }

    /**
     * Dirt_separator_attr::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_dirt_separator_attr::del($id);
    }

    /**
     * Dirt_separator_attr::getList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return mixed
     */
    static public function getList($page, $pageSize, $count){
        return Table_dirt_separator_attr::getList($page, $pageSize, $count);
    }
}
?>