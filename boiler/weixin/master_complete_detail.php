<?php
/**
 * Created by PhpStorm.
 * User: TF
 * Date: 2019/8/15
 * Time: 18:38
 * 师傅端的工单详情展示
 * 王刚涛
 */
require_once "admin_init.php";
print_r("已经完成订单的详情展示");
if(($_GET['id'])){

    $id =safeCheck($_GET['id']);
}else{
    echo "未发现项目ID";
    exit;
}
if(($_GET['person'])){

    $person =safeCheck($_GET['person']);
}else{
    echo "未发现工人的ID";
    exit;
}
var_dump($id) ;
$orderList=Boiler_repair_order::getrepair_detail($id);//使用用户的来获取维修记录
var_dump($orderList);
$order_person=Boiler_repair_order::getrepair_person($person);//使用维修工的的来获取工人信息
var_dump($order_person);
$order_repair_part=Boiler_repair_parts::getrepair_part($id);//使用的维修的的来获取零件信息
var_dump($order_repair_part);


?>

