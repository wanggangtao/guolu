<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/26
 * Time: 16:02
 */

include "../init.php";
$out_trade_no = "WXD2019082418085183";

$total_fee = "1";
Boiler_repair_order::paySuccess($out_trade_no , $total_fee);
exit();
//
//$aa = weixin_user_coupon :: addUserCoupon(1);
//print_r($aa);


//$day = 7;
//$endtime   = strtotime(date("Y-m-d",strtotime("+$day day"))) + 86400;
//echo $endtime;
//
//$params['address_all'] = "dssds";
//$params['register_person'] = "ded";
//$params['register_phone'] = "12323";
//$params['service_type'] = 1;
//echo repair_order::postOrderToCustomer($params,3);
//$weixin = new weixin();
//print_r($weixin->sendMsgToUserHaveUrl("ssss",22));

//
//$weixin = new weixin();
//
//$url = $HTTP_PATH."weixin/myorder_detail.php?id=2";
//
//$openid = "om86uuB42qIFNreEP5HbfzOhv6mk";
//$data = array(
//    "time"=>date("Y-m-d H:i:s"),
//    "type"=>"新增 100元 报修故障抵用券、20元 锅炉保养现金券"." 优惠券",
//
//);
//
//print_r($weixin->send_coupon_by_template($url,$openid,$data,$coupon_template_id));
$my_coupon = "100元 锅炉更换大促销";
$my_coupon = json_decode(json_encode($my_coupon,JSON_UNESCAPED_UNICODE));
print_r($my_coupon);

//print_r( mb_check_encoding("阿斯顿撒报销","UTF-8"));
//
//functionfixEncoding($in_str){
////检测编码
//$cur_encoding =mb_detect_encoding($in_str);
//if($cur_encoding=="UTF-8"&&mb_check_encoding($in_str,"UTF-8"))
//    return $in_str;
//else
//    return utf8_encode($in_str);
//}
