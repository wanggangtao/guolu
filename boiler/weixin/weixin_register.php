<!DOCTYPE html>
<html lang="en">
<?php
    require_once ("admin_init.php");
    $userOpenId = "";
    if($isWeixin)
    {
        $userOpenId = common::getOpenId();
        $weixin = new weixin();
        $personal_info = $weixin->getUserInfo($userOpenId);
        if(isset($personal_info['nickname'])){
            $nickname = $personal_info['nickname'];
        }else{
            $nickname = "";
        }
    }
//error_reporting(E_ALL);
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>个人信息-注册</title>
    <!-- <link rel="stylesheet" href="weui/css/weui.min.css" /> -->
    <link rel="stylesheet" href="static/weui/css/jquery-weui.min.css" />
    <link rel="stylesheet" href="static/weui/css/weuix.css" />
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css" />
    <link rel="stylesheet" href="static/css/inputAutoTips.css">

    <!-- <script src="js/query.js"></script> -->
    <script type="text/javascript" src="static/layer_mobile/layer.js"></script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/common.js"></script>

    <script src="static/weui/js/jquery-weui.min.js"></script>
    <script src="static/weui/js/picker.city.js"></script>
    <script src="static/js/swiper.min.js"></script>
    <script type="text/javascript" src="static/js/rolldate.min.js"></script>
    <script type="text/javascript" src="static/layer/layer.js"></script>

</head>
<script type="text/javascript">

    $(function () {
        $("#btn_submit").click(function () {


            var openId = "<?php echo $userOpenId?>";
            var nickname = "<?php echo $nickname?>";
            var client_name = $("#client_name").val();
            var barCode = $("#barCode").val();
            var city = $("#city-picker").val();
            var area = $("#area-picker").val();
            var detail = $("#detail").val();
            var shortMessage = $("#shortMessage").val();
            var phone = $("#input-tel").val();
//            var warranty = $("#warranty").val();
            if(barCode == ''){

                layer.msg("条码不能为空！");

                return false;
            }

            if(city == ""){

                layer.msg("省/市/区选择不能为空！");

                return false;
            }

            if(area == ""){

                layer.msg("小区选择不能为空！");

                return false;
            }

            if(detail == ""){

                layer.msg("详细地址不能为空！");

                return false;
            }
            if(client_name == ''){

                layer.msg("姓名不能为空！");

                return false;
            }

            if(shortMessage == ''){

                layer.msg("验证码不能为空！");

                return false;
            }
//            if(warranty == ''){
//
//                layer.msg("质保日期不能为空！");
//
//                return false;
//            }
            $.ajax({
                type: 'post',
                url: 'weixin_register_do.php?act=register',
                data: {
                    openId:openId,
                    client_name:client_name,
                    barCode :barCode,
                    area :area,
                    city: city,
                    detail :detail,
                    phone: phone,
                    shortMessage:shortMessage,
//                    warranty :warranty,
                    nickname : nickname
                },
                dataType: 'json',
                success: function (data) {
//                    alert(data.code);
                    var code = data.code;
                    var msg = data.msg;
                    switch (code){
                        case 1:

                            layer.msg(msg);
                            location.href = "coupon_tip.php";


                            break;
                        case 2:

                            layer.msg(msg);
                            window.setTimeout("location.href ='weixin_personal_detail.php'",1000);
                            break;
                        default :

                            layer.msg(msg);


                            break;
                    }

                },
                error : function (XMLHttpRequest, textStatus, errorThrown) {
//                    // 状态码
//                    alert(XMLHttpRequest.status);
////                    // 状态
//                    alert(XMLHttpRequest.readyState);
                    // 错误信息

//                    layer.msg(textStatus);
                    layer.msg("网络异常，请重新注册");

//                    alert(textStatus);
                }
            });
        });
        $("#barCode").blur(function (e) {
            var barCode = $("#barCode").val();
            if(!barCode){

                layer.msg("请先输入条码");

                return false;
            }
//          alert(barCode);
            $.ajax({
                type:'post',
                url: 'weixin_register_do.php?act=verifyCode',
                data:{
                    barCode :　barCode
                },
                dataType:'json',
                success:function (data) {
                    var code = data.code;
                    var msg  = data.msg;
                    switch (code){
                        case 1:
                            var arr = eval(msg);
//                            alert(typeof (arr));

                            if(typeof (arr) != "undefined"){
                                $("#city-picker").val(arr.contact_address) ;
                                $("#detail").val(arr.detail_addres) ;
                                $("#area-picker").val(arr.community);
                                packerInit(arr.childName);
                            }else{
                                if(!$("#city-picker").val()){
                                    $("#city-picker").val("") ;
                                }
                                if(!$("#detail").val()){
                                    $("#detail").val("") ;
                                }
                                if(!$("#area-picker").val()){
                                    $("#area-picker").val("") ;
                                    packerInit("");
                                }
                            }
                            break;
                        default :
                            e.preventDefault();
                            $("#barCode").val("");

                            layer.msg(msg);

                    }
                },
                error:function () {
                    e.preventDefault();
                    $("#barCode").val("");

                    layer.msg("请求失败");

                }
            })
        });
    })


