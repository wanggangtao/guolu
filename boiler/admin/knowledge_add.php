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

                var keyword = $('input[name="keyword"]').val();
                var content = $('#content').val();
                var category = $('#category').val();

                if(keyword == ''){
                    layer.tips('关键词不能为空', '#s_keyword');
                    return false;
                }
                if(content == ''){
                    layer.tips('内容不能为空', '#s_content');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        keyword  : keyword,
                        content  : content,
                        category:category,
                    },
                    dataType :    'json',
                    url :         'knowledge_do.php?act=add',
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
        <label>关键词</label>
        <input type="text" class="text-input input-length-30" name="keyword" id="keyword" placeholder="多个用逗号隔开" />
        <span class="warn-inline" id="s_admin_account">* </span>
    </p>
    <p>
        <label>类别</label>
        <select class="select-option" id="category">
            <?php
            $categoryList = knowledge_category::getList();
            if(!empty($categoryList))
            {
                foreach ($categoryList as $item)
                {
                    echo "<option value='{$item["id"]}'>{$item["name"]}</option>";
                }
            }


            ?>

        </select>
        <span class="warn-inline" id="s_content">* </span>
    </p>

    <p>
        <label>内容</label>
        <textarea rows="10" cols="" style="width:200px;height:150px" id="content" name="content" class="text-area"></textarea>
        <span class="warn-inline" id="s_content">* </span>
    </p>

    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>