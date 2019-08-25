<?php
/**
 * 修改公司动态
 * Created by PhpStorm.
 * Date: 2018/12/5
 * Time: 14:36
 */

require_once('admin_init.php');
require_once('admincheck.php');
$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = web_intro_projectpic::getInfoById($id);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){
            //编辑器初始化


            $('#btn_sumit').click(function(){
                var http = $('#http').val();
                var picurl = $('#picurl1').val();
                var reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
                if(http == ''){
                    layer.alert('网址不能为空',{icon: 5});
                    return false;
                }
                if(!reg.test(http) && http != '#'){
                    layer.alert('请按规定输入正确网址',{icon: 5,shade: false});
                    return false;
                }
                if(picurl==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id   : '<?php echo $id;?>',
                        http      : http,
                        picurl   : picurl
                    },
                    dataType :    'json',
                    url :         'content_companyintro_projectpic_do.php?act=edit',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

        });

        function ajaxUpload(value){
            if($('#upload_file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=projectpic&id='+ value;//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'upload_file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#picurl'+ value).val(msg);
                            $('#val'+ value).attr("src",'<?php echo $HTTP_PATH;?>' + msg);
                            layer.msg('上传成功');
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
    </script>
</head>
<body>
<div id="formlist">




    <p>
        <label>链接地址</label>
        <input type="text" class="text-input input-length-25" name="http" id="http"  value="<?php echo $info['http'];?>" />
        <span class="warn-inline">* 请以http://或https://开头,若无图片链接,请输入：#</span>
    </p>
    <p>
        <label>图片上传</label>
        <input id="picurl1"  name="picurl1" type="hidden"  value="<?php echo $info['picurl'];?>" />
        <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
        <span class="warn-inline">*</span>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
        <span class="warn-inline">图片大小：220px*100px</span>
    </p>
    <p style="padding-left:150px;"><img id="val1" src="<?php echo $HTTP_PATH.$info['picurl'];?>" width="220px" height="100px" alt="" /></p>

    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>