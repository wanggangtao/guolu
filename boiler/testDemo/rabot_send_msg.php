

<?php


require_once ("../init.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <link rel="stylesheet" href="../admin/css/style.css" type="text/css" />
    <link rel="stylesheet" href="../admin/css/form.css" type="text/css" />
    <script type="text/javascript" src="../admin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../admin/js/layer/layer.js"></script>
    <script type="text/javascript" src="../admin/js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){

                var room = $('#room').val();
                var type = $('#type').val();

                var content = $('input[name="content"]').val();


                if(content == ''){
                    layer.tips('请输入发送的内容', '#s_content');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        room  : room,
                        type  : type,
                        content  : content
                    },
                    dataType :    'json',
                    url :         'msg_do.php?act=send',
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
        <label>请选择群</label>
        <select name="room" class="select-option" id="room">
            <?php
            $list = chat_room::getInfoByAttrs();

            foreach($list as $item){

                echo "<option value={$item["id"]}>{$item["name"]}</option>";
            }
            ?>
        </select>
        <span class="warn-inline" id="s_room">* </span>
    </p>

    <p>
        <label>请选择发消息类型</label>
        <select name="type" class="select-option" id="type">
            <?php
            $typeList = socket_message::getMsgTypeList();

            foreach($typeList as $key=>$typeItem){

                echo "<option value={$key}>{$typeItem}</option>";
            }
            ?>
        </select>
        <span class="warn-inline" id="s_type">* </span>
    </p>
    <p>
        <label>选择发送内容</label>
        <input type="text" class="text-input input-length-30" name="content" id="content" />
        <span class="warn-inline" id="s_content">* </span>
    </p>

    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="立即发送" />
    </p>
</div>
</body>
</html>