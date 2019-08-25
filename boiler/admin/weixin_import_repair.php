<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/16
 * Time: 10:56
 */

require_once('admin_init.php');
require_once('admincheck.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>弹窗类</title>

    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="../static/js/layer/layer.js"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>

    <script type="text/javascript">
        $(document).keydown(function (e) {
            {
                var e = e || event,
                    keycode = e.which || e.keyCode;
                if (keycode == 13) {
                    if ($(".layui-layer-btn0").length > 0) {
                        $(".layui-layer-btn0")[$(".layui-layer-btn0").length-1].click();
                        return false;
                    }
                    else {
                        $("#btn_sumit").trigger("click");
                        return false;
                    }

                } else if (keycode == 27) {
                    if ($(".layui-layer-btn0").length > 0) {
                        layer.closeAll();
                        return false;
                    }
                }
            }
        });

        $(function(){

            $("#btn_sumit").click(function () {
                var index = layer.load(2, {shade: false});
                var formData = new FormData(user_info);

                $.ajax({
                    type: "POST",
                    url: "weixin_import_repair_do.php",  //同目录下的php文件
                    data: formData,
                    dataType: "json", //声明成功使用json数据类型回调
                    //如果传递的是FormData数据类型，那么下来的三个参数是必须的，否则会报错
                    cache: false,  //默认是true，但是一般不做缓存
                    processData: false, //用于对data参数进行序列化处理，这里必须false；如果是true，就会将FormData转换为String类型
                    contentType: false,  //一些文件上传http协议的关系，自行百度，如果上传的有文件，那么只能设置为false

                    success: function (data) {  //请求成功后的回调函数
//                            alert(data);
                        var code = data.code;
                        var msg = data.msg;
                        switch (code) {
                            case 1:
                                layer.alert(msg, {icon: 6}, function (index) {
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5}, function (index) {
                                    location.reload();
                                });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
//                                alert(errorThrown);
                        layer.alert('请重试', {icon: 5}, function (index) {
                            parent.location.reload();
                        });
                    }

                });

                return true; // 在这里加上原来的代码
            });
        })
    </script>
</head>

<body>
<br><br>
<div id="layerMain" align="center">
    <div class="formBox">
        <div class="form-group">
            <form enctype="multipart/form-data" id="user_info">
                <input type="hidden" id="level" name="level" value="1">

                <div class="control-counten">
                    <label for="inputEmail3" class="control-label">选择导入文件：</label>
                    <input type="file" class="form-control width200" name="file" id="file">
                </div>
                <br><br>
                <div>
                    提示：1、请先下载demo,在提供的Excel表格中输入需要的信息。2、上传Excel文件。
                </div>
                <br><br>
                <div>
                    <a href="<?php echo $HTTP_PATH."/admin/demo/保修单批量导入demo.xlsx"?>">demo下载</a>
                </div>
                <br><br>
            </form>
        </div>


        <div class="form-group" align="center">
            <div class="control-counten">
                <button type="submit" class="btn-handle" id="btn_sumit">确定</button>
            </div>
        </div>

    </div>
</div>
</body>

</html>