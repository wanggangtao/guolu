<?php


try {

    $version =  isset($_POST['version'])?safeCheck($_POST['version'],0):"";

    $type = $source - 1;
    $newestVersion = version::getAvailVersion($type);


    $resData = array();
    if(!empty($newestVersion))
    {
        $resData = array(
            "version"=> $newestVersion["name"],
            "code"=> $newestVersion["code"],
            "url"=> $HTTP_PATH.$newestVersion["url"],
            "isforce"=> $newestVersion["isforce"],
            "desc"=> $newestVersion["desc"],
        );
    }

    echo action_msg_data(api::SUCCESS_MSG,api::SUCCESS,$resData);
    //检查手机验证码

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
?>