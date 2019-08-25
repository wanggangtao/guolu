<?php
/**
 * 服务类型  service_type.php
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
$FLAG_LEFTMENU  = 'weixin_service_type_info';
$params=array();


//初始化
$count     = Service_type::getCount();
$pageSize  = 10;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Service_type::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>服务类型管理</title>
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
            //排序
            $(".orders").blur(function(){
                // alert('111');
                var sort = $(this).val();
                var id = $(this).attr("data-id");
                var orderlist = new Array();
                $('.orders').each(function() {
                    orderlist.push({"id": $(this).data('id'), "sort": $(this).val()})
                });
                $.ajax({
                    type: 'post',
                    data: {"list": orderlist},
                    dataType: 'json',
                    url: 'weixin_service_type_do.php?act=order',
                    success: function (data) {
                        // alert(data);
                        var code = data.code;
                        var msg = data.msg;
                        // alert(code);

                        if (code == 1) {

                            location.reload();
                        } else {
                            layer.alert(msg, {icon: 5});
                        }
                    }
                })
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加服务类型',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['400px', '300px'],
                    content: 'weixin_service_type_add.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                layer.open({
                    type: 2,
                    title: '修改服务类型',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['400px', '300px'],
                    content: 'weixin_service_type_edit.php?id='+thisid
                });
            });
            //详情

            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该服务类型吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_service_type_do.php?act=del',
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
        <div id="position">当前位置：<a href="weixin_service_type_info.php">服务类型管理</a> </div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>服务类型</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        if(1!=$row['id']){//判断是否是类型为报修，若为报修不能删除
                            echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">
                                <input type="number" class="orders" value="'.$row['sort'].'" data-id="'.$row['id'].'" style="width:60px;" />
                                </td>
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        }else{
                            echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">
                                <input type="number" class="orders" value="'.$row['sort'].'" data-id="'.$row['id'].'" style="width:60px;" />
                                </td>
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        }

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