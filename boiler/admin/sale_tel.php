<?php/** * Created by chen * Date: 2019/08/13 * Time: 22:36 */require_once('admin_init.php');require_once('admincheck.php');$info = afterservice::getInfoById(2);if(empty($info)){    echo '非法操作';    die();}$tel = explode(',',$info['content']);?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />    <link rel="stylesheet" href="css/style.css" type="text/css" />    <link rel="stylesheet" href="css/form.css" type="text/css" />    <link href="css/semantic.css" rel="stylesheet" type="text/css">    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>    <script type="text/javascript" src="js/layer/layer.js"></script>    <script type="text/javascript" src="js/common.js"></script>    <script type="text/javascript" src="js/upload.js"></script>    <script type="text/javascript">        $(function(){            $('#btn_submit').click(function(){                var content =$('#content').val();                var tel1 =$('#tel1').val();                var tel2 =$('#tel2').val();                if(content == ''){                    layer.msg('内容简介不能为空', {icon:5});                    return false;                }                /*if(content_length.length> 140){                    layer.msg('内容过长', {icon:5});                    return false;                }*/                $.ajax({                    type        : 'POST',                    data        : {                        tel1 : tel1,                        tel2:tel2,                        content : content,                    },                    dataType :    'json',                    url :         'sale_do.php?act=edit_tel',                    success :     function(data){                        var code = data.code;                        var msg  = data.msg;                        switch(code){                            case 1:                                layer.alert(msg, {icon: 6,shade: false}, function(index){                                    parent.location.reload();                                });                                break;                            default:                                layer.alert(msg, {icon: 5});                        }                    }                });            });        });    </script></head><body><div id="formlist">    <p>        <label>内容简介</label>        <input type="text" class="text-input input-length-100" id="content" value="<?php echo $info['title'];?>" placeholder="建议不超过30字"/>    </p>    <p>        <label>电话1：</label>        <input type="text" class="text-input input-length-30"  id="tel1" value="<?php echo $tel[0];?>"/>    </p>    <p>        <label>电话2：</label>        <input type="text" class="text-input input-length-30" id="tel2" value="<?php echo $tel[1];?>"/>    </p>    <p>        <label></label>        <input type="submit" id="btn_submit" class="btn_submit" value="保　存" />    </p></div></body></html>