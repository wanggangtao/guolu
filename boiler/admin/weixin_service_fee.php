<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 16:20
 */
require_once('admin_init.php');
require_once('admincheck.php');
$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixincontent';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理- 上门费管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="../static/js/layer/layer.js"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //添加
            $("#fee_add").click(function(){
                layer.open({
                    type: 2,
                    title: '上门费管理',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '300px'],
                    content: 'weixin_service_fee_add.php'
                });
            });
        });
        //修改上门费信息
        function edit(id) {
            layer.open({
                type: 2,
                title: '上门费管理',
                shadeClose: true,
                shade: 0.3,
                area: ['600px', '300px'],
                content:'weixin_service_fee_edit.php?id='+id
            });
        }
        //从列表中删除
        function del(id) {
            layer.confirm('确认删除该费用吗?',
                {btn: ['确认', '取消']},
                function () {
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        url: 'weixin_service_fee_do.php?act=del',
                        success: function (data) {
                            layer.close(index);
                            var code = data.code;
                            var msg = data.msg;
                            switch (code) {
                                case 1:
                                    layer.alert(msg, {icon: 6}, function (index) {
                                        parent.location.reload();
                                    });
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    });
                }, function(){}
            );
        }
    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="user_list.php">公众号管理</a> &gt; 上门费管理</div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" id="fee_add" value="新增">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>上门费(元)</th>
                    <th>操作</th>
                </tr>
                <?php
                $rows = Service_fee::getList();
                if (!empty($rows)){
                    $i = 1;
                    foreach ($rows as $row){
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['number'].'</td>
                                <td class="center">
                                    <a href="javascript:edit('.$row['id'].')">修改</a>
                                    &nbsp;&nbsp;
                                    <a href="javascript:del('.$row['id'].')">删除</a>
                                </td>
                             </tr>';
                        $i++;
                    }
                }
                else{
                    echo '<tr><td class="center" colspan="3">没有数据</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>