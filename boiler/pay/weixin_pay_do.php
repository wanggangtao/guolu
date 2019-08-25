<?php


require_once("../init.php");


$act = $_REQUEST["act"];
$money= $_REQUEST["money"];
$chargeNum= $_REQUEST["chargeNum"];



//测试环境
if($env!='online')
{
    $money = 0.01;
}

switch ($act)
{

    case 'pay':

        $openId = safeCheck($_REQUEST["openId"],0);

        $my_coupon_id = safeCheck($_REQUEST["coupon_id"]);
        $service_type = safeCheck($_REQUEST["service_type"] );
        $order_id = safeCheck($_REQUEST["order_id"]);

        $user_id = safeCheck($_REQUEST["user_id"] );

        if($my_coupon_id != -1){
            //判断当前优惠券是否正常
            $my_coupon_info =  Weixin_user_coupon::getInfoById($my_coupon_id ,1);

            if(!empty($my_coupon_info)){
                //查询优惠券基本信息用于，类型判断
                $coupon = Weixin_coupon::getInfoById($my_coupon_info['cid']);

                if(!empty($coupon)){
                    $typeArray = explode(",",$coupon['type']);
                    if(in_array($service_type,$typeArray)){
                        Weixin_user_coupon::update($my_coupon_id , array("status" =>2));

                        //订单加入优惠券
                        Repair_order::update($order_id , array("coupon_id" => $coupon['id']));

                    }
                }
            }
        }

        $weixin = new weixin_pay($WEIXIN_INFO["appid"],$WEIXIN_INFO["mch_id"],$WEIXIN_INFO["notify_url"],$WEIXIN_INFO["pay_key"]);
        $params['body'] = '订单支付';                   //商品描述
        $params['out_trade_no'] = $chargeNum;   //自定义的订单号，不能重复
        $params['total_fee'] =  $money;
        $params['trade_type'] = 'JSAPI';
        $params['openid'] = $openId;

        $params['attach'] = '#';
        $result = $weixin->unifiedOrder($params);

        if(!empty($result)&&$result["return_code"]=='SUCCESS')
        {
            $jsParams = $weixin->getJsPayParams($result["prepay_id"]);

            echo action_msg($jsParams,1);
        }
        else
        {

            echo action_msg($result["return_msg"],-1);
        }
        break;

    case 'fail_pay':
        $coupon_id =safeCheck($_REQUEST['coupon_id']);
        $order_id =safeCheck($_REQUEST['order_id']);

        if($coupon_id != -1){
            repair_order::update($order_id,array("coupon_id" => 0)); //将订单的使用的优惠券清空
            //将优惠券使用状态更改未使用
            Weixin_user_coupon::update($coupon_id , array("status" =>1));

        }
        echo action_msg("",1);

        break;
    case 'zero_pay':


        $my_coupon_id = safeCheck($_REQUEST["coupon_id"]);
        $service_type = safeCheck($_REQUEST["service_type"] );
        $order_id = safeCheck($_REQUEST["order_id"]);

        if($my_coupon_id != -1){
            //判断当前优惠券是否正常
            $my_coupon_info =  Weixin_user_coupon::getInfoById($my_coupon_id ,1);

            if(!empty($my_coupon_info)){
                //查询优惠券基本信息用于，类型判断
                $coupon = Weixin_coupon::getInfoById($my_coupon_info['cid']);

                if(!empty($coupon)){
                    $typeArray = explode(",",$coupon['type']);
                    if(in_array($service_type,$typeArray)){
                        Weixin_user_coupon::update($my_coupon_id , array("status" =>2));

                        //订单加入优惠券
                        Repair_order::update($order_id , array("coupon_id" => $coupon['id']));

                    }
                }
            }
        }

        $rs = Boiler_repair_order::paySuccess($chargeNum , 0);

        echo action_msg("支付完成",1);

        break;
}


