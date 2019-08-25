<?php
/**
 * 部门处理  user_department_do.php
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
    case 'add'://添加部门
        $name   =  safeCheck($_POST['name'], 0);

        try {

            $attrs = array(
                "name"=>$name,
                "addtime"=>time()
            );
            $rs = User_department::add($attrs);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $name   =  safeCheck($_POST['name'], 0);
        $id =  safeCheck($_POST['id']);

        try {

            $attrs = array(
                "name"=>$name,
                "addtime"=>time()
            );

            $rs = User_department::update($id, $attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除部门
        $id = safeCheck($_POST['id']);

        try {
            $userlist = User::getInfoByDepartment($id);
            if($userlist){
                echo action_msg("该部门下还有用户，请先删除或变更该部门下的用户，再删除该部门",-1);
                die();
            }
            $rs = User_department::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>