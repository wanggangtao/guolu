<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 10:20
 */
class Case_attr {

    //-----------------attr_level---------------------
    const USERLIST_ATTR_LEVEL = 3;  //用户名单模块中在attr表中的level
    //------注：开发人员在使用中若未找到某常量设置，可在此更新添加

    public function __construct() {

    }

    /**
     * Case_attr::getListByPid() 根据PID获取属性列表
     * @param $id
     * @return mixed
     */
    static public function getListByPid($pid,  $count){
        return Table_case_attr::getListByPid($pid,  $count);
    }
    static public function getListByLevel($level,  $count){
        return Table_case_attr::getListByLevel($level,  $count);
    }

    static public function getListByPidandLevel($level,$pid,$count){
        return Table_case_attr::getListByPidandLevel($level,$pid,$count);
    }


    static public function getInfoById($id){
        return Table_case_attr::getInfoById($id);
    }


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_case_attr::add($attrs);

        return $id;
    }

    static public function update($id, $attrs){
        return Table_case_attr::update($id, $attrs);
    }

    static public function del($id){
        return Table_case_attr::del($id);
    }
}