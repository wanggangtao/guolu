<?php
/**
 * Created by Lin.
 * Date: 2018/12/26
 * Time: 22:36
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = Web_intro_advantage::getInfoById($id);
if(empty($info)){
    echo '非法操作';
    die();
}
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

                var id = <?php echo $id;?>;
                var title= $("#title").val();
                var purl = $('#purl1').val();
                var content=$("#content").val();
             // alert(purl);
                if(title == ''){
                    layer.alert('请填写标题', {icon: 5});
                    return false;
                }
                if(title.length > 10){
                    layer.alert('标题过长', {icon: 5});
                    return false;
                }
                if(purl == ''){
                    layer.alert('请上传图片', {icon: 5});
                    return false;
                }
                if(content == ''){
                    layer.alert('请填写内容', {icon: 5});
                    return false;
                }
                if(content.length>80){
                    layer.alert('内容过长', {icon: 5});
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id  : id,
                        title: title,
                        content:content,
                        purl : purl
                    },
                    dataType:     'json',
                    url :         'content_comintro_distribution_do.php?act=ad_edit',

                    success :     function(data){
                        //      alert(data);
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6, shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error:     function()
                    {
                        layer.alert(msg, {icon: 5});
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
                            $('#purl'+ value).val(msg);
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
        <label>标题：</label>
        <input type="text" class="text-input input-length-30" name="title" id="title" value="<?php echo $info['title'];?>"/>
        <span class="warn-inline">* 不得超过10个字</span>
    </p>

    <p>
        <label>图片上传</label>
        <input id="purl1"  name="purl1" type="hidden"  value="<?php echo $info['purl'];?>" />
        <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
        <span class="warn-inline">*</span>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
    </p>
    <p  style="text-align:center;color:red;">图片大小：135px*135px</p>
    <p style="padding-left:150px;"><img id="val1" src="<?php echo $HTTP_PATH.$info['purl'];?>" width="135px" height="135px" alt="" /></p>
    <p>
        <label style="width: 140px;">内容：</label>
        <textarea style="padding:5px;width:60%;height:70px;" name="content" cols=200 id="content" ><?php echo $info['content'];?></textarea>

    </p>
    <p  style="text-align:center;color:red;">* 不得超过80个字</p>
    <p>
        <label></label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="保 存" style="display:block;margin:0 auto"/>
    </p>
</div>
</body>
</html>