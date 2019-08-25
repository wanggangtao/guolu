<?php
/**
 * 首页 index.php
 *
 * @version       v0.01
 * @create time   2018/06/05
 * @update time   2018/06/05
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$venderlist = Dict::getListByParentid(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" href="css/main.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script>
        $(function () {
            $('.indexTop_2').click(function(){
                $('.indexTop_2').removeClass('indexTop_checked');
                $(this).addClass('indexTop_checked');
            });
            $('.indexMtwo_1').hover(function () {
                $(this).find('.mouseset').slideDown('fast');
                var name = $(this).find('.indexMtwo_1_2').text();
                $(this).find('.mouseset').find('span').text(name);
            },function () {
                $(this).find('.mouseset').slideUp(100);
            });
        });
    </script>
</head>
<body class="body_1">
<?php include('top.inc.php');?>
<div class="indexmain">
    <?php
    if(!empty($venderlist)){
    ?>
    <div class="indexMone">锅炉</div>
    <div class="indexMtwo">
        <?php
        foreach ($venderlist as $vender){
            echo '
            <a href="guolu.php?vender='.$vender['id'].'">
                <div class="indexMtwo_1">
                    <img src="'.$HTTP_PATH.$vender['pic'].'" class="indexMtwo_1_1">
                    <div class="indexMtwo_1_2">'.$vender['name'].'</div>
                    <div class="mouseset"><span></span></div>
                </div>
            </a>';
        }
        ?>


        <!--结束时清除浮动-->
        <div class="clear"></div>
    </div>
    <?php
    }
    ?>
    <div class="indexMone">辅机</div>
    <div class="indexMtwo">
        <a href="fuji_details.php?model=2"><div class="indexMtwo_3" style="background:url('images/ranshao.png')">燃烧器</div></a>
        <a href="fuji_details.php?model=3"><div class="indexMtwo_3" style="background:url('images/sclq.png')"> 水处理设备</div></a>
        <a href="fuji_details.php?model=4"><div class="indexMtwo_3" style="background:url('images/sb.png')">水泵</div></a>
        <a href="fuji_details.php?model=6"><div class="indexMtwo_3" style="background:url('images/rsq.png')">换热器</div></a>
        <a href="fuji_details.php?model=7"><div class="indexMtwo_3" style="background:url('images/fjsq.png')">分集水器</div></a>
        <!--结束时清除浮动-->
        <div class="clear"></div>
    </div>
</div>

</body>
</html>