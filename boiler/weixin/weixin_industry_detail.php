<?php
/**
 * Created by PhpStorm.
 * User: hhx
 * Date: 2019/3/19
 * Time: 14:41
 * 资讯详情
 */
require_once('../init.php');


if(isset($_GET['id'])){
    $sid=$_GET['id'];
};
$rows = weixin_situation::getInfoById($sid);
//print_r($rows);

if(!empty($rows['picurl'])){
    $share_url = $HTTP_PATH . $rows['picurl'];
}else{
    $share_url = "static/images/bg-login.png";

}

$weixin = new weixin();
$signPackage = $weixin->getSignPackage();

$desc = htmlToTxt($rows['content'],30);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title></title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/common.js"></script>
    <script type="text/javascript" src="http://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>

        <script>
        $(function () {
            var image=$('.news-con img');
            for(var i=0;i<image.length;i++){
                if($('.news-con img')[i].width > 200){
                    $('.news-con img')[i].style='width:100%;'
                }
            }
        })


        wx.config({
            debug: false,
            appId:'<?php echo $signPackage["appId"];?>', // 必填，公众号的唯一标识
            timestamp:<?php echo $signPackage["timestamp"];?>, // 必填，生成签名的时间戳
            nonceStr: '<?php echo $signPackage["nonceStr"];?>', // 必填，生成签名的随机串
            signature:'<?php echo $signPackage["signature"];?>',// 必填，签名
            jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] //
        });

        wx.ready(function () {

            //分享到朋友圈
            wx.onMenuShareTimeline({

                link: '<?php echo $signPackage['url']?>', // 分享链接
                title: '<?php  echo $rows['title'];?>',//分享标题
                imgUrl: '<?php echo $share_url?>',// 分享图标
                <!--{if $is_login}-->
                success: function () {
                    // console.log("success");
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });

            //分享给朋友
            wx.onMenuShareAppMessage({

                title: '<?php  echo $rows['title'];?>',//分享标题
                desc: '<?php echo $desc;?>',
                link: '<?php echo $signPackage['url']?>', // 分享链接
                imgUrl: '<?php echo $share_url?>', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                <!--{if $is_login}-->
                success: function () {
                    // 用户确认分享后执行的回调函数
                    // console.log("success");

                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
                <!--{/if}-->
            });
        });


    </script>
</head>
<body>
<div id="app">
    <div class="industry">
        <div class="news-wrap">
            <?php

            //$rows['contents'] = HTMLDecode($rows['content']?$rows['content']:'');
            if (!empty($rows['content'])){
                $str = str_replace("&amp;","&",$rows['content']);
                $str = str_replace("&gt;",">",$str);
                $str = str_replace("&lt;","<",$str);
                //$str = str_replace("&nbsp;",CHR(32),$str);
                //$str = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;",CHR(9),$str);
                $str = str_replace("&#160;&#160;&#160;&#160;",CHR(9),$str);
                $str = str_replace("&quot;",CHR(34),$str);
                $str = str_replace("&#39;",CHR(39),$str);
                $str = str_replace("",CHR(13),$str);
                $str = str_replace("<br/>",CHR(10),$str);
                $str = str_replace("<br />",CHR(10),$str);
                $rows['contents'] = str_replace("<br>",CHR(10),$str);
                $rows['contentss'] = str_replace("\\","",$rows['contents']);
            }else{
                $rows['contentss'] = '';
            }
                /*$url = "http://www.ivsky.com/";
                $str = file_get_contents($url);
                $preg = '/<img[^>]*\/>/';
                preg_match_all($preg, $rows['contentss'], $matches);//过滤(所有的)img
                //print_r($matches);
                $matches = $matches[0];
                      //获取src中的链接
                $arr = [];
                foreach($matches as $v){
                    //print_r($v);
                    //$val=substr($v,0,-2);
                    //print_r($val);
                    $preg = '/http:\/\/.* /';
                    preg_match_all($preg, $v, $match);
                    $arr = $match[0][0];
                    $matches = substr($arr,0,strlen($arr)-2);
                    print_r($matches);
                    $value = '<div style="background-size:200px 300px; background:url('.$matches.')no-repeat;"></div>';
                    print_r($v);
                    print_r($value);
                    str_replace($v,$value,$rows['contentss']);
                }*/


                echo '<div class="news-title"><b>'.$rows['title'].'</b></div>
                <div class="news-tip flex">
                    <span>发布日期</span>
                    <span class="news-time">'.date('Y/m/d h:i',$rows['addtime']).'</span>
                </div>
                
                <div class="news-con" >
                    <p >'.$rows['contentss'].'</p>   
                </div>';
            ?>


            <!--<div class="news-img">
                <img src="'.$HTTP_PATH.$rows['picurl'].'" alt="">
            </div>-->

        </div>
    </div>
    <div class="footer-tel">
        <div class="footer-tel-flex flex">
            <img src="images/tell.png" alt="">
            <span>24小时服务热线：</span>
            <span class="color-pub">400-966-5890</span>
        </div>
        <div class="footer-tel-cop">西安元聚环保设备有限公司</div>
    </div>
</div>
</body>
</html>

