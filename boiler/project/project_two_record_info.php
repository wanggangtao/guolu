<?php
/**
 * 项目第一阶段修改记录列表 project_two_record.php
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
$record_two = Project_two_record::getInfoById($id);
$project_two_before = Project_two_bak::getInfoById($record_two['before_id']);
$project_two_after = Project_two_bak::getInfoById($record_two['after_id']);

$linkManListBefore = 0;
if($record_two['before_id'])
    $linkManListBefore = Project_linkman_bak::getInfoByPtId($record_two['before_id']);

$linkManListAfter = 0;
if($record_two['after_id'])
    $linkManListAfter = Project_linkman_bak::getInfoByPtId($record_two['after_id']);


if(!empty($id)) {
    $projectinfo = Project::getInfoById($project_two_after['project_id']);
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
        <?php
        if($linkManListAfter){
            for($i = 0; $i < count($linkManListAfter); $i ++){
        ?>
                <div class="visit_popDiv visit_popDiv2">
                    <?php if($linkManListAfter[$i]['isimportant'] == 1){echo '<img src="images/must_react.png" alt="">';} ?><span>联系人<?php echo $i + 1; ?>：</span>
                    <p class="<?php if($linkManListBefore[$i]['name'] != $linkManListAfter[$i]['name']) echo 'programRevise_changeDiv'; ?>"><?php echo $linkManListAfter[$i]['name']; ?></p>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg" src="images/must_react.png" alt=""> <span>部门：</span>
                    <p class="<?php if($linkManListBefore[$i]['department'] != $linkManListAfter[$i]['department']) echo 'programRevise_changeDiv'; ?>"><?php echo $linkManListAfter[$i]['department']; ?></p>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg" src="images/must_react.png" alt=""><span>职位：</span>
                    <p class="<?php if($linkManListBefore[$i]['position'] != $linkManListAfter[$i]['position']) echo 'programRevise_changeDiv'; ?>"><?php echo $linkManListAfter[$i]['position']; ?></p>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg" src="images/must_react.png" alt=""><span>联系方式：</span>
                    <p class="<?php if($linkManListBefore[$i]['phone'] != $linkManListAfter[$i]['phone']) echo 'programRevise_changeDiv'; ?>"><?php echo $linkManListAfter[$i]['phone']; ?></p>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg" src="images/must_react.png" alt=""><span>主要负责事项：</span>
                    <p class="<?php if($linkManListBefore[$i]['duty'] != $linkManListAfter[$i]['duty']) echo 'programRevise_changeDiv'; ?>"><?php echo $linkManListAfter[$i]['duty']; ?></p>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>
</body>
</html>