<?php
/**
 * 添加用户  user_add.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
$miyuInfo = Baseconfig::getInfoByKey(weixin_customer::BASECONFIG_KEY);

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
            $("#btn_sumit").click(function () {

                var name = $("#name").val();
                var id =<?php echo  $miyuInfo['id']?>;
                if(name == ''){
                    layer.alert('秘语不能为空', {icon:5});

                    return false;
                }


                $.ajax({
                    type: 'post',
                    url: 'weixin_customer_do.php?act=miyu_edit',
                    data: {
                        name:name,
                        id:id
                    },
                    dataType: 'json',
                    success: function (data) {
//                        alert(data);
                        var code=data.code;
                        var msg=data.msg;
                        switch (code){
                            case 1:
                                layer.alert(msg, {icon: 6}, function(index){
                                    parent.location.href = "weixin_customer_info.php";
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

            $("#btn_cacal").click(function () {
                parent.location.reload();
            });
        });

    </script>
</head>
<body>
<div id="formlist">

    <br><br><br>
    <p>

        <label><font color="#dc143c">*</font>秘语：</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $miyuInfo['value']?>"/>

    </p>


    <p>

        <label style="width: 150px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
        <input type="reset" id="btn_cacal" class="btn_submit" value="取 消" />
    </p>
</div>
</body>
</html>