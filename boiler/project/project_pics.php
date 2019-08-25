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
$LEFT_TAB_NVA = "";
$TOP_FLAG = 'myproject';

//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

if(isset($_GET['id']))
    $id = safeCheck($_GET['id']);
else
    die();

$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
if($projectinfo['user'] != $USERId) {
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
    <title>项目管理-我的项目-图库</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#fileinput').on('change', '#file', function(){
                ajaxUpload();
                return false;
            });

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
        });
        function ajaxUpload(){
            var uploadUrl = 'pic_upload.php';//处理文件
            var index = layer.load(0, {shade: false});
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file',
                dataType      : 'json',
                success       : function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $.ajax({
                                type        : 'POST',
                                data        : {
                                    id : <?php echo $id;?>,
                                    type : <?php echo $type;?>,
                                    url : msg
                                },
                                dataType :    'json',
                                url :         'project_pics_do.php?act=add',
                                success :     function(data){
                                    var code2 = data.code;
                                    var msg2  = data.msg;
                                    switch(code2){
                                        case 1:
                                            var thisPic = '';
                                            thisPic +='<div class="picture_detail">';
                                            thisPic +='<img class="openbigimg" src="<?php echo $HTTP_PATH;?>'+msg+'" alt="">';
                                            thisPic +='</div>';
                                            thisPic +='<input type="hidden" class="id" value="'+msg2+'"/>';
                                            $('.manageHRWJCont_middle_middle').find('.managePicture_cont').find('.picture_detail:first-child').before(thisPic);
                                            layer.alert('上传成功', {icon: 6,shade: false}, function(index){
                                                location.reload();
                                            });
                                            break;
                                        default:
                                            layer.msg('上传失败', {icon: 5});
                                    }
                                }
                            });
                            break;
                        default:
                            layer.alert(msg, {icon: 5, title:'信息'});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
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
        <?php include('project_top.inc.php');?>

<div class="manageHRWJCont">
<?php include('project_tab.inc.php');?>
<div class="manageHRWJCont_middle">
    <div class="manageHRWJCont_middle_left manageRecord_left">
        <ul>
            <a href="project_pics.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                <li <?php if($type == 1) echo 'class="manage_liCheck"';?>>商务板块</li>
            </a>
            <a href="project_pics2.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>">
                <li <?php if($type == 2) echo 'class="manage_liCheck"';?>>技术板块</li>
            </a>
        </ul>
    </div>
    <div class="manageHRWJCont_middle_middle">
        <div class="manageRecord_rightTop">
            <div class="left">
                <div class="fileinput" id="fileinput">上传图像<input type="file" name="file" id="file"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="managePicture_cont">
        <?php
            if($pics){
                foreach ($pics as $thisPic){
                    echo '
                    
                    <div class="picture_detail">
                      <img class="openbigimg" src="'.$HTTP_PATH.$thisPic['url'].'" alt="">
                    </div>
                       
                       <input type="hidden" class="id" value="'.$thisPic['id'].'"/>
                    
                    ';
                }
            }
        ?>
            <div class="clear"></div>
        </div>
    </div>
    <div id="bigimgdiv" style="display: none"><img id="bigimgcontent" src="" width="800px" height="600px"></div>
    <div class="clear"></div>
</div>
</div>
</body>
</html>