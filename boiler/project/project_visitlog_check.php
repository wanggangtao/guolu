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

$TOP_TAB_NVA = "visitlog";
$TOP_LEFT_NVA = "visitlog";
$TOP_FLAG = "projectreview";

//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$target = isset($_GET['target'])?safeCheck($_GET['target'], 0):"";
$stday = isset($_GET['stday'])?strtotime(safeCheck($_GET['stday'], 0)):0;
$endday = isset($_GET['endday'])?strtotime(safeCheck($_GET['endday'], 0)):0;
$way = isset($_GET['way'])?safeCheck($_GET['way']):0;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
$pageSize  = 5;

$projectinfo = Project::Init();
if(!empty($id)){
    $projectinfo = Project::getInfoById($id) ;
    if($projectinfo['del_flag'] == 1){
        header("Location: project_deleteed.php");exit();
    }
    $visitlog_count = Project_visitlog::getPageList(1, 10, 0, $id, $target, $way, $stday, $endday);
    $visitlog_list = Project_visitlog::getPageList($page, $pageSize, 1, $id, $target, $way, $stday, $endday);
    if(empty($projectinfo)){
        echo '非法操作！';
        die();
    }

    $userinfo = User::getInfoById($projectinfo['user']);
    if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId)) {
        echo '没有权限操作！';
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目审批-拜访记录</title>
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
            $('.editinfo').click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '评论',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '300px'],
                    content: 'project_visitlog_comment.php?logid=' + thisid
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
        <div class="manageHRWJCont_middle_left manageRecord_left">
            <ul>
                <a href="project_visitlog_check.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "visitlog") echo 'class="HRWJCont_li"';?>>拜访记录</li>
                </a>
                <a href="project_inspectlog_check.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
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
                </div>
                <div class="right">
                    <input class="manageRecord_input1" type="text" placeholder="拜访对象" id="target" value="<?php echo $target;?>">
                    <input class="manageRecord_input1" type="text" placeholder="2018-06-21" id="stday" value="<?php echo getDateStrS($stday);?>">
                    <input class="manageRecord_input1" type="text" placeholder="2018-06-25" id="endday" value="<?php echo getDateStrS($endday);?>">

                    <div class="guolumain_4 myprogram_select">
                        <div class="select_1 myprogram_select2"><span id="way" data-dictid="<?php echo $way;?>"><?php echo empty($way)?"全部":$ARRAY_visit_way[$way]; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                        <div class="guolumain_4_2">
                            <div data-value="0" class="guolumain_4_3">全部</div>
                            <?php
                            foreach ($ARRAY_visit_way as $key => $val){
                                echo '<div data-value="'.$key.'" class="guolumain_4_3">'.$val.'</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <button class="manageRecord_btn2">查询</button>
                    </div>
                 <div class="clear"></div>
                </div>
            <div class="manageRecordTable">
                <table class="manageRecord_table2">
                    <tr class="manageRecordTable_fir">
                        <td>时间</td>
                        <td>拜访对象</td>
                        <td>联系方式</td>
                        <td>职位</td>
                        <td>拜访方式</td>
                        <td>拜访细节</td>
                        <td>评论</td>
                        <td>操作</td>
                    </tr>

                    <?php
                    if($visitlog_list){
                        foreach ($visitlog_list as $thislog) {
                            $commentlist = Project_visitlog_comment::getInfoByVisitlogid($thislog['id']);
                            $commentcontent = '';
                            foreach ($commentlist as $thiscomment){
                                $comuser = '';
                                if($thiscomment['comuser']){
                                    $comuserinfo = User::getInfoById($thiscomment['comuser']);
                                    $comuser = '<p>评论人：'.$comuserinfo['name'].'</p>';
                                }
                                $commentcontent .= $thiscomment['content'].$comuser.'<p>&nbsp;</p>';
                            }

                            $dostr = "";
                            //if(empty($thislog['comuser'])){
                                $dostr ='<img class="editinfo" src="images/writing.png" alt="">';
                            //}
                            echo '
                                <tr>
                                    <td>'.getDateStrS($thislog['visittime']).'</td>
                                    <td>'.$thislog['target'].'</td>
                                    <td>'.$thislog['tel'].'</td>
                                    <td>'.$thislog['position'].'</td>
                                    <td>'.$ARRAY_visit_way[$thislog['visitway']].'</td>
                                    <td><div><p>拜访内容：</p>'.$thislog['content'].'<p>拜访效果：</p>'.$thislog['effect'].'<p>下一步计划</p>'.$thislog['plan'].'</div></td>
                                    <td><div>'.$commentcontent.'</div></td>
                                    <td>
                                        '.$dostr.'
                                        <input type="hidden" id="aid" value="'.$thislog['id'].'"/>
                                    </td>
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
                ,count: <?php echo $visitlog_count; ?> //数据总数，从服务端得到
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
                        location.href  = "project_visitlog_check.php?tag=2&target="+"<?php echo $target; ?>"+"&way="+"<?php echo $way; ?>"+"&endday="+"<?php echo $endday; ?>"+"&stday="+"<?php echo $stday; ?>"+"&id="+"<?php echo $id; ?>"+"&page="+obj.curr+"&pagesize="+obj.limit;
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
                var dictid = $(this).attr("data-value");
                $(this).parent().prev('.select_1').find('span').text(Newtext);
                $(this).parent().prev('.select_1').find('span').attr("data-dictid",dictid);
                $(this).parent().slideUp(100);
                $(this).parent().prev('.select_1').find('img').removeClass('rotate');
            });
            //查找
            $('.manageRecord_btn2').click(function(){
                var way = $('#way').attr("data-dictid");
                var target = $('#target').val();
                var stday = $('#stday').val();
                var endday = $('#endday').val();
                location.href  = "project_visitlog_check.php?tag=2&target="+target+"&stday="+stday+"&endday="+endday+"&way="+way+"&id="+<?php echo $id;?>;
            });
        });
    </script>
</body>
</html>