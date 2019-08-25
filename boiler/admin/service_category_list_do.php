<?php
/**
 * 知识点类别处理  service_category_list_do.php
 *
 * @version       v0.03
 * @create time   2019/3/20
 * @update time   2019/3/20
 * @author        guanxin
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加类别
        $name   =  safeCheck($_POST['name'], 0);



        try {

            $attrs = array(
                "name"=>$name,
                "addtime"=>time(),
            );
            $rs = Service_category::add($attrs);
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

            $rs = Service_category::update($id, $attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除(假删)
        $id = safeCheck($_POST['id']);

        try {
            $attrs = array(
                "status" => -1,
            );

            $rs = Service_category::update($id, $attrs);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

        break;
}
?>