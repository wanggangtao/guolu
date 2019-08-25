<?php

/**
 * project_linkman.class.php 项目第二阶段联系人类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_linkman {

    public function __construct() {

    }

    /**
     * Project_linkman::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_linkman::add($attrs);

        return $id;
    }

    /**
     * Project_linkman::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_linkman::getInfoById($id);
    }

    /**
     * Project_linkman::getInfoByPtId() 根据项目ID获取详情
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function getInfoByPtId($ptid){
        return Table_project_linkman::getInfoByPtId($ptid);
    }

    /**
     * Project_linkman::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_linkman::update($id, $attrs);
    }

    /**
     * Project_linkman::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_linkman::del($id);
    }

    /**
     * Project_linkman::delByProjectId() 根据项目ID删除数据
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function delByPtId($ptid){
        return Table_project_linkman::delByPtId($ptid);
    }
}
?>