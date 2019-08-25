<?php
/**
 * 项目查询 project_list_select.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "projectselect";
//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$loginuser = 0;
if($USERINFO['role'] != 3){
    $loginuser = $USERId;
}
$department = 0;
if($USERINFO['role'] == 4){
    $loginuser = 0;
    $department = $USERINFO['department'];
}
$name = isset($_GET['name'])?safeCheck($_GET['name'], 0):"";
$username = isset($_GET['username'])?safeCheck($_GET['username'], 0):"";
$type = isset($_GET['type'])?safeCheck($_GET['type']):0;
$level = isset($_GET['level'])?safeCheck($_GET['level']):-1;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
$project_count = Project::getPageSeclectList(1, 10, 0, $name, $type, -1, $level, $username, $loginuser, 0, 0, $department);
$pageSize  = 15;
$project_list = Project::getPageSeclectList($page, $pageSize, 1, $name, $type, -1, $level, $username, $loginuser, 0, 0, $department);

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
    <title>项目管理-我的项目-项目查询</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $('.showsameinfo').click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                location.href="project_same_show.php?tag=3&top_flag=1&id=" + thisid;
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
        <a href="../home.php"><div class="guolumain_1">当前位置：项目管理 ></a>
                <span>项目查询</span>
    </div>
    <div class="clear"></div>
        <div class="guolumain_2">
            <input class="myprogram_input1 manageProject_input" type="text" id="name" placeholder="请输入项目名称" value="<?php echo $name; ?>">
            <input style="margin-left: 0" class="myprogram_input1 manageProject_input" type="text" id="username" placeholder="请输入项目负责人" value="<?php echo $username; ?>">

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
                        //if($key != 0){
                            echo '<div data-value="'.$key.'" class="guolumain_4_3">'.$val.'</div>';
                        //}
                    }
                    ?>
                </div>
            </div>
            <button id="selectbtn" class="selectbtn manageProject_button">查询</button>
            <?php
            if($USERINFO['role'] != 4){
                echo '<button id="newprojectbtn" class="selectbtn manageProject_btn">报备</button>';
            }
            ?>
        </div>
        <div >
            <table class="myprogram_table">
                <tr class="GLDetils9_fir">
<!--                    <td>项目ID</td>-->
                    <td>项目编号</td>
                    <td>报备时间</td>
                    <td>项目名称</td>
                    <td>项目地址</td>
                    <td class="myprogram_table_td1">项目类型</td>
                    <td>项目阶段</td>
                    <td class="myprogram_table_td1">项目负责人</td>
                    <?php if($USERINFO['role'] == 3 || $USERINFO['role'] == 2) echo '<td>操作</td>';?>
                </tr>
                <?php
                if($project_list){
                    foreach ($project_list as $thisitem){
                        $userinfo = User::getInfoById($thisitem['user']);
                        $starstr = "";
                        if($thisitem['stop_flag'] == 1 && $thisitem['status'] == 3){
                            $starstr = $ARRAY_project_level_stop[$thisitem['level']];
                        }else{
                            $starstr = $ARRAY_project_level[$thisitem['level']];
                        }
                        $userinfo = User::getInfoById($thisitem['user']);
                        $url = "";
                        if ($thisitem['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3 || $USERINFO['role'] == 4) {
                            $url = '<a href="project_stage_turn_show.php?tag=3&id='.$thisitem['id'].'">'.$thisitem['name'].'</a>';
                        }else{
                            $url = $thisitem['name'];
                        }
                        echo '
                            <tr>
                              <!-- <td>'.str_pad($thisitem['id'], 5, '0' ,STR_PAD_LEFT).'</td>   -->
                                <td>'.$thisitem['code'].'</td>
                                <td>'.getDateStrI($thisitem['addtime']).'</td>
                                <td>'.$url.'</td>
                                <td>'.$thisitem['detail'].'</td>
                                <td class="myprogram_table_td1">'.$type_array[$thisitem['type']].'</td>
                                <td>'.$starstr.'</td>
                                <td class="myprogram_table_td1">'.$userinfo['name'].'</td>';
                        if($USERINFO['role'] == 3 || $USERINFO['role'] == 2) {
                            echo '<td>
                                        <img class="delinfo" src="images/peoject_del.png" alt="删除">';
                            $project_one = Project_one::getInfoByProjectId($thisitem['id']);
                            if($project_one){
                            	$same_count = Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', $thisitem['notsame_id']);
                            	if($same_count > 1){
                            		echo '<img class="showsameinfo" src="images/showsame.png" alt="相似项目">';
                            	}else{
                            		echo '<img class="nosameinfo" src="images/nosame.png" alt="相似项目">';
                            	}
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
                                location.href  = "project_list_select.php?tag=3&name="+"<?php echo $name; ?>"+"&type="+"<?php echo $type; ?>"+"&level="+"<?php echo $level; ?>"+"&username="+"<?php echo $username; ?>"+"&page="+obj.curr+"&pagesize="+obj.limit;
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
                        var Newtext = $(this).text();
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
                    $('#selectbtn').click(function(){
                        var level = $('#level').attr("data-dictid");
                        var type = $('#type').attr("data-dictid");
                        var name = $('#name').val();
                        var username = $('#username').val();
                        location.href  = "project_list_select.php?tag=3&name="+name+"&type="+type+"&level="+level+"&username="+username;
                    });
                    //查找
                    $('#newprojectbtn').click(function(){
                        location.href  = "project_stage_one.php";
                    });
                })
            </script>

        </div>
    </div>
</body>
</html>