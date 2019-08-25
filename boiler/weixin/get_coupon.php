<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/26
 * Time: 16:58
 */
require_once "admin_init.php";


$userid = safeCheck($_POST['id']) ;

$type = safeCheck($_POST['type']);


$my_coupon = Weixin_user_coupon::getInfoByUid($userid);

$str_name = array();
$str_id = array();

if(!empty($my_coupon)){

    foreach ($my_coupon as $item){
        $coupon_info = Weixin_coupon::getInfoByIdAndType($item['cid'],$type);
        if(!empty($coupon_info)){
            $str_name[] = $coupon_info['money']."元 ".$coupon_info['name'];
            $str_id[] = $coupon_info['id'];

        }
    }
}

array_push($str_name,Weixin_user_coupon::NO_USE_QOUPON_NAME);
array_push($str_id,-1);

$str_array[0] = $str_name;
$str_array[1] = $str_id;

echo action_msg($str_array,1);
?>