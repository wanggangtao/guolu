<?php
/**
 * Created by lp
 * Date: 2019/08/13
 * Time: 22:36
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = afterservice::getInfoById($id);
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
            $('#btn_submit').click(function(){

                var title =$('#title').val();
                var picture =$('#file_url').val();

                if(title == ''){
                    layer.alert('标题不能为空', {icon:5});
                    return false;
                }
                if(title.length > 20){
                    layer.msg('标题过长', {icon:5});
                    return false;
                }
                if(picture == ''){
                    layer.alert('请上传图片', {icon: 5});
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id   : '<?php echo $id;?>',
                        title : title,
                        picture : picture
                    },
                    dataType :    'json',
                    url :         'sale_do.php?act=edit_sketch',
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

            $('#quit').click(function(){

                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);

            });

            function  ajaxFile() {
                var HTTP_PATH = "<?php echo $HTTP_PATH?>";
                var index = layer.msg("正在上传请稍等....",{time:1000000});
                var uploadUrl = 'sale_do.php?act=upload';//处理上传的图片
                $.ajaxFileUpload({
                    url           : uploadUrl,
                    fileElementId : 'file',
                    dataType      : 'json',
                    success       : function(data, status){
                        var code = data.code;
                        var msg  = data.msg;
                        layer.close(index);
                        switch(code){
                            case 1:
                                $("#sketch").attr("src",HTTP_PATH+msg);
                                $("#file_url").val(msg);
                                break;
                            default:
                                layer.alert(msg, {icon: 5,closeBtn: 0});
                        }
                    },
                    error: function (data, status, e){
                        layer.alert(e);
                    }
                });

                $('input[type="file"]').change(function(e) {
                    ajaxFile(this);
                });

            };

            $('input[type="file"]').change(function(e) {
                ajaxFile(this);
            });

        });

    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label>标题</label>
        <input placeholder="*建议不超过20个字" type="text" class="text-input input-length-30" name="title" id="title" value="<?php echo $info['title'];?>"/>
<!--        <span style="text-align:center;color:red;">*</span>-->
    </p>

    <p>
        <label>图片上传</label>
        <input id="file_url"  name="file_url" type="hidden"  value="<?php echo $info['picture'];?>" />
        <input id="file" class="upfile_btn" type="file" name="file" style="height:24px;"/>
    </p>
    <p  style="padding-left:150px;color:darkgray;">提示：建议上传图片尺寸360px*240px</p>
    <p style="padding-left:150px;"><img id="sketch" src="<?php echo $HTTP_PATH.$info['picture'];?>" width="360px" height="240px" alt="" /></p>

    <p>
        <label></label>
        <input id="quit" type="submit" style="margin-right: -60px;margin-left: 25px;background-color: silver" class="btn_submit" value="取 消" style="display:block;margin-left: 90px;" />
        <input type="submit" style="margin-left: 108px;background-color: #f89406;margin-left: 90px"id="btn_submit" class="btn_submit" value="确 定" style="display:block;margin-left: 90px"/>
    </p>
</div>
</body>
</html>
