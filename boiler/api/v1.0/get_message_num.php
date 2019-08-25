<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $openflag = isset($_POST['openflag'])?safeCheck($_POST['openflag']):-1;
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;
    if(empty($uid)) throw new MyException("缺少用户uid参数",401);

    $result = Message_info::getPageList($page, $pageSize, 0, $uid, 0, '', $openflag);
    $resData = array("num"=>$result);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
