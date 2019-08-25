<?php
/**
 * 项目第一阶段 project_stage_one_check.php
 *
 * @version       v0.01
 * @create time   2018/06/28
 * @update time   2018/06/28
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "setting";
$TOP_FLAG = "projectreview";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';

$projectinfo = Project::getInfoById($id);
if (empty($projectinfo)) {
    echo '非法操作！';
    die();
} else {
    $userinfo = User::getInfoByParentid($USERId);
    if ($projectinfo['user'] != $USERId && empty($userinfo)) {
        echo '没有权限操作！';
        die();
    }
}
$bonus_stage = explode('|', $projectinfo['bonus_stage']);

$advicelist = Project_advice::getPageList(0, 10, 1, $id, "");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目审批-设置</title>
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
            $('#bonus').blur(function(){
                var bonus = $('#bonus').val();
                var reg = /(^[0-9]\d*$)/;
                if (bonus!= '' && !(reg.test(bonus))) {
                    layer.msg('请输入正确数字！');
                    return false;
                }
            });
            $('.manageHRWJCont_middle_left').find('li').click(function(){
                   $(this).addClass('manage_liCheck').siblings().removeClass('manage_liCheck');
               });
            $('#changeProuser').click(function(){
                var project_user = $('#project_user').val();

                if(project_user == '' || project_user == 0){
                    layer.msg('负责人不能为空');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        project_user : project_user
                    },
                    dataType :    'json',
                    url :         'project_review_do.php?act=changeProuser',
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

            $('#saveBonus').click(function(){
                var bonus = $('#bonus').val();

                if(bonus == '' || bonus == 0){
                    layer.msg('提出比例不能为空');
                    return false;
                }
                var reg = /(^[0-9]\d*$)/;
                if (bonus!= '' && !(reg.test(bonus))) {
                    layer.msg('请输入正确数字！');
                    return false;
                }
                var powernum =  $('#bonus_stage_div .input_change').length;
                var bonus_stage = "";
                for(i=0; i<powernum; i++){
                    if($('#bonus_stage_div .input_change').eq(i).prop('checked')){
                        bonus_stage = $('#bonus_stage_div .input_change').eq(i).val() + '|' + bonus_stage;
                    }
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        bonus : bonus,
                        bonus_stage : bonus_stage
                    },
                    dataType :    'json',
                    url :         'project_review_do.php?act=saveBonus',
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

            $('#notice_one').blur(function(){
                var notice_one = $('#notice_one').val();
                var reg = /(^[0-9]\d*$)/;
                if (notice_one!= '' && !(reg.test(notice_one))) {
                    layer.msg('请输入正确数字！');
                    return false;
                }
            });
            $('#notice_two').blur(function(){
                var notice_two = $('#notice_two').val();
                var reg = /(^[0-9]\d*$)/;
                if (notice_two!= '' && !(reg.test(notice_two))) {
                    layer.msg('请输入正确数字！');
                    return false;
                }
            });
            $('#notice_three').blur(function(){
                var notice_three = $('#notice_three').val();
                var reg = /(^[0-9]\d*$)/;
                if (notice_three!= '' && !(reg.test(notice_three))) {
                    layer.msg('请输入正确数字！');
                    return false;
                }
            });
            $('#saveNotice').click(function(){
                var notice_one = $('#notice_one').val();
                var notice_two = $('#notice_two').val();
                var notice_three = $('#notice_three').val();
                var reg = /(^[0-9]\d*$)/;
                if(notice_one == ''){
                    layer.msg('提醒时间一级不能为空');
                    return false;
                }
                if (!(reg.test(notice_one))) {
                    layer.msg('提醒时间一级:请输入正确数字！');
                    return false;
                }
                if(notice_two == ''){
                    layer.msg('提醒时间二级不能为空');
                    return false;
                }
                if (!(reg.test(notice_two))) {
                    layer.msg('提醒时间二级:请输入正确数字！');
                    return false;
                }
                if(notice_three == ''){
                    layer.msg('提醒时间三级不能为空');
                    return false;
                }
                if (!(reg.test(notice_three))) {
                    layer.msg('提醒时间三级:请输入正确数字！');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        notice_one : notice_one,
                        notice_two : notice_two,
                        notice_three : notice_three
                    },
                    dataType :    'json',
                    url :         'project_review_do.php?act=saveNotice',
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
            $('#saveExport').click(function(){

                var export_flag = $('input[name="export_flag"]:checked ').val();

                if(export_flag == ''){
                    layer.msg('导出标志不能为空');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        export_flag : export_flag
                    },
                    dataType :    'json',
                    url :         'project_review_do.php?act=saveExport',
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



            $('#addadvice').click(function(){
                layer.open({
                    type: 1,
                    title: '项目建议',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '300px'],
                    content: $('.Check_popCont')
                });
            });

            $('#saveAdvice').click(function(){

                var advice_content = $('#advice_content').val();
                if(advice_content == ''){
                    layer.msg('项目建议不能为空');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        advice_content : advice_content
                    },
                    dataType :    'json',
                    url :         'project_review_do.php?act=saveAdvice',
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
        <?php include('project_check_top.inc.php');?>

    <div class="manageHRWJCont">
    <?php include('project_check_tab.inc.php');?>
    <div class="manageHRWJCont_middle">
        <div class="manageHRWJCont_middle_left">
            <ul>

                <li class="manage_liCheck" onclick="setting_one(this)">项目负责人</li>

                <li onclick="setting_two(this)">提成比例</li>

                <li onclick="setting_three(this)">提醒时间</li>
                <li onclick="setting_four(this)">导出设置</li>
                <li onclick="setting_five(this)">项目建议</li>
            </ul>

        </div>
        <div class="manageHRWJCont_middle_middle">
            <div class="ProjectCheck_setting_contAll ProjectCheck_setting_cont1">
                <div class="middleDiv_one middleDiv_one_select">
                    <p>修改负责人</p>
                    <div>
                        <select id="project_user">
                            <?php
                            if($USERINFO['role'] == 3){
                                $userlist = User::getPageList(1, 999, 1, '', 1, 0, 0);
                            }else{
                                $userlist = User::getInfoByParentid($USERId);
                                $userlist[] = $USERINFO;
                            }

                            if($userlist){
                                foreach ($userlist as $thisuser){
                                    $selectstr = "";
                                    if($thisuser['id'] == $projectinfo['user']){
                                        $selectstr = "selected";
                                    }
                                    echo '<option value ="'.$thisuser['id'].'"'.$selectstr.'>'.$thisuser['name'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <button class="ProjectCheck_setting_btn" id="changeProuser">保存</button>
            </div>
            <div class="ProjectCheck_setting_contAll ProjectCheck_setting_cont2">
                <div class="middleDiv_one">
                    <div>   <p>比例</p></div>
                    <div> <input type="text" placeholder="20" id="bonus" value="<?php echo $projectinfo['bonus']*100; ?>"><span>%</span>
                        <span class="ProjectCheck_setting_span">注：项目合同金额的百分比</span>
                    </div>
                </div>
                <div class="middleDiv_eleven">
                    <p>提成阶段</p>
                    <div id="bonus_stage_div">
                        <label class="setting_ContInput"><input class="input_change" name="bonus_stage" type="checkbox" value="1" <?php if(in_array(1, $bonus_stage)) echo 'checked';?>/>预付款到帐</label>
                        <label class="setting_ContInput"><input class="input_change" name="bonus_stage" type="checkbox" value="2" <?php if(in_array(2, $bonus_stage)) echo 'checked';?>/>项目竣工验收</label>
                        <label class="setting_ContInput"><input class="input_change" name="bonus_stage" type="checkbox" value="3" <?php if(in_array(3, $bonus_stage)) echo 'checked';?>/>质保金到帐</label>
                    </div>
                </div>


                <button class="ProjectCheck_setting_btn" id="saveBonus">保存</button>
            </div>
            <div class="ProjectCheck_setting_contAll ProjectCheck_setting_cont3">
                <div class="middleDiv_one">
                    <div> <p>一级</p></div>
                    <div> <input type="text" placeholder="2" id="notice_one" value="<?php echo $projectinfo['notice_one']; ?>"><span>天</span></div>
                </div>
                <div class="middleDiv_one">
                    <div>   <p>二级</p></div>
                    <div> <input type="text" placeholder="5" id="notice_two" value="<?php echo $projectinfo['notice_two']; ?>"><span>天</span></div>
                </div>
                <div class="middleDiv_one">
                    <div>   <p>三级</p></div>
                    <div> <input type="text" placeholder="8" id="notice_three" value="<?php echo $projectinfo['notice_three']; ?>"><span>天</span></div>
                </div>
                <button class="ProjectCheck_setting_btn" id="saveNotice">保存</button>
            </div>
            <div class="ProjectCheck_setting_contAll ProjectCheck_setting_cont4">
                <div class="middleDiv_eleven">
                    <p>导出设置</p>
                    <div>
                        <label class="Check_popContBtn"><input class="input_change" name="export_flag" type="radio" value="1" <?php if($projectinfo['export_flag'] == 1) echo "checked"; ?>/>开通</label>
                        <label class="Check_popContBtn"><input class="input_change" name="export_flag" type="radio" value="0" <?php if($projectinfo['export_flag'] == 0) echo "checked"; ?>/>关闭</label>
                        <span>注：开通或关闭销售人员项目导出功能。</span>
                    </div>
                </div>
                <button class="ProjectCheck_setting_btn" id="saveExport">保存</button>
            </div>
            <div class="ProjectCheck_setting_contAll ProjectCheck_setting_cont5">
                <div class="manageRecord_rightTop">
                    <div class="left">
                        <button class="myprogram_button1 manageRecord_btn1" id="addadvice">新增</button>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="manageRecordTable">
                    <table class="manageRecord_table">
                        <tr class="manageRecordTable_fir">
                            <td>时间</td>
                            <td>建议人</td>
                            <td>建议内容</td>
                        </tr>

                        <?php
                        if($advicelist){
                            foreach ($advicelist as $thislog) {
                                $thisuser = User::getInfoById($thislog['user']);
                                echo '
                                <tr>
                                    <td>'.getDateStrS($thislog['addtime']).'</td>
                                    <td>'.$thisuser['name'].'</td>
                                    <td><div>'.$thislog['content'].'</div></td>
                                </tr>';
                            }
                        }
                        ?>
                    </table>
                    <div id="test1" class="GLthree GLthree_one"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="Check_popCont" style="display: none">
                <div>
                    <div id="reviewopinionDiv">
                        <textarea style="width: 500px;" name="advice_content" id="advice_content" cols="30" rows="6" placeholder="项目建议、项目提醒等"></textarea>
                    </div>
                    <button id="saveAdvice">保存</button>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
</body>
</html>