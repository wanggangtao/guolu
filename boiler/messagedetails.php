<?php
/**
 * 站内信详情 messagedetails.php
 *
 * @version       v0.01
 * @create time   2018/07/01
 * @update time   2018/07/01
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('init.php');
require_once('usercheck.php');

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$messageinfo = Message_info::getInfoById($id);
if (empty($messageinfo)) {
    echo '非法操作！';
    die();
} else {
    if ($messageinfo['recipients'] != $USERId) {
        echo '没有权限操作！';
        die();
    }

    if($messageinfo['openflag'] == 0){
        $arr = array('openflag' => 1);
        Message_info::update($id, $arr);
    }
}
$message_notopen_count = Message_info::getPageList(1, 10, 0, $USERId, 0, '', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>站内信</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/newlayui.css">
    <link rel="stylesheet" href="static/layui/css/layui.css">
    <link rel="stylesheet" href="static/layui/css/layui.css">
    <script type="text/javascript" src="static/js/jquery-1.4.4.min.js"></script>
</head>
<body class="body_1">
    <div class="indexTop">
        <div class="indexTop_1">
            <a href="home.php"><div class="indexTop_logo">
                    <img src="static/images/top_logo.png" alt="">
                </div></a>
            <a href="message.php">
                <div class="indexTop_2 ">
                    <img src="static/images/emil.png" >
                    <img src="static/images/emil.png">
                    <span>站内信<?php if($message_notopen_count > 0) echo '<div class="num">'.$message_notopen_count.'</div>'; ?></span>
                </div></a>

            <a href="logout.php"><img src="static/images/backlogon.png" class="indexTop_4"><span class="indexTop_6">退出登录</span></a>
            <span class="indexTop_7"><?php echo $USERINFO['name']; ?>，欢迎您！</span>
        </div>
    </div>

    <div id="pagecontent" class="message_1" style="padding-bottom: 0">
        <div class="MesDateils"><?php echo $messageinfo['title']; ?><span class="MesDateils_1"><?php echo date('Y年m月d日 H:i:s',$messageinfo['addtime']); ?></span></div>
        <div class="MesDateils_2">
            <?php echo $messageinfo['content']; ?>
        </div>

    </div>
<script>window.jQuery || document.write('<script src="static/js/jquery-2.1.1.min.js"><\/script>')</script>
<script type="text/javascript" src="static/js/FileSaver.js"></script>
<script type="text/javascript" src="static/js/jquery.wordexport.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".MesDateils_9").click(function(event) {
            $("#pagecontent").wordExport();
        });
    });
</script>
</body>
</html>