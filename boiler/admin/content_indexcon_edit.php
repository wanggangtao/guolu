<?php


require_once('admin_init.php');
require_once('admincheck.php');
$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$type = isset($_GET["type"])?safeCheck($_GET["type"]):0;
$info = Webcontent::getInfoById($id);

if(empty($info)){
    echo '非法操作';
    die();
}
//print_r($type);
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
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){

            $('#btn_submit_edit1').click(function(){
                var title1 = $('#title1').val();
                var title_supplement1 = $('#title_supplement1').val();
                var subtitle1 = $('#subtitle1').val();
                var content1 = $('#content1').val();

                if(title1 == ''){
                    layer.alert('标题不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(title1.length >5){
                    layer.alert('标题过长', {icon: 5,shade: false});
                    return false;
                }
                if(title_supplement1.length >2){
                    layer.alert('标题备注过长', {icon: 5,shade: false});
                    return false;
                }
                if(subtitle1 == ''){
                    layer.alert('副标题不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(subtitle1.length >12){
                    layer.alert('副标题过长', {icon: 5,shade: false});
                    return false;
                }
                if(content1 == ''){
                    layer.alert('内容不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(content1.length >38){
                    layer.alert('内容过长', {icon: 5,shade: false});
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id   : '<?php echo $id;?>',
                        title   : title1,
                        title_supplement  : title_supplement1,
                        subtitle      : subtitle1,
                        content   : content1
                    },
                    dataType :    'json',
                    url :         'content_do.php?act=content_edit',
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

            $('#btn_submit_edit2').click(function(){
                var title2 = $('#title2').val();
                var content2 = $('#content2').val();

                if(title2 == ''){
                    layer.alert('标题不能为空',{icon: 5,shade: false});
                    return false;
                }
                if(title2.length >5){
                    layer.alert('标题过长', {icon: 5,shade: false});
                    return false;
                }

                if(content2 == ''){
                    layer.alert('内容不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(content2.length >38){
                    layer.alert('内容过长', {icon: 5,shade: false});
                    return false;
                }


                $.ajax({
                    type        : 'POST',
                    data        : {
                        id   : '<?php echo $id;?>',
                        title   : title2,
                        content   : content2
                    },
                    dataType :    'json',
                    url :         'content_do.php?act=content_edit',
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

    <input type="hidden" class="text-input input-length-50" name="id1" id="id1" value=""/>
    <?php
    if($type == 1) {
    ?>

    <p>
        <label>标题</label>
        <input type="text" class="text-input input-length-50" name="title1" id="title1" value="<?php echo $info["title"];?>"/>
        <span class="warn-inline">* 不得超过5个字 </span>
    </p>
        <p>
            <label>标题备注</label>

            <input type="text" class="text-input input-length-50" name="title_supplement1" id="title_supplement1" value="<?php echo $info["title_supplement"];?>"/>
            <span class="warn-inline">  不得超过2个字 </span>
        </p>

        <p class="subtitle">
            <label>副标题</label>
            <input type="text" class="text-input input-length-50" name="subtitle1" id="subtitle1" value="<?php echo $info["subtitle"];?>"/>
            <span class="warn-inline">* 不得超过12个字 </span>
        </p>

    <p>
        <label>内容</label>
        <input type="text" class="text-input input-length-50" name="content1" id="content1" value="<?php echo $info["content"];?>"/>
        <span class="warn-inline">* 不得超过38个字 </span>
    </p>
    <p>
        <label>&nbsp;</label>
        <input type="submit" id="btn_submit_edit1" class="btn_submit" value="确　定" />
    </p>
    <?php
    }?>
    <?php
    if($type == 2) {
        ?>

        <p>
            <label>标题</label>
            <input type="text" class="text-input input-length-50" name="title2" id="title2" value="<?php echo $info["title"];?>"/>
            <span class="warn-inline">* 不得超过5个字 </span>
        </p>


        <p>
            <label>内容</label>
            <input type="text" class="text-input input-length-50" name="content2" id="content2" value="<?php echo $info["content"];?>"/>
            <span class="warn-inline">* 不得超过38个字 </span>
        </p>
        <p>
            <label>&nbsp;</label>
            <input type="submit" id="btn_submit_edit2" class="btn_submit" value="确　定" />
        </p>
        <?php
    }?>

</div>
</body>
</html>