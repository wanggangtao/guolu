<?php
/**
 * 项目第一阶段修改记录列表 project_one_record.php
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
$TOP_FLAG = "projectselect";
$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$projectinfo = Project::Init();
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    $project_one_count = Project_one_record::getPageList(1, 10, 0, $id);
    $project_one_list = Project_one_record::getPageList(1, $project_one_count, 1, $id);
    if (empty($projectinfo)) {
        echo '非法操作！';
        die();
    } else {
        $userinfo = User::getInfoById($projectinfo['user']);
        if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3 || ($USERINFO['role'] == 4 && $userinfo['department'] == $USERINFO['department']))) {
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
    <title>项目管理-项目查询-修改查看</title>
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
                    area: ['700px', '700px'],
                    content: 'project_one_record_info.php?id=' + thisid
                });
            });
        });

    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>

            <?php include('project_show_top.inc.php');?>

    <div class="manageHRWJCont">
        <?php include('project_show_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <div class="programRevise">
                <div class="programRevise_top">
                    第一阶段修改记录
                </div>
                <div class="programRevise_cont">
                    <table>
                        <tr>
                            <th>修改时间</th>
                            <th>修改人</th>
                            <th>操作</th>
                        </tr>
                        <?php
                        if($project_one_list){
                            foreach ($project_one_list as $oner){
                                echo '
                                    <tr>
                                        <td>'.getDateStr($oner['addtime']).'</td>
                                        <td>'.$oner['user_name'].'</td>
                                        <td> <a href="javascript:void(0);return false;" class="detail">查看>></a>
                                            <input type="hidden" id="aid" value="'.$oner['id'].'"/>
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