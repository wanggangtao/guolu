<?php
/**
 * 售后修改页面  after_sale_edit.php
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

$tplattr = '';
$con_content = '';
$inl_content = '';

if($id != 0 ){
    $tplattr = Table_case_tpl::getInfoById($id);
    $tplid= $tplattr[0]['id'];

    $con_content = Table_case_tplcontent::getInfoByAttridAndTplid(16,$tplid);
    $inl_content = Table_case_tplcontent::getInfoByAttridAndTplid(17,$tplid);
    //先读取case_attr表中的inserid，然后作为arrtid插入case_tplcontent表中
    //case_attr表中，“售后简介”id=16，“售后资质”id=17
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
            var ckeditor2 = CKEDITOR.replace('intelligence', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });

            $('#btn_submit_edit').click(function () {

                var name = $('input[name="name"]').val();
                var content     = ckeditor.getData();
                var intelligence     = ckeditor2.getData();

                if (name == '') {
                    layer.tips('售后名称不能为空', '#s_name');
                    return false;
                }
                if (content == '') {
                    layer.tips('售后介绍不能为空', '#s_content');
                    return false;
                }
                if (intelligence == '') {
                    layer.tips('售后资质不能为空', '#s_intelligence');
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    data: {
                        name: name,
                        content: content,
                        intelligence:intelligence,
                        id : <?php echo $id?>
                    },
                    dataType: 'json',
                    url: 'after_sale_do.php?act=edit',
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
        <label>售后名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $tplattr[0]['name'];?>"  />
        <span class="warn-inline" id="s_name">* </span>
    </p>

    <p>
        <label style="width: 140px;">售后介绍</label>
    <div style="margin-top:40px;margin-left:10%">
        <span class="warn-inline" >*横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="content" value=""><?php echo $con_content[0]['content']//HTMLDecode($con_content[0]['content']?$con_content[0]['content']:'');?></textarea>
        <span class="warn-inline" id="s_content">* </span>
    </div>
    </p>
    <p>
        <label style="width: 140px;">售后资质</label>
    <div style="margin-top:40px;margin-left:10%">
        <br class="warn-inline" >*横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="intelligence" value=""><?php echo HTMLDecode($inl_content[0]['content']?$inl_content[0]['content']:'');?></textarea>
        <span class="warn-inline" id="s_intelligence">* </span>
    </div>
    </p>

    <p>
        <label>&nbsp;</label>
        <input type="button" id="btn_submit_edit" class="btn_submit" style="margin-left:30px;" value="提　交" />
    </p>




</div>
</body>
</html>