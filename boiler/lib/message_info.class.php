<?php

/**
 * message_info.class.php 站内信类
 *
 * @version       v0.01
 * @create time   2018/7/3
 * @update time   2018/3/3
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Message_info {

    const UNREAD = 0;
    const READED = 1;


    public function __construct() {

    }

    /**
     * Message_info::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_message_info::add($attrs);

        return $id;
    }

    /**
     * Message_info::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_message_info::getInfoById($id);
    }

    /**
     * Message_info::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_message_info::update($id, $attrs);
    }

    /**
     * Message_info::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_message_info::del($id);
    }

    /**
     * Message_info::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $recipients       收件人
     * @param number $sender           发件人
     * @param string $title            标题
     * @param number $readflg          是否已读
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $recipients, $sender, $title, $readflg = -1){
        return Table_message_info::getPageList($page, $pageSize, $count, $recipients, $sender, $title, $readflg);
    }
}
?>