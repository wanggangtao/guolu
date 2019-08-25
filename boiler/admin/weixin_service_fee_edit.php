<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 17:32
 */
require_once('admin_init.php');
require_once('admincheck.php');
$id=$_GET['id'];
$fee_info=Service_fee::getInfoById($id);
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
            $('#service_fee_edit').click(function(){
                var id=<?php echo $id;?>;
                var fee_number = $('input[name="fee_number"]').val();
                $.ajax({
                    dataType      :'json',
                    type          :'POST',
                    url           :'weixin_service_fee_do.php?act=edit',
                    data          :{
                        id: id,
                        fee_number : fee_number
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
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
            $('#service_fee_cancel').click(function() {
                parent.location.reload();
            });
        });
    </script>
</head>
<body>
<div id="formlist">
    <br><br><br>


    <p>
        <label style="margin-left: 10%">上门费用</label>
        <input type="text" class="text-input input-length-30" name="fee_number" id="fee_number"/><label style="width: 20px">元</label>
    </p>
    <br><br>
    <p>
        <label style="margin-left: 10%">　　</label>
        <input type="submit" id="service_fee_edit" class="btn-handle" value="修   改" >
        <input type="button" id="service_fee_cancel" class="btn-grey btn-handle" value="取   消" >
    </p>
</div>
</body>
</html>
