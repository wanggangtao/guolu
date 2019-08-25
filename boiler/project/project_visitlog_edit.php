<?php
/**
 * 项目拜访记录修改 project_visitlog_edit.php
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
    if ($projectinfo['user'] != $USERId) {
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
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#addvisitlog').click(function(){
                var target = $('#target').val();
                var tel = $('#tel').val();
                var positionstr = $('#position').val();
                var visitway = $('#visitway').val();
                var content = $('#content').val();
                var effect = $('#effect').val();
                var plan = $('#plan').val();

                if(target == ''){
                    layer.msg('拜访对象不能为空');
                    return false;
                }
                if(tel == ''){
                    layer.msg('联系方式不能为空');
                    return false;
                }
                if(positionstr == ''){
                    layer.msg('职位不能为空');
                    return false;
                }
                if(visitway == '' || visitway == 0){
                    layer.msg('拜访方式不能为空');
                    return false;
                }
                if(content == ''){
                    layer.msg('拜访内容不能为空');
                    return false;
                }
                if(effect == ''){
                    layer.msg('拜访效果不能为空');
                    return false;
                }
                if(plan == ''){
                    layer.msg('下步计划不能为空');
                    return false;
                }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $logid;?>,
                        target : target,
                        tel  : tel,
                        positionstr  : positionstr,
                        visitway  : visitway,
                        content  : content,
                        effect  : effect,
                        plan  : plan
                    },
                    dataType :    'json',
                    url :         'project_log_do.php?act=visitedit',
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
            <img src="images/must_react.png" alt=""> <span>拜访对象</span>
            <input type="text" id="target" placeholder="拜访对象" value="<?php echo $vloginfo['target'];?>">
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>联系方式</span>
            <input type="text" id="tel" placeholder="联系方式" value="<?php echo $vloginfo['tel'];?>">
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>职位</span>
            <input type="text" id="position" placeholder="联系人职位" value="<?php echo $vloginfo['position'];?>">
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>拜访方式</span>
            <select name="visitway" id="visitway">
                <?php
                $sstr = "";
                foreach ($ARRAY_visit_way as $key => $val){
                    if($vloginfo['visitway'] == $key){
                        $sstr = "selected";
                    }
                    echo '<option value="'.$key.'"'.$sstr.'>'.$val.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="visit_popDiv">
            <div class="left">
                <img src="images/must_react.png" alt=""> <span>拜访内容</span>
            </div>
            <div class="right">
                 <textarea name="content" id="content" cols="30" rows="10"><?php echo HTMLDecode($vloginfo['content']);?></textarea>
            </div>
        </div>
        <div class="visit_popDiv">
            <div class="left">
                <img src="images/must_react.png" alt=""> <span> 拜访效果</span>
            </div>
            <div class="right">
                 <textarea name="effect" id="effect" cols="30" rows="10"><?php echo HTMLDecode($vloginfo['effect']);?></textarea>
            </div>
        </div>
        <div class="visit_popDiv">
            <div class="left">
                <img src="images/must_react.png" alt=""> <span> 下步计划</span>
            </div>
            <div class="right">
                 <textarea name="plan" id="plan" cols="30" rows="10"><?php echo HTMLDecode($vloginfo['plan']);?></textarea>
            </div>
        </div>

        <div class="visit_popDiv">
            <button id="addvisitlog">确定</button>
        </div>
    </div>
</div>
</body>
</html>