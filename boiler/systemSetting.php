<?php
/**
 * 项目考察记录 project_inspectlog.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('init.php');
require_once('usercheck.php');

$type = isset($_GET['type'])?safeCheck($_GET['type']):'1';
$id = 0;
$tplinfo = Message_tpl::getInfoByType($type);
if($tplinfo){
    $id = $tplinfo['id'];
}
if(!in_array($USERINFO['role'], array(2,3))){
    echo '没有操作权限！';
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>系统设置-销售提醒设置</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/main_two.css">
    <link rel="stylesheet" href="static/css/newlayui.css">
    <link rel="stylesheet" href="static/js/layui/css/layui.css">
    <script type="text/javascript" src="static/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="static/js/myprogram.js"></script>
    <script type="text/javascript" src="static/js/layui/layui.js"></script>
    <script type="text/javascript">
        $(function(){
            $('.submit_pop2_btn').click(function(){
                var content = $('#content').val();

                if(content == ''){
                    layui.use('layer', function(){
                        var layer = layui.layer;
                        layer.tips('内容不能为空',"#content");
                    });
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        type : <?php echo $type;?>,
                        content : content
                    },
                    dataType :    'json',
                    url :         'setting_do.php',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layui.use('layer', function(){
                                    var layer = layui.layer;
                                    layer.alert(msg, {icon: 6,shade: false}, function(index){
                                        location.reload();
                                    });
                                });

                                break;
                            default:
                                layui.use('layer', function(){
                                    var layer = layui.layer;
                                    layer.alert(msg, {icon: 5});
                                });
                        }
                    }
                });
            });

        });
    </script>
</head>
<body class="body_1">
    <div class="indexTop">
        <div class="indexTop_1">
            <a href="home.php"><div class="indexTop_logo">
                    <img src="static/images/top_logo.png" alt="">
                </div></a>
            <a href="systemSetting.php">
                <div class="indexTop_2 indexTop_checked ">
                    <img src="static/images/setting_gray.png" >
                    <img src="static/images/setting_check.png">
                    <span>系统设置</span>
                </div></a>

            <a href="logout.php"><img src="static/images/backlogon.png" class="indexTop_4"><span class="indexTop_6"></span></a>
        </div>
    </div>

    <div class="manageHRWJCont">

        <div class="manageHRWJCont_middle">
            <div class="manageHRWJCont_middle_left">
                <ul>
                    <a href="systemSetting.php?type=1"><li <?php if($type == 1) echo 'class="manage_liCheck"';?>>销售提醒设置</li></a>
                    <a href="systemSetting.php?type=2"><li <?php if($type == 2) echo 'class="manage_liCheck"';?>>销售经理,总经理提醒设置</li></a>
                </ul>
            </div>
            <div class="manageHRWJCont_middle_middle">
                <div class="middleDiv_all">

                    <div class="middleDiv_four middleDiv_eight">

                        <textarea name="content" id="content" cols="30" rows="10" placeholder="Lorem ipsum dolor sit amet, consectetur ..."><?php echo HTMLDecode($tplinfo['content']);?></textarea>
                    </div>
                    <div class="middleDiv_two">
                        <button class="submit_pop2_btn">保存</button>
                    </div>

                </div>

                <div class="clear"></div>
            </div>
        </div>
    </div>
</body>
</html>