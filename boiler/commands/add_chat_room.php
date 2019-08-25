<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/4/26
 * Time: 5:36 PM
 */

require_once ("../init.php");

//添加群

$project = Project::getAllSendList();

foreach ($project as $item){
    $chat = Chat_room::getInfoByProject($item['id']);
    if (empty($chat)){
        Chat_room::AddRoomByProject($item['id']);
        print_r('添加'.$item['name'].'群成功');
    }
}
