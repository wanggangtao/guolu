<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/20
 * Time: 10:06
 */
require_once('api_init.php');
try {
//    $master_id=9;
    $master_id = isset($_POST['master_id'])?safeCheck($_POST['master_id'],1):'0';
    if(empty($master_id)) throw new MyException("缺少openid参数",101);
    $orderList=Boiler_repair_order::getList_repair_master($master_id ,2,21);    $params=array();
//    var_dump($orderList);
    $params=array();
    if(!empty($orderList)) {
        foreach ($orderList as $key=>$coupon) {
            $params[$key] = array(
                'id' => $coupon["id"],
                'service_type' => $coupon["service_type"],
                'register_person' => $coupon["register_person"],
                'linkphone' => $coupon["linkphone"],
                'address_all' =>  $coupon["address_all"],
            );
        }
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $params);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}
