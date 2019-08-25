<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-28
 * Time: 16:15
 */
$start= !empty($_GET['start'])?$_GET['start']:die('');
$end = !empty($_GET['end'])?$_GET['end']:die('');

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
    <title>我的优惠券-详情</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
</head>
<body>
<div id="app">
    <div class="coupon" style="background-color: #f6f6f6">
        <div class="coupon_detail">
            <div class="coupon_detail_con">
                <div class="coupon_detail_top">
                    <p>使用条件：</p>
                    <p>1、一次有效、不找零、不分次。</p>
                    <p>2、如有退款，券面金额不退。</p>
                    <p>3、请在有效期内使用，过期作废。</p>
                    <p>注：此券最终解释权归我司所有。</p>
                </div>
                <div class="coupon_detail_date">有效期：<?php echo $start."-".$end ?></div>
            </div>
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

