<?php
/**
 * 我的项目的选型方案-预览
 *
 * @version       v0.01
 * @create time   2019/3/6
 * @update time   2019/3/6
 * @author        sxm
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');


$plan_front_id = $_GET['plan_front_id'];
$content = "";
if(!empty($plan_front_id)){
    $content = file_get_contents("{$HTTP_PATH}selection/selection_plan_print.php?planId={$plan_front_id}");
}else{
    echo '非法操作！';
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-选型方案</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui_preview.css">
    <!--<link rel="stylesheet" href="layui/css/layui.css">-->
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <style>
        .content_div
        {
            margin-top: 0px;
            margin-left: 15%;
            width: 70%;
            height: auto;
            background: #ffffff;
            padding-bottom: 48px;
            border-collapse: collapse;
        }
        .page_div
        {
            margin-top: 0px;
            margin-left: 15%;
            width: 70%;
            height: auto;
            padding-bottom: 48px;
            border-collapse: collapse;
        }
        .inner_page_div
        {
            margin-top: 20px;
        }
        .manageHRWJCont {
            background: #EEEEEE;
        }


    </style>
    <script type="text/javascript">

    </script>
</head>
<body class="body_1">
 <div class="manageHRWJCont">
     <div  class="content_div"  >
         <div class="page_div">
             <div class="inner_page_div">
                 <?php echo $content;?>
             </div>
         </div>

     </div>
 </div>
<div class="clear"></div>
</body>
</html>