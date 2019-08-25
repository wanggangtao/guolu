<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/17
 * Time: 10:17
 */
require_once('admin_init.php');
require_once('admincheck.php');

if (isset($_GET['id'])){
    $id = safeCheck($_GET['id']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理 - 报修管理</title>
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
        <?php
            echo '
                <div id="formlist">
                <table>
                    <tbody>
                        <tr>
                        <td>用户反馈:
                        
                        <select  id="user_feedback" class="select-handle">;
                            <option value="1">非常满意</option>
                            <option value="2">满意</option>
                            <option value="3">一般</option>
                            <option value="4">不满意</option>
                            <option value="5">非常不满意</option>
                         </select>
                         </td>
                         </tr>
                         <tr>
                         <td>反馈内容:
                         <textarea style="width: 60%;" id = "feedback" rows="8" cols="80"></textarea></td>
                         </tr>
                    </tbody>
                </table>
                <p>
                    <label>　　</label>
                    <input type="submit" id="btn_submit" class="btn-handle" value="添   加" />
                    <input type="button" id="btn_cancel" class="btn-handle" value="取   消" />
                </p>
                </div>
            ';
        ?>
    <script type="text/javascript">
        $(function(){
            $('#btn_submit').click(function(){
                var user_feedback = $("#user_feedback option:selected").val();
                var feedback = $('#feedback').val();

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id              : <?php echo $id;?>,
                        user_feedback   : user_feedback,
                        feedback        : feedback
                    },
                    dataType :    'json',
                    url :         'weixin_repair_do.php?act=verify',
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
            $('#btn_cancel').click(function() {
                parent.location.href = "javascript:history.go(-1)";
            });

        });
    </script>
</body>
</html>