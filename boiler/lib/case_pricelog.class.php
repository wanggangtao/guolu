<?php

/**
 * case_pricelog.class.php 价格记录类
 *
 * @version       v0.01
 * @create time   2018/10/20
 * @update time   2018/10/20
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Case_pricelog {

    public function __construct() {

    }

    /**
     * Case_pricelog::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_case_pricelog::add($attrs);

        return $id;
    }

    /**
     * Case_pricelog::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_case_pricelog::getInfoById($id);
    }

    /**
     * Case_pricelog::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_case_pricelog::update($id, $attrs);
    }

    /**
     * Case_pricelog::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_case_pricelog::del($id);
    }

    /**
     * Case_pricelog::delByObject() 根据对象删除数据
     * @param number $type             类型
     * @param number $objectid         对象id
     * @return mixed
     */
    static public function delByObject($type, $objectid){
        return Table_case_pricelog::delByObject($type, $objectid);
    }



    /**
     * Case_pricelog::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @param number $objectid         对象id
     * @param number $addtype          添加方式
     * @param number $sttime           查询开始时间
     * @param number $endtime          查询结束时间
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $type, $objectid, $addtype, $sttime, $endtime){
        return Table_case_pricelog::getPageList($page, $pageSize, $count, $type, $objectid, $addtype, $sttime, $endtime);
    }
}
?>