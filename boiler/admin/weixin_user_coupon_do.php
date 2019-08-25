<?php

require_once('admin_init.php');
require_once('admincheck.php');
$act = safeCheck($_GET['act'], 0);
switch($act){

    case 'del':
        $id = safeCheck($_POST['id']);

        try {
            Table_weixin_user_coupon::dels($id);
            echo  action_msg("删除成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'unallow':
        $id = safeCheck($_POST['id']);

        try {
            Table_weixin_user_coupon::forbidden($id);
            echo  action_msg("禁用成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;



}
?>
