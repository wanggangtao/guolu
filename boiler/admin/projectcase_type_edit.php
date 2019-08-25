<?php
/**
 * 修改用户类型  common_edit.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/27
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
//$name="";
//$english_name="";
//$order="";
if($id != 0)
{
    $usertype=Projectcase_type::getInfoById($id);
//    print_r($usertype);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){

                var name = $('input[name="name"]').val();
                var eng_name = $('input[name="english_name"]').val();
                var order = $('input[name="order"]').val();

                if(name == ''){
                    layer.tips('名称不能为空', '#s_name');
                    return false;
                }
                if(eng_name == ''){
                    layer.tips('英文名不能为空', '#s_english_name');
                    return false;
                }
                if(order == ''){
                    layer.tips('序号不能为空', '#s_order');
                    return false;
                }
                if(order < 0 || order == 0){
                    layer.tips('请输入正确的序号', '#s_order');
                    return false;
                }


                $.ajax({
                    type        : 'POST',
                    data        : {
                        name  : name,
                        eng_name  : eng_name,
                        order  : order,
                        id :<?php echo $id?>
                    },
                    dataType :    'json',
                    url :         'projectcase_type_do.php?act=edit',
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
        <label>名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $usertype[0]['name'];?>"  />
        <span class="warn-inline" id="s_name">* </span>
    </p>
    <p>
        <label>英文名称</label>
        <input type="text" class="text-input input-length-30" name="english_name" id="english_name" value="<?php echo $usertype[0]['english_name'];?>" />
        <span class="warn-inline" id="s_english_name">* </span>
    </p>
    <p>
        <label>排序</label>
        <input type="text" class="text-input input-length-30" name="order" id="order" value="<?php echo $usertype[0]['order'];?>" />
        <span class="warn-inline" id="s_order">* </span>
    </p>
    <p>
        <label >　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>