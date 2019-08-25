<?php
/**
 * 售后详情页面  after_sale_detail.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/27
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');
$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;


$casetpl = Table_case_tpl::getInfoById($id);
$tplid=$casetpl[0]['id'];

$con_content = Table_case_tplcontent::getInfoByAttridAndTplid(16,$tplid);
$inl_content = Table_case_tplcontent::getInfoByAttridAndTplid(17,$tplid);
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
                //编辑器初始化
                var ckeditor2 = CKEDITOR.replace('intelligence', {
                    toolbar: 'Common',
                    forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                    filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                    filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
                });


        });
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>售后名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $casetpl[0]['name'];?>"  readonly/>
    </p>
    <p>
        <label style="width: 140px;">售后介绍</label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="content" value="" readonly><?php echo HTMLDecode($con_content[0]['content']?$con_content[0]['content']:'');?></textarea>
    </div>
    </p>
    <p>
        <label style="width: 140px;">售后资质</label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="intelligence" value="" readonly><?php echo HTMLDecode($inl_content[0]['content']?$inl_content[0]['content']:'');?></textarea>
    </div>
    </p>

</div>
</body>
</html>