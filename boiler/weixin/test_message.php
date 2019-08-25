<?php
//
require_once('admin_init.php');
//function postOrderToCustomer($id)
//{
//    global $HTTP_PATH;
//    global $kf1_template_id;//接单的消息模板的id
//    $orderList=Boiler_repair_order::getrepair_detail($id);//使用用户的来获取维修记录
////var_dump($orderList);
//    $uid=$orderList["uid"];//求出uid
////uid在求出用户的openid
//    $user_onfo= User_account::getInfoById($uid);
////var_dump($user_onfo);
//    $openid=$user_onfo["openid"];//求出用户的openid
//    var_dump($openid);
//    if(!empty($item['openid'])){//当用户的openid不是空的时候
//        $url =  $HTTP_PATH."weixin/weixin_coupon_have.php";
//        $data = array(
//            "time"=>date("Y-m-d H:i:s"),
//            "type"=> $my_coupon,
//        );
//        $weixin = new weixin();
//        $weixin->send_coupon_by_template($url,$openid,$data,$kf1_template_id) ;
//    }
//
//}
//$id=3;
//
//
//if($openid){
//    $url =  $HTTP_PATH."weixin/weixin_coupon_have.php";
//
//    $my_coupon = json_decode(json_encode($my_coupon,JSON_UNESCAPED_UNICODE));
//
//    $data = array(
//        "time"=>date("Y-m-d H:i:s"),
//        "type"=> $my_coupon,
//    );
//    $weixin = new weixin();
//    $weixin->send_coupon_by_template($url,$openid,$data,$coupon_template_id) ;
//}
//
////求出用户的openid
//
//
repair_order::postOrderToCustomerss(3);
//repair_order::postOrderToCustomerpay(3);
//repair_order::postOrderToCustomers(3);



?>