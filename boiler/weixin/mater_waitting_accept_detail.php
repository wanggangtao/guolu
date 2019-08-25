<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/17
 * Time: 11:40
 */
require_once "admin_init.php";
print_r("待接单的详情展示");
if(($_GET['id'])){

    $id =safeCheck($_GET['id']);
}else{
    echo "未发现项目ID";
    exit;
}
$orderList=Boiler_repair_order::getrepair_detail($id);//使用用户的来获取维修记录
var_dump($orderList);

?>