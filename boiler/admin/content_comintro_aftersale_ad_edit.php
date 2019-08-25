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
                var content=$("#content").val();
                //     alert(id);
                if(title == ''){
                    layer.tips('请填写标题', '#title');
                    return false;
                }
                if(title.length > 12){
                    layer.alert('标题过长', '#title');
                    return false;
                }
                if(content == ''){
                    layer.tips('请填写内容', '#content');
                    return false;
                }
                if(content.length > 50){
                    layer.alert('内容过长', '#content');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id  : id,
                        title: title,
                        content:content
                    },
                    dataType:     'json',
                    url :         'content_comintro_aftersale_do.php?act=ad_edit',

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
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label>标题：</label>
        <input type="text" class="text-input input-length-30" name="title" id="title" value="<?php echo $info['title'];?>"/>
        <span  style="text-align:center;color:red;">* 不得超过12个字</span>
    </p>

    <p>
        <label style="width: 140px;">内容：</label>
        <textarea style="padding:5px;width:60%;height:70px;" name="content" cols=200 id="content" ><?php echo $info['content'];?></textarea>
    </p>
    <p  style="text-align:center;color:red;">* 不得超过50个字</p>
    <p>
        <label></label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="保 存" style="display:block;margin:0 auto"/>
    </p>
</div>
</body>
</html>