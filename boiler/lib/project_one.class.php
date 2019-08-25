<?php

/**
 * project_one.class.php 项目第一阶段类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_one {

    static public function Init() {
        $r = array();
        $r['id']                           = 0;
        $r['project_id']                   = 0;
        $r['project_name']                 = '';
        $r['project_detail']               = '';
        $r['project_lat']                  = '';
        $r['project_long']                 = '';
        $r['project_type']                 = 0;
        $r['project_partya']               = '';
        $r['project_partya_address']       = '';
        $r['project_partya_desc']          = '';
        $r['project_partya_pic']           = '';
        $r['project_linkman']              = '';
        $r['project_linktel']              = '';
        $r['project_history']              = '';
        $r['project_history_attr']         = '';
        $r['project_linkposition']         = '';
        $r['project_boiler_num']           = '';
        $r['project_boiler_tonnage']       = '';
        $r['project_wallboiler_num']       = '';
        $r['project_brand']                = '';
        $r['project_xinghao']              = '';
        $r['project_build_type']           = 0;
        $r['project_isnew']                = 0;
        $r['project_pre_buildtime']        = '';
        $r['project_competitive_brand']    = '';
        $r['project_competitive_desc']     = '';
        $r['project_desc']                 = '';
        $r['project_addtime']              = '';
        $r['project_lastupdate']           = '';

        return $r;
    }
    /**
     * Project_one::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_one::add($attrs);

        return $id;
    }

    /**
     * Project_one::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_one::getInfoById($id);
    }

    /**
     * Project_one::getInfoByProjectId() 根据项目ID获取详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoByProjectId($prid){
        return Table_project_one::getInfoByProjectId($prid);
    }

    /**
     * Project_one::getInfoByLinktel() 根据项目联系人电话获取详情
     * @param $tel  联系人电话
     * @return mixed
     */
    static public function getInfoByLinktel($tel){
        return Table_project_one::getInfoByLinktel($tel);
    }

    /**
     * Project_one::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_one::update($id, $attrs);
    }

    /**
     * Project_one::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_one::del($id);
    }

    /**
     * Project_one::delByProjectId() 根据项目ID删除数据
     * @param $prid            项目ID
     * @return mixed
     */
    static public function delByProjectId($prid){
        return Table_project_one::delByProjectId($prid);
    }

    /**
     * Project_one::getPageSameList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param string $detail           地址
     * @param string $partya           甲方单位
     * @param string $address          甲方地址
     * @param string $linkman          联系人
     * @param string $linktel          电话
     * @return mixed
     */
    static public function getPageSameList($page, $pageSize, $count, $name, $detail, $partya, $address, $linkman, $linktel, $project_notsame_id=''){
        return Table_project_one::getPageSameList($page, $pageSize, $count, $name, $detail, $partya, $address, $linkman, $linktel, $project_notsame_id);
    }
}
?>