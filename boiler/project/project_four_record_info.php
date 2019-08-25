<?php
/**
 * 项目第四阶段修改记录列表 project_four_record_info.php
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
$record_four = Project_four_record::getInfoById($id);

$project_four_before = Project_four_bak::Init();

if($record_four['before_id'])
    $project_four_before = Project_four_bak::getInfoById($record_four['before_id']);

$project_four_after = Project_four_bak::getInfoById($record_four['after_id']);

$companyListBefore = 0;
if($record_four['before_id'])
    $companyListBefore = Project_bid_company_bak::getInfoByPfId($record_four['before_id']);

$companyListAfter = 0;
if($record_four['after_id'])
    $companyListAfter = Project_bid_company_bak::getInfoByPfId($record_four['after_id']);

if(!empty($id)) {
    $projectinfo = Project::getInfoById($project_four_after['project_id']);
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
        <div class="visit_popDiv visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>招标公司：</span>
            <p class="<?php if($project_four_before['project_cid_company'] != $project_four_after['project_cid_company']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_four_after['project_cid_company']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>负责人：</span>
            <p class="<?php if($project_four_before['project_cid_linkman'] != $project_four_after['project_cid_linkman']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_four_after['project_cid_linkman']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>联系方式：</span>
            <p class="<?php if($project_four_before['project_cid_linkphone'] != $project_four_after['project_cid_linkphone']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_four_after['project_cid_linkphone']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>招标文件：</span>
            <p class="<?php if($project_four_before['project_cid_file'] != $project_four_after['project_cid_file']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_four_after['project_cid_ac_file']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>投标文件：</span>
            <p class="<?php if($project_four_before['project_bid_file'] != $project_four_after['project_bid_file']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_four_after['project_bid_ac_file']; ?></p>
        </div>

        <?php
        if($companyListAfter){
            $sucessbid = "";
            $bdeforsucessbid = "";
            for($i = 0; $i < count($companyListAfter); $i ++){
                if($companyListBefore[$i]['isimportant'] == 1){
                    $bdeforsucessbid = $companyListBefore[$i]['name'];
                }
                if($companyListAfter[$i]['isimportant'] == 1){ $sucessbid = $companyListAfter[$i]['name'];}
        ?>
                <div class="visit_popDiv visit_popDiv2">
                    <span >公司<?php echo $i + 1; ?>：</span>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>公司名称：</span>
                    <p class="<?php if($companyListBefore[$i]['name'] != $companyListAfter[$i]['name']) echo 'programRevise_changeDiv'; ?>"><?php echo $companyListAfter[$i]['name']; ?></p>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg_one" src="images/must_react.png" alt="">  <span>现场价格：</span>
                    <p class="<?php if($companyListBefore[$i]['price'] != $companyListAfter[$i]['price']) echo 'programRevise_changeDiv'; ?>"><?php echo $companyListAfter[$i]['price']; ?></p>
                </div>
                <div class="visit_popDiv visit_popDiv2">
                    <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span>投标品牌：</span>
                    <p class="<?php if($companyListBefore[$i]['brand'] != $companyListAfter[$i]['brand']) echo 'programRevise_changeDiv'; ?>"><?php echo $companyListAfter[$i]['brand']; ?></p>
                </div>
        <?php
            }
        }
        ?>
        <div class="visit_popDiv visit_popDiv2">
            <img class="must_reactImg_one" src="images/must_react.png" alt=""> <span style="width: 120px">中标公司：</span>
            <p class="<?php if($bdeforsucessbid != $sucessbid) echo 'programRevise_changeDiv'; ?>"><?php echo $sucessbid; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
            <span style="width: 120px">招投标情况：</span>
            <p class="<?php if($project_four_before['project_cbid_situation'] != $project_four_after['project_cbid_situation']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_four_after['project_cbid_situation']; ?></p>
        </div>
    </div>
</div>
</body>
</html>