//    window.onload = function(){
//        new rolldate.Date({
//            el:'#warranty',
//            format:'YYYY-MM-DD',
//            beginYear:2000,
//            endYear:2100
//        })
//
//    }

</script>

<body>
<div id="app">
    <div class="person-wrap">
        <div class="person-top">
            <div class="person-title">注册</div>
            <div class="person-tip flex">
                <img src="static/images/person-tip.png" alt="" />
                <span>温馨提示：* 为必填项。</span>
            </div>
        </div>
        <div class="person-form">
            <div class="form-title flex">
                <p></p>
                <span>注册</span>
            </div>
            <form>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <img src="static/images/person-star.png" alt="" />
                        <span>产品条码</span>
                    </div>
                    <!-- onkeyup="findNames()" -->
                    <div class="form-input flex">
                        <input
                                placeholder="请输入产品条码"
                                type="text"
                                id ="barCode"
                                
                        />
<!--                        <img src="static/images/arrow-right.png" alt="" />-->

                    </div>
                    <div id="form-box">
                        <p class="form-box-item">000111</p>      
                    </div>
                </div>
                <p>
                <!-- <div  style="padding-left:5px id="popup">
                    <ul id="ul_name"></ul>
                </div> -->
                </p>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <img src="static/images/person-star.png" alt="" />
                        <span>地址</span>
                    </div>
                    <div class="form-input flex">
                        <input
                                id="city-picker"
                                placeholder="请选择省/市/区"
                                type="text"
                                readonly="readonly"

                        />
                        <img src="static/images/arrow-right.png" alt="" />
                    </div>
                    <div class="form-input flex" id="box">
                        <input
                                id="area-picker"
                                placeholder="请选择小区"
                                type="text"
                                readonly="readonly"
                        />
                        <img src="static/images/arrow-right.png" alt="" />
                    </div>
                    <div class="form-input flex">
                                <textarea
                                        name="detail"
                                        placeholder="请输入详细地址"
                                        id="detail"
                                ></textarea>
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <img src="static/images/person-star.png" alt="" />
                        <span>姓名</span>
                    </div>
                    <div class="form-input flex">
                        <input
                                placeholder="请输入您的姓名"
                                type="text"
                                id = "client_name"
                        />
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <img src="static/images/person-star.png" alt="" />
                        <span>电话</span>
                    </div>
                    <div class="form-input flex">
                        <input
                                id="input-tel"
                                name="input-tel"
                                placeholder="请输入您的联系方式"
                                type="text"
                        />
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item-title flex">
                        <img src="static/images/person-star.png" alt="" />
                        <span>短信验证码</span>
                    </div>
                    <div class="form-input-wrap flex">
                        <div class="form-input flex" style="width: 100px;">
                            <input
                                    placeholder="请输入短信验证码"
                                    type="text"
                                    id ="shortMessage"
                            />
                        </div>
                        <button id="code" disabled="disabled">
                            获取验证码
                        </button>
                    </div>
                </div>

<!--                <div class="form-item">-->
<!--                    <div class="form-item-title flex">-->
<!--                        <img src="static/images/person-star.png" alt="" />-->
<!--                        <span>质保日期</span>-->
<!--                    </div>-->
<!--                    <div class="form-input flex">-->
<!--                        <input readonly class="form-control" type="text" id="warranty" placeholder="请输入质保日期">-->
<!--                    </div>-->
<!--                </div>-->

                <div class="from-submit">
                    <input type="button" value="注册" id="btn_submit" />
                </div>
            </form>
        </div>
    </div>
</div>
</body>

