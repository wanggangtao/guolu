<?php
/**
 * 产品类别列表  category_child.php
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "products";
$FLAG_LEFTMENU  = 'category';

$pid = safeCheck($_GET["pid"]);
$pcatinfo = Category::getInfoById($pid);
if(empty($pcatinfo)){
    echo '非法操作';
    die();
}
//初始化
$totalcount= Category::getPageList(1, 10, 0, $pid);
$rows      = Category::getPageList(0, 0, 1, $pid);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title><?php echo $pcatinfo['name']; ?> - 类别管理 - 产品中心 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            var pid = '<?php echo $pid; ?>';
            //添加类别
            $('#addcat').click(function(){
                layer.open({
                    type: 2,
                    title: '添加产品类别',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '200px'],
                    content: 'category_add.php?id=0&pid='+pid
                });
            });

            //修改类别
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改产品类别',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '200px'],
                    content: 'category_add.php?id='+thisid+'&pid='+pid
                });
            });

            //删除管理员
            $(".delete").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除该类别吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:thisid
                            },
                            dataType : 'json',
                            url : 'category_do.php?act=del',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
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
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('products_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="admingroup.php">产品中心</a> &gt; 类别管理 &gt; <?php echo $pcatinfo['name']; ?></div>
        <div id="handlelist">
            <input type="button" class="btn-handle" href="javascript:" id="addcat" value="添 加">
            <span class="table_info">共<?php echo $totalcount;?>条数据</span>
            <div>
            </div>
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>类别id</th>
                    <th>名称</th>
                    <th>操作人</th>
                    <th>操作时间</th>
                    <th>操作</th>
                </tr>
                <?php

                $i=1;
                //如果列表不为空
                if(!empty($rows)){
                    foreach($rows as $row){
                        //获取管理员账号
                        try {
                            $admin       = new Admin($row['operator']);
                            $account     = $admin->getAccount();
                        }catch(MyException $e){
                            $account = '-';
                        }
                        echo '<tr>
                                    <td class="center">'.$row['id'].'</td>
                                    <td class="center">'.$row['name'].'</td>
                                    <td class="center">'.$account.'</td>
                                    <td class="center">'.date("Y-m-d H:i:s",$row['addtime']).'</td>
                                    <td class="center">
                                        <a class="editinfo" href="javascript:void(0)">修改</a>
                                        <a class="delete" href="javascript:void(0)">删除</a>
                                        <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                    </td>
                                </tr>';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="5">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>