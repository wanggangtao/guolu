<?php

/**
 * project_bid_company_bak.class.php 项目第四阶段招标公司备份类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_bid_company_bak {

    public function __construct() {

    }

    /**
     * Project_bid_company_bak::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_bid_company_bak::add($attrs);

        return $id;
    }

    /**
     * Project_bid_company_bak::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_bid_company_bak::getInfoById($id);
    }

    /**
     * Project_bid_company_bak::getInfoByPfId() 根据项目ID获取详情
     * @param $pfid  第四阶段关联项目ID
     * @return mixed
     */
    static public function getInfoByPfId($pfid){
        return Table_project_bid_company_bak::getInfoByPfId($pfid);
    }

    /**
     * Project_bid_company_bak::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_bid_company_bak::update($id, $attrs);
    }

    /**
     * Project_bid_company_bak::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_bid_company_bak::del($id);
    }

    /**
     * Project_bid_company_bak::delByProjectId() 根据项目ID删除数据
     * @param $pfid  第四阶段关联项目ID
     * @return mixed
     */
    static public function delByPfId($pfid){
        return Table_project_bid_company_bak::delByPfId($pfid);
    }
}
?>