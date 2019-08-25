<?php
/**
 * 项目第三阶段 project_stage_three.php
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
$LEFT_TAB_NVA = "three";
$TOP_FLAG = 'myproject';

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
if(!empty($id)){
    $project_three = Project_three::getInfoByProjectId($id);
    if(empty($project_three)){
        $project_three = Project_three::Init();
    }
    if($projectinfo['user'] != $USERId) {
        echo '没有权限操作！';
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-<?php echo $projectinfo['name'];?>三级</title>
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
            $('#project_three_sbumit').click(function(){
                var competitive_brand_situation = $('#competitive_brand_situation').val();
                var progress_situation = $('#progress_situation').val();
                var invitation_situation = $('#invitation_situation').val();
                var other_situation = $('#other_situation').val();
                if(competitive_brand_situation == ''){
                    layer.msg('竞争品牌动向不能为空');
                    return false;
                }
                if(progress_situation == ''){
                    layer.msg('工作进展程度不能为空');
                    return false;
                }
                if(invitation_situation == ''){
                    layer.msg('招标情况不能为空');
                    return false;
                }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_three['id'];?>,
                        project_id : <?php echo $id;?>,
                        competitive_brand_situation : competitive_brand_situation,
                        progress_situation  : progress_situation,
                        invitation_situation  : invitation_situation,
                        other_situation  : other_situation
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_three_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                $.ajax({
                                    type        : 'POST',
                                    data        : {
                                        id : <?php echo $project_three['id'];?>,
                                        project_id : <?php echo $id;?>
                                    },
                                    dataType :    'json',
                                    url :         'project_do.php?act=project_three_submit',
                                    success :     function(data){
                                        var code = data.code;
                                        var msg  = data.msg;
                                        switch(code){
                                            case 1:
                                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                                    location.reload();
                                                });
                                                break;
                                            default:
                                                layer.alert(msg, {icon: 5});
                                        }
                                    }
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

            $('#project_three_save').click(function(){
                var competitive_brand_situation = $('#competitive_brand_situation').val();
                var progress_situation = $('#progress_situation').val();
                var invitation_situation = $('#invitation_situation').val();
                var other_situation = $('#other_situation').val();
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_three['id'];?>,
                        project_id : <?php echo $id;?>,
                        competitive_brand_situation : competitive_brand_situation,
                        progress_situation  : progress_situation,
                        invitation_situation  : invitation_situation,
                        other_situation  : other_situation
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_three_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
            $('#record_three').click(function(){
                location.href = 'project_three_record.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });
        });
    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
            <?php include('project_top.inc.php');?>
    <div class="manageHRWJCont">
        <?php include('project_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">
                <div class="middleDiv_all">
                    <div class="middleDiv_one">
                        <span></span>
                        <div class="manageHRWJCont_middle_right">
                            <?php if(($projectinfo['three_status'] != 2 && $projectinfo['three_status'] != 3 && ($projectinfo['two_status'] == 2 || $projectinfo['two_status'] == 3)  && $projectinfo['stop_flag'] != 1 && $projectinfo['level'] >= 1)){ ?>
                                <button id="project_three_sbumit" class="submit">提交</button>
                            <?php } ?>
                            <button id="record_three">修改记录</button>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="middleDiv_four middleDiv_four_cheack">
                        <p><img class="must_reactImg" src="images/must_react.png" alt="">竞争品牌动向</p>
                        <textarea name="competitive_brand_situation" id="competitive_brand_situation" cols="30" rows="10"placeholder="竞争品牌动向..."><?php echo HTMLDecode($project_three['competitive_brand_situation']); ?></textarea>

                    </div>
                    <div class="middleDiv_four">
                        <p><img class="must_reactImg" src="images/must_react.png" alt="">工作进展程度</p>
                        <textarea name="progress_situation" id="progress_situation" cols="30" rows="10"placeholder="工作对象及进展程度......."><?php echo HTMLDecode($project_three['progress_situation']); ?></textarea>

                    </div>
                    <div class="middleDiv_four">
                        <p><img class="must_reactImg" src="images/must_react.png" alt="">招标情况</p>
                        <textarea name="invitation_situation" id="invitation_situation" cols="30" rows="10"placeholder="招标形式，招标公司....."><?php echo HTMLDecode($project_three['invitation_situation']); ?></textarea>

                    </div>
                    <div class="middleDiv_four">
                        <p>其他</p>
                        <textarea name="other_situation" id="other_situation" cols="30" rows="10"placeholder="其他情况....."><?php echo HTMLDecode($project_three['other_situation']); ?></textarea>

                    </div>

                    <div class="middleDiv_two">
                        <?php if($projectinfo['three_status'] != 2 && $projectinfo['stop_flag'] != 1){ ?>
                            <button id="project_three_save">保存</button>
                        <?php } ?>
                    </div>

                </div>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>