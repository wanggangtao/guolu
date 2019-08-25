<?php
/**
 * 项目考察记录 project_inspectlog.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "visitlog";
$TOP_LEFT_NVA = "inspectlog";
$TOP_FLAG = 'myproject';

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$target = isset($_GET['target'])?safeCheck($_GET['target'], 0):"";
$stday = isset($_GET['stday'])?strtotime(safeCheck($_GET['stday'], 0)):0;
$endday = isset($_GET['endday'])?strtotime(safeCheck($_GET['endday'], 0)):0;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
$pageSize  = 5;

$projectinfo = Project::Init();
if(!empty($id)){
    $projectinfo = Project::getInfoById($id) ;
    if($projectinfo['del_flag'] == 1){
        header("Location: project_deleteed.php");exit();
    }
    $inspectlog_count = Project_inspectlog::getPageList(1, 10, 0, $id, $target, $stday, $endday);
    $inspectlog_list = Project_inspectlog::getPageList($page, $pageSize, 1, $id, $target, $stday, $endday);
    if(empty($projectinfo)){
        echo '非法操作！';
        die();
    }

    if($projectinfo['user'] != $USERId){
        echo '没有权限操作！';
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-我的项目-考察记录</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#addinslog').click(function(){
                layer.open({
                    type: 2,
                    title: '添加考察记录',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '600px'],
                    content: 'project_inspectlog_add.php?id=' + '<?php echo $id;?>'
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
        <div class="manageHRWJCont_middle_left manageRecord_left">
            <ul>
                <a href="project_visitlog.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "visitlog") echo 'class="HRWJCont_li"';?>>拜访记录</li>
                </a>
                <a href="project_inspectlog.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "inspectlog") echo 'class="HRWJCont_li"';?>>考察记录</li>
                </a>
                <a href="project_communication.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "communication") echo 'class="HRWJCont_li"';?>>内部沟通</li>
                </a>
            </ul>
        </div>
        <div class="manageHRWJCont_middle_middle">
            <div class="manageRecord_rightTop">
                <div class="left">
                    <button class="myprogram_button1 manageRecord_btn1" id="addinslog">添加</button>
                </div>
                <div class="right">
                    <input class="manageRecord_input1" type="text" placeholder="考察单位" id="target" value="<?php echo $target;?>">
                    <input class="manageRecord_input1" type="text" placeholder="2018-06-21" id="stday" value="<?php echo getDateStrS($stday);?>">
                    <input class="manageRecord_input1" type="text" placeholder="2018-06-25" id="endday" value="<?php echo getDateStrS($endday);?>">
                    <button class="manageRecord_btn2">查询</button>
                </div>

                <div class="clear"></div>
            </div>

            <div class="manageRecordTable">
                <table class="manageRecord_table">
                    <tr class="manageRecordTable_fir">
                        <td>时间</td>
                        <td>考察人员</td>
                        <td>考察单位</td>
                        <td>考察品牌</td>
                        <td>考察地点</td>
                        <td>考察情况</td>
                    </tr>

                    <?php
                    if($inspectlog_list){
                       foreach ($inspectlog_list as $thislog) {
                           echo '
                                <tr>
                                    <td>'.getDateStrS($thislog['inspecttime']).'</td>
                                    <td>'.$thislog['member'].'</td>
                                    <td>'.$thislog['company'].'</td>
                                    <td>'.$thislog['brand'].'</td>
                                    <td>'.$thislog['address'].'</td>
                                    <td><div>'.$thislog['situation'].'</div></td>
                                </tr>';
                       }
                    }
                    ?>
                </table>
                <div id="test1" class="GLthree GLthree_one"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    </div>
    <script src="layui/layui.js"></script>
    <script>
        layui.use('laypage', function(){
            var laypage = layui.laypage;

            //执行一个laypage实例
            laypage.render({
                elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
                ,count: <?php echo $inspectlog_count; ?> //数据总数，从服务端得到
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
                        location.href  = "project_inspectlog.php?tag=1&target="+"<?php echo $target; ?>"+"&way="+"<?php echo $way; ?>"+"&endday="+"<?php echo $endday; ?>"+"&stday="+"<?php echo $stday; ?>"+"&id="+"<?php echo $id; ?>"+"&page="+obj.curr+"&pagesize="+obj.limit;
                    }
                }

            });
        });
    </script>
    <script>
        $(function () {
            laydate({
                elem: '#stday', //需显示日期的元素选择器
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
            laydate({
                elem: '#endday', //需显示日期的元素选择器
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
            $('.select_1').click(function () {
                $(this).next('.guolumain_4_2').slideDown('fast');
                $(this).find('img').addClass('rotate');
            });
            $('.guolumain_4_3').click(function () {
                var Newtext = $(this).text();
                $(this).parent().prev('.select_1').find('span').text(Newtext);
                $(this).parent().slideUp(100);
                $(this).parent().prev('.select_1').find('img').removeClass('rotate');
            });
            //查找
            $('.manageRecord_btn2').click(function(){
                var target = $('#target').val();
                var stday = $('#stday').val();
                var endday = $('#endday').val();
                location.href  = "project_inspectlog.php?tag=1&target="+target+"&stday="+stday+"&endday="+endday+"&id="+<?php echo $id;?>;
            });
        });
    </script>
</body>
</html>