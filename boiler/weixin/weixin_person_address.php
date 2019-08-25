<!DOCTYPE html>
<html lang="en">
<?php
require_once ("admin_init.php");
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();
}
$userInfo = user_account::getInfoByOpenid($userOpenId);
if(empty($userInfo)){
    echo "未查询到该个人信息";
    exit();
}

?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>个人信息-地址</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script src="js/query.js"></script>
</head>

<body>
<div id="app">
    <div class="personal-center">
        <div class="personal-center-head flex">
            <img class="person-img" src="static/images/icon-bark.png" alt="">
            <span class="person-name">地址</span>
            <button type="button" class="complate">返回</button>
        </div>
        <div class="personal-center-con person-form">
            <div class="form-item">
                <div class="form-input flex">
                    <span><?php echo $userInfo['contact_address']?> </span>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item-tip flex">
                    <img src="static/images/person-tip.png" alt="">
                    <span>注：如果地址跟您居住地址不匹配，请联系客服400-9665890。</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="static/js/jquery.min.js"></script>
<script src="static/js/swiper.min.js"></script>
<script src="static/js/common.js"></script>
<script>
    $(function () {
            $(".complate").click(function () {
                location.href = "weixin_personal_detail.php";
            })
            $(".person-img").click(function () {
                location.href = "weixin_personal_detail.php";
            })
    })
</script>

</html>