<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/20
 * Time: 10:16
 */
require_once('api_init.php');
try {
    $part_list=Repair_parts::getList();
//    var_dump($part_list);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $part_list);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}


