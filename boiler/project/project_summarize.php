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

$TOP_TAB_NVA = "summarize";
$TOP_FLAG = 'myproject';
$LEFT_TAB_NVA = "";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$projectinfo = Project::Init();
if(!empty($id)){
    $projectinfo = Project::getInfoById($id) ;
    if(empty($projectinfo)){
        echo '非法操作！';
        die();
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
    <title>项目管理-项目沉淀</title>
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
            $('#summarize_save').click(function(){
                var summarize = $('#summarize').val();
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        project_id : <?php echo $id;?>,
                        summarize  : summarize
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=summarize_save',
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
            <div class="manage_programDown">
                <div class="middleDiv_four">
                    <p>项目沉淀</p>
                    <textarea class="textarea_summarize" name="summarize" id="summarize" cols="30" rows="10" placeholder="项目沉淀..."><?php echo HTMLDecode($projectinfo['summarize']); ?></textarea>
                </div>

                <div class="middleDiv_two">
                    <button id="summarize_save">保存</button>
                </div>
            </div>
            <div class="clear"></div>

        </div>
</body>
</html>