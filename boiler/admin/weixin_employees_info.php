<?php
/**
 * 用户列表  user_list.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixin_employees_info';
$params=array();


//初始化
$count     = Repair_person::getCount();
$pageSize  = 10;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Repair_person::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>信息管理 - 服务人员管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //查找
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加员工',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_employees_add.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                layer.open({
                    type: 2,
                    title: '修改员工',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_employees_edit.php?id='+thisid
                });
            });
            //详情

            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该员工吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_employees_do.php?act=del',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
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
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="user_list.php">信息管理</a> &gt; 服务人员管理</div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>姓名</th>
                    <th>联系电话</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['name'].'</td>
                           
                                <td class="center">'.$row['phone'].'</td>
                            
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="7">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $count;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pageSize, $count, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>