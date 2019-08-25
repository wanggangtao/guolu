<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 5:32
 */
require_once ('web_init.php');
$TOP_MENU="aftersale";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>售后服务</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/aftersale.css" />
    <link rel="stylesheet" href="css/query.css">
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="js/anime.min.js"></script>
    <script type="text/javascript" src="js/jquery.md5.js">//$.md5()</script>
    <script type="text/javascript" src="js/jquery.base64.js">//$.base64.encode()</script>
    <script src="https://cdn.jsdelivr.net/npm/fuwenben@1.0.1/fuwenben.js"></script>
    <style type="text/css">
        a:link,
        a:visited {
            text-decoration: none;
            /*超链接无下划线*/
        }

        .banner {
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .banner img {
            max-height: 500px;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="home_tab">
            <div class="home_tab_main">
                <img class="home_tab_logo" src="imgs/home_logo.png" alt="home_logo">
                <ul class="home_tab_list font_p">
                    <li><a href="contactus.php">联系我们</a></li>
                    <li><a href="aftersale.html" class="been_checked">售后服务</a></li>
                    <li><a href="distribution.php">渠道分销</a></li>
                    <li><a href="projectcase.php">项目案例</a></li>
                    <li><a href="company_situation.php">新闻中心</a></li>
                    <li><a href="company_introduction.php">公司介绍</a></li>
                    <li><a href="index.php">首页</a></li>
                </ul>
                <p class="nav-tell"><img src="imgs/icon-phone.png" />24小时服务热线：400-966-5890</p>
                <div class="phone-menu"></div>
            </div>
        </div>

        <!-- 顶部服务轮播图 -->
        <div class="company-example" style="width: 100%;margin-top: 100px;background-color: #F8F8F8;max-height: 500px">

        </div>

    </div>

    <!-- 温馨提示文字跑马灯 -->
    <div class="row-wrap" style="background-color: #F8F8F8">
        <div class="text-wrap">
            <div><img src="imgs/servicelaba.png" alt="" style="width: 32px; height: 32px;"></div>
            <p id="pm-title">温馨提示：</p>
            <div class="text-roll">
                <div class="boxes">
                    <div class="box  box1" style="width: 75em"><bdo dir="ltr" class="pm-content"></bdo>
                    </div>
                    <div class="box  box2" style="width: 75em"><bdo dir="ltr" class="pm-content"></bdo>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- 服务介绍 -->
    <div class="row-wrap">
        <div class="our-service" id="p2">
            <div class="service-title">

                <div class="title-color" style="margin: 0 0 14px 0">我们的服务</div> <span style="margin: 0 0 14px 10px;">Our services</span>
            </div>
            <div class="service-content">

            </div>
        </div>
    </div>


    <div class="row-wrap" style="background-color: #F8F8F8">
        <div class="our-service">
            <div class="service-title">
                <div class="title-color">
                    找到我们
                </div>
                <span style="margin: 0 0 0px 10px;">Find us</span>
            </div>
            <div class="find-us">
                <div class="repair repair1">
                    <p class="title">电话报修</p>
                    <p><img src="imgs/servicephone.png" alt="" style="height: 247px" class="bg1">
                </div>
                <div class="repair repair2" id="p3" style="position: relative">
                    <p class="title">微信报修</p>
                    <p><img src="imgs/servicewechat.png" alt="" class="wechat-img" class="bg1"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row-wrap" style="background-image: linear-gradient(90deg, #58B5EE 0%, #20D7FF 100%);">
        <div class="our-service" id="p4">
            <div class="service-title">
                <div class="title-color textwhite">
                    专业、快捷、透明服务
                </div>

                <span class="textwhite" style="margin: 0 0 0 10px;opacity: 0.7;">Professional, fast and transparent service</span>
            </div>
            <div class="service-content2">

            </div>
        </div>
    </div>
    <!-- 服务剪影照片 -->
    <div class="row-wrap" style="margin-bottom: 7px">
        <div class="our-service" id="p5">
            <div class="service-title" style="margin-bottom: 19px">
                <div class="title-color">
                    服务剪影
                </div>
                <span style="margin: 0 0 0 10px;">Service Silhouette</span>
            </div>
            <div class="service-content">

            </div>
        </div>
    </div>


    <div class="home_beforefoot">
    </div>
    <div class="home_footer">
        <div class="home_footer_container">
            <div class="footer_left">
                <div class="footer_left_line"></div>
                <a href="contactus.php">
                    <div class="footer_left_title">Contact Us</div>
                </a>
                <a href="contactus.php">
                    <p class="font_p3">联系我们</p>
                </a>
                <p class="font_p2  company_name">西安元聚环保设备有限公司</<img>
                    <div class="footer_left_content">
                        <p class="font_p2 ">电话：029-88455890</p>
                    </div>
                    <div class="footer_left_content footer_left_content1">
                        <p class="font_p2 ">邮箱：3120667885@qq.com</p>
                    </div>
                    <div class="footer_left_content footer_left_content2">
                        <p class="font_p2 ">地址：西安市高新区唐延路旺座现代城B座27层022701室</p>
                    </div>
            </div>

            <div class="footer_center">
                <div class="footer_left_line"></div>
                <div class="footer_left_title">Quick Links</div>
                <p class="font_p3">快速导航</p>
                <div class="footer_center_item">
                    <a href="company_introduction.php">
                        <p class="font_p2 ">公司介绍</p>
                    </a>
                </div>
                <!--            <div class="footer_center_item">-->
                <!--                <p class="font_p2 ">加盟合作</p>-->
                <!--            </div>-->
                <a href="company_situation.php">
                    <div class="footer_center_item">
                        <p class="font_p2 ">新闻中心</p>
                </a>
            </div>
            <div class="footer_center_item">
                <a href="../login.php">
                    <p class="font_p2 ">员工通道</p>
                </a>
            </div>
            <a href="projectcase.php">
                <div class="footer_center_item">
                    <p class="font_p2 ">项目案例</p>
            </a>
        </div>
        <a href="distribution.php">
            <div class="footer_center_item">
                <p class="font_p2 ">渠道分销</p>
        </a>
    </div>
    <a href="recruit.php">
        <div class="footer_center_item">
            <p class="font_p2 ">人才招聘</p>
        </div>
    </a>
    <a href="contactus.php">
        <div class="footer_center_item">
            <p class="font_p2 ">联系我们</p>
    </a>
    </div>
    </div>
    <div class="footer_right">
        <div><img src="http://www.xayuanju.com/userfiles/upload/web/erweima/20190128/201901280923425079.png"
                alt="erweima1" /></div>
        <div><img src="http://www.xayuanju.com/userfiles/upload/web/erweima/20190128/201901280923503611.png"
                alt="erweima2" /></div>
    </div>
    </div>
    <div class="footer_boottom">
        <p>24小时服务热线：400-966-5890</p>
    </div>
    </div>
    </div>
</body>
<!-- 文字跑马灯配置-->
<script>
    xTrans = [];
    
    anime.set('.box', {
        translateX: function (el, i, l) {
            xTrans[i] = { x: i*1500 };
            return i * 1500;
        },
    });
    
    
    anime({
        targets: xTrans,
        direction: 'reverse',
        duration: 44000, //走一周持续时间
        easing: 'linear',
        x: "+=3000",
        delay: 10000,
        loop: true,
        update: function (anime) {
            anime.set('.box', {
                translateX: function (el, i, l) {
                    return xTrans[i].x % 3000
                    
                }
            });
            
        }
    })
</script>

</html>
<script language="javascript">

    $(function () {
        var method = 'get_afersales'
        var timestamp = new Date().getTime();
        var source = 2
        var secret = "boiler_android";
        if (source == 3)
            secret = "boiler_ios";
        var sign = $.md5(method + timestamp + source + secret);
        var sign2 = $.md5('get_aftersales_picurl' + timestamp + source + secret); //API2 校验
        var page = 1;
        var pageSize = 15;
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: method,
                timestamp: timestamp,
                source: 2,
                sign: sign,
                page: 1,
                pageSize: 15,
                openflag: 1,
            },
            success: function (res) {
                a = JSON.parse(res)
                var str = a.data[0].content.slice(9, a.data[0].content.length - 10)
                $('.pm-content').append(str)
            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });
        //p2渲染
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: method,
                timestamp: timestamp,
                source: 2,
                sign: sign,
                page: 1,
                pageSize: 15,
                openflag: 2,
            },
            success: function (res) {
                a = JSON.parse(res)
                for (let i = 0; i < a.data.length; i++) {
                    $('#p2 .service-content').append(
                        `<div class="service-card"
                        style="${(i+1)%3==2 ?`margin: 30px 20px 0 20px;`:` `}"
                        >
                    <p class="hover-img" style="position:relative"><img src="http://boiler.xazhima.com/${a.data[i].picture}" 
                    alt=""></p>
                    <p class="title">${a.data[i].title}</p>
                    <p class="desc">${a.data[i].content}</p>
                </div>`
                    )
                }
            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });
        //p4渲染
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: method,
                timestamp: timestamp,
                source: 2,
                sign: sign,
                page: 1,
                pageSize: 15,
                openflag: 5,
            },
            success: function (res) {
                a = JSON.parse(res)
                for (let i = 0; i < a.data.length; i++) {
                    $('#p4 .service-content2').append(
                        `<div class="service-card" style="width: 24%;height: 100%;margin: 0;">
                    <p><img src="http://boiler.xazhima.com/${a.data[i].picture}" class="service-img" ></p>
                    <p class="title textwhite" style="margin-bottom:11px">${a.data[i].title}</p>
                    <p class="desc textwhite" >${a.data[i].content}</p>
                </div>`
                    )
                }
            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });
        //p5渲染
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: method,
                timestamp: timestamp,
                source: 2,
                sign: sign,
                page: 1,
                pageSize: 15,
                openflag: 6,
            },
            success: function (res) {
                a = JSON.parse(res)
                for (let i = 0; i < a.data.length; i++) {
                    $('#p5 .service-content').append(
                        `<div class="img-card"  style="${(i+1)%3==1 ?`margin: 25px 13.666px 0 19.666px;`:` `}${(i+1)%3==0 ?`margin: 25px 16.666px 0 13.666px;`:` `}" >
                    <img src="http://boiler.xazhima.com/${a.data[i].picture}" alt="">
                    <div class="img-mask">
                        <div class="num">
                            <div style="border: 1px solid white;width:16%;height:0;margin-right: 1%"></div>
                            <p> 0${i + 1}</p>
                        </div>
                        <p class="desc">${a.data[i].title}</p>
                    </div>
                </div>`
                    )
                }
            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });

        //报修渲染
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: method,
                timestamp: timestamp,
                source: 2,
                sign: sign,
                page: 1,
                pageSize: 15,
                openflag: 3,
            },
            success: function (res) {
                a = JSON.parse(res)
                
                
                    let phones = new Array(); //定义一数组
                 phones = a.data[0].content.split(",");
                 titles = a.data[0].title.substring(0,15)
                 titles2 = a.data[0].title.substring(15,a.data[0].title.length)
                 console.log(titles2);
                 console.log(titles);
                    $('.repair1').append(
                  `
                    </p>
                        <p class="t1" >${titles}</p>
                        <p class="t2" style="position: absolute">${titles2}</p>
                        <p class="t3" style="position: absolute">${phones[0]}</p>
                        <p class="t4" style="position: absolute">${phones[1]}</p>
                    
                  `      
                    )
                
            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: method,
                timestamp: timestamp,
                source: 2,
                sign: sign,
                page: 1,
                pageSize: 15,
                openflag: 4,
            },
            success: function (res) {
                a = JSON.parse(res)
                console.log(a);
                
                $('.repair2').append(
                    `
                        
                        <img src="http://boiler.xazhima.com/${a.data[0].picture}" alt=""
                        style="position: absolute;width: 80px;height: 80px;bottom:43px;left:20px;">
                        <p class="t1" style="position: absolute">${a.data[0].title}</p>
                        <p class="t2" style="position: absolute">${a.data[0].content}</p>
                        `
                )
                
            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });
        //SWIPER渲染
        $.ajax({
            type: "POST",
            url: "../api/v1.0/api.php",
            data: {
                method: 'get_aftersales_picurl',
                timestamp: timestamp,
                source: 2,
                sign: sign2,
                page: 1,
                pageSize: 15,
                type: 5,
            },
            success: function (res) {

                a = JSON.parse(res)

                for (let i = 0; i < a.data.length; i++) {
                    $('.company-example').append(
                        `<div class="banner">
                    <img src="http://boiler.xazhima.com/${a.data[i].url}" alt="服务" style="width:100%" ></a>
                </div>`
                    )
                }


            },
            error: function (e) {
                console.log(e.status);
                console.log(e.responseText);
            }
        });




    })
</script>
</body>