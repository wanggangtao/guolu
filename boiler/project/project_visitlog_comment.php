<?php
/**
 * 项目拜访记录评论 project_visitlog_comment.php
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

$logid = isset($_GET['logid'])?safeCheck($_GET['logid']):'0';
$vloginfo = Project_visitlog::getInfoById($logid);

if (empty($vloginfo)) {
    echo '非法操作！';
    die();
} else {
    $projectinfo = Project::getInfoById($vloginfo['projectid']);
    $userinfo = User::getInfoById($projectinfo['user']);
    if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId || ($USERINFO['role'] == 4 && $userinfo['department'] == $USERINFO['department']))) {
        echo '没有权限操作！';
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>拜访记录</title>
    <link rel="stylesheet" href="css/main_two.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#addvisitlog').click(function(){
                var comment = $('#comment').val();

                /*if(comment == ''){
                    layer.msg('评论内容不能为空');
                    return false;
                }*/
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $logid;?>,
                        comment : comment
                    },
                    dataType :    'json',
                    url :         'project_log_do.php?act=visitcomment',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
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
<body>
<div class="visit_pop">

    <div class="vist_popCont">
        <div class="visit_popDiv">
            <div class="left">
                <span>评论</span>
            </div>
            <div class="right">
                 <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
            </div>
        </div>
        <div class="visit_popDiv">
            <button id="addvisitlog">确定</button>
        </div>
    </div>
</div>
</body>
</html>