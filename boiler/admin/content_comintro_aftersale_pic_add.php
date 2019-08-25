<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/12/28 0028
 * Time: 下午 3:28
 */

require_once('admin_init.php');
require_once('admincheck.php');

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

        function ajaxUpload(value){
            if($('#upload_file'+value).val() == ''){
                layer.tips('请选择文件', '#upload_file'+value, {tips: 3});
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
                    var http_path = '<?php echo $HTTP_PATH;?>';
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

        function selectfile(id){
            return $("#upload_file"+id).click();
        }

        function file_selected(id){
            if($('#upload_file'+id).val()){
                $('#btn_selectfile'+id).html($('#upload_file'+id).val());
            }else
                $('#btn_selectfile'+id).html('点击选择图片');
        }


        $(function() {
            $("#btn_sumit").click(function () {

                var picurl1 = $('#picurl1').val();
                var picurl2 = $('#picurl2').val();
                var picurl3 = $('#picurl3').val();
                var picurl4 = $('#picurl4').val();

                if(picurl1 == ''){
                    layer.alert('图片1不能为空', {icon: 5});
                    return false;
                }
                if(picurl2 == ''){
                    layer.alert('图片2不能为空', {icon: 5});
                    return false;
                }
                if(picurl3 == ''){
                    layer.alert('图片3不能为空', {icon: 5});
                    return false;
                }
                if(picurl4 == ''){
                    layer.alert('图片4不能为空', {icon: 5});
                    return false;
                }

                $.ajax({
                    type : 'post',
                    data : {
                        picurl1 : picurl1,
                        picurl2 : picurl2,
                        picurl3 : picurl3,
                        picurl4 : picurl4,
                    },
                    dataType : 'json',
                    url  : 'content_comintro_aftersale_do.php?act=add_pic',
                    success : function(data){
                        var code = data.code;
                        var msg  = data.msg;

                        if(code == 1){
                            layer.alert(msg, {icon: 6}, function(index){
                                parent.location.reload();
                            })
                        }else{
                            layer.alert(msg, {icon: 5});
                        }
                    }
                });
                return false;
            });
        });
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>图片上传</label>
        <input id="picurl1"  name="picurl1" type="hidden"/>
        <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上 传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 420*420</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val1" src="" width="420px" height="420px" alt="" /></p>

    <p>
        <label>图片上传</label>
        <input id="picurl2"  name="picurl2" type="hidden"/>
        <input id="upload_file2" class="upfile_btn" type="file" name="upload_file2" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(2);" value="上传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 300*420</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val2" src="" width="300px" height="420px" alt="" /></p>
    <p>
        <label>图片上传</label>
        <input id="picurl3"  name="picurl3" type="hidden"/>
        <input id="upload_file3" class="upfile_btn" type="file" name="upload_file3" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(3);" value="上传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 230*210</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val3" src="" width="230px" height="210px" alt="" /></p>
    <p>
        <label>图片上传</label>
        <input id="picurl4"  name="picurl4" type="hidden"/>
        <input id="upload_file4" class="upfile_btn" type="file" name="upload_file4" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(4);" value="上传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小 230*210</p>
    <p style="padding-left:150px;">网页显示样式：<img id="val4" src="" width="230px" height="210px" alt="" /></p>
    <p>
        <label></label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="保 存" style="display:block;margin:0 auto"/>
    </p>
</div>
</body>
</html>