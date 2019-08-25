<?php
/**
 * 项目审批 project_list_review.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "projectreview";

//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$user = $USERId;
$name = isset($_GET['name'])?safeCheck($_GET['name'], 0):"";
$username = isset($_GET['username'])?safeCheck($_GET['username'], 0):"";
$type = isset($_GET['type'])?safeCheck($_GET['type']):0;
$level = isset($_GET['level'])?safeCheck($_GET['level']):-1;
$status = isset($_GET['status'])?safeCheck($_GET['status']):2;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
if($USERINFO['role'] == 3){
    $user=0;
}
$project_count = Project::getPageReviewList(1, 10, 0, $name, $type, $status, $level, $user, $username);
$pageSize  = 15;
$project_list = Project::getPageReviewList($page, $pageSize, 1, $name, $type, $status, $level, $user, $username);

$typlist = Project_type::getAllList();
$type_array = array();
foreach ($typlist as $thistype){
    $type_array[$thistype['id']] = $thistype['name'];
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
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $('.myprogram_button1').click(function(){
                location.href = 'project_stage_one.php';
            });

            $('.showsameinfo').click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                location.href="project_same_check.php?tag=2&top_flag=0&id=" + thisid;
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
                    time: 500
                });
            });
            $(".showsameinfo").mouseover(function(){
                layer.tips('相似项目', $(this), {
                    tips: [4, '#04A6FE'],
                    time: 1000
                });
            });
            $(".nosameinfo").mouseover(function(){
                layer.tips('相似项目', $(this), {
                    tips: [4, '#04A6FE'],
                    time: 1000
                });
            });
        });
    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
    <div class="guolumain_project">
        <a href="../home.php"><div class="guolumain_1">当前位置：项目管理 ></a><span> 项目审批</span></div><div class="clear"></div>
        <div class="guolumain_2">
            <input class="myprogram_input1 myprogram_input2" type="text" placeholder="项目名称" id="name" value="<?php echo $name;?>">
            <input class="myprogram_input1 myprogram_input2" type="text" placeholder="项目负责人" id="username" value="<?php echo $username;?>">
            <div class="guolumain_4 myprogram_select">
                <div class="select_1 myprogram_select1"><span id="status" data-dictid="<?php echo $status;?>"><?php echo ($status == -1)?"全部":$ARRAY_project_status_review[$status]; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                <div class="guolumain_4_2">
                    <div data-value="-1" class="guolumain_4_3">全部</div>
                    <?php
                    foreach ($ARRAY_project_status_review as $key => $val){
                        echo '<div data-value="'.$key.'" class="guolumain_4_3">'.$val.'</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="guolumain_4 myprogram_select">
                <div class="select_1 myprogram_select1"><span id="type" data-dictid="<?php echo $type;?>"><?php $typeinfo = Project_type::getInfoById($type); echo empty($typeinfo)?"全部":$typeinfo['name']; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                <div class="guolumain_4_2 ">
                    <div data-value="0" class="guolumain_4_3">全部</div>
                    <?php
                    foreach ($typlist as $thisinfo){
                        echo '<div data-value="'.$thisinfo['id'].'" class="guolumain_4_3">'.$thisinfo['name'].'</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="guolumain_4 myprogram_select">
                <div class="select_1 myprogram_select1"><span id="level" data-dictid="<?php echo $level;?>"><?php echo ($level == -1)?"全部":$ARRAY_project_level[$level]; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                <div class="guolumain_4_2">
                    <div data-value="-1" class="guolumain_4_3">全部</div>
                    <?php
                    foreach ($ARRAY_project_level as $key => $val){
                        echo '<div data-value="'.$key.'" class="guolumain_4_3">'.$val.'</div>';
                    }
                    ?>
                </div>
            </div>
            <button class="selectbtn myprogram_button2">查询</button>
        </div>
        <div >
            <table class="myprogram_table">
                <tr class="GLDetils9_fir">
                    <td>项目编号</td>
                    <td>提交时间</td>
                    <td>项目名称</td>
                    <td>项目地址</td>
                    <td class="myprogram_table_td1">项目类型</td>
                    <td>项目阶段</td>
                    <td class="myprogram_table_td1">状态</td>
                    <td>项目负责人</td>
                    <?php if($USERINFO['role'] == 3 || $USERINFO['role'] == 2) echo '<td>操作</td>';?>
                </tr>
                <?php
                if($project_list){
                    foreach ($project_list as $thisitem){
                        $starstr = "";
                        if($thisitem['stop_flag'] == 1 && $thisitem['status'] == 3){
                            $starstr = $ARRAY_project_level_stop[$thisitem['level']];
                        }else{
                            $starstr = $ARRAY_project_level[$thisitem['level']];
                        }
                        $userinfo = User::getInfoById($thisitem['user']);
                        echo '
                            <tr>
                                <td>'.$thisitem['code'].'</td>
                                <td>'.getDateStr($thisitem['lastupdate']).'</td>
                                <td><a href="project_stage_turn_check.php?tag=2&id='.$thisitem['id'].'">'.$thisitem['name'].'</a></td>
                                <td>'.$thisitem['detail'].'</td>
                                <td class="myprogram_table_td1">'.$type_array[$thisitem['type']].'</td>
                                <td>'.$starstr.'</td>
                                <td class="myprogram_table_td1">'.$ARRAY_project_status_review[$thisitem['status']].'</td>
                                <td>'.$userinfo['name'].'</td>';
                        if($USERINFO['role'] == 3 || $USERINFO['role'] == 2) {
                            echo '<td>
                                        <img class="delinfo" src="images/peoject_del.png" alt="删除">';
                            $project_one = Project_one::getInfoByProjectId($thisitem['id']);
                            if(!empty($project_one)){
                                $same_count = Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $thisitem['notsame_id']);

                            }else{
                                $same_count=0;
                            }
                            if($same_count > 1){
                                echo '<img class="showsameinfo" src="images/showsame.png" alt="相似项目">';
                            }else{
                                echo '<img class="nosameinfo" src="images/nosame.png" alt="相似项目">';
                            }
                            echo '
                                        <input type="hidden" id="aid" value="' . $thisitem['id'] . '"/>
                                  </td>';
                        }
                        echo '</tr>
                        ';
                    }
                }

                ?>
            </table>
            <div id="test1" class="GLthree GLthree_two"></div>
            <script src="layui/layui.js"></script>
            <script>
                layui.use('laypage', function(){
                    var laypage = layui.laypage;

                    //执行一个laypage实例
                    laypage.render({
                        elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
                        ,count: <?php echo $project_count; ?> //数据总数，从服务端得到
                        ,curr:<?php echo $page; ?>
                        ,limit:<?php echo $pageSize; ?>
                        ,groups:3
                        ,layout:['count','prev','page','next']
                        ,jump: function(obj, first){
                            //obj包含了当前分页的所有参数，比如：
                            //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                            //console.log(obj.limit); //得到每页显示的条数
                            //首次不执行
                            if(!first){
                                //do something
                                location.href  = "project_list_review.php?tag=2&name="+"<?php echo $name; ?>"+"&username="+"<?php echo $username; ?>"+"&type="+"<?php echo $type; ?>"+"&level="+"<?php echo $level; ?>"+"&status="+"<?php echo $status; ?>"+"&page="+obj.curr+"&pagesize="+obj.limit;
                            }
                        }

                    });
                });
            </script>
            <script>
                $(function () {
                    $('.select_1').click(function () {
                        $(this).next('.guolumain_4_2').slideDown('fast');
                        $(this).find('img').addClass('rotate');
                    });
                    $('.guolumain_4_3').click(function () {
                        var Newtext = $(this).text();if(Newtext == ''){
                            Newtext = $(this).html();
                        }
                        var dictid = $(this).attr("data-value");
                        $(this).parent().prev('.select_1').find('span').html(Newtext);
                        $(this).parent().prev('.select_1').find('span').attr("data-dictid",dictid);
                        $(this).parent().slideUp(100);
                        $(this).parent().prev('.select_1').find('img').removeClass('rotate');
                    });
                    //查找
                    $('.selectbtn').click(function(){
                        var level = $('#level').attr("data-dictid");
                        var type = $('#type').attr("data-dictid");
                        var status = $('#status').attr("data-dictid");
                        var name = $('#name').val();
                        var username = $('#username').val();
                        location.href  = "project_list_review.php?tag=2&name="+name+"&type="+type+"&level="+level+"&status="+status+"&username="+username;
                    });
                });
            </script>

        </div>
    </div>
</body>
</html>