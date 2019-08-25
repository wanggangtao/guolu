<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 17:31
 */
require_once('admin_init.php');
require_once('admincheck.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('PRC');
$act = safeCheck($_GET['act'], 0);

switch ($act){
    case 'add':
        $fee_number = safeCheck($_POST['fee_number']);
        try{
            Service_fee::add($fee_number);
            echo action_msg("增加成功", 1);;
        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        break;

    case 'edit':
        $id = safeCheck($_POST['id']);
        $fee_number =safeCheck($_POST['fee_number']);

        try{
            Service_fee::update($id,$fee_number);
            echo action_msg("修改成功", 1);;
        }
        catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del':
        $id = safeCheck($_POST['id']);
        try{
            Service_fee ::delete($id);
            echo action_msg("修改成功", 1);;
        }
        catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


}

?>