<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <link rel="stylesheet" href="static/css/main.css">
    <script type="text/javascript" src="static/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="static/js/layui/layui.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#account').blur(function(){
                var account = $('#account').val();
//                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
//                if (!(reg.test(account))) {
//                    layui.use('layer', function(){
//                        var layer = layui.layer;
//                        layer.alert('手机号格式不正确！', {icon: 5});
//                    });
//                    return false;
//                }
            });
            /**点击登录**/
            $('#btn_login').click(function(){
                var ac      = $('input[name="account"]').val();
                var pwd     = $('input[name="password"]').val();
                var remember = 0;
                if($('#mds_3').prop('checked')){
                    remember = 1;
                }

                if(ac == ''){
                    $('input[name="account"]').focus();
                    return false;
                }
                if(pwd == ''){
                    $('input[name="pwd"]').focus();
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        account   : ac,
                        pass      : pwd,
                        remember  : remember
                    },
                    dataType : 'json',
                    url :         'login_do.php',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                location.href = 'home.php';
                                break;
                            default:
                                layui.use('layer', function(){
                                    var layer = layui.layer;
                                    layer.alert(msg, {icon: 5});
                                });
                        }
                    }
                });
            });

        });
    </script>
</head>
<body class="body_1">
<div class="logon_1">西安元聚环保设备有限公司</div>
<div class="logon_2">
    <input class="logon_2_1" placeholder="账户" id="account" name="account">
    <input class="logon_2_2" placeholder="密码" id="password" name="password" type="password">
<!--    <div class="row">-->
<!--        <input type="checkbox" id="mds_3">-->
<!--        <label for="mds_3" class="green"></label>-->
<!--    </div>-->
<!--    <div class="logon_2_4">记住密码</div>-->
    <a><button class="logon_2_3" id="btn_login"><span>登录</span></button></a>
</div>
</body>
</html>