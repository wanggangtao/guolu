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


//$before = "";
//if(isset($_GET["before"]))
//{
//    $before = $_GET["before"];
//}

$id=safeCheck($_GET["id"]);

$labelInfo=Service_label::getInfoById($id);

//print_r($labelInfo);
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
                var id=<?php echo $labelInfo['id']?>;

                var before = $('input[name="before_code"]').val();

                var keyword = $('input[name="keyword"]').val();

                var after = $('#after_code').val();


                if(before == ''){
                    layer.tips('前置代码不能为空', '#before');
                    return false;
                }

                if(keyword == ''){
                    layer.tips('触发条件不能为空', '#s_keyword');
                    return false;
                }


                if(after == ''){
                    layer.tips('触发条件不能为空', '#s_after');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id:id,
                        keyword  : keyword,
                        before  : before,
                        after:after
                    },
                    dataType :    'json',
                    url :         'services_label_list_do.php?act=edit',
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

        function getCodeByCategory()
        {
           var category = $("#category").val();

           if(category==0) return false;


           var factDom = $("#after_code");

            factDom.html("<option value=\"0\">----请选择----</option>");
            $.ajax({
                type        : 'POST',
                data        : {
                    category  : category,
                },
                dataType :    'json',
                url :         'service_problem_do.php?act=getFact',
                success :     function(data){
                    var code = data.code;
                    var data  = data.msg;
                    switch(code){
                        case 1:

                            var html = "";
                            for(var i=0;i<data.length;i++)
                            {
                                factDom.append("<option value='"+data[i].code+"'>"+data[i].code+"</option>")
                            }

                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });

        }

        function getFact()
        {
            var code = $("#after_code").val();

            if(code==0) return false;

            var contentDom = $("#content");

            contentDom.html("");
            $.ajax({
                type        : 'POST',
                data        : {
                    code  : code,
                },
                dataType :    'json',
                url :         'service_problem_do.php?act=getInfo',
                success :     function(data){
                    var code = data.code;
                    var data  = data.msg;
                    switch(code){
                        case 1:

                            contentDom.val(data.content);

                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });

        }
    </script>
</head>

<body>
<div id="formlist">

    <p>
        <label>前置条件代码</label>
        <input type="text" class="text-input input-length-30" name="before_code" id="before_code" value="<?php echo $labelInfo['before']?>"/>
    </p>
    <p>
        <label>规则关键词</label>
        <input type="text" class="text-input input-length-30" name="keyword" id="keyword" value="<?php echo $labelInfo['keyword']?>"/>
        <span class="warn-inline" id="s_keyword">* </span>
    </p>
    <p>
        <label>选择类别</label>
        <select class="select-option" id="category" onchange="getCodeByCategory()">
            <?php
                $categoryList = Service_category::getPageList();
                $category=Service_problem::getInfoByCode($labelInfo['after'])['category'];
//                print_r($category);
//                print_r($categoryList);
                if(!empty($categoryList))
                {
                    foreach ($categoryList as $item)
                    {
                        if($item['id'] == $category)
                            echo "<option value='{$item["id"]}' selected='selected'>{$item["name"]}</option>";
                        else
                            echo "<option value='{$item["id"]}'>{$item["name"]}</option>";
                    }
                }


            ?>

        </select>
        <span class="warn-inline" id="s_content">* </span>
    </p>
    <p>
        <label>后置条件代码</label>
        <select class="select-option" id="after_code" onchange="getFact()">
            <?php
            $params=array();
            $category=Service_problem::getInfoByCode($labelInfo['after'])['category'];
            $params['category']=$category;
            $after_code=Service_problem::getList($params);
            if(!empty($after_code))
            {
                foreach ($after_code as $item)
                {
                    if($item['code'] == $labelInfo['after'])
                        echo "<option value='{$item["code"]}' selected='selected'>{$item["code"]}</option>";
                    else
                        echo "<option value='{$item["code"]}'>{$item["code"]}</option>";
                }
            }
            ?>
        </select>
        <span class="warn-inline" id="s_after">* </span>
    </p>

    <p style="" id="afterCodeP">
        <label>后置条件内容</label>
        <textarea disabled="disabled" rows="10" cols="" style="width:200px;height:150px" id="content" name="content" class="text-area"><?php
            echo Service_problem::getInfoByCode($labelInfo['after'])['content'];
            ?>
        </textarea>
        <span class="warn-inline" id="s_content">* </span>
    </p>
    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>