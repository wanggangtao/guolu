<?php
/**
 * 除污器列表  dirt_separator_list.php
 *
 * @version       v0.01
 * @create time   2018/5/30
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "products";
$FLAG_LEFTMENU  = 'waterbox_list';
$tabmodelid = 9;
$tabcatid = 3;
//初始化
$count     = Dirt_separator_attr::getList(1, 15, 1);
$pageSize  = 15;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);
$rows      = Dirt_separator_attr::getList($page, $pageSize, 0);
$nums = $count-($page-1)*$pageSize;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>除污器 - 管件 - 产品管理 - 产品中心 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加除污器',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '200px'],
                    content: 'dirt_separator_add.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改除污器',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '200px'],
                    content: 'dirt_separator_edit.php?id='+thisid
                });
            });
            //详情
            $(".info").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '修改除污器',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '200px'],
                    content: 'dirt_separator_detail.php?id='+thisid
                });
            });
            //删除
            $(".delete").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                var pid = $(this).parent('td').find('#pid').val();
                layer.confirm('确认删除吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id : thisid,
                                pid : pid
                            },
                            dataType : 'json',
                            url : 'dirt_separator_do.php?act=del',
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
        <div id="position">当前位置：<a href="guolu_list.php">产品中心</a> &gt; 产品管理 &gt; 管件 &gt; 除污器</div>
        <?php include('products_tab.inc.php');?>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>型号</th>
                    <th>直径（DN）</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    foreach($rows as $row){
                        echo '<tr>
                                <td class="center">'.$nums.'</td>
                                <td class="center">'.$row['separator_version'].'</td>
                                <td class="center">'.floatval($row['separator_diameter']).'</td>
                                <td class="center">
                                    <a class="info" href="javascript:void(0)">详情</a> 
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <a href="pricelog_list.php?type=1&objectid='.$row['products_id'].'&leftmenu=waterbox_list">价格记录</a>
                                    <input type="hidden" id="aid" value="'.$row['separator_id'].'"/>
                                    <input type="hidden" id="pid" value="'.$row['products_id'].'"/>
                                </td>
                            </tr>';
                        $nums--;
                    }
                }else{
                    echo '<tr><td class="center" colspan="4">没有数据</td></tr>';
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