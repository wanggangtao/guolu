<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 16:53
 */
require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$casetpl = Table_case_tpl::getInfoById($id);

//规格型号对应的attrid=31
$tplcontent = Table_case_tplcontent::getInfoByAttridAndTplid(31,$id);
//print_r($tplcontent);
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


        });
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>属性名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $casetpl[0]['name'];?>"  readonly/>
    </p>
    <p>
        <label style="width: 140px;">规格参数</label>
    <div style="margin-top:40px;margin-left:10%">
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="content" value="" readonly><?php echo HTMLDecode($tplcontent?$tplcontent[0]['content']:'');?></textarea>
    </div>
    </p>

</div>
</body>
</html>