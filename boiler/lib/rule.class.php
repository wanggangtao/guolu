<?php

/**
 * rule.class.php 管理员日志类
 *
 * @version       v0.01
 * @create time   2014/09/09
 * @update time   2016/02/18 2016/3/25
 * @author        tq
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class rule {

    public function __construct() {

    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_rule::add($attrs);

        return $id;
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_rule::getInfoById($id);
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_rule::update($id, $attrs);
    }


    static public function del($id){
        return Table_rule::del($id);
    }

    static public function getList(){
        return Table_rule::getList();
    }


    static public function getCount(){
        return Table_rule::getCount();
    }


    static public function getPageList($page,$pageSize,$before,$after){
        return Table_rule::getPageList($page,$pageSize,$before,$after);
    }


    static public function getInfoByBeforeAndKeyword($before,$keyword){
        return Table_rule::getInfoByBeforeAndKeyword($before,$keyword);
    }

}
?>