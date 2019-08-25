<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 13:36
 */
require_once('admin_init.php');
require_once('admincheck.php');
$id=$_GET['id'];
$parts_info=Repair_parts::getInfoById($id);
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
    <link rel="stylesheet" href="css/results.css">
    <script type="text/javascript">
        $(function(){
            $('#parts_sumit').click(function(){
                var id=<?php echo $id;?>;
                var part_num = $('input[name="part_num"]').val();
                var parts_unit_price = $('input[name="parts_unit_price"]').val();
                if(part_num == ''){
                    layer.msg('零件名称不能为空');
                    return false;
                }
                if(parts_unit_price == ''){
                    layer.msg('零件数量不能为空');
                    return false;
                }
                $.ajax({
                    dataType       :'json',
                    type           :'POST',
                    url            :'weixin_repair_parts_do.php?act=edit',
                    data           :{
                        id                 : id,
                        part_num           : part_num,
                        parts_unit_price   : parts_unit_price
                    },
                    success: function (data) {
                        var code=data.code;
                        var msg=data.msg;
                        switch (code){
                            case 1:
                                layer.alert(msg, {icon: 6}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
            $('#parts_cancel').click(function() {
                parent.location.reload();
            });
        });
    </script>
</head>
<body>
    <div id="formlist">
        <p>
            <label style="margin-left: 29%">配件名称：<?php echo $parts_info['name']?></label>
        </p>
        <p>
            <label style="margin-left: 20%">配件数量</label>
            <input type="text" class="text-input input-length-10" name="part_num" id="part_num"/><label style="width: 20px">件</label>
        </p>
        <p>
            <label style="margin-left: 20%">配件单价</label>
            <input type="text" class="text-input input-length-10" name="parts_unit_price" id="parts_unit_price"/><label style="width: 20px">元</label>
        </p>
        <p>
            <label style="margin-left: 10%">　　</label>
            <input type="submit" id="parts_sumit" class="btn-handle" value="添   加" />
            <input type="button" id="parts_cancel" class="btn-grey btn-handle" value="取   消" />
        </p>
    </div>
</body>
</html>
