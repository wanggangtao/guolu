<?php
/**
 * 项目拜访记录 project_visitlog_check.php
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
$TOP_ARR  = ['projectreview','projectselect'];
$THIS_PAGE = 'same_check';
$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$top_flag = isset($_GET['top_flag'])?safeCheck($_GET['top_flag']):'0';
$TOP_FLAG = $TOP_ARR[$top_flag];
$projectinfo = Project::getInfoById($id);
$project_one = Project_one::getInfoByProjectId($id);
if(empty($projectinfo) || empty($project_one)){
    echo '非法操作';
    die();
}
/*$userinfo = User::getInfoById($projectinfo['user']);
if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId)) {
    echo '没有权限操作！';
    die();
}*/
if (!($USERINFO['role'] == 3 || $USERINFO['role'] == 2)) {
    echo '没有权限操作！';
    die();
}
$typlist = Project_type::getAllList();
$type_array = array();
foreach ($typlist as $thistype){
    $type_array[$thistype['id']] = $thistype['name'];
}
$project_list = Project_one::getPageSameList(0, 10, 1, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $projectinfo['notsame_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目审批-相似项目</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            //移除相似项目
            $(".delsameinfo").click(function(){
                var sameid = $(this).parent('td').find('#aid').val();
                layer.confirm('确认移除这个项目吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:<?php echo $id;?>,
                                sameid : sameid
                            },
                            dataType : 'json',
                            url : 'project_do.php?act=del_sameproject',
                            success : function(data){
                                layer.close(index);
                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            location.reload();
                                        });
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    }, function(){}
                );
            });
            //删除
            $(".delinfo").click(function(){
                var id = $(this).parent('td').find('#aid').val();
                var mydel = layer.open({
                    type: 2,
                    title: '删除原因',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '300px'],
                    content: 'project_delete.php?id=' + id
                });
            });

            $(".delinfo").mouseover(function(){
                layer.tips('删除项目', $(this), {
                    tips: [4, '#04A6FE'],
                    time: 1000
                });
            });
            $(".delsameinfo").mouseover(function(){
                layer.tips('移除相似项目', $(this), {
                    tips: [4, '#04A6FE'],
                    time: 1000
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

        <div class="programRevise">
            <div class="programRevise_top">
                相似项目
            </div>
            <div class="myprogram_table">
                <table class="myprogram_table">
                    <tr class="GLDetils9_fir">
                        <td>项目编号</td>
                        <td>报备时间</td>
                        <td>项目名称</td>
                        <td>项目地址</td>
                        <td>项目类型</td>
                        <td>项目阶段</td>
                        <td>状态</td>
                        <td>项目负责人</td>
                        <?php if($USERINFO['role'] == 3 || $USERINFO['role'] == 2) echo '<td>操作</td>';?>
                    </tr>
                    <?php
                    if($project_list) {
                        foreach ($project_list as $thisitem) {
                            if ($thisitem['project_id'] != $id) {

                                $thisproject = Project::getInfoById($thisitem['project_id']);
                                $starstr = "";
                                if ($thisproject['stop_flag'] == 1 && $thisproject['status'] == 3) {
                                    $starstr = $ARRAY_project_level_stop[$thisproject['level']];
                                } else {
                                    $starstr = $ARRAY_project_level[$thisproject['level']];
                                }
                                $userinfo = User::getInfoById($thisproject['user']);
                                if (!empty($userinfo)) {
                                    $url = "";
                                    if ($thisproject['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3) {
                                        $url = '<a href="project_stage_turn_check.php?id=' . $thisitem['project_id'] . '&tag=2">' . $thisitem['project_name'] . '</a>';
                                    } else {
                                        $url = $thisitem['project_name'];
                                    }
                                    echo '
                                    <tr>
                                        <td>' . $thisproject['code'] . '</td>
                                        <td>' . getDateStrI($thisproject['addtime']) . '</td>
                                        <td>' . $url . '</td>
                                        <td>' . $thisitem['project_detail'] . '</td>
                                        <td>' . ($thisitem['project_type']?$type_array[$thisitem['project_type']]:'') . '</td>
                                        <td>' . $starstr . '</td>
                                        <td>' . $ARRAY_project_status[$thisproject['status']] . '</td>
                                        <td>' . $userinfo['name'] . '</td>';

                                    if ($USERINFO['role'] == 3 || $USERINFO['role'] == 2 )
                                        echo '<td>
                                        <img class="delinfo" src="images/peoject_del.png" alt="删除">
                                        <img class="delsameinfo" src="images/same_del.png" alt="移除相似项目">
                                        <input type="hidden" id="aid" value="' . $thisitem['project_id'] . '"/>
                                  </td>';
                                    echo '</tr>
                                ';
                                }
                            }
                        }
                    }
                    ?>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>