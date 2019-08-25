<?php

/**
 * project_advice.class.php 项目建议类
 *
 * @version       v0.01
 * @create time   2018/8/23
 * @update time   2018/8/23
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_advice {

    public function __construct() {

    }

    /**
     * Project_advice::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_advice::add($attrs);

        return $id;
    }

    /**
     * Project_advice::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_advice::getInfoById($id);
    }


    /**
     * Project_advice::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_advice::update($id, $attrs);
    }

    /**
     * Project_advice::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_advice::del($id);
    }

    /**
     * Project_advice::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $projectid        项目ID
     * @param string $content          内容
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $projectid, $content=""){
        return Table_project_advice::getPageList($page, $pageSize, $count, $projectid, $content);
    }
}
?>