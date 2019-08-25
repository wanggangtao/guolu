<?php

/**
 * project_two_record.class.php 项目第二阶段修改记录类
 *
 * @version       v0.01
 * @create time   2018/6/24
 * @update time   2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_two_record {

    public function __construct() {

    }

    /**
     * Project_two_record::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_two_record::add($attrs);

        return $id;
    }

    /**
     * Project_two_record::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_two_record::getInfoById($id);
    }

    /**
     * Project_two_record::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_two_record::update($id, $attrs);
    }

    /**
     * Project_two_record::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_two_record::del($id);
    }

    /**
     * Project_two_record::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $projectid){
        return Table_project_two_record::getPageList($page, $pageSize, $count, $projectid);
    }

    static public function getLastIdByProject($projectid){
        return Table_project_two_record::getLastIdByProject($projectid);
    }
}
?>