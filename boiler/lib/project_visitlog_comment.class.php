<?php

/**
 * project_visitlog_comment.class.php 拜访记录评论类
 *
 * @version       v0.01
 * @create time   2018/8/16
 * @update time   2018/8/16
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_visitlog_comment {

    public function __construct() {

    }

    /**
     * Project_visitlog_comment::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_visitlog_comment::add($attrs);

        return $id;
    }

    /**
     * Project_visitlog_comment::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_visitlog_comment::getInfoById($id);
    }

    /**
     * Project_visitlog_comment::getInfoByVisitlogid() 根据拜访记录ID获取详情
     * @param $visitlog_id
     * @return mixed
     */
    static public function getInfoByVisitlogid($visitlog_id){
        return Table_project_visitlog_comment::getInfoByVisitlogid($visitlog_id);
    }

    /**
     * Project_visitlog_comment::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_visitlog_comment::update($id, $attrs);
    }

    /**
     * Project_visitlog_comment::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_visitlog_comment::del($id);
    }
}
?>