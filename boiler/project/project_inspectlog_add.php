<?php
/**
 * 项目考察记录添加 project_inspectlog_add.php
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
$projectinfo = Project::Init();
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    if (empty($projectinfo)) {
        echo '非法操作！';
        die();
    } else {
        if ($projectinfo['user'] != $USERId) {
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
    <title>考察记录</title>
    <link rel="stylesheet" href="css/main_two.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            laydate({
                elem: '#inspecttime', //需显示日期的元素选择器
                event: 'click', //触发事件
                format: 'YYYY-MM-DD', //日期格式
                istime: false, //是否开启时间选择
                isclear: true, //是否显示清空
                istoday: true, //是否显示今天
                issure: true, //是否显示确认
                festival: true, //是否显示节日
                choose: function(dates){ //选择好日期的回调
                }
            });

            $('#addinspectlog').click(function(){
                var inspecttime = $('#inspecttime').val();
                var member = $('#member').val();
                var company = $('#company').val();
                var brand = $('#brand').val();
                var address = $('#address').val();
                var situation = $('#situation').val();

                if(inspecttime == ''){
                    layer.msg('考察时间不能为空');
                    return false;
                }
                if(member == ''){
                    layer.msg('考察人员不能为空');
                    return false;
                }
                if(company == ''){
                    layer.msg('考察单位不能为空');
                    return false;
                }
                if(brand == ''){
                    layer.msg('考察品牌能为空');
                    return false;
                }
                if(address == ''){
                    layer.msg('考察地点不能为空');
                    return false;
                }
                if(situation == ''){
                    layer.msg('考察情况不能为空');
                    return false;
                }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        inspecttime : inspecttime,
                        member  : member,
                        company  : company,
                        brand  : brand,
                        address  : address,
                        situation  : situation
                    },
                    dataType :    'json',
                    url :         'project_log_do.php?act=inspectadd',
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
            <img src="images/must_react.png" alt=""> <span> 考察时间</span>
            <input type="text" placeholder="时间" id="inspecttime" readonly>
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>考察人员</span>
            <input type="text" placeholder="人员" id="member">
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>考察单位</span>
            <input type="text" placeholder="考察单位" id="company">
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>考察品牌</span>
            <input type="text" placeholder="品牌名字" id="brand">
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>考察地点</span>
            <input type="text" placeholder="考察地点" id="address">
        </div>
        <div class="visit_popDiv">
            <div class="left">
                <img src="images/must_react.png" alt=""> <span>考察情况</span>
            </div>
            <div class="right">
                 <textarea name="situation" id="situation" cols="30" rows="10"></textarea>
            </div>
        </div>

        <div class="visit_popDiv">
            <button id="addinspectlog">确定</button>
        </div>


    </div>
</div>
</body>
</html>