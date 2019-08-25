<?php
/**
 * 产品类别处理  category_do.php
 *
 * @version       v0.01
 * @create time   2018/5/27
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
        $id =  safeCheck($_POST['id']);
        $pid =  safeCheck($_POST['pid']);

        try {

            $attrs = array(
              "name"=>$name,
              "addtime"=>time(),
              "parentid"=>$pid,
              "weight"=>0,
              "operator"=>$_SESSION[$session_ADMINID],
            );
            if($id == 0){
                $rs = Category::add($attrs);
                if($rs > 0){
                    echo action_msg("添加成功",1);
                }
            }else{
                $rs = Category::update($id, $attrs);
                if($rs >= 0){
                    echo action_msg("修改成功",1);
                }
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除
        $id = safeCheck($_POST['id']);

        try {
            $child = Category::getInfoByParentid($id);
            if($child){
                echo action_msg("请先删除子类别！",-1);
                die();
            }
            $rs = Category::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>