<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-26
 * Time: 11:56
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
$params['expired'] = $nowTime;
$couponList = Weixin_user_coupon::getListALLInfo($user_info["id"],1,$params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>我的优惠券</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
</head>
<body>
<div id="app">
    <div class="coupon" style="background-color: #f6f6f6">
        <div class="coupon_tab">
            <a href="weixin_coupon_have.php">已领取</a>
            <a href="weixin_coupon_used.php">已使用</a>
            <a href="weixin_coupon_expired.php" class="active">已过期</a>
        </div>
        <div class="coupon_con">
            <?php if(!empty($couponList)){
            foreach ($couponList as $coupon){
//                if($coupon['coupon_endtime'] < time()){
                    ?>
            <a class="coupon_item">
                <div class="coupon_item_top">
                    <div class="coupon_item_left">
                        <div class="coupon_item_name coupon_item_used">小元服务</div>
                    </div>
                    <div class="coupon_item_center">
                        <p class="coupon_item_title coupon_item_used">小元壁挂炉服务</p>
                        <p class="coupon_item_text coupon_item_used">
                            <span class="coupon_text_red coupon_item_used"><?php echo $coupon['coupon_money']."元";?></span>
                            <span><?php echo $coupon['coupon_name']?></span>
                        </p>
                    </div>
                    <div class="coupon_item_right">
                        <img src="static/images/coupon_expired.png" alt="">
                    </div>
                </div>
                <div class="coupon_item_bottom">有效期：<?php echo date('Y.m.d',$coupon['coupon_starttime']).'-'.date('Y.m.d',$coupon['coupon_endtime'])?></div>
            </a>
            <?php }} ?>


        </div>
    </div>
</div>
</body>
<script src="static/js/jquery.min.js"></script>
<script>
    $(function () {

    })
</script>
</html>

