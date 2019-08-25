<?php

/**
 * project_pictures.class.php 图片库类
 *
 * @version       v0.01
 * @create time   2018/6/24
 * @update time   2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_pictures {

    public function __construct() {

    }

    /**
     * Project_pictures::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_pictures::add($attrs);

        return $id;
    }

    /**
     * Project_pictures::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_pictures::getInfoById($id);
    }

    /**
     * Project_pictures::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_pictures::update($id, $attrs);
    }

    /**
     * Project_pictures::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_pictures::del($id);
    }

    /**
     * Project_pictures::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @param number $type             图片类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $projectid, $typ){
        return Table_project_pictures::getPageList($page, $pageSize, $count, $projectid, $typ);
    }
}
?>