<?php
/**
 * 项目第三阶段 project_stage_three_check.php
 *
 * @version       v0.01
 * @create time   2018/06/28
 * @update time   2018/06/28
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = 'three';
$TOP_FLAG = "projectselect";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$projectinfo = Project::getInfoById($id);
$project_three = Project_three::getInfoByProjectId($id);
if (empty($projectinfo)) {
    echo '非法操作！';
    die();
} else {
    $userinfo = User::getInfoById($projectinfo['user']);
    if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3 || ($USERINFO['role'] == 4 && $userinfo['department'] == $USERINFO['department']))) {
        echo '没有权限操作！';
        die();
    }
}
if(empty($project_three)){
    $project_three = Project_three::Init();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目查询三级</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#record_three').click(function(){
                location.href = 'project_three_record_show.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });
        });

    </script>
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
                    <div class="middleDiv_nine_left2"><p><img class="must_reactImg_one" src="images/must_react.png" alt="">竞争品牌动向</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_three['competitive_brand_situation']; ?></div>
                        <button id="record_three">修改记录</button></div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"><p><img class="must_reactImg_one" src="images/must_react.png" alt="">工作进展程度</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_three['progress_situation']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"><p><img class="must_reactImg_one" src="images/must_react.png" alt="">招标情况</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_three['invitation_situation']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"><p>其他</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_three['other_situation']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>