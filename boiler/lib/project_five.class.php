<?php

/**
 * project_five.class.php 项目第五阶段类
 *
 * @version       v0.01
 * @create time   2018/6/25
 * @update time   2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project_five {

    public function __construct() {

    }

    static public function Init(){
        $r = array();
        $r['id']                 = 0;
        $r['project_id']         = 0;
        $r['after_solve']        = '';
        $r['money']              = 0;
        $r['pay_condition']      = '';
        $r['contract_file']      = '';
        $r['contract_ac_file']      = '';

        $r['pay_condition']      = '';

        $r['cost_plan']          = '';
        $r['first_reward']       = 0;
        $r['second_reward']      = 0;
        $r['third_reward']       = 0;
        $r['pre_build_time']     = '';
        $r['pre_check_time']     = '';
        $r['addtime']            = '';
        $r['lastupdate']         = '';

        return $r;
    }

    /**
     * Project_five::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project_five::add($attrs);

        return $id;
    }

    /**
     * Project_five::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project_five::getInfoById($id);
    }

    /**
     * Project_five::getInfoByProjectId() 根据项目ID获取详情
     * @param $prid  项目ID
     * @return mixed
     */
    static public function getInfoByProjectId($prid){
        return Table_project_five::getInfoByProjectId($prid);
    }

    /**
     * Project_five::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project_five::update($id, $attrs);
    }

    /**
     * Project_five::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project_five::del($id);
    }

    /**
     * Project_five::delByProjectId() 根据项目ID删除数据
     * @param $prid            项目ID
     * @return mixed
     */
    static public function delByProjectId($prid){
        return Table_project_five::delByProjectId($prid);
    }
}
?>