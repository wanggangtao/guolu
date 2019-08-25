<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/4/22
 * Time: 4:57 PM
 */

try {

    $user_id = isset($_POST['uid'])?safeCheck($_POST['uid']):'0';
    $room_id = isset($_POST['room_id'])?safeCheck($_POST['room_id']):'0';
    $project_id = isset($_POST['project_id'])?safeCheck($_POST['project_id']):'0';
    if(empty($user_id)) throw new MyException("缺少用户id参数",101);

    $userInfo = User::getInfoById($user_id);

    if($userInfo['role']==1||$userInfo['role']==4) throw new MyException("无权限查看本部门人员",422);

    if ($userInfo['role']==2){
        $user_list = User::getInfoByParentid($user_id,1);
    }else{
        $project_info = Project::getInfoById($project_id);
        $department = User::getInfoById($project_info['user'])['department'];
        if (!empty($department)){
            $user_list = User::getRoleInfoByDepartment($department,1);
        }
    }

    foreach ($user_list as $item){
        $isChoose = Chat_room_msg_config::getInfoByUid($item['id'],$room_id);
        if (!empty($isChoose)){
            $item['isChose'] = 1;
        }else{
            $item['isChose'] = 0;
        }
        $data[]= $item;
    }

//print_r($data);
//    $data = array();
//    if (!empty($user_list)){
//        $data = $user_list;
//    }

//    print_r($data);

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}