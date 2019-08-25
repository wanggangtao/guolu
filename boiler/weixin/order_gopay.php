<?php
/**
 * Created by PhpStorm.
 * User: wanggangtao
 *用户取消订单
 * Date: 2019/8/13
 * Time: 14:18
 */
require_once "admin_init.php";
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();//获取到用户的id
}
$user_info = user_account::getInfoByOpenid($userOpenId);//查找出用户的信息


if(empty($user_info)){
    header("Location: weixin_login.php");
    exit();
}

//$user_info['id'] = 4;

if(isset($_GET["id"])){
    $order_id = safeCheck($_GET["id"]);//订单的id
}else{
    echo "订单号异常";
    exit();
}

$orderList=Boiler_repair_order::getrepair_detail($order_id);

if(empty($orderList)){
    echo "未发现用户该订单";
    exit;
}
$server_type = $orderList['service_type'];

$chargeNum = $orderList['pay_num'];

$my_all_coupon = Weixin_user_coupon::getMyCouponInfo(array("uid" => $user_info['id']));


$have_coupon = 1;
if(empty($my_all_coupon)){
    $have_coupon = 0;

}else{
    foreach ($my_all_coupon as $key => $value){
        $my_all_coupon[$key]['starttime'] = date("Y.m.d",$value['starttime']);
        $my_all_coupon[$key]['endtime'] = date("Y.m.d",$value['endtime']);
    }
//    print_r($my_all_coupon);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="format-detection" content="telephone=yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>支付详情</title>
    <link rel="stylesheet" href="static/weui/css/weui.min.css" />
    <link rel="stylesheet" href="static/weui/css/jquery-weui.min.css" />
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">

    <script src="js/rem.js" async="async"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
</head>

<style>
    html,
    body {
        background: rgba(243, 243, 243, 1);
    }

    .top-tab {
        display: flex;
        position: sticky;
        top: 0px;
        height: .8rem;
        background: rgba(255, 255, 255, 1);
        font-size: .32rem;
        font-weight: 600;
        color: rgba(60, 60, 60, 1);
        line-height: .8rem;
    }

    .top-tab p {
        flex: 1;
        align-self: center;
    }

    .top-tab p img {
        width: .2rem;
        height: .3rem;
        margin: 0 0 0 6%;
    }

    .bottom-tab {
        display: flex;
        width: 100%;
        position: fixed;
        bottom: 0px;
        height: .8rem;
        background: rgba(255, 255, 255, 1);
        font-size: .32rem;
        font-weight: 600;
        color: rgba(60, 60, 60, 1);
        line-height: .8rem;
        z-index:999;
    }

    .bottom-tab .p1 {
        flex: 5;
        text-align: right;
        padding: 0 .5rem 0 0;
        font-weight: 400;
        color: rgba(34, 34, 44, 1);
    }

    .bottom-tab .p2 {
        flex: 1;
        text-align: center;
        color: rgba(255, 255, 255, 1);
        font-weight: 600;
        background: linear-gradient(136deg, rgba(2, 175, 243, 1) 0%, rgba(3, 166, 254, 1) 100%);
    }



    .wrap {
        background-color: white;
        border-radius: 0.12rem;
        padding: 5% 4% 6% 4%;
        margin: 2% 2% 2% 2%;
        font-size: 0.28rem;
    }

    .flex-wrap {
        display: flex;

        flex-direction: column
    }

    .cancell {
        background-color: white;
        border-radius: 0.12rem;
        padding: 3% 0 6% 3%;
        margin: 2% 2% 2% 2%;
        font-size: 0.28rem;
    }

    .pay-card .cancell {
        margin: 0;
        padding: 0;
    }

    .cancell .sel {
        height: .4rem;
        width: 100%;
        margin: 0 0 7% 0;

    }

    .cancell .sel label {
        display: flex;
        align-items: center;
    }

    .c-check {
        opacity: 0;
    }

    .check-img {
        display: inline-block;
        height: .4rem;
        width: .4rem;
        background: url("images/c-unchecked.png");
        background-size: 0.4rem;
        background-repeat: no-repeat;
    }

    .c-check:checked+label .check-img {
        background: url("images/c-checked.png");
        background-size: 0.4rem;
        background-repeat: no-repeat;
    }

    .pay-card div:nth-of-type(1) {
        align-self: center;

        height: .45rem;
        font-size: .32rem;
        font-weight: 500;
        color: rgba(60, 60, 60, 1);
        line-height: .45rem;
        margin: .6rem 0 .2rem 0;
    }

    .pay-card div:nth-of-type(2) {
        align-self: center;
        height: .77rem;
        font-size: .44rem;
        font-family: DINAlternate-Bold;
        font-weight: bold;
        color: rgba(60, 60, 60, 1);
        line-height: .51rem;
    }

    /* 按钮样式定义 */
    .swich {
        width: .52rem;
        height: .32rem;
    }

    .weui-switch-cp__box:after,
    .weui-switch:after {
        width: .28rem;
        height: .28rem;
        border-radius: .15rem;
        box-shadow: none;
    }

    .weui-switch-cp__box:before,
    .weui-switch:before {
        width: .5rem;
        height: .3rem;
        border-radius: .15rem;
    }

    .weui-switch-cp__input:checked~.weui-switch-cp__box:after,
    .weui-switch:checked:after {
        -webkit-transform: translateX(.2rem);
        transform: translateX(.2rem);
    }

    .weui-switch-cp__input:checked~.weui-switch-cp__box,
    .weui-switch:checked {
        border-color: #04A6FE;
        background-color: #04A6FE;
    }

    .weui-cell:before {
    content: " ";
    position: absolute;
    left: 0;
    top: 0rem;
    right: 0;
    height: 1px;
    border-top: 1px solid #e5e5e5;
    color: #e5e5e5;
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
    -webkit-transform: scaleY(.5);
    transform: scaleY(.5);
    left: .3rem;
    z-index: 2;
}
</style>

<body>
<div class="top-tab">
    <p><a href="#" onClick="javascript :history.back(-1);"><img src="images/icon-bark.png"></a></p>
    <p style="text-align: center">支付详情</p>
    <p></p>
</div>

<div class="regular_display">
    <?php
    $total_money = 0;
    if(isset($orderList['repair_hand_money'])){
        $total_money = $total_money + $orderList['repair_hand_money'];
    }
    if(isset($orderList['repair_part_money'])){
        $total_money = $total_money + $orderList['repair_part_money'];
    }

    ?>
    <div class="wrap flex-wrap pay-card">
        <div>支付金额</div>
        <div class="pay-money">￥<?php echo $total_money;?></div>
        <p>选择支付方式</p>
        <div class="cancell">
        <form methond="get" action="">
            <p class="sel"><input type="radio" name="radio" value="1" id="radio1" class="c-check"
                                  checked="checked"><label for="radio1"><i class="check-img"></i>&nbsp; 微信支付</label></p>
        </form>
        </div>

    </div>
    <div class="wrap">
        <div class="weui-cell">
            <div class="weui-cell__bd">抵用券</div>

            <div class="weui-cell__ft">
                <div id = "is_use" style="color: red;text-align: right">
                            <span id ="is_use_item"><?php if($have_coupon == 0){
                                    echo "暂无优惠券";
                                }else{
                                    echo "不使用优惠券";
                                }?></span>
                <input type="hidden" id = "use_item_id" value="-1">
                <img src="images/arr-item.png" alt=""style="height: 0.27rem">
                </div>
            </div>
        </div>

        <div class="weui-cell weui-cell_switch">
        <div class="weui-cell__bd">朋友代付</div>
            <div class="weui-cell__ft">
            <label for="switchCP" class="weui-switch-cp">
                <input id="switchCP" class="weui-switch-cp__input" type="checkbox" >
                <div class="weui-switch-cp__box swich"></div>
            </label>
            </div>
        </div>
    </div>

    <div class="bottom-tab">
    <p class="p1">合计金额：<span id="sum_money">￥<?php echo $total_money;?></span></p>
    <p class="p2" >确&nbsp;定</p>
    </div>

</div>
<div id="app">
<div class="coupon" style="display: none"></div>
</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="weui/js/jquery-weui.min.js"></script>
<script type="text/javascript" src="static/layer_mobile/layer.js"></script>

<script src="static/js/mobileSelect.js" type="text/javascript"></script>
<script src="static/weui/js/jquery-weui.min.js"></script>
<script src="static/js/swiper.min.js"></script>
<script src="static/js/common.js"></script>
<script type="text/javascript">

    

</script>
<script>

    $(function () {
        $("#is_use").click(function () {

            var have_coupon = <?php echo $have_coupon?>;
            var uid = <?php echo $user_info['id']  ?>;
            var service_type = <?php echo $server_type?>;
            var my_all_coupon = <?php echo json_encode($my_all_coupon)?>;
            var now_coupon = $("#use_item_id").val();
            if(have_coupon == 1){
                $.ajax({
                    type: 'post',
                    url: 'weixin_my_coupon.php?act=all',
                    data: {
                        service_type:service_type,
                        uid: uid,
                    },
                    dataType: 'json',
                    success: function (data) {
                        var msg = data.msg;
                        //       alert(msg);

                        var content = '<div class="coupon_con">';
                        var no_use1 = '  <a class="coupon_item"> ' +
                            '<div class="coupon_item_top"> ' +
                            '<div class="coupon_item_center"> ' +
                            '<span style="align-self: center;right: -50px;position: relative;">不使用优惠券</span> ' +
                            '</div> ' +
                            '<div class="coupon_item_right"> ' +
                            '<label class="coupon_label" for="coupon_radio"> ';
                        var style_no_use = "";
                        var use = "";
                        var flag ;
                        var is_checked = "";
                        var no_use_checked = "";
                        var content_item = "";
                        var content_item_content;
                        if(now_coupon == -1){
                            no_use_checked = "checked";
                        }
                        var index_radio;
//                    console.log(my_all_coupon.length);

                        for(var i = 0 ; i < my_all_coupon.length ; i++){
//                        console.log(my_all_coupon[i]);
                            style_no_use = "";
                            use = "";
                            flag = 0;
                            is_checked ="";
                            content_item_content ="";
                            for(var j =0 ;j< msg.length ;j++){
                                if(my_all_coupon[i]['myid']== msg[j]){
                                    flag = 1;
                                    break;
                                }
                            }

                            if(my_all_coupon[i]['myid'] == now_coupon){
                                is_checked = "checked";
                            }

                            if(flag) {
                                index_radio = i + 100;

                                use =   '<div class="coupon_item_right">  <label class="coupon_label" for="coupon_radio' +
                                    index_radio +
                                    '"> ' +
                                    '<input value="' +
                                    my_all_coupon[i]['myid'] +
                                    '" myname = "' +
                                    my_all_coupon[i]['money'] +
                                    '元 ' +
                                    my_all_coupon[i]['name']+
                                    '" class="coupon_radio" type="radio" id="" name="coupon_radio"' +
                                    is_checked +
                                    '> <span></span> ' +
                                    '</label></div>';
                            }else{
                                style_no_use = 'coupon_item_used';

                            }

                            content_item_content =
                                ' <a class="coupon_item"> ' +
                                '<div class="coupon_item_top"> ' +
                                '<div class="coupon_item_left"> ' +
                                '<div class="coupon_item_name ' +
                                style_no_use +
                                '">小元服务</div> ' +
                                '</div> ' +
                                '<div class="coupon_item_center"> ' +
                                '<p class="coupon_item_title ' +
                                style_no_use +
                                '">小元壁挂炉服务</p> ' +
                                '<p class="coupon_item_text"> <span class="coupon_text_red ' +
                                style_no_use +
                                '">' +
                                my_all_coupon[i]['money'] +
                                '</span> <span  class="' +
                                style_no_use+
                                '">' +
                                my_all_coupon[i]['name'] +
                                '</span> </p> ' +
                                '</div> ' +
                                use +
                                '</div><div class="coupon_item_bottom">有效期：' +
                                my_all_coupon[i]['starttime'] +
                                '-' +
                                my_all_coupon[i]['endtime'] +
                                '</div> ' +
                                '</a>';

                            if(flag){
                                content_item = content_item_content + content_item ;
                            }else{
                                content_item = content_item +content_item_content;
                            }

                        }

                        content = content + content_item + no_use1;
                        content +=  '<input class="coupon_radio" value="-1" myname = "不使用优惠券" type="radio" id="coupon_radio4" name="coupon_radio" ' +
                            no_use_checked +
                            '> ' +
                            '<span></span> ' +
                            '</label> </div> </div> ' +
                            '</a>';
                        content += '</div>';

                        $(".coupon").html(content);

                        $(".regular_display").css('display','none');
                        $(".coupon").css('display','block');
                    },
                    error : function (XMLHttpRequest, textStatus, errorThrown) {

                        layer.open({
                            content: "优惠券使用失败"
                            ,btn: '我知道了'
                        });
                    }

                });
            }


        })

    })

    $(document).on("click",'.coupon_item',function(){
//    console.log($(this).find("input").first());
        $(this).find("input").first().attr('checked','true');
        var val = $('input:radio[name="coupon_radio"]:checked').val();  //选中的优惠券的值
        var name = $('input:radio[name="coupon_radio"]:checked').attr("myname");

        $(".regular_display").css('display','block');
        $(".coupon").css('display','none');

        $("#is_use_item").html(name);
        $("#use_item_id").val(val);
        var is_use_coupon = name.indexOf("元");
        var coupon_money = 0;
        if(is_use_coupon != -1){
            coupon_money =  name.substring(0,is_use_coupon);

            var total_money = <?php echo $total_money?>;

            var  sum_money = total_money - coupon_money;

            if(sum_money >=0 ){
                $("#sum_money").html("￥"+sum_money);
            }else{
                $("#sum_money").html("￥0");
            }
        }


    })

</script>
<script>


    $(function () {
        $(".p2").click(function () {
            var num_money = $("#sum_money").html();
            num_money = num_money.substring(1);
            var user_id = "<?php echo $user_info['id'];?>";
            var coupon_id =  $("#use_item_id").val();
            var service_type = <?php echo $server_type?>;
            var order_id = <?php echo $order_id?>;


            if(num_money == 0 ){
                $.ajax({
                    type: 'POST',
                    data: {
                        money:num_money,
                        chargeNum:"<?php echo $chargeNum?>",
                        coupon_id :coupon_id,
                        service_type:service_type,
                        order_id :order_id,
                        user_id :user_id
                    },
                    dataType: 'json',
                    url: '../pay/weixin_pay_do.php?act=zero_pay',
                    success: function (data) {
                        var code = data.code;
                        var msg = data.msg;

                        switch (code)
                        {
                            case 1:
                                layer.msg(msg);
                                location.href = "pay_success.php?money="+money+"&user_id="+user_id;

                                break;
                            default:
                        }

                    }
                });

            }else{

                $.ajax({
                    type: 'POST',
                    data: {
                        money:num_money,
                        openId:"<?php echo $userOpenId?>",
                        chargeNum:"<?php echo $chargeNum?>",
                        coupon_id :coupon_id,
                        service_type:service_type,
                        order_id :order_id,
                        user_id :user_id
                    },
                    dataType: 'json',
                    url: '../pay/weixin_pay_do.php?act=pay',
                    success: function (data) {
                        var code = data.code;
                        var msg = data.msg;

                        switch (code)
                        {
                            case 1:

                                callpay(msg , num_money ,user_id , order_id , coupon_id);

                                break;
                            default:
                        }

                    }
                });

            }

        });
    })



        //调用微信JS api 支付
        function jsApiCall(jsApiParameters ,money ,user_id , order_id , coupon_id)
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                jsApiParameters,
                function(res){
                    if(res.err_msg=="get_brand_wcpay_request:ok")
                    {
                        location.href = "pay_success.php?money="+money+"&user_id="+user_id;

                    }
                    else if(res.err_msg=="get_brand_wcpay_request:cancel")
                    {


                        if(coupon_id != -1){

                            $.ajax({
                                type: 'POST',
                                data: {
                                    coupon_id :coupon_id,
                                    order_id :order_id,
                                },
                                dataType: 'json',
                                url: '../pay/weixin_pay_do.php?act=fail_pay',
                                success: function (data) {
                                    var code = data.code;
                                    var msg = data.msg;
                                    switch (code)
                                    {
                                        case 1:
                                            alert("支付取消");
                                            break;
                                        default:
                                    }

                                }
                            });
                        }else{
                            alert("支付取消");

                        }


                    }
                    else
                    {
                        if(coupon_id != -1){
                            $.ajax({
                                type: 'POST',
                                data: {
                                    coupon_id :coupon_id,
                                    order_id :order_id,
                                    user_id :user_id
                                },
                                dataType: 'json',
                                url: '../pay/weixin_pay_do.php?act=fail_pay',
                                success: function (data) {
                                    var code = data.code;
                                    var msg = data.msg;
                                    switch (code)
                                    {
                                        case 1:
                                            alert("支付失败");
                                            break;
                                        default:
                                    }

                                }
                            });

                        }else{
                            alert("支付失败");

                        }
                    }
                }
            );
        }

        function callpay(jsApiParameters ,money ,user_id , order_id , coupon_id)
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall(jsApiParameters ,money ,user_id , order_id , coupon_id);
            }
        }



</script>


</html>