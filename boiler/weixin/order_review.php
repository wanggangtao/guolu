<?php
/**
 * Created by PhpStorm.
 * User: wanggangtao
 *评价
 * Date: 2019/8/13
 * Time: 14:18
 */
require_once "admin_init.php";
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();//获取到用户的id
}
$user_info = user_account::getInfoByOpenid($userOpenId);//查找出用户的信息
$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}
if(empty($user_info)){
    header("Location: weixin_login.php?type=".$type);
    exit();
}
$id=$_GET["id"];//订单的id
print_r($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>评价</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/common.css" />
<!--         为图片添加样式，有重复样式-->
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">
//        var FILE_PATH = "<?php //echo 'http://127.0.0.1/zhimaopen/code_manage/'?>//";//'https://www.zhimawork.com/';     //网站访问路径，根据实际情况修改，务必以“/”结尾。
        var FILE_PATH="http://localhost/boiler/"             //文件的本地路径
        var fileNum = 0;//默认上传一张照片
         $(function() {
             function ajax_Picture_File() {
                 var index = layer.msg('正在提交,请稍后...', {
                     icon: 16
                     , shade: 0.3
                     , time: 100000
                 });
                 if (fileNum < 1) {
                     var uploadUrl = 'picture_upload_do.php?act=picture_upload';//处理上传的图片
                     var index = layer.msg('正在上传,请稍后...', {
                         icon: 16
                         , shade: 0.3
                         , time: 10000
                     });

                     $.ajaxFileUpload({
                         url: uploadUrl,
                         fileElementId: 'file',//要传的数据
                         dataType: 'json',
                         success: function (data) {

                             layer.close(index);
                             var code = data.code;//1
                             var msg = data.msg;//文件的想过路径+文件名
                             console.log(msg);
                             console.log(code);
                             switch (code) {//如果上传成功，则显示
                                 case 1:
                                     var picPath = FILE_PATH + msg;//本地服务器的绝对路径+文件名
//                                        console.log(picPath);//FILE_PATH已经在配置文件中定义
                                     var html = "<li>";//像是图片预览
                                     html += '<div class="fileList_img">';
                                     html += '<img src=\"' + picPath + '\"/>';
                                     html += '<input type="hidden" id="picture" value="' + msg + '"/>';//将这部分内容添加到页面指定位置
                                     html += '</div>';                                   //它的值为相对路径+文件名
                                     html += '</li>';
                                     $(".uploadImg .fileList ul").append(html);//在弹框中追加图片文件
                                     $(".fileList").show();
                                     $('#file').value('');
                                     break;
                                 default:
                                     layer.alert(msg, {icon: 5, closeBtn: 0});
                             }
                         },
                         error: function (data, status, e) {
                             layer.alert(e);
                         }
                     });
                 } else {
                     layer.alert('最多上传1张', {icon: 5});
                 }
             }
             $('#file').change(function () {
                 ajax_Picture_File();
                 return false;
             });
                $('#btn_sumit').click(function(){
                    var content = $("#content").val();//获取文本内容
                    var   id= <?php echo $id ?>;
                    var picture =$("#picture").val();//获取图片的相对路径+文件名
                    if( picture == null){
                        layer.msg("请上传图片",{icon:5});
                        return false;
                    }
                    alert(id);
                    if( content == ""){
                        layer.msg("请填写评价内容",{icon:5});
                        return false;
                    }
                    alert(content);
                    if( picture == ""){
                        layer.msg("请上传图片",{icon:5});
                        return false;
                    }
                    alert(picture);
//                    die();
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            content:content,//文本域
                            id:id,
                            picture:picture,
//                            act:"review"
                        },
                        dataType : 'json',
                        url : 'review_do.php?act=review',//数据进行添加到数据库
//                        url : 'review_do.php',
                        success:function (data) {
                            var code = data.code;
                            var msg  = data.msg;switch (code){
                                case 1:
                                    layer.alert(msg, {icon: 6}, function(index){
                                        parent.location.reload();
                                    });
                                    location.href="weixin_my_subscribe.php";
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    })
                    return true; // 在这里加上原来的代码
                });
            })
    </script>
</head>
<body>
<label><strong>评价：</strong></label><br>
<textarea  id="content" cols="50" rows="10"></textarea><br>


    <div class="control-counten">
        <label for="inputEmail3" class="control-label">作品截图:</label>
        <div class="btn btn-null-primary">
            <input type="file" name="file" id="file" style="opacity:0;position:absolute" />
            <span>添加图片</span>
        </div>
    </div>
    <div class="uploadImg">
        <!--                            //要在这追加内容，进行预览-->
        <div class="fileList">
            <ul>

            </ul>
        </div>
    </div>



<input type="submit" id="btn_sumit" value="确定"  name="submit" />
</body>
</html>
