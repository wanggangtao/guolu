<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 17:32
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
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#service_fee_add').click(function(){
                var fee_number = $('input[name="service_fee_number"]').val();

                if(fee_number == ''){
                    layer.msg('新上门费不能为空');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        fee_number       : fee_number
                    },
                    dataType    :    'json',
                    url         :         'weixin_service_fee_do.php?act=add',
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
            $('#service_fee_cancel').click(function () {
                parent.location.reload();
            });
        });

    </script>
</head>
<body>
<div id="formlist" style="margin-top: 10%;">
    <p>
        <label style="margin-left: 10%">上门费</label>
        <input type="text" class="text-input input-length-30" name="service_fee_number" id="service_fee_number"><label style="width: 20px">元</label>

    </p>
    <br>
    <br>
    <p>
        <label style="margin-left: 10%">　　</label>
        <input type="submit" id="service_fee_add" class="btn-handle" value="添   加" >
        <input type="submit" id="service_fee_cancel" class="btn-grey btn-handle" value="取   消" >
    </p>
</div>
</body>
</html>
