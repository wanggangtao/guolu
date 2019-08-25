<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/26
 * Time: 21:49
 */
require_once "admin_init.php";
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();
}
$user_info = user_account::getInfoByOpenid($userOpenId);
$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}

if(empty($user_info)){
    header("Location: weixin_login.php?type=".$type);
    exit();
}
$nowTime = time();
//$user_info["id"] = 22;
$params['no_expired'] = $nowTime;
$couponList = Weixin_user_coupon::getListALLInfo($user_info["id"],1,$params);
$num = count($couponList);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>我的优惠券-提醒</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css?v=1.06" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
</head>

<body>
<div id="app">
    <div class="coupon">
        <div class="coupon_tip">
            <div class="coupon_tip_con">
                <div class="coupon_tip_wrap">
                    <div class="coupon_tip_top">
                        <img class="coupon_tip_img" src="static/images/tip_img.png" alt="">
                        <div class="coupon_tip_text">您有<?php echo $num?>张优惠券，可在我的优惠券中查看</div>
                    </div>
                    <div class="coupon_con" style="background-color: #ffffff;width:100%;">
                        <?php if(!empty($couponList)){
                        foreach ($couponList as $coupon){
                        ?>
                        <a class="coupon_item">
                            <div class="coupon_item_top">
                                <div class="coupon_item_left">
                                    <div class="coupon_item_name">小元服务</div>
                                </div>
                                <div class="coupon_item_center">
                                    <p class="coupon_item_title">小元壁挂炉服务</p>
                                    <p class="coupon_item_text">
                                        <span class="coupon_text_red"><?php echo $coupon['coupon_money']."元";?></span>
                                        <span><?php echo $coupon['coupon_name']?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="coupon_item_bottom">有效期：<?php echo date('Y.m.d',$coupon['coupon_starttime']).'-'.date('Y.m.d',$coupon['coupon_endtime'])?></div>
                        </a>
                        <?php }} ?>
                    </div>
                </div>
                <div style="width: 100%;height: auto;">
                <div class="coupon_tip_btn" id ="detail">我知道了</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="static/js/jquery.min.js"></script>
<script>
    $(function () {
        $("#detail").click(function () {
            location.href = "weixin_personal_detail.php";

        })

    })
</script>

</html>
