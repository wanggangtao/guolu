<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 10:59
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
            $('#parts_submit').click(function(){
                var parts_name = $('input[name="parts_name"]').val();
                var parts_unit_price = $('input[name="parts_unit_price"]').val();
                var parts_number = $('input[name="parts_number"]').val();

                if(parts_name == ''){
                    layer.msg('零件名称不能为空');
                    return false;
                }
                if(parts_number == ''){
                    layer.msg('零件数量不能为空');
                    return false;
                }

                if(parts_unit_price == ''){
                    layer.msg('零件单价不能为空');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        parts_name         : parts_name,
                        parts_number       : parts_number,
                        parts_unit_price   : parts_unit_price
                    },
                    dataType :    'json',
                    url :         'weixin_repair_parts_do.php?act=add',
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
            $('#parts_cacal').click(function () {
                parent.location.reload();
            });
        });
    </script>
</head>
<body>
    <div id="formlist">
        <p>
            <label style="margin-left: 10%">配件名称</label>
            <input type="text" class="text-input input-length-30" name="parts_name" id="parts_name" placeholder="配件名称不超过20个字"/>

        </p>
        <br>
        <p>
            <label style="margin-left: 10%">配件数量</label>
            <input type="text" class="text-input input-length-10" name="parts_number" id="parts_number"/><label style="width: 20px">件</label>

        </p>
        <br>
        <p>
            <label style="margin-left: 10%">配件金额</label>
            <input type="text" class="text-input input-length-10" name="parts_unit_price" id="parts_unit_price"/><label style="width: 20px">元</label>

        </p>
        <br>
        <p>
            <label style="margin-left: 10%">　　</label>
            <input type="submit" id="parts_submit" class="btn-handle" value="确   定" />
            <input type="button" id="parts_cancel" class="btn-grey btn-handle" value="取   消" />
        </p>
    </div>
</body>
</html>
