<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    if(!isset($_POST['id']))
        throw new MyException("请传入ID",101);

    $id = safeCheck($_POST['id']);

    $info = Project_inspectlog::getInfoById($id) ;

    if(empty($info))
        throw new MyException("记录不存在",101);

    $resData = array();

    $resData["id"] = $info["id"];
    $resData["inspecttime"] = getDateStrC($info['inspecttime']);
    $resData["member"] = $info['member'];
    $resData["company"] = $info["company"];
    $resData["brand"] = $info['brand'];
    $resData["address"] = $info["address"];
    $resData["situation"] = $info['situation'];

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
