<?php
/**
 * 项目第一阶段 project_stage_one.php
 *
 * @version       v0.01
 * @create time   2018/06/28
 * @update time   2018/06/28
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$postion = isset($_GET['postion'])?safeCheck($_GET['postion'], 0):'';
$projectinfo = Project::Init();
$project_one = Project_one::Init();
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    $project_one = Project_one::getInfoByProjectId($id);
    if (empty($projectinfo)) {
        echo '非法操作！';
        die();
    } else {
        $userinfo = User::getInfoById($projectinfo['user']);
        if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId)) {
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
    <title>项目管理-项目审批</title>
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
            $('#review_btn').click(function(){
                var reviewopinion = $('#reviewopinion').val();
                var project_review = $('input[name="project_review"]:checked ').val();

                if(project_review == 4){
                    if(reviewopinion == ''){
                        layer.msg('驳回原因不能为空');
                        return false;
                    }
                }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        postion : '<?php echo $postion;?>',
                        project_review : project_review,
                        reviewopinion  : reviewopinion
                    },
                    dataType :    'json',
                    url :         'project_review_do.php?act=project_review',
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

            $('input[type=radio][name=project_review]').change(function() {
                var val =  this.value;

                if( val == 3){
                    //$('#reviewopinionDiv').hide();
                    $('#reviewtitle').text('通过批注:');
                    $('#reviewopinion').text('');
                    $('#reactimg').hide();
                }else if( val == 4){
                    $('#reviewtitle').text('驳回原因:');
                    $('#reviewopinion').text('');
                    $('#reactimg').show();
                    //$('#reviewopinionDiv').show();
                }
            });
        });
    </script>
</head>
<body class="Check_pop_body" style="width: auto;">
<div class="Check_pop" style="position: inherit;">
    <div class="Check_popCont">
        <img src="images/must_react.png" alt=""> <span>审核:</span>
        <label class="Check_popContBtn"><input class="input_change" name="project_review" type="radio" value="3" />通过</label>
        <label class="Check_popContBtn"><input class="input_change" name="project_review" type="radio" value="4" />驳回</label>
        <div>
            <div id="reviewopinionDiv">
                <p><img id="reactimg" src="images/must_react.png" alt=""><span id="reviewtitle">驳回原因:</span></p>
                <textarea name="reviewopinion" id="reviewopinion" cols="30" rows="8"></textarea>
            </div>
            <button id="review_btn">确定</button>
        </div>
    </div>
</div>

</body>
</html>