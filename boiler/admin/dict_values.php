<?php
/**
 * 产品属性值列表  dict_valueadd.php
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$id =  isset($_GET["id"])?safeCheck($_GET["id"]):0;
$dictinfo = Dict::getInfoById($id);
if(empty($dictinfo)){
    echo '非法操作';
    die();
}

$valuelist = Dict::getListByParentid($id);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title><?php  echo $dictinfo['name'].' - ';?> - 属性管理 - 产品中心 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            //添加类别
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加属性值',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '300px'],
                    content: 'dict_valueadd.php?id=<?php echo $id;?>'
                });
            });
            //修改类别
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改属性值 ',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '300px'],
                    content: 'dict_valueedit.php?id='+thisid
                });
            });
            //批量删除
            $("#can").click(function(){
                var arrChk = $("input[name='itemcheck']:checked");
                var idStr = '';
                for(var i = 0;i < arrChk.length; i++){
                    idStr += arrChk[i].value + ',';
                }
                if(idStr == ''){
                    layer.msg('请至少选择一个值');
                    return false;
                }
                layer.confirm('确认删除吗？', {
                    btn: ['确认','取消']
                }, function(){
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            idStr : idStr
                        },
                        url :         'dict_do.php?act=dels',
                        dataType:     'json',
                        success :     function(data){
                                            layer.close(index);
                                            var code = data.code;
                                            var msg  = data.msg;
                                            switch(code){
                                                case 1:
                                                    layer.alert(msg, {icon: 6, shade: false}, function(index){
                                                        location.reload();
                                                    });
                                                    break;
                                                default:
                                                    layer.alert(msg, {icon: 5});
                                            }
                        }
                    });
                }, function(){}
                );
            });
        });
    </script>
</head>
<body>
<div id="container">
    <div id="maincontent" style="width: 92%">
        <div id="handlelist">
            <input type="button" class="btn-handle" href="javascript:" id="add" value="+">
            <input type="button" class="btn-handle" href="javascript:" id="can" value="-">
        </div>
        <div class="tablelist">
            <table>
                <?php
                $i=1;
                if(!empty($valuelist)){
                    foreach($valuelist as $row){
                        $pictd = "";
                        if($id == 1){
                            $pictd = '<td class="center"><img src="'.$HTTP_PATH.$row['pic'].'" width="100px" height="50px"></td>';
                        }
                        echo '<tr>
                                  <td class="center"><input type="checkbox" name="itemcheck" value="'.$row['id'].'"/></td>
                                  <td class="center">'.$row['name'].'</td>'.$pictd.'
                                  <td class="center"><a class="editinfo" href="javascript:void(0)">修改</a><input type="hidden" id="aid" value="'.$row['id'].'"/></td>
                              </tr>';
                    }
                }else{
                    echo '<tr><td class="center" colspan="<?php echo $id == 1?4:3; ?>">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>