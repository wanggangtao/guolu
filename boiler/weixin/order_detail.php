<?php
/**
 * Created by PhpStorm.
 * User: 王刚涛
 * Date: 2019/8/12
 * Time: 10:14
 */
require_once "admin_init.php";
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
$order_person=Boiler_repair_order::getrepair_person($person);//使用维修工的的来获取工人信息
var_dump($order_person);
$order_repair_part=Boiler_repair_parts::getrepair_part($id);//使用的维修的的来获取零件信息
var_dump($order_repair_part);
$bar_code_info=Product_info::getInfoBycode($bar_code);//用产品的id来获取产品的保修信息
var_dump($bar_code_info);

$part_money=0;
foreach ($order_repair_part as $k => $v) {
    $part_money+=$order_repair_part[$k][part_money]*$order_repair_part[$k][part_num];
    }


echo "配件费用:".$part_money."  元";
echo " <br />";
foreach ($orderList as $k => $v) {
     $repair_hand_money=$orderList[$k]["repair_hand_money"];
     echo  "手工费".$repair_hand_money."元";

echo " <br />";
$sum=$part_money+$repair_hand_money;
echo "合计手工费和配件费".$sum."元"."<br/>";
if($orderList[$k]["status"]===33)
{
    echo "已支付"."<br/>";
}
else{
    echo "待支付"."<br/>";

}
    echo "实际支付".$orderList[$k]["sum_money"]."元"."<br/>";
}
?>