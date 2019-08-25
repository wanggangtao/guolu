<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 22:47
 *
 * 用户名单中的用户类型button
 */
require_once('admin_init.php');
require_once('admincheck.php');

$attrid = isset($_GET['attrid'])?safeCheck($_GET['attrid']):0;
//目前仅锅炉用户名单页面
//$attrid =20;
$caseattr = Case_attr::getInfoById($attrid);
if(empty($caseattr)){
    echo '非法操作';
    die();
}

//$level = $caseattr[0]['level'];
//通过attrid对应的parentid=14和level=4，找出所有的属性项
$attrs = Case_attr::getListByPidandLevel(4,14,0);
//print_r($attrs);
//固定属性值,写死不变
//$fixedids = array();
//if(20 == $attrid){
//    $fixedids = array(20,21,22);
//}elseif(23 == $attrid){
//    $fixedids = array(23,24,25);
//}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title> 属性管理 </title>
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
                    title: '添加用户类型',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '250px'],
                    content: "usertype_add.php?pid="+'<?php echo 14;?>'+"&level="+'<?php echo 4;?>'
                });
            });
            //修改类别
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改用户类型 ',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '250px'],
                    content: 'usertype_edit.php?id='+thisid
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
                            url :         'userlist_do.php?act=delt',
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
                foreach ($attrs as $k=>$attr){
                    echo '<tr>
                                  <td class="center"><input type="checkbox" name="itemcheck" value="'.$attr['id'].'"/></td>
                                  <td class="center">'.$attr['name'].'</td>
                                  <td class="center"><a class="editinfo" href="javascript:void(0)">修改</a><input type="hidden" id="aid" value="'.$attr['id'].'"/></td>
                              </tr>';

                }
                ?>
            </table>
        </div>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>