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
    $orderList=Boiler_repair_order::getList_repair_master($master_id ,2,21);
    $params=array();
    if(!empty($orderList)) {
        foreach ($orderList as $key=>$coupon) {
            if($coupon["service_type"]==1)
            {
                $coupon["service_type"]="报修故障";
            }
            else if($coupon["service_type"]==2)
            {
                $coupon["service_type"]="锅炉保养";
            }
            else if($coupon["service_type"]==3)
            {
                $coupon["service_type"]="地暖冲洗";
            }
            else if($coupon["service_type"]==46)
            {
                $coupon["service_type"]="地暖清洗";
            }
            else if($coupon["service_type"]==47)
            {
                $coupon["service_type"]="安全检查";
            }
            else if($coupon["service_type"]==48)
            {
                $coupon["service_type"]="以旧换新";
            }
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
