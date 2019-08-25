<?php
/**
 * 项目查询的内部沟通 project_inspectlog.php
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
$TOP_LEFT_NVA = "communication";

//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
if($tag == 1){//我的项目
    $TOP_FLAG = 'myproject';
    $project_visitlog="project_visitlog.php";
    $project_inspectlog="project_inspectlog.php";
}elseif ($tag == 2) {//项目审批
    $TOP_FLAG = 'projectreview';
    $project_visitlog="project_visitlog_check.php";
    $project_inspectlog="project_inspectlog_check.php";
}elseif ($tag == 3){//项目查询
    $TOP_FLAG = 'projectselect';
    $project_visitlog="project_visitlog_show.php";
    $project_inspectlog="project_inspectlog_show.php";
}else{
    echo '非法操作！';
    die();
}

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$keywords = isset($_GET['keywords'])?safeCheck($_GET['keywords'],0):"";
//$keywords = isset($_GET['keywords'])?$_GET['keywords']:"";
$stday = isset($_GET['stday'])?strtotime(safeCheck($_GET['stday'], 0)):0;
$endday = isset($_GET['endday'])?strtotime(safeCheck($_GET['endday'], 0)):0;
//$stday = isset($_GET['stday'])?safeCheck($_GET['stday'], 0):0;
//$endday = isset($_GET['endday'])?safeCheck($_GET['endday'], 0):0;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
$page = 1;
$pageSize  = 20;

$projectinfo = Project::Init();
if(!empty($id)){
    $projectinfo = Project::getInfoById($id) ;
    if(empty($projectinfo)){
        echo '非法操作！';
        die();
    }
    $roominfo=Chat_room::getInfoByProject($id);
    $room_id=!empty($roominfo)?$roominfo['id']:"";
    $communication_count = null;
    $communication_list =null;
    if($room_id){
        $communication_count=Chat_room_msg::getPageList($page, $pageSize, 1, $room_id, $keywords, $stday, $endday);
        $pagecount = ceil($communication_count / $pageSize);
        $page      = getPage($pagecount);
        $communication_list=Chat_room_msg::getPageList($page, $pageSize, 0, $room_id, $keywords, $stday, $endday);
    }

}else{
    echo '非法操作！';
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目查询-内部沟通</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
<!--    <link rel="stylesheet" href="./css/xadmin.css">-->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
<!--    <link rel="stylesheet" href="layui/css/layui.css">-->
    <link rel="stylesheet" href="css/video-js.min.css" />
    <link rel="stylesheet" href="css/video-play.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript">


    </script>
</head>
<body class="body_1">
<?php include('top.inc.php');?>
<?php include('project_top.inc.php');?>
<div class="manageHRWJCont" style="height: auto;">
    <?php include('project_check_tab.inc.php');?>
    <div class="manageHRWJCont_middle">
        <div class="manageHRWJCont_middle_left manageRecord_left">
            <ul>
                <a href="<?php echo $project_visitlog?>?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "visitlog") echo 'class="HRWJCont_li"';?>>拜访记录</li>
                </a>
                <a href="<?php echo $project_inspectlog?>?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "inspectlog") echo 'class="HRWJCont_li"';?>>考察记录</li>
                </a>
                <a href="project_communication.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                    <li <?php if($TOP_LEFT_NVA == "communication") echo 'class="HRWJCont_li"';?>>内部沟通</li>
                </a>
            </ul>
        </div>
        <div class="manageHRWJCont_middle_middle">
            <div class="manageRecord_rightTop">
                <div class="right">
                    <input class="manageRecord_input1" type="text" placeholder="内容关键词" id="keywords" value="<?php echo $keywords;?>">
                    <input class="manageRecord_input1" type="text" placeholder="年-月-日" id="stday" value="<?php echo getDateStrC($stday);?>">
                    <input class="manageRecord_input1" type="text" placeholder="年-月-日" id="endday" value="<?php echo getDateStrC($endday);?>">
                    <button class="manageRecord_btn2">查询</button>
                </div>

                <div class="clear"></div>
            </div>

            <div class="manageRecordTable">
                <table class="manageRecord_table">
                    <tr class="manageRecordTable_fir">
                        <td style="width:200px;height: 48px; ">时间</td>
                        <td style="width:160px;height: 48px; ">责任人</td>
                        <td style="width:606px;height: 48px; ">内容</td>
                    </tr>

                    <?php
                    if(!empty($communication_list)){
                        foreach ($communication_list as $this_list) {

                            if ($this_list['type'] != 6) {

                                $html = "";
                                $html .= '<tr>
                                    <td>' . date('Y-m-d H:i', $this_list['addtime']) . '</td>
                                    <td>' . $this_list['uname'] . '</td>
                                    <td style="text-align:left;">';
//----------------------------------------文本消息-----------------------------------------------------
                                if ($this_list['type'] == 1) {
                                    $html .= $this_list['content'];

                                    //----------------------------------------视频消息---------------------
                                } elseif ($this_list['type'] == 3) {
                                    if (strpos($this_list['content'], "http:") === false) {//视频路径
                                        $currentUrl = $HTTP_PATH . $this_list['content'];
                                    } else {
                                        $currentUrl = $this_list['content'];
                                    }
                                    if (strpos($this_list['extra'], "http:") === false) {//视频第一帧路径
                                        $Url = $HTTP_PATH . $this_list['extra'];
                                    } else {
                                        $Url = $this_list['extra'];
                                    }
                                    $html .= '
                                
                                 <div style="width: auto;height: 100%;position: sticky;display: inline-block;">
                                    <img src="' . $Url . '" style="height: 100px;width: auto;max-width: 200px !important;" />
                                    <div class="btn-play" data-value="' . $currentUrl . '"><img src="images/play.png" alt="" >
                                   </div>
                                </div>
                            
                                ';
                                    //----------------------------------------图片消息--------------------
                                } elseif ($this_list['type'] == 4) {

                                    if (strpos($this_list['content'], "http:") === false) {
                                        $currentUrl = $HTTP_PATH . $this_list['content'];
//
                                    } else {
                                        $currentUrl = $this_list['content'];
//
                                    }
//
                                    $html .= '<div><img class="pic" src="' . $currentUrl . '" style="width:auto;height:100px;" /></div>';
                                    //----------------------------------------文档消息--------------------
                                } elseif ($this_list['type'] == 5) {
                                    if (strpos($this_list['content'], "http:") === false) {
                                        $currentUrl = $HTTP_PATH . $this_list['content'];
                                    } else {
                                        $currentUrl = $this_list['content'];
                                    }
                                    $html .= '<a href="' . $currentUrl . '" style="color: #04A6FE"  
                                        download="' . $currentUrl . '">文件</a>';
                                }
                                $html .= '<input type="hidden" id="id" value="' . $this_list['id'] . '"/></td>
                                    </tr>';
                                echo $html;
                            }
                        }
                    }else{
                        echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
                    }
                  ?>
                </table>
                <div id="test1" class="GLthree GLthree_one"></div>
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
            ,count: <?php echo $communication_count; ?> //数据总数，从服务端得到
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
                    location.href  = "project_communication.php?tag="+"<?php echo $tag; ?>"+"&keywords="+"<?php echo $keywords; ?>"+"&endday="+"<?php echo $endday; ?>"+"&stday="+"<?php echo $stday; ?>"+"&id="+"<?php echo $id; ?>"+"&page="+obj.curr+"&pagesize="+obj.limit;
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
        //查找
        $('.manageRecord_btn2').click(function(){
            var keywords = $('#keywords').val();
            var stday = $('#stday').val();
            var endday = $('#endday').val();
            location.href  = "project_communication.php?tag="+"<?php echo $tag?>"+"&keywords="+keywords+"&stday="+stday+"&endday="+endday+"&id="+<?php echo $id;?>;
        });



        //视频预览播放
        $('.btn-play').click(function(){
            var URL=$(this).data("value");
            var loadstr='<video width="100%" height="100%"   controls="controls" autobuffer="autobuffer"  autoplay="autoplay" loop="loop" style="position:fixed!important;top:0;left:0;">'
                + '<source src="'+URL+'" ></video>';
            layer.open({
                type:1,
                title: false,
                area: ['80%'],
                shade: 0.3,
                closeBtn: 1,
                content: loadstr,
            })
        });

        //图片弹框预览
        // $('.pic').click(function(){
        //     var _this = $(this);//将当前的pimg元素作为_this传入函数
        //     imgShow("#outerdiv", "#innerdiv", "#bigimg", _this);
        // });
        $('.pic').click(function(){
            var src = $(this).attr("src");//获取当前点击的pimg元素中的src属性
            getImageWidth(src,function(w,h){
                console.log({width:w,height:h});
                var realWidth = w;//获取图片真实宽度
                var realHeight = h;//获取图片真实高度
                var windowW = $(window).width();//获取当前窗口宽度
                var windowH = $(window).height();//获取当前窗口高度
                var imgWidth, imgHeight;
                var scale = 0.8;//缩放尺寸，当图片真实宽度和高度大于窗口宽度和高度时进行缩放
                if (realHeight > windowH * scale) {//判断图片高度
                    imgHeight = windowH * scale;//如大于窗口高度，图片高度进行缩放
                    imgWidth = imgHeight / realHeight * realWidth;//等比例缩放宽度
                    if (imgWidth > windowW * scale) {//如宽度扔大于窗口宽度
                        imgWidth = windowW * scale;//再对宽度进行缩放
                    }
                } else if (realWidth > windowW * scale) {//如图片高度合适，判断图片宽度
                    imgWidth = windowW * scale;//如大于窗口宽度，图片宽度进行缩放
                    imgHeight = imgWidth / realWidth * realHeight;//等比例缩放高度
                } else {//如果图片真实高度和宽度都符合要求，高宽不变
                    imgWidth = realWidth;
                    imgHeight = realHeight;
                }
                imgHeight += "px";
                imgWidth += "px";
                console.log(imgHeight);
                console.log(imgWidth);
                var img_infor = "<img src='" + src + "' style='width: 100%;height: 100%'/>" ;
                layer.open({
                    type:1,
                    area:[imgWidth,imgHeight],
                    title:false,
                    shade:0.3,
                    shadeClose: false,
                    // maxmin:true,
                    content:img_infor
                })
            });
        });
    });
    //获取图片真实的大小
    function getImageWidth(url,callback){
        var img = new Image();
        img.src = url;

        // 如果图片被缓存，则直接返回缓存数据
        if(img.complete){
            callback(img.width, img.height);
        }else{
            // 完全加载完毕的事件
            img.onload = function(){
                callback(img.width, img.height);
            }
        }

    }
    // function imgShow(outerdiv, innerdiv, bigimg, _this) {
    //     var src = _this.attr("src");//获取当前点击的pimg元素中的src属性
    //     $(bigimg).attr("src", src);//设置#upimg元素的src属性
    //
    //     /*获取当前点击图片的真实大小，并显示弹出层及大图*/
    //     $("<img/>").attr("src", src).load(function () {
    //         var windowW = $(window).width();//获取当前窗口宽度
    //         // alert(windowW);
    //         var windowH = $(window).height();//获取当前窗口高度
    //         var realWidth = this.width;//获取图片真实宽度
    //         var realHeight = this.height;//获取图片真实高度
    //         var imgWidth, imgHeight;
    //         var scale = 0.8;//缩放尺寸，当图片真实宽度和高度大于窗口宽度和高度时进行缩放
    //
    //         if (realHeight > windowH * scale) {//判断图片高度
    //             imgHeight = windowH * scale;//如大于窗口高度，图片高度进行缩放
    //             imgWidth = imgHeight / realHeight * realWidth;//等比例缩放宽度
    //             if (imgWidth > windowW * scale) {//如宽度扔大于窗口宽度
    //                 imgWidth = windowW * scale;//再对宽度进行缩放
    //             }
    //         } else if (realWidth > windowW * scale) {//如图片高度合适，判断图片宽度
    //             imgWidth = windowW * scale;//如大于窗口宽度，图片宽度进行缩放
    //             imgHeight = imgWidth / realWidth * realHeight;//等比例缩放高度
    //         } else {//如果图片真实高度和宽度都符合要求，高宽不变
    //             imgWidth = realWidth;
    //             imgHeight = realHeight;
    //         }
    //         $(bigimg).css("width", imgWidth);//以最终的宽度对图片缩放
    //
    //         var w = (windowW - imgWidth) / 2;//计算图片与窗口左边距
    //         var h = (windowH - imgHeight) / 2;//计算图片与窗口上边距
    //         $(innerdiv).css({"top": h, "left": w});//设置#innerdiv的top和left属性
    //         $(outerdiv).fadeIn("fast");//淡入显示#outerdiv及.pimg
    //     });
    //
    //     $(outerdiv).click(function () {//再次点击淡出消失弹出层
    //         $(this).fadeOut("fast");
    //     });
    // }

</script>
</body>
</html>