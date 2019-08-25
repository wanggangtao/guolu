<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 9:43
 */


require_once('admin_init.php');
require_once('admincheck.php');

$attrid = 13;
$contentattrid = 31;//规格参数的attrid=31
/*$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;

$caseattr = Table_case_attr::getInfoById($id);
$tplcontent = Table_case_tplcontent::getInfoByAttrid($id);*/
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
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function() {
            //编辑器初始化
            var ckeditor = CKEDITOR.replace('content', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });


            $('#btn_submit_add').click(function () {

                var name = $('input[name="name"]').val();
                var content     = ckeditor.getData();

                if (name == '') {
                    layer.tips('属性名称不能为空', '#s_name');
                    return false;
                }
                if (content == '') {
                    layer.tips('内容不能为空', '#s_content');
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    data: {
                        name: name,
                        content: content,
                        attrid: <?php echo $attrid?>,
                        contentattrid: <?php echo $contentattrid?>
                    },
                    dataType: 'json',
                    url: 'quote_do.php?act=add',
                    success: function (data) {
                        var code = data.code;
                        var msg = data.msg;
                        switch (code) {
                            case 1:
                                layer.alert(msg, {icon: 6, shade: false}, function (index) {
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
        })
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>属性名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value=""  />
        <span class="warn-inline" id="s_name">* </span>
    </p>

    <p>
        <label style="width: 140px;">规格参数</label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="content" value=""></textarea>
        <span class="warn-inline" id="s_content">* </span>
    </div>
    </p>

    <p>
        <label>&nbsp;</label>
        <input type="button" id="btn_submit_add" class="btn_submit" style="margin-left:30px;" value="提　交" />
    </p>




</div>
</body>
</html>