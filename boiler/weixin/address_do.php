<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/12
 * Time: 15:10
 */
require_once ('admin_init.php');
try{
    $address = safeCheck($_POST['address'],0);
    $addressArray= explode(' ',$address);
//    print_r($addressArray);

    if(count($addressArray) != 3) echo action_msg('地区选择异常', 101);


    $childList =community::getCommunityByAddress($addressArray[0],$addressArray[1],$addressArray[2]);
    $childName = array();
    if(!empty($childList)){
        $childName = array_column($childList,'name');
    }
    array_push($childName,"其他");
    header('Content-Type:application/json; charset=utf-8');
    $childName = json_encode($childName, JSON_UNESCAPED_UNICODE);
    echo $childName;

}catch(MyException $e){
    $e ->jsonMsg();

}
?>