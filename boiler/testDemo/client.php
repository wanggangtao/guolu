<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2019/4/17
 * Time: 上午10:38
 */
require_once('../init.php');



//获取全局链接的所有客户端
$clientIds = Gateway::getAllClientSessions(-1);


print_r($clientIds);
//获取当前房间所有的客户端
$currentUidArr = Gateway::getUidListByGroup(1);

print_r($currentUidArr);
