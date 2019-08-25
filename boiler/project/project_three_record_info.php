<?php
/**
 * 项目第三阶段修改记录列表 project_three_record.php
 *
 * @version       v0.01
 * @create time   2018/07/01
 * @update time   2018/07/01
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$record_three = Project_three_record::getInfoById($id);
$project_three_before = Project_three_bak::getInfoById($record_three['before_id']);
$project_three_after = Project_three_bak::getInfoById($record_three['after_id']);

if(!empty($id)) {
    $projectinfo = Project::getInfoById($project_three_after['project_id']);
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-我的项目-修改查看</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
</head>
<body>
<div class="visit_pop">

    <div class="vist_popCont">
        <div class="visit_popDiv">
            <img class="must_reactImg_one" src="images/must_react.png" alt="">  <span>竞争品牌动向：</span>
            <p class="<?php if($project_three_before['competitive_brand_situation'] != $project_three_after['competitive_brand_situation']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_three_after['competitive_brand_situation']; ?></p>
        </div>
        <div class="visit_popDiv">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>工作进展程度：</span>
            <p class="<?php if($project_three_before['progress_situation'] != $project_three_after['progress_situation']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_three_after['progress_situation']; ?></p>
        </div>
        <div class="visit_popDiv">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>招标情况：</span>
            <p class="<?php if($project_three_before['invitation_situation'] != $project_three_after['invitation_situation']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_three_after['invitation_situation']; ?></p>
        </div>
        <div class="visit_popDiv">
             <span>其他：</span>
            <p class="<?php if($project_three_before['other_situation'] != $project_three_after['other_situation']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_three_after['other_situation']; ?></p>
        </div>

    </div>
</div>
</body>
</html>