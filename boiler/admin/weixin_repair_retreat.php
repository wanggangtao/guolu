<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/16
 * Time: 16:29
 */
require_once('admin_init.php');
require_once('admincheck.php');
$all_usr = repair_person::getList();
if (isset($_GET['id'])){
    $id = safeCheck($_GET['id']);
}
$service = repair_order::getInfoById($id);
$service_person = $service['person'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理 - 预约管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</head>
<body>
    <?php echo '
        <div id="formlist" style="margin-top: 10%">
        <p>
            <label>服务人员:</label>
            <select  id="service_person1" class="select-handle">
            <option value=""></option>';
                if (!empty($all_usr)){
                    foreach ($all_usr as $row){
                        if ($row['id'] != $service_person){
                            echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
                        }
                    }
                }
                echo '
                </select>
        </p>
            
            <p>
            <label>联系电话：</label>
             <input type="text" name="service_phone1" id="service_phone1"/>
            </p>
            
            <p>
            <label>　　</label>
            <input type="submit" id="btn_retreat" class="btn-handle" value="改派" />
            <input type="submit" id="btn_cancel" class="btn-handle btn-grey" value="取消" />
            
        </p>
        </div>
            ';
    ?>
    <script type="text/javascript">
        $(function(){
            $('#service_person1').change(function(){
               var service_person1 = $("#service_person1 option:selected").val();
               if(service_person1!=""){
                    $.ajax({
                        type:'post',
                        url: 'weixin_repair_do.php?act=person_info',
                        data:{
                            service_person1 :　service_person1
                        },
                        dataType:'json',
                        success:function (data) {

                            var code = data.code;
                            var msg = data.msg;

                            console.log(msg['repair_phone']);
                            switch (code) {
                                case 1:
                                    if (msg != "") {
                                        $("#service_phone1").val(msg['0'].repair_phone);
                                    }
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    });
               }
            });

            $('#btn_retreat').click(function(){
                var service_person = $("#service_person1").find("option:selected").text();
                var service_phone = $('input[name="service_phone1"]').val();

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id               : <?php echo $id;?>,
                        service_person   : service_person,
                        service_phone    : service_phone
                    },
                    dataType :    'json',
                    url :         'weixin_repair_do.php?act=retreat',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.href='weixin_repair_treating.php';
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

            $('#btn_cancel').click(function(){
                parent.location.reload();
            });
            });

       
    </script>
</body>
</html>
