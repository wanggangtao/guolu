<?php

/**
 * picture.class.php 图片类
 *
 * @version       v0.01
 * @create time   2018/6/24
 * @update time   2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Picture {

    /**
     * Picture::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_picture::add($attrs);
        return $id;
    }

    /**
     * Picture::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_picture::getInfoById($id);
    }

    /**
     * Picture::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = Table_picture::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Picture::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_picture::del($id);
    }

    /**
     * Picture::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @param number $status           状态
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $type, $status){
        return Table_picture::getPageList($page, $pageSize, $count, $type, $status);
    }
}
?>