<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/18
 * Time: 12:56account
 */
require_once "admin_init.php";
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();//获取用户的openid，只要用户关注就会有一个openid且唯一
}
$user_info = user_account::getInfoByOpenid($userOpenId);//根据openid查找出用户的相关信息
$weixin = new weixin();//生成新的对象
$personal_info = $weixin->getUserInfo($userOpenId);//查找出用户的私人信息
if(empty($user_info)){//如果用户信息为空，则返回到登录的页面
    header("Location: weixin_login.php");
    exit;
}
if(empty($user_info['nickname'])){
    $nickname = filter($personal_info['nickname']);
    user_account::update($user_info['id'],array('nickname'=>$nickname));
}

/**提交代码的时候将下边的注释，,现在是将条码写死了，为了调试方便，将上边的放开*/
//$userOpenId = "om86uuMzFWIytR_S5SHbMZTCVw0A";//你是谁
//$userOpenId="om86uuCRcTOSedWjSpdytUWmI-34";//武鹏
$user_info = user_account::getInfoByOpenid($userOpenId);
//var_dump($user_info) ;
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>个人信息</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css?v=1.01" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script src="static/js/query.js"></script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/swiper.min.js"></script>
    <script src="static/js/common.js"></script>
    <script type="text/javascript" src="static/layer_mobile/layer.js"></script>

</head>

<body>
<script type="text/javascript">

    $(function () {

        $("#logout").click(function () {

            var openId = "<?php echo $userOpenId?>";

            layer.open({
                content: '亲，确定要退出吗？'
                ,btn: ['确定','取消']
                ,yes: function(index){

                    $.ajax({
                        type : 'post',
                        url: "login_out.php",
                        data :{
                            openId : openId
                        },
                        dataType: 'json',

                        success: function (data) {

                            var code=data.code;
                            var msg=data.msg;

                            switch (code){
                                case 1:

                                    location.href = "weixin_login.php";
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

<div id="app">
    <div class="personal-info">
        <div class="personal-info-head flex">
            <div class="info-head flex">
                <img class="person-img" src="<?php echo $personal_info['headimgurl']?>" alt="">
                <span class="person-name"><?php if(isset($user_info['name'])) echo $user_info['name'];?></span>
            </div>
            <button type="button" id="logout">注销</button>
        </div>
        <div class="bg-gray"></div>
        <div class="personal-cells">
            <a class="personal-cell flex" href="">
                <div class="cell-left flex" >

                    <img src="static/images/icon-name.png">
                      <span >
                              姓名：<?php if(isset($user_info['name'])) echo $user_info['name'];
                          else{
                              echo "暂无";
                          }?>
                      </span>
                </div>
                <div class="cell-right flex">
<!--                    <img src="static/images/arr-item.png" alt="">-->
                </div>
            </a>
            <a class="personal-cell flex" href="">
                <div class="cell-left flex">
                    <img src="static/images/icon-tell.png" alt="">
                    <span >电话：<?php if(isset($user_info['phone']))
                        echo $user_info['phone'];
                        else{
                            echo "暂无";
                        };?></span>
                </div>
                <div class="cell-right flex">
<!--                    <img src="static/images/arr-item.png" alt="">-->
                </div>
            </a>
            <a class="personal-cell flex" href="">
                <div class="cell-left flex">
                    <img src="static/images/icon-code.png" alt="">
                    <span >条码：<?php
                        if(!empty($user_info['product_code'])){
                            echo $user_info['product_code'];
                        }else{
                            echo "暂无";
                        }
                        ?>
                    </span>
                </div>
                <div class="cell-right flex">
<!--                    <img src="static/images/arr-item.png" alt="">-->
                </div>
            </a>

            <a class="personal-cell flex" href="">
                <div class="cell-left flex">
                    <img src="static/images/icon-address.png" alt="">
                    <span >地址：
                        <?php
                            if(!empty($user_info['contact_address']))
                                echo $user_info['contact_address'];
                            else{
                                echo "暂无";
                            }
                        ?>
                    </span>
                </div>
                <div class="cell-right flex">
<!--                    <img src="static/images/arr-item.png" alt="">-->
                </div>
            </a>
            <a class="personal-cell flex" href="../weixinHtml/my_order.html?id=<?php echo $user_info['id'];?>">
                <div class="cell-left flex">
                    <img src="static/images/bg-login.png" alt="">
                    <span>我的预约</span>
                </div>
                <div class="cell-right flex">
                    <img src="static/images/arr-item.png" alt="">
                </div>
            </a>

            <a class="personal-cell flex" href="weixin_coupon_have.php?">
                <div class="cell-left flex">
                    <img src="static/images/coupon.png" alt="">
                    <span>我的优惠券</span>
                </div>
                <div class="cell-right flex">
                    <img src="static/images/arr-item.png" alt="">
                </div>
            </a>


            <a class="personal-cell flex" href="weixin_repair_recode.php?code=<?php echo $user_info['product_code'];?>">
                <div class="cell-left flex">
                    <img src="static/images/icon-pro.png" alt="">
                    <span>服务记录</span>
                </div>
                <div class="cell-right flex">
                    <img src="static/images/arr-item.png" alt="">
                </div>
            </a>
            <?php

                echo "<p style='color: red;text-indent:2em'><font size ='2'>提示：如信息不匹配，请联系客服400-9665890。</font></p>";
            ?>
        </div>
        <div class="personal-bottom">
            <a href="weixin_repair.php" >一键预约</a>
        </div>
    </div>

</div>
<script type="text/javascript">
</script>
</body>


</html>