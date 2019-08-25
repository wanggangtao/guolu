<?php

/**
 * project_two.class.php 项目第二阶段类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_two {

    public function __construct() {

    }
    static public function Init(){
        $r = array();
        $r['id']                          = 0;
        $r['project_id']                  = '';
        $r['addtime']                     = '';
        $r['lastupdate']                  = '';

        return $r;
    }
    /**
     * Project_two::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_two::add($attrs);

        return $id;
    }

    /**
     * Project_two::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_two::getInfoById($id);
    }

    /**
     * Project_two::getInfoByProjectId() 根据项目ID获取详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoByProjectId($prid){
        return Table_project_two::getInfoByProjectId($prid);
    }

    /**
     * Project_two::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_two::update($id, $attrs);
    }

    /**
     * Project_two::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_two::del($id);
    }

    /**
     * Project_two::delByProjectId() 根据项目ID删除数据
     * @param $prid            项目ID
     * @return mixed
     */
    static public function delByProjectId($prid){
        return Table_project_two::delByProjectId($prid);
    }
}
?>