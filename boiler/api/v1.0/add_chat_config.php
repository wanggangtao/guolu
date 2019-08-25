<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/4/22
 * Time: 4:08 PM
 */

try {

    $room_id = isset($_POST['id'])?safeCheck($_POST['id']):'0';
    $user_id =  isset($_POST['user_id'])?$_POST['user_id']:'0';

    if(empty($room_id)) throw new MyException("缺少群id参数",101);

    $roomInfo = Chat_room::getInfoById($room_id);
    $projectInfo = Project::getInfoById($roomInfo['project']);
    $USER_Info = User::getInfoById($projectInfo['user']);
    if ($USER_Info['role']==1){
        if(empty($user_id)) throw new MyException("销售人员个数不能为空！",101);
    }
    $user_id = explode(',',$user_id);
    $userInfo = User::getInfoById($uid);

    if($userInfo['role']==1||$userInfo['role']==4) throw new MyException("无权限添加用户",422);
    $data = array();
    $userId = Chat_room_msg_config::GetByRoomId($room_id);
    if (!empty($userId)){
        foreach ($userId as $item){
            Chat_room_msg_config::delById($item['id']);
        }
    }
//    Chat_room_msg_config::delByRoomId($room_id);
    if (!empty($user_id)) {
        foreach ($user_id as $key => $value) {
            $info = Chat_room_msg_config::getInfoByUid($value, $room_id);
            if (empty($info)) {


                $config_attr = array(
                    "room_id" => $room_id,
                    "uid" => $value,
                    "addtime" => time()
                );
                $thisid = Chat_room_msg_config::add($config_attr);
                $resData[] = $thisid;
            } else {
                $user = User::getInfoById($value);
                throw new MyException("本群已有群员" . $user['name'], 422);
            }
        }
    }
    $resData = implode(',',$resData);
    $data['id'] = $resData;
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}