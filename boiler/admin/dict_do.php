<?php
/**
 * 产品属性处理  dict_do.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加修改
        $name   =  safeCheck($_POST['name'], 0);
        $code   =  safeCheck($_POST['code'], 0);
        $id =  safeCheck($_POST['id']);
        $modelid =  safeCheck($_POST['modelid']);

        try {

            $attrs = array(
              "name"=>$name,
              "code"=>$code,
              "addtime"=>time(),
              "model"=>$modelid,
              "operator"=>$_SESSION[$session_ADMINID],
            );
            if($id == 0){
                $rs = Dict::add($attrs);
                if($rs > 0){
                    echo action_msg("添加成功",1);
                }
            }else{
                $rs = Dict::update($id, $attrs);
                if($rs >= 0){
                    echo action_msg("修改成功",1);
                }
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
        
    case 'addvalue':
        $name   =  safeCheck($_POST['name'], 0);
        $code   =  safeCheck($_POST['code'], 0);
        $id     =  safeCheck($_POST['id']);
        $modelid =  safeCheck($_POST['modelid']);
        $pic =  safeCheck($_POST['pic'], 0);
        $cat = safeCheck($_POST['cat']);
        try {
            $attrs = array(
                "name"=>$name,
                "code"=>$code,
                "parent"=>$id,
                "addtime"=>time(),
                "model"=>$modelid,
                "operator"=>$_SESSION[$session_ADMINID],
                "pic"=>$pic,
                "cat" =>$cat
            );
            $rs = Dict::add($attrs);
            if($rs > 0){
                echo action_msg("添加成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del'://删除
        $id = safeCheck($_POST['id']);

        try {
            $child = Dict::getInfoByParentid($id);
            if($child){
                echo action_msg("请先删除属性值！",-1);
                die();
            }
            $rs = Dict::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
        
    case 'dels'://删除
        $idStr = safeCheck($_POST['idStr'], 0);
        $array = explode(',', $idStr);
        if($array && count($array)){
            for ($index = 0; $index < (count($array) -1);$index++){
                Dict::del($array[$index]);
            }
        }
        echo action_msg("删除成功",1);
        break;
        
    case 'editvalue'://修改属性值 
        $name   =  safeCheck($_POST['name'], 0);
        $id =  safeCheck($_POST['id']);
        $pic =  safeCheck($_POST['pic'], 0);
        $cat = safeCheck($_POST['cat']);
        try {
            $attrs = array(
                "name"=>$name,
                "pic"=>$pic,
                "cat"=>$cat
            );
            $rs = Dict::update($id, $attrs);
            if($rs >= 0){
                echo action_msg("修改成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>