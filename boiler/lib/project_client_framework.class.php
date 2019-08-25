<?php

/**
 * project_client_framework.class.php 项目第二阶段组织架构类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_client_framework {

    public function __construct() {

    }

    /**
     * Project_client_framework::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_client_framework::add($attrs);

        return $id;
    }

    /**
     * Project_client_framework::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_client_framework::getInfoById($id);
    }

    /**
     * Project_client_framework::getInfoByPtId() 根据项目ID获取详情
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function getInfoByPtId($ptid){
        return Table_project_client_framework::getInfoByPtId($ptid);
    }

    /**
     * Project_client_framework::getInfoByParentId() 根据父亲ID获取详情
     *
     * @param Integer $ParentId  父亲ID
     * @return mixed
     */
    static public function getInfoByParentId($ParentId){
        return Table_project_client_framework::getInfoByParentId($ParentId);
    }
    /**
     * Project_client_framework::getInfoByPtId() 根据项目ID获取详情
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function getInfoByPtIdFirst($ptid){
        return Table_project_client_framework::getInfoByPtIdFirst($ptid);
    }
    /**
     * Project_client_framework::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_client_framework::update($id, $attrs);
    }

    /**
     * Project_client_framework::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_client_framework::del($id);
    }

    /**
     * Project_client_framework::delAllByPid() 递归删除
     *
     * @param $pid
     *
     * @return mixed
     */
    static public function delAllByPid($pid){
        $list = Table_project_client_framework::getInfoByParentId($pid);
        if($list){
            foreach ($list as $thisOne){
                Project_client_framework::delAllByPid($thisOne['id']);
            }
            return Table_project_client_framework::del($pid);
        }else{
           return Table_project_client_framework::del($pid);
        }
    }

    /**
     * Project_client_framework::delByProjectId() 根据项目ID删除数据
     * @param $ptid  第二阶段关联项目ID
     * @return mixed
     */
    static public function delByPtId($ptid){
        return Table_project_client_framework::delByPtId($ptid);
    }
}
?>