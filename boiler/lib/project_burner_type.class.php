<?php

/**
 * project_burner_type.class.php 项目第一阶段燃烧器类型类
 *
 * @version       v0.01
 * @create time   2018/6/30
 * @update time   2018/6/30
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_burner_type {

    public function __construct() {

    }

    /**
     * Project_burner_type::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_burner_type::add($attrs);

        return $id;
    }

    /**
     * Project_burner_type::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_burner_type::getInfoById($id);
    }

    /**
     * Project_burner_type::getInfoByPoId() 根据项目ID获取详情
     * @param $poid  第一阶段关联项目ID
     * @return mixed
     */
    static public function getInfoByPoId($poid){
        return Table_project_burner_type::getInfoByPoId($poid);
    }

    /**
     * Project_burner_type::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_burner_type::update($id, $attrs);
    }

    /**
     * Project_burner_type::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_burner_type::del($id);
    }

    /**
     * Project_burner_type::delByProjectId() 根据项目ID删除数据
     * @param $poid  第一阶段关联项目ID
     * @return mixed
     */
    static public function delByPoId($poid){
        return Table_project_burner_type::delByPoId($poid);
    }
}
?>