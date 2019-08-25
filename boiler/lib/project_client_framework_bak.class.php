<?php

/**
 * project_client_framework_bak.class.php 项目第二阶段组织架构备份类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_client_framework_bak {

    public function __construct() {

    }

    /**
     * Project_client_framework_bak::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_client_framework_bak::add($attrs);

        return $id;
    }

    /**
     * Project_client_framework_bak::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_client_framework_bak::getInfoById($id);
    }

    /**
     * Project_client_framework_bak::getInfoByPtId() 根据项目ID获取详情
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function getInfoByPtId($ptid){
        return Table_project_client_framework_bak::getInfoByPtId($ptid);
    }

    /**
     * Project_client_framework_bak::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_client_framework_bak::update($id, $attrs);
    }

    /**
     * Project_client_framework_bak::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_client_framework_bak::del($id);
    }

    /**
     * Project_client_framework_bak::delByProjectId() 根据项目ID删除数据
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function delByPtId($ptid){
        return Table_project_client_framework_bak::delByPtId($ptid);
    }
}
?>