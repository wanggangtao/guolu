<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/21
 * Time: 10:36
 */

require_once "admin_init.php";

$service_type = safeCheck($_POST['service_type']);
$uid = safeCheck($_POST['uid']);

$act= safeCheck($_GET['act'],0);

switch ($act){
    case "all":
        $my_use_coupon = Weixin_user_coupon::getMyCouponInfo(array("uid" => $uid , "type" => $service_type));

        $use_id_array = array();
        foreach ($my_use_coupon as $item){
            if(empty($item)) continue;

            $use_id_array[] = $item['myid'];
        }
        echo action_msg($use_id_array,1);

        break;
    case "one":

        $my_use_coupon = Weixin_user_coupon::getMyCouponInfo(array("uid" => $uid , "type" => $service_type));

        $use_array = array();
        if(!empty($my_use_coupon)){
            $coupon_size  = count($my_use_coupon);

            $use_array['myid'] = $my_use_coupon[$coupon_size - 1]['myid'];
            $use_array['name'] = $my_use_coupon[$coupon_size - 1]['name'];
            $use_array['money'] = $my_use_coupon[$coupon_size - 1]['money'];

        }
        echo action_msg($use_array,1);

        break;
}


?>