<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/26
 * Time: 10:14
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
$params['no_expired'] = $nowTime;
$couponList = Weixin_user_coupon::getListALLInfo($user_info["id"],1,$params);
//var_dump($couponList);
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
            <a href="weixin_coupon_have.php" class="active">已领取</a>
            <a href="weixin_coupon_used.php">已使用</a>
            <a href="weixin_coupon_expired.php">已过期</a>
        </div>
        <div class="coupon_con">

            <?php if(!empty($couponList)){
                    foreach ($couponList as $coupon){
                            ?>
            <a href="weixin_coupon_detail.php?start=<?php echo date('Y.m.d',$coupon['coupon_starttime'])?>&end=<?php echo date('Y.m.d',$coupon['coupon_endtime'])?>" class="coupon_item">
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
                    <div class="coupon_item_right">
                        <img src="static/images/coupon_have.png" alt="">
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