<script>
    $(function() {

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
            var _this = $(this);

            var phone = $("#input-tel").val();
            var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;

            _this.attr("disabled","disabled");
            _this.removeClass("active-code");

          if(!isNumber.test(phone)){
                $('input[name="input-tel"]').focus();
                $("#input-tel").val("");

                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active");

              layer.msg("手机号码格式不正确");

              return false;
            }
            e.preventDefault();


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
                            //获取成功执行以下函数
                            //获取验证码

                            timer = setInterval(function () {
                                num --;
                                _this.text("("+num+"s)重新获取");
                                _this.attr("disabled","disabled");
                                _this. removeClass("active");
                                if (num == 0) {
                                    _this.text("获取验证码");
                                    _this.removeAttr("disabled");
                                    _this.addClass("active");
                                    clearInterval(timer);
                                    num = 60;
                                }
                            },1000);

                            break;
                        default :

                            layer.msg("请求失败");

                    }
                }
            });

        })
        $("#shortMessage").blur(function (e) {

            var phone = $("#input-tel").val();
            var isNumber =/^[1][3,4,5,7,8,9][0-9]{9}$/;


            if(!isNumber.test(phone)){
                $('input[name="input-tel"]').focus();
                $("#input-tel").val("");

                $("#code").attr("disabled","disabled");
                $("#code").removeClass("active");

                layer.msg("手机号码格式不正确");

                return false;
            }

        })
//        $("#city-picker").on("click",function() {
//            setTimeout(function(){

                $("#city-picker").cityPicker({
                    title: "选择省市区/县",
                    onChange : function(picker, values, displayValues) {
                        console.log(values, displayValues);
                    }
                });
//            }, 1000);



//        })


        $("body").on("click", ".hd-close,.close-picker", function() {

            if($(".title").text() == "选择省市区/县"){
                $("#box").empty();
                $("#box").html("<input type='text' id='area-picker' placeholder='请选择小区' value=''/><img src='images/arrow-right.png' />");
                var address = $("#city-picker").val();
                $.ajax({
                    url: "address_do.php",
                    data: {
                        address : address,
                    },
                    type: "Post",
                    dataType: "json",

                    success: function (result) {
//                        alert(result);
                        packerInit(result);
                    },
                })
            }

        });
        
        // 条码
//        $("#barCode").bind("input propertychange", function() {
//            var val = $(this).val();
//            if (val == "") {
//                $("#form-box").hide();
//                //clearNames();
//            } else {
//                $.get(
//                "inputAutoTips.php",{oData: val} ,function(data){
//                    var aResult = new Array();
//
//                    if($.trim(data).length != 0){
//                        aResult = data.split(",");
////                        var nameArr = aResult.pop();
//                        var nameLength = aResult.length;
////                        alert(nameLength);
//                        //console.log(aResult,nameArr,nameLength);
//                        if(nameLength >= 5){
//                            nameLength = 4
//                        }
//                        var html = "";
//                        if (nameLength == 0) {
//                            $("#form-box").show();
//                            $("#form-box").html("<p>暂无结果</p>")
//                        } else {
//                            $("#form-box").show();
//                            for(var i=0;i<nameLength;i++){
//                                if(aResult[i] == "") continue;
//                                html += "<p>"+aResult[i]+"</p>";
//                            }
//                            $("#form-box").html(html);
//                        }
//                    }else{
//                        $("#form-box").show();
//                        $("#form-box").html("<p>暂无结果</p>")
//                    }
//                });
//
//            }
//        });

//        $("body").on("mousedown", "#form-box p",function () {
//            var text = $(this).text();
//            if (text == "暂无结果") {
//                $("#form-box").hide();
//                $("#barCode").val("");
//            } else {
//                $("#form-box").hide();
//                $("#barCode").val(text);
//            }
//        })

    });



    function packerInit(v) {
//        alert(v);
        $("#area-picker").picker({
            title: "请选择小区",
            cols: [
                {
                    textAlign: 'center',
                    values: ['金辉世界城','紫郡长安','曲江千林郡','中海凯旋门','长丰园小区','其他']
                }
            ],
            onOpen:function (picker) {
                picker.cols[0].replaceValues(v);
                picker.updateValue();
//                picker.pickers[0].setSelectedIndex(1)

            },
            onChange: function(p, v, dv) {
                console.log(v);
            },
            onClose: function(p, v, d) {
                console.log("close");
            }

        });

    }

    $("input,textarea").on("blur",function(){
        setTimeout(function(){
            window.scrollTo(0,0);
        },100)
    }).on('focus',function(){
        var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
        var offsetTop = $(this).offset().top - (clientHeight / 4);
        setTimeout(function(){
            window.scrollTo(0,offsetTop);
        },100)
    })

</script>

</html>
