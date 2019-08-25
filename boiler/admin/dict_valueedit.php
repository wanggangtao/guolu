<?php
/**
 * 添加属性  dict_valueedit.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
$id = safeCheck($_GET["id"]);
if(!isset($_GET['id']))
	die();
$dictinfo = Dict::getInfoById($id);
if(empty($dictinfo))
	die();

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
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){

                var name = $('#name').val();
                var logo = $('#logo1').val();
                var cat = 0;
                var vals = [];
                $('input:checkbox:checked').each(function (index, item) {
                    vals.push($(this).val());
                });
                if(vals.length === $('input:checkbox').length){
                    cat = 10;
                }else {
                    cat = vals[0];
                }

                var id = <?php echo $id; ?>;

                if(id === 1){
                    if(cat===0){
                        layer.tips('没有选择类别', '#cat');
                        return false;
                    }
                }

                if(name == ''){
                    layer.tips('名称不能为空', '#name');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        name  : name,
                        id    : id,
                        pic  : logo,
                        cat : cat
                    },
                    dataType :    'json',
                    url :         'dict_do.php?act=editvalue',
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
        });

        function ajaxUpload(value){
            if($('#file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=dictlogo';//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#logo'+ value).val(msg);
                            $('#val'+ value).html(msg);
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
        <label>属性值 </label>
        <input type="text" class="text-input input-length-30" name="name" id="name" placeholder="" value="<?php echo $dictinfo['name']; ?>"/>
        <span class="warn-inline">* </span>
    </p>
    <p style="<?php echo $dictinfo['parent'] == 1?"display: block;":"display: none;"; ?>">
        <label>厂家logo</label>
        <input id="logo1"  name="logo1" type="hidden" value="<?php echo $dictinfo['pic']; ?> "/>
        <input id="file1" class="upfile_btn" type="file" name="file" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
    </p>
    <p style="padding-left:150px;font-size:14px;<?php echo $dictinfo['parent'] == 1?"display: block;":"display: none;"; ?>" id="val1"><?php echo $dictinfo['pic']; ?></p>

    <p style="<?php echo $dictinfo['parent'] == 1?"display: block;":"display: none;"; ?>">
        <label>类别</label>
        <?php  $cat = $dictinfo['cat'];?>
        <input type="checkbox" id="cat" value="1" <?php if($cat == 1 || $cat == 10) echo 'checked';?> />工业锅炉
        <input type="checkbox"  value="2" <?php if($cat == 2 || $cat == 10) echo 'checked';?> />壁挂锅炉
    </p>

    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>