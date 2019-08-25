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
    <title>个人信息-姓名</title>
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
            <span class="person-name">姓名</span>
            <button type="button" class="complate">完成</button>
        </div>
        <div class="personal-center-con person-form">
            <div class="form-item">
                <div class="form-input flex">
                     <input id ="userName" name="userName" type="text" value="<?php echo $userInfo['name']?>">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        $(".person-img").click(function () {
            location.href = "weixin_personal_detail.php";
        })
        $(".complate").click(function () {
            var userName = $("#userName").val();
            var type ='name';
            var id = "<?php echo $userInfo['id'];?>";
            layer.open({
                content: '修改成功？'
                ,btn: ['确定','取消']
                ,yes: function(index){

                    $.ajax({
                        type : 'post',
                        url: "weixin_edit_person_do.php",
                        data :{
                            type : type,
                            id:id,
                            name:userName
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



        })

    })
</script>

</html>