<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 10:18
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
        $parts_name = safeCheck($_POST['parts_name'],0);
        $parts_number = safeCheck($_POST['parts_number']);
        $parts_unit_price = safeCheck($_POST['parts_unit_price']);
        try{
           Repair_parts::add($parts_name, $parts_number, $parts_unit_price);
           echo action_msg("增加成功", 1);;
        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        break;

    case 'edit':
        $id = safeCheck($_POST['id']);
        $part_num =safeCheck($_POST['part_num']);
        $parts_unit_price =safeCheck($_POST['parts_unit_price']);

        try{
            Repair_parts::update($id,$part_num,$parts_unit_price);
            echo action_msg("修改成功", 1);;
        }
        catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del':
        $id = safeCheck($_POST['id']);
        try{
            Repair_parts::delete($id);
            echo action_msg("删除成功", 1);;
        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        break;
}

?>