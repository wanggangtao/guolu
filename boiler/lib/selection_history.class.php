<?php

/**
 * selection_history.class.php 选型历史类
 *
 * @version       v0.01
 * @create time   2018/7/18
 * @update time   2018/7/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Selection_history {


    const HISTORY_CAPACITY = 1;//智能选型
    const HISTORY_HAND = 2;//手动选型
    const HISTORY_CHANGE = 3;//更换锅炉

    const HISTORY_Selection = 0;//选型阶段
    const HISTORY_Quote = 1;//报价阶段
    const HISTORY_Plan = 3;//方案阶段
    const HISTORY_Pool = 5;//方案阶段
    const HISTORY_Finish = 10;//已完成



    public function __construct() {

    }

    /**
     * Selection_history::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_selection_history::add($attrs);

        return $id;
    }

    /**
     * Selection_history::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_selection_history::getInfoById($id);
    }

    /**
     * Selection_history::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_selection_history::update($id, $attrs);
    }

    /**
     * Selection_history::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_selection_history::del($id);
    }

    /**
     * Project::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $customer         客户名称
     * @param number $user             所属人
     * @param number $status           状态
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $customer, $user, $status){
        return Table_selection_history::getPageList($page, $pageSize, $count, $customer, $user, $status);
    }

    /**
     * 获取需要处理的文件
     * @return array|mixed
     */
    static public function getListByStatus(){
        return Table_selection_history::getListByStatus();
    }
}

?>