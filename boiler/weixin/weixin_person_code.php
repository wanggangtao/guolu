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
    <title>个人信息-条形码</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script src="static/js/query.js"></script>
    <script type="text/javascript" src="static/layer_mobile/layer.js"></script>

</head>

<body>
<div id="app">
    <div class="personal-center">
        <div class="personal-center-head flex">
            <img class="person-img" src="static/images/icon-bark.png" alt="">
            <span class="person-name">条形码</span>
            <button type="button" class="complate">完成</button>
        </div>
        <div class="personal-center-con person-form">
            <div class="form-item">
                <div class="form-input flex">
                    <input id ="code" name="code" type="text" value="<?php echo $userInfo['product_code']?>">
                </div>
                <div id="form-box">
                    <p class="form-box-item">000111</p>
                </div>
            </div>
            <div class="form-item">
                <div class="form-item-tip flex">
                    <img src="static/images/person-tip.png" alt="">
                    <span>注：如果变更条形码，地址会相应变化，如果地址跟您居住地址不匹配，请联系客服400-9665890。</span>
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
        $(".person-img").click(function () {
            location.href = "weixin_personal_detail.php";
        })
        $(".complate").click(function () {
            var inpt_code = $("#code").val();
            var type ='code';
            var id = "<?php echo $userInfo['id'];?>";
            var nowcode = "<?php echo $userInfo['product_code'];?>";

            $.ajax({
                type:'post',
                url: 'weixin_register_do.php?act=verifyCode',
                data:{
                    barCode :　inpt_code
                },
                dataType:'json',
                success:function (data) {
                    var code = data.code;
                    var msg  = data.msg;
                    switch (code){
                        case 1:
                            layer.open({
                                content: '确定修改？'
                                ,btn: ['确定','取消']
                                ,yes: function(index){
                                    $.ajax({
                                        type : 'post',
                                        url: "weixin_edit_person_do.php",
                                        data :{
                                            type : type,
                                            id:id,
                                            inpt_code:inpt_code,
                                            nowcode :nowcode
                                        },
                                        dataType: 'json',

                                        success: function (data) {

                                            var code=data.code;
                                            var msg=data.msg;

                                            switch (code){
                                                case 1:

                                                    location.href = "weixin_personal_detail.php";
                                                    layer.close(index);

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
                                }
                            });
                            break;
                        default :
                            $("#code").val("");
                            layer.open({
                                content: msg
                                ,btn: '我知道了'
                            });
                    }
                },
                error:function () {
                    layer.open({
                        content: "请求失败"
                        ,btn: '我知道了'
                    });
                }
            })





        })

        $("#code").bind("input propertychange", function() {
            var val = $(this).val();
            if (val == "") {
                $("#form-box").hide();
                //clearNames();
            } else {
                $.get(
                    "inputAutoTips.php",{oData: val} ,function(data){
                        var aResult = new Array();

                        if($.trim(data).length != 0){
                            aResult = data.split(",");
//                        var nameArr = aResult.pop();
                            var nameLength = aResult.length;
//                        alert(nameLength);
                            //console.log(aResult,nameArr,nameLength);
                            if(nameLength >= 5){
                                nameLength = 4
                            }
                            var html = "";
                            if (nameLength == 0) {
                                $("#form-box").show();
                                $("#form-box").html("<p>暂无结果</p>")
                            } else {
                                $("#form-box").show();
                                for(var i=0;i<nameLength;i++){
                                    if(aResult[i] == "") continue;
                                    html += "<p>"+aResult[i]+"</p>";
                                }
                                $("#form-box").html(html);
                            }
                        }else{
                            $("#form-box").show();
                            $("#form-box").html("<p>暂无结果</p>")
                        }
                    });

            }
        });

        $("body").on("mousedown", "#form-box p",function () {
            var text = $(this).text();
            if (text == "暂无结果") {
                $("#form-box").hide();
                $("#code").val("");
            } else {
                $("#form-box").hide();
                $("#code").val(text);
            }
        })
    })

</script>

</html>