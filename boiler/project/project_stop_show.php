<?php
/**
 * 项目沉淀 project_summarize.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$TOP_FLAG = 'projectselect';
$LEFT_TAB_NVA = "stop";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$projectinfo = Project::Init();
if(!empty($id)){
    $projectinfo = Project::getInfoById($id) ;
    if(empty($projectinfo)){
        echo '非法操作！';
        die();
    }

    $userinfo = User::getInfoById($projectinfo['user']);
    if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3 || ($USERINFO['role'] == 4 && $userinfo['department'] == $USERINFO['department']))) {
        echo '没有权限操作！';
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-<?php echo $projectinfo['name'];?>终止</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>

</head>
<body class="body_1">
    <?php include('top.inc.php');?>
        <?php include('project_show_top.inc.php');?>
    <div class="manageHRWJCont">
        <?php include('project_show_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_show_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"><p><img class="must_reactImg" src="images/must_react.png" alt="">终止原因</p></div>
                    <div class="middleDiv_nine_right2">
                        <?php echo HTMLDecode($projectinfo['stopreason']);?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
</body>
</html>