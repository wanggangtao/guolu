<?php

/**
 * project_one_bak.class.php 项目第一阶段备份类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_one_bak {

    public function __construct() {

    }

    /**
     * Project_one_bak::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_one_bak::add($attrs);

        return $id;
    }

    /**
     * Project_one_bak::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_one_bak::getInfoById($id);
    }

    /**
     * Project_one_bak::getInfoByProjectId() 根据项目ID获取详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoByProjectId($prid){
        return Table_project_one_bak::getInfoByProjectId($prid);
    }

    /**
     * Project_one_bak::getInfoNewRecodeByPrid() 根据项目ID获取最新记录详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoNewRecodeByPrid($prid){
        return Table_project_one_bak::getInfoNewRecodeByPrid($prid);
    }

    /**
     * Project_one_bak::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_one_bak::update($id, $attrs);
    }

    /**
     * Project_one_bak::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_one_bak::del($id);
    }

    /**
     * Project_one_bak::delByProjectId() 根据项目ID删除数据
     * @param $prid            项目ID
     * @return mixed
     */
    static public function delByProjectId($prid){
        return Table_project_one_bak::delByProjectId($prid);
    }
}
?>