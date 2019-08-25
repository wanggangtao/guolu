<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $data = Version_iosreview::getInfoById(1);
    unset($data["id"]);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);


}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
