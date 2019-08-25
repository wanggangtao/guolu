<?php
/**
 * 项目第二阶段修改记录列表 project_two_record.php
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
$TOP_FLAG = 'myproject';

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::Init();
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    $project_two_count = Project_two_record::getPageList(1, 10, 0, $id);
    $project_two_list = Project_two_record::getPageList(1, $project_two_count, 1, $id);
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
    <title>项目管理-我的项目-修改查看</title>
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
                    area: ['600px', '600px'],
                    content: 'project_two_record_info.php?id=' + thisid
                });
            });
        });

    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
            <?php include('project_top.inc.php');?>

    <div class="manageHRWJCont">
        <?php include('project_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <div class="programRevise">
                <div class="programRevise_top">
                    第二阶段修改记录
                </div>
                <div class="programRevise_cont">
                    <table>
                        <tr>
                            <th>修改时间</th>
                            <th>修改人</th>
                            <th>操作</th>
                        </tr>
                        <?php
                        if($project_two_list){
                            foreach ($project_two_list as $twor){
                                echo '
                                    <tr>
                                        <td>'.getDateStr($twor['addtime']).'</td>
                                        <td>'.$twor['user_name'].'</td>
                                        <td> <a href="javascript:void(0);return false;" class="detail">查看>></a>
                                            <input type="hidden" id="aid" value="'.$twor['id'].'"/>
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