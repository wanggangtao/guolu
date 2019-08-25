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
$videoList = weixin_video::getListByProudctId($id);
//$page = ceil(count($videoList)/3);
//print_r($page);
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
    <link rel="stylesheet" href="static/css/style.css?vdsssa" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <link rel="stylesheet" href="css/DPlayer.min.css">

    <script src="static/js/query.js"></script>
    <script src="js/DPlayer.min.js"></script>


</head>
<body>
<div id="app">
    <div class="product-wrap">
        <div class="product-detail">
            <div class="product-detail-banner">
                <div id="dplayer" style="width: 100%;height: 100%;display: none" ></div>
                <div class="placeHold" style="width: 100%;height: 100%;position: sticky;">
                    <?php if(!empty($videoList[0]['imgpath']) && $videoList[0]['path']){?>
                    <img id="video_img" src="<?php echo $HTTP_PATH.$videoList[0]['imgpath'];?>"
                         v_src = "<?php echo $HTTP_PATH.$videoList[0]['path'];?>"
                         style="height: 100%;width: 100%;max-width: 100% !important; object-fit: contain;" />
                    <div class="btn-play"><img  src="static/images/play_black.png" alt=""></div>
                    <?php } else { ?>
                    <img id="video_img" src="static/images/123.png" style="height: 100%;width: 100%;max-width: 100% !important; object-fit: cover;" />
                        <div class="btn-play" style="display: none"><img  src="static/images/play.png" alt=""></div>
                    <?php } ?>
                </div>
            </div>


            <div class="project-detail-tab">
                <?php $flag = 0; foreach ($videoList as $video) {?>
                <a href="#" class="detail-tab <?php  if($flag==0) echo ' active';?>" imgpath="<?php echo $HTTP_PATH.$video['imgpath'];?>"
                   videopath="<?php echo $HTTP_PATH.$video['path'];?>">
                    <?php echo $video['name'];?>
                </a>
                <?php $flag++;} ?>
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
                        <div class="detail-item-left">外型尺寸</div>
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
        var swiper = new Swiper('.swiper-detail-tab', {
            loop : false,
        });
        let flagitemleft = [];
        function getItemLeftPx () {
            let viewWidth = $(".project-detail-tab").width()/2;
            let dom = $(".detail-tab");
            let length = dom.length;
            let itemleft;any = 0;
            for (let i = 0; i < length; i++) {
                itemleft = dom.eq(i).position().left + (dom.eq(i).width() / 2) + 12 - viewWidth;
                itemleft = parseInt(itemleft);
                flagitemleft.push(itemleft);
            }
        }
        getItemLeftPx ();
        console.log(flagitemleft);


        var HTTP_PATH = '<?php echo $HTTP_PATH;?>';
        var dp;
        $('.detail-tab').click(function () {
            var index = $(this).index();
            $(".detail-tab").removeClass("active");
            $(this).addClass("active");
            var path = $(this).attr('videopath');
            var img = $(this).attr('imgpath');
            if(path.replace(HTTP_PATH,'') != '' && img.replace(HTTP_PATH,'') !=''){
                $('#video_img').attr('v_src',path);
                $('#video_img').attr('src',img);
                $('.btn-play').show();
            }else {
                $('#video_img').attr('src','static/images/123.png');
                $('#video_img').removeAttr('v_src');
                $('#video_img').css('object-fit','cover');
                $('.btn-play').hide();
            }
            $('#dplayer').hide();
            $('.placeHold').show();
            $(".project-detail-tab").animate({"scrollLeft":flagitemleft[index] + 3},300);
            if(dp){
                dp.destroy();
            }

        });

        $('.btn-play img').click(function () {
            var path = $('#video_img').attr('v_src');
            var img = $('#video_img').attr('src');
            dp = new DPlayer({
                container: document.getElementById('dplayer'),
                lang: 'en',
                video: {
                    url: path,
                    pic: img,
                },
            });
            $('#dplayer').show();
            $('.placeHold').hide();
            dp.play();
        })


    })
</script>

</html>