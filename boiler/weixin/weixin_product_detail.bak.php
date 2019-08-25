<?php
/**
 * 微信产品详情界面 weixin_product_detail.php
 *
 * @version       v0.01
 * @create time   2018/3/17
 * @update time
 * @author        ww
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 *  * 产品说明
 */

require_once('admin_init.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Smallguolu::getInfoById($id);
if(empty($info))
    die();

$pInfo = Products::getInfoById($info['proid']);
$imgs = !empty($pInfo['detail_imgs'])?explode(';',$pInfo['detail_imgs']):array();
$imgsCount = count($imgs);
if($imgsCount>0){
    $imgs = array_slice($imgs,0,$imgsCount-1);
}
$deatail_video = $pInfo['detail_video'];
$videoarr  = explode(';',$deatail_video);
$videopath = $videoarr[0];
$placehold = $videoarr[1];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?php echo $info['version']; ?></title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script src="static/js/query.js"></script>
    <style type="text/css">
        .product-detail-banner .btn-play {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 999;
            /*background-image: url(*/<?php //echo $HTTP_PATH.$placehold; ?>/*);*/
            background-size: 100% 100%;
            /*background-repeat: no-repeat;*/
            /*background-position: center;*/
            background: url(<?php echo $HTTP_PATH.$placehold; ?>) no-repeat center ;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="product-wrap">
        <div class="product-detail">
            <div class="product-detail-banner">
                <div class="swiper-container ">
                    <div class="swiper-wrapper">
                        <?php if(!empty($videopath)){?>
                            <div class="swiper-slide">
                                <a href="#">
<!--                                    <video src="--><?php //echo $HTTP_PATH.$pInfo['detail_video'];?><!--" style="object-fit:fill" autoplay controls muted loop></video>-->
                                    <video class="video" src="<?php echo $HTTP_PATH.$videopath;?>"  x5-playsinline="" playsinline="" webkit-playsinline="" preload="auto" width="100%" height="100%" style="margin-top: 0;object-fit: fill"></video>
                                    <div class="btn-play"><img src="static/images/play.png" alt=""></div>
                                </a>
                            </div>
                        <?php }?>
                        <?php if($imgsCount > 0){foreach ($imgs as $img) { ?>
                            <div class="swiper-slide">
                                <a href="#">
                                    <img src="<?php echo $HTTP_PATH.$img ;?>">
                                </a>
                            </div>
                        <?php }} else{ ?>
                        <div class="swiper-slide">
                            <a href="#">
                                <img src="static/images/123.png">
                            </a>
                        </div>
                        <?php }?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <div class="product-detail-title flex">
                <img src="static/images/icon-product-detail.png" alt="">
                <span>产品介绍</span>
            </div>
            <div class="product-detail-main">
                <div class="product-detail-head">
                    <?php
                    if(!empty($pInfo['wxdesc'])){
                        echo $pInfo['wxdesc'];
                    }
                    ?>
                </div>
                <div class="product-detail-table">
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">设备型号</div>
                        <div class="detail-item-right"><?php echo $info['version']; ?></div>
                    </div>

                    <div class="product-detail-item flex">
                        <div class="detail-item-left">类型</div>
                        <div class="detail-item-right">壁挂锅炉</div>
                    </div>

                    <div class="product-detail-item flex">
                        <div class="detail-item-left">额定输出功率(KW)</div>
                        <div class="detail-item-right"><?php echo $info['power']?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">供暖热水温度调节范围(°c)</div>
                        <div class="detail-item-right"><?php echo $info['heat_temperature'];?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">生活热水温度调节范围(°c)</div>
                        <div class="detail-item-right"><?php echo $info['live_temperature'];?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">最高热效率(%)</div>
                        <div class="detail-item-right"><?php echo $info['thermal_efficiency'];?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">中国能效标识等级</div>
                        <div class="detail-item-right"><?php echo $info['efficiency_level'];?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">外型尺寸(mm)</div>
                        <div class="detail-item-right"><?php echo $info['size'];?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">净重(KG)</div>
                        <div class="detail-item-right"><?php echo $info['weight'];?></div>
                    </div>
                    <div class="product-detail-item flex">
                        <div class="detail-item-left">防护等级</div>
                        <div class="detail-item-right"><?php echo $info['protection_level'];?></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="footer-tel">
        <div class="footer-tel-flex flex">
            <img src="static/images/tell.png" alt="">
            <span>24小时服务热线：</span>
            <span class="color-pub">400-966-5890</span>
        </div>
        <div class="footer-tel-cop">西安元聚环保设备有限公司</div>
    </div>
</div>
</body>

<script src="static/js/jquery.min.js"></script>
<script src="static/js/swiper.min.js"></script>
<script src="static/js/common.js"></script>
<script>
    $(function () {

        var swiper = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
                type: 'fraction',
            },
            // loop : true,
            on: {
                slideChangeTransitionStart: function(){
                    $(".video").get(0).pause();
                    // $(".btn-play").show();
                    //$(".btn-play img").show();
                },
            }
        });

        window.onload = function () {
            var u = navigator.userAgent;
            if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
                $('.video').removeAttr('controls');
            } else if (u.indexOf('iPhone') > -1) {//苹果手机
                $('.video').attr('controls','true');
            } else if (u.indexOf('Windows Phone') > -1) {//winphone手机
                $('.video').attr('controls','false');
            }
        };

        $(".btn-play img").on("click",function () {
            var video = $(".video").get(0);
            var isPaused = video.paused;
            if (isPaused) {
                video.play();
            } else {
                video.pause();
            }
            $(".btn-play").hide();
        });
        $(".video").on("click",function () {
            //$(".btn-play img").show();
        })


    })
</script>

</html>