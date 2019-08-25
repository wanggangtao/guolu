<?php

/**
 * user_department.class.php 部门类
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time   2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class User_department {

    public function __construct() {

    }

    /**
     * User_department::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_user_department::add($attrs);

        return $id;
    }

    /**
     * User_department::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_user_department::getInfoById($id);
    }

    /**
     * User_department::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_user_department::update($id, $attrs);
    }

    /**
     * User_department::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_user_department::del($id);
    }

    /**
     * User_department::getAllList() 列出所有信息
     * @return mixed
     */
    static public function getAllList(){
        return Table_user_department::getAllList();
    }
}
?>