<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/20
 * Time: 10:16
 */
require_once('api_init.php');
try {
//    $master_id=9;
    $master_id = isset($_POST['master_id'])?safeCheck($_POST['master_id'],1):'0';
    $orderList1=Boiler_repair_order::getList_repair_master($master_id ,2,21);
    $rows=count($orderList1);
    $orderList2=Boiler_repair_order::getList_repair_master($master_id ,2,22);
    $rows2=count($orderList2);
    $orderList3=Boiler_repair_order::getList_repair_master($master_id ,2,23);
    $rows3=count($orderList3);
    $orderList4=Boiler_repair_order::getList_repair_master($master_id ,3,33);//
    $rows4=count($orderList4);
    $params=array(
        "待接单"=>$rows,
        "已接单"=>$rows2,
        "待支付"=>$rows3,
        "已完工"=>$rows4,
    );
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $params);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}


