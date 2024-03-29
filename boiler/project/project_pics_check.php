<?php
/**
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "pics";
$TOP_FLAG = "projectreview";

if(isset($_GET['id']))
    $id = safeCheck($_GET['id']);
else
    die();
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
$userinfo = User::getInfoById($projectinfo['user']);
if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId)) {
    echo '没有权限操作！';
    die();
}
$type = 1;
$count = Project_pictures::getPageList(1, 10, 0, $id, $type);
$pics = Project_pictures::getPageList(1, $count, 1, $id, $type);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目审批-图库</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>    <script type="text/javascript">
        $(function(){

            $("body").on('click','.openbigimg',function(){
                var obj = $(this).attr("src");
                $('#bigimgcontent').attr("src",obj);
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area: '800px',
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true,
                    content: $('#bigimgdiv')
                });
            });
            $("body").on("click", ".picture_deletBtn", function(){
                var thisid = $(this).parent().parent().find('.id').val();
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : thisid
                    },
                    dataType :    'json',
                    url :         'project_pics_do.php?act=del',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert('删除成功', {icon: 6,shade: false}, function(index){
                                    location.reload();
                                });
                                break;
                            default:
                                layer.msg('删除失败', {icon: 5});
                        }
                    }
                });
            });
            $('.picture_detail').hover(function () {
                $(this).find('p').css('display','block');
            },function () {
                $(this).find('p').css('display','none');
            })
        });
    </script>
    <style>
        .del {
            width: 80px;
            height: 30px;
            line-height: 30px;
            background: #04A6FE;
            box-shadow: 0 6px 24px 0 rgba(10,122,182,0.30);
            border-radius: 6px;
            font-family: PingFangSC-Regular;
            font-size: 20px;
            color: #FFFFFF;
            letter-spacing: 0.93px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            margin-left: 10px;
            text-align: center;
        }
        .fileinput {
            float: left;
            position: relative;
            overflow: hidden;
            width: 140px;
            height: 40px;
            line-height: 40px;
            background: #04A6FE;
            box-shadow: 0 6px 24px 0 rgba(10,122,182,0.30);
            border-radius: 6px;
            font-family: PingFangSC-Regular;
            font-size: 20px;
            color: #FFFFFF;
            letter-spacing: 0.93px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            text-align: center;
        }

        .fileinput input {
            position: absolute;
            right: 0;
            top: 0;
            opacity: 0;
        }
    </style>
</head>
<body class="body_1">
<?php include('top.inc.php');?>
        <?php include('project_check_top.inc.php');?>

    <div class="manageHRWJCont">
<?php include('project_check_tab.inc.php');?>
<div class="manageHRWJCont_middle">
    <div class="manageHRWJCont_middle_left manageRecord_left">
        <ul>
            <a href="project_pics_check.php?tag=<?php echo $tag; ?>&id=<?php echo $id;?>">
                <li <?php if($type == 1) echo 'class="manage_liCheck"';?>>商务板块</li>
            </a>
            <a href="project_pics2_check.php?tag=<?php echo $tag; ?>&id=<?php echo $id;?>">
                <li <?php if($type == 2) echo 'class="manage_liCheck"';?>>技术板块</li>
            </a>
        </ul>
    </div>
    <div class="manageHRWJCont_middle_middle">
        <div class="manageRecord_rightTop">

        </div>
        <div class="managePicture_cont">
        <?php
            if($pics){
                foreach ($pics as $thisPic){
                    echo '
                   
                    
                    <div class="picture_detail">
                      <img class="openbigimg" src="'.$HTTP_PATH.$thisPic['url'].'" alt="">
                        <p><img class="picture_deletBtn" src="images/picture_delet.png" alt=""></p>
                         <input type="hidden" class="id" value="'.$thisPic['id'].'"/>
                    </div>
                       
                      
                  
                    ';
                }
            }
        ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<div id="bigimgdiv" style="display: none"><img id="bigimgcontent" src="" width="800px" height="600px"></div>
</body>
</html>