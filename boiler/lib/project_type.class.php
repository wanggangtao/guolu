<?php

/**
 * project_type.class.php 项目类别类
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time   2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_type {

    public function __construct() {

    }

    /**
     * Project_type::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_type::add($attrs);

        return $id;
    }

    /**
     * Project_type::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_type::getInfoById($id);
    }

    /**
     * Project_type::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_type::update($id, $attrs);
    }

    /**
     * Project_type::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_type::del($id);
    }

    /**
     * Project_type::getAllList() 列出所有信息
     * @return mixed
     */
    static public function getAllList(){
        return Table_project_type::getAllList();
    }
}
?>