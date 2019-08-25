<?php

/**
 * table_message_info.class.php 数据库表:站内信表
 *
 * @version       v0.01
 * @createtime    2018/7/3
 * @updatetime    2018/7/3
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_message_info extends Table {


    private static  $pre = "message_";

    static protected function struct($data){
        $r = array();
        $r['id']          = $data['message_id'];
        $r['recipients']  = $data['message_recipients'];
        $r['sender']      = $data['message_sender'];
        $r['title']       = $data['message_title'];
        $r['content']     = $data['message_content'];
        $r['openflag']    = $data['message_openflag'];
        $r['addtime']     = $data['message_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "recipients"=>"number",
            "sender"=>"number",
            "title"=>"string",
            "content"=>"string",
            "openflag"=>"number",
            "addtime"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }



    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'message_info', $params);
        return $r;
    }


    static public function update($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "message_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'message_info', $params, $where);
        return $r;
    }

    /**
     * Table_message_info::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."message_info where message_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }


    /**
     * Table_message_info::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $recipients       收件人
     * @param number $sender           发件人
     * @param string $title            标题
     * @param number $readflg          是否已读
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $recipients, $sender, $title, $readflg){
        global $mypdo;

        $where = " where 1=1 ";
        if($recipients){
            $recipients = $mypdo->sql_check_input(array('number', $recipients));
            $where .= " and message_recipients = $recipients ";
        }
        if($sender){
            $sender = $mypdo->sql_check_input(array('number', $sender));
            $where .= " and message_sender = $sender ";
        }
        if($title){
            $title = $mypdo->sql_check_input(array('string', "%".$title."%"));
            $where .= " and message_title like $title ";
        }
        if($readflg > -1){
            $readflg = $mypdo->sql_check_input(array('number', $readflg));
            $where .= " and message_openflag = $readflg ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."message_info".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."message_info".$where;
            $sql .=" order by message_id desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
            }

            return $r;
        }
    }
    
    /**
     * Table_message_info::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "message_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'message_info', $where);
    }
}
?>