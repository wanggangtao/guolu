<?php

/**
 * message_tpl.class.php 角色类
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time   2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Message_tpl {

    public function __construct() {

    }

    /**
     * Message_tpl::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_message_tpl::add($attrs);

        return $id;
    }

    /**
     * Message_tpl::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_message_tpl::getInfoById($id);
    }

    /**
     * Message_tpl::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoByType($type){
        return Table_message_tpl::getInfoByType($type);
    }

    /**
     * Message_tpl::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_message_tpl::update($id, $attrs);
    }

    /**
     * Message_tpl::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_message_tpl::del($id);
    }

    /**
     * Message_tpl::getAllList() 列出所有信息
     * @return mixed
     */
    static public function getAllList(){
        return Table_message_tpl::getAllList();
    }
}
?>