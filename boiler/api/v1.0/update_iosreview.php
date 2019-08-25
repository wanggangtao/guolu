<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $isregister      =  isset($_POST['isregister'])?safeCheck($_POST['isregister']):0;
    $version         =  isset($_POST['version'])?safeCheck($_POST['version'],0):'';

    $arr = array(
        "isregister"  => $isregister,
        "version"  => $version
    );
    Version_iosreview::update(1, $arr);
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, null);


}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
