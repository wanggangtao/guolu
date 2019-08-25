<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {

    if(empty($uid)) throw new MyException("缺少用户uid参数",101);

    $userInfo = user::getInfoById($uid);


    if(empty($userInfo))
    {
        throw new MyException("用户信息不存在",102);
    }



    $resData = array();

    $resData["projectTotalNum"] = project::getTotalCount($uid);

    $resData["projectNotReportNum"] = project::getNotReportCount($uid);
    $resData["projectAlreadyReportNum"] = project::getReviewCount($uid,$userInfo['role']);
    $resData["projectStopNum"] = project::getStopCount($uid);



    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resData);

    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
