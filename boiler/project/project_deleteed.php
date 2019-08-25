<?php
/**
 * 项目删除后显示页面 project_deleteed.php
 *
 * @version       v0.01
 * @create time   2018/07/01
 * @update time   2018/07/01
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');
$TOP_FLAG = 'myproject';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>站内信</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
    <div id="pagecontent" class="message_1" style="padding-bottom: 0">
        <div class="MesDateils_2">
            <div class="NOmessage" style="margin-left: 0px">
                <img src="images/u8345.png" style="width: 75px;height: 75px;left: 560px;position: absolute;">
                <div class="NOmessage_2" style="top: 100px;">项目已被删除不存在</div>
            </div>
        </div>

    </div>
</body>
</html>