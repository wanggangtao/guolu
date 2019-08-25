<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/8
 * Time: 20:49
 */
require_once ('web_init.php');

$paprm=array();
$contactus_info=Web_contactus::getList($paprm);

$contactus_info=$contactus_info[0];




//echo "<pre>";
//print_r($contactus_info);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>联系我们</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
<!--    <script src="http://api.map.baidu.com/getscript?v=v=2.0:1 " type="text/javascript"></script>-->
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>



    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <style>
        .marker{
            position: relative;
            width:25px;
            height:28px;
            background: url(imgs/dingwei.png) no-repeat;
            background-size: 100% 100%;
            text-align: center;
            line-height: 28px;
            color: #fff;
            -webkit-perspective: 500;
            -moz-perspective: 500;
            -ms-perspective: 500;
            perspective: 500;
            -ms-transform: perspective(500px);
            -moz-transform: perspective(500px); /*重要*/
            transform-style: preserve-3d; /*重要*/
            overflow: visible;
            z-index: 999;
        }
        .marker::after {
            position: absolute;
            content: "";
            width: 30px;
            height: 30px;
            border: 8px solid #999;
            background-color: #555;
            border-radius: 100%; 
            transform-style: preserve-3d; /*重要*/
            transform: rotateX(80deg);
            bottom: -15px;
            left: -3px;
            opacity: 0;
            animation: animateCir 1.5s ease-out;
            animation-iteration-count: infinite;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        @-webkit-keyframes animateCir {
            0% {
                transform: scale(0.4);
                opacity: 1;
            }
            10% {
                transform: scale(0.6);
                opacity: 0.8;
            }
            20% {
                transform: scale(0.7);
                opacity: 0.6;
            }
            30% {
                transform: scale(0.8);
                opacity: 0.4;
            }
            75% {
                transform: scale(0.9);
                opacity: 0.3;
            }
            100% {
                transform: scale(1);
                opacity: 0.2;
            }
        }

        .center{
            width: 100%;
        }
        .btmtip {
            cursor: pointer;
            border-radius: 5px;
            background-color: #0D9BF2;
            padding: 3px;
            width: 80px;
            color: white;
            margin: 0 auto;
            text-align: center;
        }

        .luxian{
            position: fixed;

            width:60px;
            height:60px;
            left:10px;
            bottom: 24px;

        }
        .amap-toolbar{
            z-index:3;
        }
        .amap-marker-label{
            cursor: pointer;
            border-radius: 5px;
            background-color: #1194f2;
            /*background-color:transparent!important;*/
            border:0px!important;
            color: black;
            margin: 0 auto;
            text-align: center;
        }

    </style>
    <script src="http://webapi.amap.com/maps?v=1.3&key=d7c5cdb73a595b9ee6556c08ff37abf9"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>

</head>
<body>
<div class="container">
    <div class="home_tab">
        <div class="home_tab_main">
            <img class="home_tab_logo" src="imgs/home_logo.png" alt="home_logo">
            <ul class="home_tab_list font_p">
                <li>
                    <a href="#" class="been_checked">联系我们</a>
                </li>
                <li><a href="aftersale.php">售后服务</a></li>
                <li><a href="distribution.php">渠道分销</a></li>
                <li><a href="projectcase.php">项目案例</a></li>
                <li><a href="company_situation.php">新闻中心</a></li>
                <li><a href="company_introduction.php">公司介绍</a></li>
<!--                <li><a href="#"  class="been_checked">首页</a></li>-->
                <li><a href="index.php"  class="">首页</a></li>
            </ul>

            <p class="nav-tell"><img src="imgs/icon-phone.png" />24小时服务热线：<?php if(!empty($contactus_info))echo $contactus_info['hotline'] ?></p>
            <div class="phone-menu"></div>
        </div>
    </div>

    <div class="contactus_body">
        <div class="dynamics_body">
            <div class="body_head ">
                <span>联系我们</span>
<!--                <img src="imgs/contact_us_word.png" class="width200"/>-->
                <span style="display: inline-block;margin-left: 14px !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(213,213,213,1);">CONTACT US</span>
                <p>联系我们</p>
                <p>></p>
                <p>首页</p>
            </div>
            <div class="contactus_left">
                <p><?php echo $contactus_info['company']  ?></p>
                <p>24小时服务热线：<?php echo $contactus_info['hotline']  ?></p>
                <p>联系人：<?php echo $contactus_info['contacter']  ?></p>
                <p style="display: none;">手机：<?php echo $contactus_info['phone'] ?></p>
                <p>电话：<?php echo $contactus_info['telephone'] ?></p>
                <p>网址：<?echo $contactus_info['website'] ?></p>
                <p>邮箱：<?php  echo $contactus_info['email'] ?></p>
                <p>地址：<?php echo $contactus_info['address'] ?></p>
            </div>
            <div class="contactus_right">
                <div id="map" class="contactmap">
                </div>

            </div>

        </div>



        <script>

            var marker, map = new AMap.Map("map", {
                resizeEnable: true,
                center: [116.397428, 39.90923],
                zoom: 13
            });

            var X=<?php echo $contactus_info["lat"]?>;
            var Y=<?php echo $contactus_info["lng"]?>;



            var name = "<?php echo $contactus_info["company"]?>";
            var address = "<?php echo $contactus_info["address"]?>";
            var positon=[X,Y];


            marker = new AMap.Marker({
                position: positon,
                content:'<div class="marker"></div>'
            });

            marker.setLabel({//label默认蓝框白底左上角显示，样式className为：amap-marker-label
                offset: new AMap.Pixel(20, 20),//修改label相对于maker的位置
                // content: "<div class='amap-marker-label'>"+name+"</div>"
                content: ""
            });

            var infoWindow = new AMap.InfoWindow({
                // content:"<p style=\"font-size:18px;font-weight:bold;color:#333;line-height:50px;margin-left:20px\">西安元聚环保设备有限公司 </br></p>" +
                //     "<p style=\"font-size:14px;line-height:24px;color:#999;width:272px;margin-left:20px\">西安市高新区唐延路旺座现代城B座27层022701室</br>" +
                //     "</p>'",//信息窗体的内容
                content:"<p style=\"font-size:18px;font-weight:bold;color:#333;line-height:50px;margin-left:20px\">"+name+" </br></p>" +
                "<p style=\"font-size:14px;line-height:24px;color:#999;width:292px;margin-left:38px\">" +address+
                "<br></p>",//信息窗体的内容
                offset:new AMap.Pixel(10, -10)
            });
            marker.on('mouseover',function(e){
                infoWindow.open(map,marker.getPosition()); //信息窗体打开

              })
            map.setCenter(positon);
            marker.setMap(map);




        </script>





<?php require_once('foot.inc.php')?>
