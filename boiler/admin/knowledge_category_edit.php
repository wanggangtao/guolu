<?php
/**
 * 添加管理员  admin_add.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/27
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');


$id = safeCheck($_GET["id"]);

$categoryInfo = knowledge_category::getInfoById($id);

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
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){

                var name = $('input[name="name"]').val();

                if(name=="")
                {
                    layer.tips('名字不能为空', '#s_name');
                    return false;
                }


                $.ajax({
                    type        : 'POST',
                    data        : {
                        id:<?php echo $id?>,
                        name  : name,
                    },
                    dataType :    'json',
                    url :         'knowledge_category_do.php?act=edit',
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
        <label>分类名字</label>
        <input type="text" class="text-input input-length-30" name="name" id="name"  value="<?php echo $categoryInfo["name"]?>"/>
        <span class="warn-inline" id="s_name">* </span>
    </p>


    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>