<?php
/**
 * Created by PhpStorm.
 * User: 王刚涛
 * Date: 2019/8/12
 * Time: 10:14
 */
require_once "admin_init.php";
//$userOpenId = "";
//if($isWeixin)
//{
//    $userOpenId = common::getOpenId();//获取到用户的id
//}
//$user_info = user_account::getInfoByOpenid($userOpenId);//查找出用户的信息
//$type = 1;
//if(isset($_GET['type'])){
//    $type = $_GET['type'];
//}
//
//
//if(empty($user_info)){
//    header("Location: weixin_login.php?type=".$type);
//    exit();
//}
if(($_GET['id'])){
    $id = safeCheck($_GET['id'],1);
}else{
    echo "未发现用户ID";
    exit;
}
if(($_GET['person'])){
    $person = safeCheck($_GET['person'],1);
}else{
    echo "未发现维修工人的ID";
    exit;
}
if(($_GET['bar_code'])){
    $bar_code = safeCheck($_GET['bar_code'],0);
}else{
    echo "未发现产品的ID";
    exit;
}


$orderList=Boiler_repair_order::getrepair_detail($id);//使用用户的来获取维修记录
var_dump($orderList);
$bar_code_info=Product_info::getInfoBycode($bar_code);//用产品的id来获取产品的保修信息
var_dump($bar_code_info);


?>