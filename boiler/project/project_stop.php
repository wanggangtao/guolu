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
$TOP_FLAG = 'myproject';
$LEFT_TAB_NVA = "stop";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
if(!empty($id)){
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
    <title>项目管理-<?php echo $projectinfo['name'];?>终止</title>
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
            $('#stop_save').click(function(){
                var stopreason = $('#stopreason').val();
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        project_id : <?php echo $id;?>,
                        stopreason  : stopreason
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=stopreason_save',
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
            $('#stop_submit').click(function(){
                var stopreason = $('#stopreason').val();

                $.ajax({
                    type        : 'POST',
                    data        : {
                        project_id : <?php echo $id;?>,
                        stopreason  : stopreason
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=stopreason_submit',
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
                            <?php if($projectinfo['status'] != 2 && $projectinfo['stop_flag'] != 1){ ?>
                                <button id="stop_submit">提交</button>
                            <?php } ?>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="middleDiv_four middleDiv_four_cheack">
                        <p><img class="must_reactImg" src="images/must_react.png" alt="">终止原因</p>
                        <textarea name="stopreason" id="stopreason" cols="30" rows="10" placeholder="终止原因..."><?php echo HTMLDecode($projectinfo['stopreason']);?></textarea>
                    </div>

                    <div class="middleDiv_two">
                        <?php if($projectinfo['status'] != 2 && $projectinfo['stop_flag'] != 1){ ?>
                            <button id="stop_save">保存</button>
                        <?php } ?>
                    </div>

                </div>

                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>