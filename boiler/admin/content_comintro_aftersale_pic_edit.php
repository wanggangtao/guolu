<?php
/**
 * Created by Lin.
 * Date: 2018/12/26
 * Time: 22:36
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = Web_intro_aftersale_pic::getInfoById($id);
if(empty($info)){
    echo '非法操作';
    die();
}
//echo $HTTP_PATH.$info['picurl1'];
//print_r($info);
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
            $('#btn_sumit').click(function(){

                var picurl1 = $('#picurl1').val();
                var picurl2 = $('#picurl2').val();
                var picurl3 = $('#picurl3').val();
                var picurl4 = $('#picurl4').val();

                if(picurl1==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }
                if(picurl2==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }
                if(picurl3==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }
                if(picurl4==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id   :    '<?php echo $id;?>',
                        picurl1   : picurl1,
                        picurl2   : picurl2,
                        picurl3   : picurl3,
                        picurl4   : picurl4
                    },
                    dataType :    'json',
                    url :         'content_comintro_aftersale_do.php?act=edit_pic',
                    success :     function(data){
                      //  alert(data);
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
            var uploadUrl = 'all_upload.php?type=aftersalepic&id='+ value;//处理文件
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
        <label>图片上传</label>
        <input id="picurl1"  name="picurl1" type="hidden"  value="<?php echo $info['picurl1'];?>" />
        <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上 传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 420*420</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val1" src="<?php echo $HTTP_PATH.$info['picurl1'];?>" width="420px" height="420px" alt="" /></p>
    <p>
        <label>图片上传</label>
        <input id="picurl2"  name="picurl2" type="hidden" value="<?php echo $info['picurl2'];?>"/>
        <input id="upload_file2" class="upfile_btn" type="file" name="upload_file2" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(2);" value="上 传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 300*420</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val2" src="<?php echo $HTTP_PATH.$info['picurl2'];?>" width="300px" height="420px" alt="" /></p>
    <p>
        <label>图片上传</label>
        <input id="picurl3"  name="picurl3" type="hidden" value="<?php echo $info['picurl3'];?>"/>
        <input id="upload_file3" class="upfile_btn" type="file" name="upload_file3" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(3);" value="上 传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 230*210</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val3" src="<?php echo $HTTP_PATH.$info['picurl3'];?>" width="240px" height="210px" alt="" /></p>
    <p>
        <label>图片上传</label>
        <input id="picurl4"  name="picurl4" type="hidden" value="<?php echo $info['picurl4'];?>"/>
        <input id="upload_file4" class="upfile_btn" type="file" name="upload_file4" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(4);" value="上 传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 230*210</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val4" src="<?php echo $HTTP_PATH.$info['picurl4'];?>" width="230px" height="210px" alt="" /></p>
    <p>
        <label></label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="保 存" style="display:block;margin:0 auto"/>
    </p>
</div>
</body>
</html>