<?php
/**
 * 项目第五阶段修改记录列表 project_five_record.php
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
$record_five = Project_five_record::getInfoById($id);
$project_five_before = Project_five_bak::getInfoById($record_five['before_id']);
$project_five_after = Project_five_bak::getInfoById($record_five['after_id']);
if(!empty($id)) {
    $projectinfo = Project::getInfoById($project_five_after['project_id']);
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
        <div class="visit_popDiv2">
             <span>善后情况：</span>
            <p class="<?php if($project_five_before['after_solve'] != $project_five_after['after_solve']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_five_after['after_solve']; ?></p>
        </div>
        <div class="visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>合同金额：</span>
            <p class="<?php if($project_five_before['money'] != $project_five_after['money']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_five_after['money']; ?></p>
        </div>
        <div class="visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""><span>付款条件：</span>
            <p class="<?php if($project_five_before['pay_condition'] != $project_five_after['pay_condition']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_five_after['pay_condition']; ?></p>
        </div>

        <div class="visit_popDiv2">
            <div class="cont">
                 <span>项目提成：</span><p>预付款到账后所得：<span id="first_reward" class="<?php if($project_five_before['first_reward'] != $project_five_after['first_reward']) echo 'programRevise_changeDiv'; ?>"><?php echo number_format($project_five_after['first_reward'],2);?></span>元</p>
                <p>项目竣工验收后得：<span id="second_reward" class="<?php if($project_five_before['second_reward'] != $project_five_after['second_reward']) echo 'programRevise_changeDiv'; ?>"><?php echo number_format($project_five_after['second_reward'],2);?></span>元</p>
                <p>质保金到账后所得：<span id="third_reward" class="<?php if($project_five_before['third_reward'] != $project_five_after['third_reward']) echo 'programRevise_changeDiv'; ?>"><?php echo number_format($project_five_after['third_reward'],2);?></span>元</p>
            </div>
        </div>
        <div class="visit_popDiv2">
            <span>用款计划：</span>
            <p class="<?php if($project_five_before['cost_plan'] != $project_five_after['cost_plan']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_five_after['cost_plan']; ?></p>
        </div>
        <div class="visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt="">  <span>拟开工日期：</span>
            <p class="<?php if($project_five_before['pre_build_time'] != $project_five_after['pre_build_time']) echo 'programRevise_changeDiv'; ?>"><?php echo getDateStrS($project_five_after['pre_build_time']); ?></p>
        </div>
        <div class="visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>验收日期：</span>
            <p class="<?php if($project_five_before['pre_check_time'] != $project_five_after['pre_check_time']) echo 'programRevise_changeDiv'; ?>"><?php echo getDateStrS($project_five_after['pre_check_time']); ?></p>
        </div>
    </div>
</div>
</body>
</html>