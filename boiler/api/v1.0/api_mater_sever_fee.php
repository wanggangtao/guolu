<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/20
 * Time: 10:16
 */
require_once('api_init.php');
try {
    $sever_list=Service_fee::getList();
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $sever_list);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}


