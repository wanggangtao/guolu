<?php
/**
 * 用户类型处理  common_do.php
 *
 * @version       v0.03
 * @create time   2014-9-4
 * @update time   2016/3/25
 * @author        dxl jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');



$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $name   =  safeCheck($_POST['name'], 0);
        $eng_name  =  safeCheck($_POST['eng_name'], 0);
        $order     =  safeCheck($_POST['order'],0);

        try {
            $attr=array(
                "name" => $name,
                "english_name" => $eng_name,
                "order" => $order,
                "operator"=>$_SESSION[$session_ADMINID],
                "addtime"=>time()
            );
            $rs = Projectcase_type::add($attr);
            if($rs>0){
                echo action_msg("添加成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit'://编辑管理员
        $id            = safeCheck($_POST['id']);
        $name   =  safeCheck($_POST['name'], 0);
        $eng_name  =  safeCheck($_POST['eng_name'], 0);
        $order     =  safeCheck($_POST['order'],0);

        try {
            $attr=array(
                "name" => $name,
                "english_name" => $eng_name,
                "order" => $order,
                "operator"=>$_SESSION[$session_ADMINID],
                "addtime"=>time()
            );
            $rs = Projectcase_type::update($id,$attr);
            if($rs>0){
                echo action_msg("修改成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除管理员
        $id = safeCheck($_POST['id']);

        try {
            $rs = Projectcase_type::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>