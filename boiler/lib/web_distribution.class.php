<?php

/**
 * web_distribution.class.php 渠道分销类
 *
 * @version       v0.01
 * @create time   2018/11/21
 * @update time   2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Web_distribution {

    /**
     * Web_distribution::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_web_distribution::add($attrs);
        return $id;
    }

    /**
     * Web_distribution::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_web_distribution::getInfoById($id);
    }

    /**
     * Web_distribution::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = Table_web_distribution::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Web_distribution::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_web_distribution::del($id);
    }

    /**
     * Web_distribution::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count,$province=null,$city=null){
        return Table_web_distribution::getPageList($page, $pageSize, $count,$province,$city);
    }

    static public function getList($params){
        return Table_web_distribution::getList($params);
    }

    static public function getAllCount($params){
        return Table_web_distribution::getAllCount($params);
    }
    static public function getListQianDuan($params = array()){
        return Table_web_distribution::getListQianDuan($params);
    }
    static public function getOtherList(){
        return Table_web_distribution::getOtherList();
    }
    static public function getDistrict($params){
        return Table_web_distribution::getDistrict($params);
    }
}
?>