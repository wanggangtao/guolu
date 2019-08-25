<?php
/**
 * 首页内容  content_indexcon.php
 *
 * @version       v0.01
 * @create time   2018/9/13
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$type = isset($_GET['type'])?safeCheck($_GET['type']):0;

$FLAG_TOPNAV	= "webcontent";
$FLAG_LEFTMENU  = "indexabout";

$titlename = '关于我们';

$rows      = Webcontent::getPageList(1, 1, 1, 3);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title><?php echo $titlename; ?> - 首页 - 前端内容管理 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){
        	var ckeditor = CKEDITOR.replace('content',{
                toolbar : 'Common',
                forcePasteAsPlainText : 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl : 'ckeditor_upload.php?type=img',
                filebrowserFlashUploadUrl : 'ckeditor_upload.php?type=flash',
                filebrowserUploadUrl : 'ckeditor_upload.php?type=file'
            });
            
            $("#btn_submit_edit").click(function(){
                var id = '<?php echo $rows[0]['id']; ?>';
                var content = ckeditor.getData();

                if(content == ''){
                    layer.alert('内容不能为空', {icon: 0});
                    return false;
                }
                $.ajax({
                    type : 'post',
                    data : {
                        id : id,
                        content : content
                    },
                    dataType : 'json',
                    url  : 'content_do.php?act=content_edit_about',
                    success : function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        if(code > 0){
                            layer.alert(msg, {icon: 6,shade: false}, function(index){
                                location.reload();
                            });
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
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('content_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="content_indexpic.php">前端内容管理</a> &gt; 首页 &gt; <?php echo $titlename; ?></div>
        <div id="formlist">
            <p>
                <label>内容</label>
            	<div style="margin-left:10%">
                	<textarea style="padding:5px;width:70%;height:70px;" name="content" id="content" value=""><?php echo $rows[0]['content']; ?></textarea>
                </div>
            </p>
            <p>
                <label>&nbsp;</label>
                <input type="button" id="btn_submit_edit" class="btn_submit" style="margin-left:30px;" value="提　交" />
            </p>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>