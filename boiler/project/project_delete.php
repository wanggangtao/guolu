<?php
/**
 * 项目删除原因 project_delete.php
 *
 * @version       v0.01
 * @create time   2018/08/23
 * @update time   2018/08/23
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    $project_one = Project_one::getInfoByProjectId($id);
    if (empty($projectinfo)) {
        echo '非法操作！';
        die();
    } else {
        if (!($USERINFO['role'] == 3 || $USERINFO['role'] == 2)) {
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
    <title>项目管理-项目删除</title>
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
            $('#review_cancel').click(function(){
                var mydel = parent.layer.getFrameIndex(window.name);
                parent.layer.close(mydel);
            });
            $('#review_btn').click(function(){
                var del_reason = $('#del_reason').val();

                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        del_reason  : del_reason
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_delete',
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
<body class="Check_pop_body" style="width: auto;">
<div class="Check_pop" style="position: inherit;">
    <div class="Check_popCont">
        <div>
            <div id="reviewopinionDiv">
                <textarea name="del_reason" id="del_reason" cols="30" rows="8" placeholder="删除原因" style="width: 500px;"></textarea>
            </div>
            <div>
                <button id="review_cancel" align="left"  style="margin: 0px 100px 0px;float:left;background-color: #FFFFFF;color: #04A6FE;border: solid 1px;">取消</button>
                <button id="review_btn" style="margin: 0px 0px 0px;float: left;" >确定</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>