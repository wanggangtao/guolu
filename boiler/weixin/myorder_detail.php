<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/28
 * Time: 11:43
 */
require_once('admin_init.php');

if(isset($_GET['id'])){
    $id = safeCheck($_GET['id']);
}else{
    echo "参数获取失败";
    exit();
}
$order_info = repair_order::getInfoById($id);
if(empty($order_info)){
    echo "参数获取失败";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>订单详情</title>
    <style>
        .detailBox {
            width: 100%;
            font-size: 45px;
            line-height: 90px;
            margin-top:5%;
        }
        .detailBox>div:nth-child(1){
            font-weight: bold;
            font-size: 60px;
        }
        .detailBox > div {
            width: 90%;
            margin: 0 auto;
        }
    </style>
</head>
<body>


<div class="detailBox">
    <div>
<!--        <span>订单详情</span>-->
<!--        <span></span>-->
    </div>
    <div>
        <span>联系人：&#12288;</span>
        <span><?php
            $register_person = "";

            if(isset($order_info['register_person'])){
                $register_person =  $order_info['register_person'];
            }
            echo $register_person;
            ?></span>
    </div>
    <div>
        <span>联系电话：</span>
        <span><?php
            $phone = "";

            if(isset($order_info['phone'])){
                $phone =  $order_info['phone'];
            }
            echo $phone;
            ?></span>
    </div>

    <div style="display:flex;">
        <div style="width: 26%;font-size: 45px;">联系地址：</div>
        <div style="display: block;width: 70%;font-size: 45px;margin-left: 2%"><?php
            $address_all = "";

            if(isset($order_info['address_all'])) echo $order_info['address_all'];
            echo $address_all;

            ?></div>
    </div>

    <div>
        <span>服务类型：</span>
        <span><?php

            $service_type = "";
            if(isset($order_info['service_type'])){
                $service_info = Service_type::getInfoById($order_info['service_type']);
                if(isset($service_info['name'])){
                    $service_type = $service_info['name'];
                }
            }
            echo $service_type;

            ?></span>
    </div>
    <div>
        <span>优惠券：&#12288;</span>
        <span><?php


            $coupon_name = "";
            if(isset($order_info['coupon_id'])){
                if($order_info['coupon_id'] == -1){
                    $coupon_name = "暂无";
                }else{
                    $coupon_info = Weixin_coupon::getInfoById($order_info['coupon_id']);
                    if(isset($coupon_info['name'])){
                        $coupon_name = $coupon_info['money']."元 ".$coupon_info['name'];
                    }
                }


            }
            echo $coupon_name;

            ?></span>
    </div>
    <div>
        <span>提交时间：</span>
        <span><?php
            $addtime = "";

            if(isset($order_info['addtime'])){
                $addtime = date("Y-m-d H:i:s", $order_info['addtime']);
            }
            echo $addtime;
            ?></span>
    </div>
</div>


</body>
</html>
