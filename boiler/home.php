<?php

require_once('init.php');
require_once('usercheck.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/main_two.css">
    <link rel="stylesheet" href="static/css/newlayui.css">
    <link rel="stylesheet" href="static/js/layui/css/layui.css">
    <script type="text/javascript" src="static/js/jquery-1.4.4.min.js"></script>
     <script type="text/javascript" src="static/js/myprogram.js"></script>
    <script type="text/javascript" src="static/layer/layer.js"></script>


    <script type="text/javascript">
        function waitOpen()
        {
            layer.msg("正在紧急开发中,敬请期待.....");
            return false;

        }
    </script>
</head>
<body class="body_1">

<div class="indexTop">
    <div class="indexTop_1">
        <div class="indexTop_logo">
            <img src="static/images/top_logo.png" alt="">
        </div>
        <?php
        $message_notopen_count = Message_info::getPageList(1, 10, 0, $USERId, 0, '', 0);
        ?>
        <a href="message_unread.php"><img src="static/images/emil.png" class="indexTop_3"><span class="indexTop_5">  <?php if($message_notopen_count > 0)echo '<div class="num">'.$message_notopen_count.'</div>'; ?></span></a>
        <a href="logout.php"><img src="static/images/backlogon.png" class="indexTop_4"><span class="indexTop_6"></span></a>
        <a href=""><span class="indexTop_7"><?php echo $USERINFO['name']; ?>，欢迎您！</span></a>
    </div>
</div>
   <div class="homePage">
   	 <div class="homePage_cont cont1">
         <a href="product/"><img src="static/images/product.png"></a>
   </div>
    <div class="homePage_cont cont2">
        <a href="selection/selection.php"><img src="static/images/boiler.png"></a>
   </div>
    <div class="homePage_cont cont3">
        <a href="<?php echo $USERINFO['role'] == 4?'project/project_list_select.php':'project/project_list_my.php'; ?>"><img src="static/images/project.png"></a>
   </div>
   <?php if(in_array($USERINFO['role'], array(2,3))){ ?>
       <div class="homePage_cont cont4">
           <a href="systemSetting.php"><img src="static/images/setting.png"></a>
       </div>
   <?php } ?>
   </div>

     <script>
        $(function () {
            $('.cont1').hover(function () {
                $(this).find('img').attr('src','static/images/product_slip.png');
            },function () {
                $(this).find('img').attr('src','static/images/product.png');
            })
             $('.cont2').hover(function () {
                $(this).find('img').attr('src','static/images/boiler_slip.png');
            },function () {
                $(this).find('img').attr('src','static/images/boiler.png');
            })
              $('.cont3').hover(function () {
                $(this).find('img').attr('src','static/images/project_slip.png');
            },function () {
                $(this).find('img').attr('src','static/images/project.png');
            })
               $('.cont4').hover(function () {
                $(this).find('img').attr('src','static/images/setting_slip.png');
            },function () {
                $(this).find('img').attr('src','static/images/setting.png');
            })


        })
    </script>


</body>
</html>