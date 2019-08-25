<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/4/18
 * Time: 10:47 AM
 */

try {
    $user_id = isset($_POST['id'])?safeCheck($_POST['id']):'0';
    if(empty($user_id)) throw new MyException("缺少用户id参数",101);
    $userInfo = User::getInfoById($user_id);
    if ($userInfo['role']==1||$userInfo['role']==4){
        $count=0;
    }else{
        $count = Project::getReviewProjectCount($user_id,$userInfo['role']);
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $count);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}