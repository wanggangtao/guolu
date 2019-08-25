<?php
/**
 * 企业修改页面 company_edit.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/27
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
error_reporting(E_ALL ^ E_NOTICE);
require_once('admin_init.php');
require_once('admincheck.php');
$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;

$tplattr = '';
$con_content = '';
$inl_content = '';
$pic_content = '';
if($id != 0 ){
    $tplattr = Table_case_tpl::getInfoById($id);
    $tplid= $tplattr[0]['id'];
    $con_content = Table_case_tplcontent::getInfoByAttridAndTplid(8,$tplid);
    $inl_content = Table_case_tplcontent::getInfoByAttridAndTplid(9,$tplid);
    $pic_content = Table_case_tplcontent::getInfoByAttridAndTplid(10,$tplid);
    //先读取case_attr表中的inserid，然后作为arrtid插入case_tplcontent表中
    //case_attr表中，“企业简介”id=8，“企业资质”id=9，“企业logo”id=10
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
    <script type="text/javascript" src="js/upload.js"></script>
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
            //文件上传
            $('.fileinput').on('change', 'input[type=file]', function () {
                $('.fileloading').html(' 上传中...');
                ajaxFileUpload();
                return false;
            });

            function ajaxFileUpload() {
                var uploadUrl = 'company_photoupload.php';//处理文件

                $.ajaxFileUpload({
                    url: uploadUrl,
                    fileElementId: 'logo',
                    dataType: 'json',
                    success: function (data, sex) {
                        var code = data.code;
                        var msg = data.msg;

                        $('#logo').val('');
                        $('.fileloading').html('');

                        switch (code) {
                            case 1:
                                $('input[name=logo]').val(msg);
                                $('#picpreview').html('<img src="<?php echo $HTTP_PATH?>' + msg + '" width="100"/>');
                                layer.msg('上传成功');
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error: function (data, sex, e) {
                        layer.alert(e);
                    }
                });
                return false;
            }

            $('#btn_submit_edit').click(function () {

                var name = $('input[name="name"]').val();
                var content     = ckeditor.getData();
                var intelligence     = ckeditor2.getData();
                var logo = $('input[name="logo"]').val();
                if (name == '') {
                    layer.tips('企业名称不能为空', '#s_name');
                    return false;
                }
                if (content == '') {
                    layer.tips('企业简介不能为空', '#s_content');
                    return false;
                }
                if (intelligence == '') {
                    layer.tips('企业资质不能为空', '#s_intelligence');
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    data: {
                        name: name,
                        content: content,
                        intelligence:intelligence,
                        logo:logo,
                        id : <?php echo $id?>
                    },
                    dataType: 'json',
                    url: 'company_do.php?act=edit',
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
        <label>企业名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $tplattr[0]['name'];?>"/>
        <span class="warn-inline" id="s_name">* </span>
    </p>

    <p>
        <label style="width: 140px;">企业介绍</label>
    <div style="margin-top:40px;margin-left:10%">
        <span class="warn-inline" >*横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="content" value=""><?php echo HTMLDecode($con_content[0]['content']?$con_content[0]['content']:'');?></textarea>
        <span class="warn-inline" id="s_content">* </span>
    </div>
    </p>
    <p>
        <label style="width: 140px;">企业资质</label>
    <div style="margin-top:40px;margin-left:10%">
        <span class="warn-inline" >*横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
        <textarea style="padding:5px;width:70%;height:70px;" name="intelligence" cols=200 id="intelligence" value=""><?php echo HTMLDecode($inl_content[0]['content']?$inl_content[0]['content']:'');?></textarea>
        <span class="warn-inline" id="s_intelligence">* </span>
    </div>
    </p>
    <p>
        <label>企业logo</label>
        <input name="logo" type="text" readonly="readonly" class="text-input input-length-30 readonlyinput" value=""/>
        <span class="fileinput">更换图片
                            <input type="file" name="logo" id="logo"/>
                        </span>
        <span class="fileloading"></span>
        <span class="warn-block" id="picpreview"></span>
    <p style="padding-left:150px;"><img id="val1" src="<?php echo $HTTP_PATH.$pic_content[0]['content'];?>"  /> </p>
    </p>
    <p>
        <label>&nbsp;</label>
        <input type="button" id="btn_submit_edit" class="btn_submit" style="margin-left:30px;" value="提　交" />
    </p>




</div>
</body>
</html>