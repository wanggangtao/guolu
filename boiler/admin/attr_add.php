<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/22
 * Time: 8:14
 */
require_once('admin_init.php');
require_once('admincheck.php');
$pid = safeCheck($_GET["pid"]);

if(!isset($_GET["pid"]))
    die();

$pattrinfo = Case_attr::getInfoById($pid);
if(empty($pattrinfo))
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

                if(name == ''){
                    layer.tips('名称不能为空', '#name');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        name  : name,
                        level : 3,
                        parentid    : <?php echo $pid; ?>

                    },
                    dataType :    'json',
                    url :         'userlist_do.php?act=addattr',
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

    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label>属性值</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" placeholder="" value=""/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>