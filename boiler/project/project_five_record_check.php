<?php
/**
 * 项目第五阶段修改记录列表 project_five_record.php
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
$TOP_FLAG = "projectreview";
$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$projectinfo = Project::Init();
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    $project_five_count = Project_five_record::getPageList(1, 10, 0, $id);
    $project_five_list = Project_five_record::getPageList(1, $project_five_count, 1, $id);
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
    <title>项目管理-项目审批-修改查看</title>
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
            $('.detail').click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '信息',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'project_five_record_info.php?id=' + thisid
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
                    第五阶段修改记录
                </div>
                <div class="programRevise_cont">
                    <table>
                        <tr>
                            <th>修改时间</th>
                            <th>修改人</th>
                            <th>操作</th>
                        </tr>
                        <?php
                        if($project_five_list){
                            foreach ($project_five_list as $fiver){
                                echo '
                                    <tr>
                                        <td>'.getDateStr($fiver['addtime']).'</td>
                                        <td>'.$fiver['user_name'].'</td>
                                        <td> <a href="javascript:void(0);return false;" class="detail">查看>></a>
                                            <input type="hidden" id="aid" value="'.$fiver['id'].'"/>
                                        </td>
                                    </tr>';
                            }
                        }
                        ?>
                    </table>
                </div>

                <div class="clear"></div>
            </div>
        </div>
    </div>

</body>
</html>