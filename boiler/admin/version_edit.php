<?php
/**
 * 修改用户  version_edit.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$versioninfo = version::getInfoById($id);
if(empty($versioninfo)){
    echo "非法操作！";
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
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){
                var name = $('#name').val();
                var app_type = $('#app_type').val();
                var desc = $('#desc').val();
                var url = $('#headimg1').val();
                var isforce = $('#isforce').val();
                if(name == ''){
                    layer.tips('版本号不能为空', '#name');
                    return false;
                }
                if(desc == ''){
                    layer.tips('版本描述不能为空', '#desc');
                    return false;
                }
                if(app_type == '' || app_type == 0){
                    layer.tips('设备类型不能为空', '#app_type');
                    return false;
                }
                if(isforce == '' || isforce == -1){
                    layer.tips('是否强制不能为空', '#isforce');
                    return false;
                }
                if(url == ''){
                    layer.tips('文件不能为空', '#url');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id     : <?php echo $id;?>,
                        name   : name,
                        desc   : desc,
                        app_type  : app_type,
                        url   : url,
                        isforce      : isforce
                    },
                    dataType :    'json',
                    url :         'version_do.php?act=edit',
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
            var uploadUrl = 'all_upload.php?type=appfile';//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#headimg'+ value).val(msg);
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
        <label>版本号</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $versioninfo['name'];?>"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>版本描述</label>
        <input type="text" class="text-input input-length-50" name="desc" id="desc" value="<?php echo $versioninfo['desc'];?>"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>设备类型</label>
        <select class="select-option" id="app_type">
            <option value="0">----请选择----</option>
            <?php
            foreach ($ARRAY_app_type as $key => $val)
            {
                $selected = "";
                if($key == $versioninfo['app_type']) $selected = "selected='selected'";
                echo "<option value='{$key}' {$selected}>{$val}</option>";
            }
            ?>

        </select>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label>是否强制</label>
        <select class="select-option" id="isforce">
            <option value="-1">----请选择----</option>
            <?php
            foreach ($ARRAY_version_isforce as $key => $val)
            {
                $selected = "";
                if($key == $versioninfo['isforce']) $selected = "selected='selected'";
                echo "<option value='{$key}' {$selected}>{$val}</option>";
            }
            ?>

        </select>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>文件</label>
        <input id="headimg1"  name="headimg1" type="hidden"  value="<?php echo $versioninfo['url'];?>"/>
        <input id="file1" class="upfile_btn" type="file" name="file" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
        <span id="url" class="warn-inline">* </span>
    </p>
    <p id="val1" style="padding-left:150px;"><?php echo $versioninfo['url'];?></p>
    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>