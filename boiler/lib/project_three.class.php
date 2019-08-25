<?php

/**
 * project_three.class.php 项目第三阶段类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_three {

    public function __construct() {

    }

    static public function Init(){
        $r = array();
        $r['id']                          = 0;
        $r['project_id']                  = '';
        $r['competitive_brand_situation'] = '';
        $r['progress_situation']          = '';
        $r['invitation_situation']        = '';
        $r['other_situation']             = '';
        $r['addtime']                     = '';
        $r['lastupdate']                  = '';

        return $r;
    }

    /**
     * Project_three::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_three::add($attrs);

        return $id;
    }

    /**
     * Project_three::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_three::getInfoById($id);
    }

    /**
     * Project_three::getInfoByProjectId() 根据项目ID获取详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoByProjectId($prid){
        return Table_project_three::getInfoByProjectId($prid);
    }

    /**
     * Project_three::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_three::update($id, $attrs);
    }

    /**
     * Project_three::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_three::del($id);
    }

    /**
     * Project_three::delByProjectId() 根据项目ID删除数据
     * @param $prid            项目ID
     * @return mixed
     */
    static public function delByProjectId($prid){
        return Table_project_three::delByProjectId($prid);
    }
}
?>