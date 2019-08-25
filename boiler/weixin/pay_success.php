<?php
require_once("../init.php");

if(isset($_GET['money']) &&  isset($_GET['user_id'])){
    $money = safeCheck($_GET['money']);
    $user_id = safeCheck($_GET['user_id']);
}else{
    exit;
}
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
    <title>订单提交</title>
    <link rel="stylesheet" href="weui/css/weui.min.css" />
    <link rel="stylesheet" href="weui/css/jquery-weui.min.css" />


    <link rel="stylesheet" href="css/swiper.min.css">
    <script src="js/query.js"></script>
</head>
<style>
    html,
    body {
        background: rgba(243, 243, 243, 1);
    }
    .wrap {
        background-color: white;
        border-radius: 0.12rem;
        padding: 5% 4% 6% 4%;
        margin: 2% 2% 2% 2%;
        font-size: 0.28rem;
    }
    .order-wrap {
        padding-top: 40px;
    }

    .order-top {
        text-align: center;
    }

    .order-top img {
        width: 55px;
        height: 55px;
    }

    .order-top span {
        display: block;
        color: #22222C;
        font-size: 16px;
        margin: 10px 0 20px;
    }

    .order-top p {
        color:rgba(4,166,254,1);
        font-size: .28rem;
    }

    .iknow {
        position: static;
        margin: 1.7rem auto 0.2rem auto;
    }
    .personal-bottom {
        display: flex;
        justify-content: space-around;
        width: 80%;
    }

    .personal-bottom a {
        display: inline-block;
        width: 40%;
        height: 100%;
        -webkit-border-radius: 40px;
        border-radius: 40px;
        font-size: 16px;
        text-align: center;
        color: #fff;
        background-color: #02AFF3;
        line-height: 40px;
    }
</style>
<body>
<div class="wrap">
    <div class="order-top">
        <img src="images/success.png" alt="">
        <span>支付成功</span>
        <p><?php echo "实付￥".$money."元";?></p>

    </div>
    <div class="iknow personal-bottom">
        <a href="weixin_personal_detail.php">首&nbsp;页</a> <a href="../weixinHtml/my_order.html?id=<?php echo $user_id;?>">订单中心</a>
    </div>
</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="weui/js/jquery-weui.min.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {

    })
</script>

</html>
