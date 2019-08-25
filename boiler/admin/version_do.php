<?php
/**
 * 用户处理  user_do.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加用户
        $desc    =  safeCheck($_POST['desc'], 0);
        $name       =  safeCheck($_POST['name'], 0);
        $app_type   =  safeCheck($_POST['app_type']);
        $url        =  safeCheck($_POST['url'], 0);
        $isforce    =  safeCheck($_POST['isforce']);

        try {
            $attrs = array(
                "name"=>$name,
                "app_type"=>$app_type,
                "desc"=>$desc,
                "url"=>$url,
                "isforce"=>$isforce,
                "operator"=>$adminId,
                "addtime"=>time(),
                "lastupdate"=>time()
            );
            $rs = version::add($attrs);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $id =  safeCheck($_POST['id']);

        $desc    =  safeCheck($_POST['desc'], 0);
        $name       =  safeCheck($_POST['name'], 0);
        $app_type   =  safeCheck($_POST['app_type']);
        $url        =  safeCheck($_POST['url'], 0);
        $isforce    =  safeCheck($_POST['isforce']);

        try {
            $attrs = array(
                "name"=>$name,
                "app_type"=>$app_type,
                "desc"=>$desc,
                "url"=>$url,
                "isforce"=>$isforce,
                "operator"=>$adminId,
                "lastupdate"=>time()
            );

            $rs = version::update($id, $attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除用户
        $id = safeCheck($_POST['id']);

        try {
            $rs = version::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>