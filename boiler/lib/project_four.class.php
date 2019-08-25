<?php

/**
 * project_four.class.php 项目第四阶段类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_four {

    public function __construct() {

    }
    static public function Init(){
        $r = array();
        $r['id']                          = 0;
        $r['project_id']                  = '';

        $r['project_cid_company']                     = '';
        $r['project_cid_linkman']                  = '';
        $r['project_cid_linkphone']                     = '';
        $r['project_cid_file']                  = '';
        $r['project_bid_file']                     = '';
        $r['project_cid_ac_file']                  = '';
        $r['project_bid_ac_file']                     = '';
        $r['project_success_bid_company	']                  = '';
        $r['project_cbid_situation']                     = '';

        $r['addtime']                     = '';
        $r['lastupdate']                  = '';

        return $r;
    }
    /**
     * Project_four::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_four::add($attrs);

        return $id;
    }

    /**
     * Project_four::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_four::getInfoById($id);
    }

    /**
     * Project_four::getInfoByProjectId() 根据项目ID获取详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoByProjectId($prid){
        return Table_project_four::getInfoByProjectId($prid);
    }

    /**
     * Project_four::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_four::update($id, $attrs);
    }

    /**
     * Project_four::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_four::del($id);
    }

    /**
     * Project_four::delByProjectId() 根据项目ID删除数据
     * @param $prid            项目ID
     * @return mixed
     */
    static public function delByProjectId($prid){
        return Table_project_four::delByProjectId($prid);
    }
}
?>