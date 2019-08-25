<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/3/26
 * Time: 7:51 PM
 */

require_once ("../init.php");

$user = User::getRoleList();
$name = "康达暖通元聚环保群";
$room_attr = array(
    "name"=>$name,
    "first"=>Chat_room::getFirstCharter($name),
    "addtime"=>time(),
);
$room_id = Chat_room::update(1,$room_attr);

foreach ($user as $key=>$val){
    $config_attr = array(
        "room_id"=>1,
        "uid"=>$val,
        "addtime"=>time()
    );
    Chat_room_msg_config::add($config_attr);
}


echo '更新完毕';