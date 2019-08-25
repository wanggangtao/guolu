<?php
/**
 * 项目第五阶段 project_stage_five_check.php
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
$LEFT_TAB_NVA = 'five';
$TOP_FLAG = "projectselect";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::getInfoById($id);
$project_five = Project_five::getInfoByProjectId($id);
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
if(empty($project_five)){
    $project_five = Project_five::Init();
}
$bonus_stage = explode('|', $projectinfo['bonus_stage']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目查询五级</title>
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
            $('#record_five').click(function(){
                location.href = 'project_five_record_show.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });
            $('.project_contract_ac_file').click(function(){
                var filepath = $(this).parent().find('input[name="contract_file_path"]').val();
                window.open(
                    '<?php echo $HTTP_PATH; ?>'+'project/file_preview.php?' +
                    'filepath='+ filepath +
                    '&username='+ '<?php echo $USERINFO['name'];?>',
                    '文件预览','height=500,width=611,scrollbars=yes,status=yes');
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
                    <div class="middleDiv_nine_left2"><p>善后情况</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_five['after_solve']; ?></div>
                        <button id="record_five">修改记录</button></div>
                    <div class="clear"></div>
                </div>

                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2 "> <p><img class="must_reactImg_one" src="images/must_react.png" alt="">合同金额</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo number_format($project_five['money'],2); ?>元</p>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="middleDiv_seven">
                    <div class="top">
                        <p>项目提成</p>
                    </div>
                    <div class="cont">
                        <?php if(in_array(1, $bonus_stage)){ ?><p>预付款到账后所得：<span id="first_reward"><?php echo number_format($project_five['first_reward'],2);?></span>元</p><?php } ?>
                        <?php if(in_array(2, $bonus_stage)){ ?><p>项目竣工验收后得：<span id="second_reward"><?php echo number_format($project_five['second_reward'],2);?></span>元</p><?php } ?>
                        <?php if(in_array(3, $bonus_stage)){ ?><p>质保金到账后所得：<span id="third_reward"><?php echo number_format($project_five['third_reward'],2);?></span>元</p><?php } ?>
                    </div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>合同文件</p></div>
                    <div class="clear"></div>
                </div>
                <?php
                if($project_five['contract_ac_file']){
                    $cfileurlarr = explode('|', $project_five['contract_file']);
                    $cfilenamearr = explode('|', $project_five['contract_ac_file']);
                    for ($i= 0; $i < count($cfileurlarr); $i++){
                        echo '<div class="middleDiv_nine">
                            <div class="middleDiv_one_div"><a class="project_contract_ac_file">'.$cfilenamearr[$i].'</a><input name="contract_file_path" type="hidden" value="'.$cfileurlarr[$i].'"/></div>
                        </div>';
                    }
                }
                ?>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"><p><img class="must_reactImg_one" src="images/must_react.png" alt="">付款条件</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_five['pay_condition']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"><p>用款计划</p></div>
                    <div class="middleDiv_nine_right2">
                        <div><?php echo $project_five['cost_plan']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2 "> <p><img class="must_reactImg_one" src="images/must_react.png" alt="">拟开工日期</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo getDateStrS($project_five['pre_build_time']); ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2 "> <p><img class="must_reactImg_one" src="images/must_react.png" alt="">验收日期</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo getDateStrS($project_five['pre_check_time']); ?></p>
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