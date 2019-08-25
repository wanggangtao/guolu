<?php
/**
 * Created by PhpStorm.
 * User: hhx
 * Date: 2019/3/19
 * Time: 15:33
 */
class  weixin_situation{

    static public function getPageListWeiXin($page, $shownum, $count){
        return Table_weixin_situation::getPageListWeiXin($page, $shownum, $count);
    }
    static public function getListWeiXin(){
        return Table_weixin_situation::getListWeiXin();
    }
    static public function getInfoById($sid){
        return Table_weixin_situation::getInfoById($sid);
    }
    static public function add($params){
        return Table_weixin_situation::add($params);
    }
    static public function del($id){
        return Table_weixin_situation::del($id);
    }
    static public function edit($params,$id){
        return Table_weixin_situation::edit($params,$id);
    }
}
?>