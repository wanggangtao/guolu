<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/17
 * Time: 22:04
 *
 * 个人信息
 */

require_once("admin_init.php");
$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();
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
    <title>登录</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css?v=12221" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script src="static/js/query.js"></script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/swiper.min.js"></script>
    <script src="static/js/common.js"></script>
    <script type="text/javascript" src="static/layer_mobile/layer.js"></script>

</head>


<script type="text/javascript">
    $(function(){


        /**点击登录**/
        $('#btn-login').click(function(){
            var type = <?php echo $type;?>

            var phone = $("#input-tel").val();
            var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;

            if(!isNumber.test(phone)){
                $('input[name="input-tel"]').focus();
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");
                $("#btn-login").removeClass("active-login");

                $("#input-tel").val("");

                layer.open({
                    content: "手机号码格式不正确"
                    ,btn: '我知道了'
                });
                return false;

            }
            var ac = $("#input-tel").val();
            var verifyCode = $("#input-code").val();

            var userOpenId = "<?php echo $userOpenId?>";

            $.ajax({
                type        : 'POST',
                data        : {
                    account         : ac,
                    verifyCode      : verifyCode,
                    userOpenId      : userOpenId,
                },
                dataType : 'json',
                url :         'login_do.php?act=verifyAccount',
                success :     function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:

                            if(type == 1){
                                location.href = 'weixin_personal_detail.php';
                            }else{
                                location.href = 'weixin_repair.php';
                            }
                            break;
                        default:

                            layer.open({
                                content: msg
                                ,btn: '我知道了'
                            });
                    }
                }
            });
        });

    })


</script>
<body>
<div id="app">
    <div class="person-wrap">
        <div class="login-top-wrap">
            <div class="login-top flex">
                <img src="static/images/bg-login.png" alt="">
                <div>
                    <p>小元服务</p>
                    <span>壁挂炉综合服务平台</span>
                </div>
            </div>
        </div>
        <div class="login-form">
            <form>
                <div class="form-item">
                    <div class="form-input flex">
                        <input id="input-tel" placeholder="请输入手机号" type="text">
                        <img id="input-close" src="static/images/input-close.png" alt="">
                    </div>
                    <div class="form-input flex">
                        <input id="input-code" placeholder="请输入短信验证码" type="text">
                        <button type="button" class="code" id="code" disabled="disabled">获取验证码</button>
                    </div>
                </div>
                <div class="from-btn flex">
                    <button type="button"  id="btn-login">登录</button>
                    <button type="button" id="btn-register" >注册</button>
                </div>
            </form>
        </div>
    </div>

</div>
</body>

<script>
    $(function () {
        $("#btn-register").click(function () {

            location.href = "weixin_register.php";
        })
        $("#input-close").hide();
        $("#input-close").on("click",function () {
            $("#input-tel").val("");
            $(this).hide();
        });
        $('#input-tel').bind('input propertychange', function() {
            var val = $(this).val().length;
            if (val != 11) {
                $("#input-close").hide();
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");
            } else {
                $("#input-close").show();
                $("#code").removeAttr("disabled");
                $("#code").addClass("active-code");
            }
        });

        $("#input-code").on("click",function (e) {

            var phone = $("#input-tel").val();
            var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;

            if(phone == ''){
                $('input[name="input-tel"]').focus();

                $("#input-tel").val("");
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");

                layer.open({
                    content: "手机号码不能为空"
                    ,btn: '我知道了'
                });
                return false;
            }else if(!isNumber.test(phone) || phone.length != 11){
                $('input[name="input-tel"]').focus();
                $("#input-tel").val("");
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");
                layer.open({
                    content: "手机号码格式不正确"
                    ,btn: '我知道了'
                });
                return false;

            }else{
                var _this = $(this);
                $.ajax({
                    type        : 'POST',
                    data        : {
                        account         : phone,
                    },
                    dataType : 'json',
                    url :         'login_do.php?act=verifyPhone',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                break;
                            default:
                                $("#code").attr("disabled","disabled");
                                $("#code").removeClass("active-code");
                                layer.open({
                                    content: msg
                                    ,btn: '我知道了'
                                });

                        }
                    }
                });
            }

        })

        $('#input-code').bind('input propertychange', function() {
            var val = $(this).val();
            if (val == "") {
                $("#btn-login").removeClass("active-login");
            } else {
                $("#btn-login").addClass("active-login");
            }
        });


        var num = 60;
        var timer = null;


        $("#code").on("click",function (e) {
            e.preventDefault();

            var _this = $(this);

            _this.attr("disabled","disabled");
            _this.removeClass("active-code");

            var phone = $("#input-tel").val();
            var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;

            if(phone == ''){
                $('input[name="input-tel"]').focus();

                $("#input-tel").val("");
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");

                layer.open({
                    content: "手机号码不能为空"
                    ,btn: '我知道了'
                });
                return false;
            }else if(!isNumber.test(phone) || phone.length != 11){
                $('input[name="input-tel"]').focus();
                $("#input-tel").val("");
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");

                layer.open({
                    content: "手机号码格式不正确"
                    ,btn: '我知道了'
                });
                return false;

            }else{


                $.ajax({
                    type        : 'POST',
                    data        : {
                        account         : phone,
                    },
                    dataType : 'json',
                    url :         'login_do.php?act=verifyPhone',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                            $.ajax({
                                type        : 'POST',
                                data        : {
                                    mobile         : phone,
                                },
                                dataType : 'json',
                                url :         'get_code.php',
                                success :     function(data){
                                    var code = data.code;
                                    var msg  = data.msg;
                                    switch(code){
                                        case 1:

                                timer = setInterval(function () {
                                    num --;
                                    _this.text("("+num+"s)重新获取");
                                    _this.attr("disabled","disabled");
                                    _this.removeClass("active-code");
                                    if (num == 0) {
                                        _this.text("获取验证码");

                                        _this.removeAttr("disabled");
                                        _this.addClass("active-code");
                                        clearInterval(timer);
                                        num = 60;
                                    }
                                },1000);

                                            break;
                                        default :
                                            alert("请求失败");

                                    }
                                }
                            });
                                break;
                            default:
                                $("#code").attr("disabled","disabled");
                                $("#code").removeClass("active-code");

                                layer.open({
                                    content: msg
                                    ,btn: '我知道了'
                                });
                        }
                    }
                });
            }



        })

    })
</script>

</html>