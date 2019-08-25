<?php

/**
 * web_projectcase.class.php 项目案例类
 *
 * @version       v0.01
 * @create time   2018/11/21
 * @update time   2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Web_projectcase {

    /**
     * Web_projectcase::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_web_projectcase::add($attrs);
        return $id;
    }

    /**
     * Web_projectcase::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_web_projectcase::getInfoById($id);
    }

    /**
     * Web_projectcase::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = Table_web_projectcase::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Web_projectcase::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_web_projectcase::del($id);
    }

    /**
     * Web_projectcase::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $type){
        return Table_web_projectcase::getPageList($page, $pageSize, $count, $type);
    }

    static public function getList($params= array()){
        return Table_web_projectcase::getList($params);
    }
    static public function getAllCount($params){
        return Table_web_projectcase::getAllCount($params);
    }

    static public function UpdateCountInfo($id){
        return Table_web_projectcase::updateCountInfo($id);
    }

    static public function getListQianDuan($params = array()){
        return Table_web_projectcase::getListQianDuan($params);
    }
    static public function getOtherList(){
        return Table_web_projectcase::getOtherList();
    }
}
?>