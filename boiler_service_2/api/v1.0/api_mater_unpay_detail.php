<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/20
 * Time: 10:16
 */
require_once('api_init.php');
try {
//    $id=1;
    $id = isset($_POST['id'])?safeCheck($_POST['id'],1):'0';
    if(empty($id)) throw new MyException("缺少openid参数",101);
    $orderList=Boiler_repair_order::getrepair_detail($id);//使用项目的ID的来获取维修记录
        $order_repair_part=Boiler_repair_parts::getrepair_part($id);//使用的维修的的来获取零件信息
        $orderList["parts"]=$order_repair_part;
        echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $orderList);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}


