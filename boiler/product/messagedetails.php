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
require_once('web_init.php');
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>站内信</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
    <div id="pagecontent" class="message_1" style="padding-bottom: 0">
        <div class="MesDateils"><?php echo $messageinfo['title']; ?><span class="MesDateils_1"><?php echo date('Y年m月d日 H:i:s',$messageinfo['addtime']); ?></span></div>
        <div class="MesDateils_2">
            <?php echo $messageinfo['content']; ?>
        </div>

    </div>
<script>window.jQuery || document.write('<script src="js/jquery-2.1.1.min.js"><\/script>')</script>
<script type="text/javascript" src="js/FileSaver.js"></script>
<script type="text/javascript" src="js/jquery.wordexport.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".MesDateils_9").click(function(event) {
            $("#pagecontent").wordExport();
        });
    });
</script>
</body>
</html>