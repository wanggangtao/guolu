<?php
/**
/**
 * 售后服务 - 我们的服务（添加）
 *
 * @create time   2019.08.11
 * @update time
 * @author        lp
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
<!--    <link href="css/semantic.css" rel="stylesheet" type="text/css">-->
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">

        $(function(){
            $('#btn_submit').click(function () {

                var title =$('#title').val();
                var content =$('#content').val();
                var picture = $('#file_url').val();

                if (title == '') {
                    layer.tips('标题不能为空', '#title');
                    return false;
                }
                if(title.length > 10){
                    layer.alert('标题过长', {icon: 5});
                    return false;
                }
                if (content == '') {
                    layer.tips('内容不能为空', '#content');
                    return false;
                }
                if(content.length> 75){
                    layer.alert('内容过长', {icon: 5});
                    return false;
                }
                if(picture == ''){
                    layer.alert('请上传图片', {icon: 5});
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    data: {
                        title: title,
                        content: content,
                        picture: picture
                    },
                    dataType: 'json',
                    url: 'sale_do.php?act=add_ourservice',
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

            $('#quit').click(function(){

                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);

            });

            function  ajaxFile() {
                var HTTP_PATH = "<?php echo $HTTP_PATH?>";
                var index = layer.msg("正在上传请稍等....",{time:1000000});
                var uploadUrl = 'sale_do.php?act=upload';//处理上传的图片
                $.ajaxFileUpload({
                    url           : uploadUrl,
                    fileElementId : 'file',
                    dataType      : 'json',
                    success       : function(data, status){
                        var code = data.code;
                        var msg  = data.msg;
                        layer.close(index);
                        switch(code){
                            case 1:
                                $("#ourservice").attr("src",HTTP_PATH+msg);
                                $("#file_url").val(msg);
                                break;
                            default:
                                layer.alert(msg, {icon: 5,closeBtn: 0});
                        }
                    },
                    error: function (data, status, e){
                        layer.alert(e);
                    }
                });

                $('input[type="file"]').change(function(e) {
                    ajaxFile(this);
                });
            };

            $('input[type="file"]').change(function(e) {
                ajaxFile(this);
            });

        });

    </script>

</head>
<body>

<div id="formlist">

    <p>
        <label>标题</label>
        <input placeholder="*标题不超过10个字" type="text" class="text-input input-length-30" name="title" id="title" value=""  />
<!--        <span class="warn-inline" id="s_name">*不得超过10个字 </span>-->
    </p>
    <p>
        <label style="width: 140px;">内容</label>
        <div style="margin-top:40px;margin-left:10%;margin-right:10%;display: contents;">
        <textarea placeholder="*内容不超过75个字" style="padding:5px;width:31%;height:70px;" name="content" cols=200 id="content" ></textarea>
<!--        <span style="text-align:center;color:red;">* 不得超过75个字</span>-->
        </div>
    </p>

    <p>
        <label>图片上传</label>
        <input id="file_url"  name="file_url" type="hidden"  value="<?php echo $info['picture'];?>" />
        <input id="file" class="upfile_btn" type="file" name="file" style="height:24px;"/>
    </p>
    <p  style="padding-left:150px;color:darkgray;">提示：建议上传图片尺寸52px*52px</p>
    <p style="padding-left:150px;"><img id="ourservice" src="<?php echo $HTTP_PATH.$info['picture'];?>" width="52px" height="52px" alt="" /></p>

    <p style="position: absolute; bottom:120px;">
        <label>&nbsp;</label>
        <input id="quit" type="submit" style="margin-right: -60px;margin-left: 25px;background-color: silver" class="btn_submit" value="取 消" style="display:block;margin-left: 90px;" />
        <input type="submit" style="margin-left: 108px;background-color: #f89406;margin-left: 90px"id="btn_submit" class="btn_submit" value="确 定" style="display:block;margin-left: 90px"/>
    </p>
    
</div>
</body>
</html>