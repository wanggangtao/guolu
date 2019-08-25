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
print_r("详情展示");
if(isset($_GET['id'])){
    $id =$_GET['id'];

}else{
    echo "未发现项目的ID";
    exit;
}
//var_dump($id) ;

$orderList=Boiler_repair_order::getrepair_detail($id);//使用项目的ID的来获取维修记录
var_dump($orderList) ;
?>

