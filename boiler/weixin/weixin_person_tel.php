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
    <title>个人信息-电话</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script type="text/javascript" src="static/layer_mobile/layer.js"></script>

    <script src="static/js/query.js"></script>
</head>

<body>
<div id="app">
    <div class="personal-center">
        <div class="personal-center-head flex">
            <img class="person-img" src="static/images/icon-bark.png" alt="">
            <span class="person-name">电话</span>
            <button type="button" class="complate">完成</button>
        </div>
        <div class="personal-center-con person-form">
            <div class="form-item">
                <div class="form-item-title flex">
                    <img src="static/images/person-star.png" alt="">
                    <span>电话</span>
                </div>
                <div class="form-input flex">
                    <input id="input-tel" value="<?php echo $userInfo['phone'];?>" type="text">
                </div>
            </div>
            <div class="form-item">
                <div class="form-item-title flex">
                    <img src="static/images/person-star.png" alt="">
                    <span>短信验证码</span>
                </div>
                <div class="form-input-wrap flex">
                    <div class="form-input flex" style="width: 100px;">
                        <input placeholder="请输入短信验证码" type="text" id="input-code">
                    </div>
                    <button id="code" disabled="disabled">获取短信验证码</button>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item-tip flex">
                    <img src="static/images/person-tip.png" alt="">
                    <span>注：此电话是您注册时绑定的手机号，如果变更，再次登录时请用此手机号登录。</span>
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
        $('#input-tel').bind('input propertychange', function() {
            var val = $(this).val().length;
            if (val != 11) {
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active");
            } else {
                $("#code").removeAttr("disabled");
                $("#code").addClass("active");
            }
        });

        var num = 60;
        var timer = null;

        $("#code").on("click",function (e) {
            e.preventDefault();

            var phone = $("#input-tel").val();
            var isNumber = /^[0-9]+.?[0-9]*$/;


            if(!isNumber.test(phone)){
                $('input[name="input-tel"]').focus();
                $("#input-tel").val("");

                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active");
                layer.open({
                    content: "手机号码格式不正确"
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                return false;
            }

//            $.ajax({
//                type        : 'POST',
//                data        : {
//                    mobile         : phone,
//                },
//                dataType : 'json',
//                url :         'get_code.php',
//                success :     function(data){
//                    var code = data.code;
//                    var msg  = data.msg;
//                    switch(code){
//                        case 1:
            //获取成功执行以下函数
            //获取验证码

            var _this = $(this);

            timer = setInterval(function () {
                num --;
                _this.text("("+num+"s)重新获取");
                _this.attr("disabled","disabled").removeClass("active-code");
                if (num == 0) {
                    _this.text("获取验证码");
                    _this.removeAttr("disabled");
                    clearInterval(timer);
                    num = 60;
                }
            },1000);

//                            break;
//                        default :
//                            alert("请求失败");
//
//                    }
//                }
//            });

        })
        $(".person-img").click(function () {
            location.href = "weixin_personal_detail.php";
        })
        $(".complate").click(function () {

            var phone = $("#input-tel").val();
            var isNumber = /^[0-9]+.?[0-9]*$/;

            if(phone == ''){
                $('input[name="input-tel"]').focus();

                $("#input-tel").val("");
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");
                layer.open({
                    content: "手机号码不能为空"
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                return false;
            }else if(!isNumber.test(phone) || phone.length != 11){
                $('input[name="input-tel"]').focus();
                $("#input-tel").val("");
                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active-code");
                layer.open({
                    content: "手机号码格式不正确"
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });

                return false;

            }
            var verifyCode = $("#input-code").val();
            if(verifyCode == ""){
                layer.open({
                    content: "验证码不能为空"
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });

                return false;
            }

            var type ='tel';
            var id = "<?php echo $userInfo['id'];?>";
            $.ajax({
                type : 'post',
                url: "weixin_edit_person_do.php",
                data :{
                    type : type,
                    id:id,
                    phone:phone,
                    verifyCode:verifyCode,
                },
                dataType: 'json',

                success: function (data) {

                    var code=data.code;
                    var msg=data.msg;

                    switch (code){
                        case 1:

                            layer.open({
                                content: msg
                                ,btn: ['确定']
                                ,yes: function(index){
                                    location.href = "weixin_personal_detail.php";
                                    layer.close(index);
                                }
                            });

                            break;
                        default :
                            layer.open({
                                content: msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                    }
                },
                error: function () {
                    layer.open({
                        content: "请求失败"
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            })




        })

    })
</script>

</html>