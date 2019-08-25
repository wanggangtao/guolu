<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/3/20
 * Time: 9:48 AM
 */

try {
    $project_id = isset($_POST['id'])?safeCheck($_POST['id']):'0';
    if(empty($project_id)) throw new MyException("缺少项目id参数",101);

    //$projectinfo = Project::getInfoById($projectid) ;
    $room = array();
    $room_info = Chat_room::getInfoByProject($project_id) ;
    $project_info = Project::getInfoById($project_id);
    if(empty($room_info)&&$project_info['level']!=0){

        $room_id = Chat_room::AddRoomByProject($project_id);
        $room_info = Chat_room::getInfoById($room_id);
        $room['room_id']= $room_id;
        $room['name'] = $room_info['name'];
    }else{
        $room['room_id']= $room_info['id'];
        $room['name'] =  $room_info['name'];

    }


    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $room);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
