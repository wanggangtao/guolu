<?php

/**
 * web_recruit.class.php 人员招聘类
 *
 * @version       v0.01
 * @create time   2018/11/21
 * @update time   2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Web_recruit {

    /**
     * Web_recruit::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  table_web_recruit::add($attrs);
        return $id;
    }

    /**
     * Web_recruit::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return table_web_recruit::getInfoById($id);
    }

    /**
     * Web_recruit::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = table_web_recruit::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Web_recruit::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return table_web_recruit::del($id);
    }

    /**
     * Web_recruit::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count){
        return table_web_recruit::getPageList($page, $pageSize, $count);
    }
    static public function getList($params){
        return table_web_recruit::getList($params);
    }
    static public function getCount($params){
        return table_web_recruit::getCount($params);
    }
}
?>