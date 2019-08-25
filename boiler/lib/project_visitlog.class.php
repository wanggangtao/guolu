<?php

/**
 * project_visitlog.class.php 拜访记录类
 *
 * @version       v0.01
 * @create time   2018/6/24
 * @update time   2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_visitlog {

    public function __construct() {

    }

    /**
     * Project_visitlog::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_visitlog::add($attrs);

        return $id;
    }

    /**
     * Project_visitlog::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_visitlog::getInfoById($id);
    }

    /**
     * Project_visitlog::getInfoNew() 取得最新记录
     * @param $prid
     * @return mixed
     */
    static public function getInfoNew($prid){
        return Table_project_visitlog::getInfoNew($prid);
    }

    /**
     * Project_visitlog::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_visitlog::update($id, $attrs);
    }

    /**
     * Project_visitlog::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_visitlog::del($id);
    }

    /**
     * Project_visitlog::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @param string $target           拜访对象
     * @param number $way              拜访方式
     * @param number $stday            开始日期
     * @param number $endday           结束日期
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $projectid, $target, $way, $stday, $endday,$content=""){
        return Table_project_visitlog::getPageList($page, $pageSize, $count, $projectid, $target, $way, $stday, $endday,$content);
    }
}
?>