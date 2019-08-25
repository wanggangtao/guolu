<?php
/**
 * 工厂修改页面 factory_edit.php
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

$tplattr = "";
$con_content = "";
$inl_content = "";
$ven_content="";
$vender="";
if($id != 0 ){
    $tplattr = Table_case_tpl::getInfoById($id);
    $tplid= $tplattr[0]['id'];

    $ven_content = Table_case_tplcontent::getInfoByAttridAndTplid(1,$tplid);
    if($ven_content) {
        $vender = Table_dict::getInfoById($ven_content[0]['content']);
    }

    $con_content = Table_case_tplcontent::getInfoByAttridAndTplid(11,$tplid);
    $inl_content = Table_case_tplcontent::getInfoByAttridAndTplid(12,$tplid);
    //先读取case_attr表中的inserid，然后作为arrtid插入case_tplcontent表中
    //case_attr表中，“工厂简介”id=11，“工厂资质”id=12
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
                var vender=$('#vender').val();
                var content     = ckeditor.getData();
                var intelligence     = ckeditor2.getData();

                if (name == '') {
                    layer.tips('工厂名称不能为空', '#s_name');
                    return false;
                }
                if(vender == ''){
                    layer.tips('请选择锅炉厂家', '#s_vender');
                    $("#vender").focus();
                    return false;
                }
                if (content == '') {
                    layer.tips('工厂介绍不能为空', '#s_content');
                    return false;
                }
                if (intelligence == '') {
                    layer.tips('工厂资质不能为空', '#s_intelligence');
                    return false;
                }
                // alert();
                // return false;
                $.ajax({
                    type: 'POST',
                    data: {
                        name: name,
                        vender:vender,
                        content: content,
                        intelligence:intelligence,
                        id : <?php echo $id?>
                    },
                    dataType: 'json',
                    url: 'factory_do.php?act=edit',
                    success: function (data) {
                        // alert(data);
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
        <label>工厂名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $tplattr[0]['name'];?>"  />
        <span class="warn-inline" id="s_name">* </span>
    </p>
    <p>
        <label>厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByParentid(1);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $vender['id'])
                        $isselect = 'selected';
                    echo $isselect;
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
    <p>
    <p>
        <label style="width: 140px;">工厂介绍</label>
    <div style="margin-top:40px;margin-left:10%">
        <span class="warn-inline" >横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
        <textarea style="padding:5px;width:70%;height:70px;" name="content" cols=200 id="content" value=""><?php echo HTMLDecode($con_content?$con_content[0]['content']:'');?></textarea>
        <span class="warn-inline" id="s_content">* </span>
    </div>
    </p>
    <p>
        <label style="width: 140px;">工厂资质</label>
    <div style="margin-top:40px;margin-left:10%">
        <span class="warn-inline" >*横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
        <textarea style="padding:5px;width:70%;height:70px;" name="intelligence" cols=200 id="intelligence" value=""><?php echo HTMLDecode($inl_content?$inl_content[0]['content']:'');?></textarea>
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