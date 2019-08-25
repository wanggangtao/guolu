<?php

/**
 * webcontent.class.php 图片类
 *
 * @version       v0.01
 * @create time   2018/9/13
 * @update time   2018/9/13
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Webcontent {

    /**
     * Webcontent::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $rs =  Table_webcontent::add($attrs);
        if($rs >= 0){
            return action_msg("添加成功！", 1);
        }else{
            return action_msg("添加失败！", 101);
        }
    }

    /**
     * Webcontent::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_webcontent::getInfoById($id);
    }

    /**
     * Webcontent::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = Table_webcontent::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Webcontent::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_webcontent::del($id);
    }

    /**
     * Webcontent::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $type){
        return Table_webcontent::getPageList($page, $pageSize, $count, $type);
    }
}
?>