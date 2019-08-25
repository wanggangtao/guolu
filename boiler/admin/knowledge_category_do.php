<?php
/**
 * 管理员处理  admin_do.php
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
    case 'add'://添加管理员
        $name   =  safeCheck($_POST['name'], 0);



        try {

            $attrs = array(
                "name"=>$name,
                "addtime"=>time(),
            );
            $rs = knowledge_category::add($attrs);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $name   =  safeCheck($_POST['name'], 0);
        $id  =  safeCheck($_POST['id'], 0);


        try {

            $attrs = array(
                "name"=>$name,
            );

            $rs = knowledge_category::update($id, $attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除管理员
        $id = safeCheck($_POST['id']);

        try {
            $rs = knowledge_category::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

        break;
}
?>