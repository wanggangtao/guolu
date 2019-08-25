<?php

require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixin_service_record';
$params=array();
$id=$_GET['id'];
$params['openid']=$id;
//初始化
$count     = Service_record::getCountByOpenId($params);
$pageSize  = 10;

$pagecount = ceil($count / $pageSize);

$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Service_record::getListByOpenId($params);

/**
 *
 */


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>公众号管理- 产品管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="../static/js/layer/layer.js"></script>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //查找
            $('#search').click(function(){
                var name = $('#name').val();
                var startTime = $('#starttime').val();
                var stopTime = $('#stoptime').val();
                location.href  = "weixin_product.php?code="+name+"&starttime="+startTime+"&stoptime="+stopTime;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加产品',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_product_add.php'
                });
            });
            $('#import').click(function(){
                layer.open({
                    type: 2,
                    title: '产品批量导入',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_import_product.php'
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                console.log(thisid);
                layer.open({
                    type: 2,
                    title: '修改用户',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '500px'],
                    content: 'weixin_product_edit.php?id='+thisid
                });
            });
            //详情
            $(".view_record").click(function(){
                var thisid = $(this).parent('td').find('#cid').val();
                console.log(thisid);
                location.href  = "weixin_service_record_list.php?id="+thisid;
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该产品吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_product_do.php?act=del',
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
        <div id="position">当前位置：<a href="user_list.php">公众号管理</a> &gt; 机器人聊天管理</div>
        <div id="handlelist">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>昵称</th>
                    <th>小区</th>
                    <th>品牌</th>
                    <th>类型</th>
                    <th>最后聊天时间</th>
                    <!--                    <th>类型</th>-->
                    <!--                    <th>操作</th>-->
                </tr>
                <?php
                if(!empty($rows)){
                    $user_type="游客";
                    $user_info=User_account::getAllByOpenid($rows[0]['user_openid']);
                    if($user_info){
                        $user_type="注册用户";

                    }
                        echo '<tr>
                                <td class="center">'.$rows[0]['account'].'</td>
                                <td class="center">'.$rows[0]['community'].'</td>
                                <td class="center">'.$rows[0]['brand'].'</td>
                                <td class="center">'.$user_type.'</td>
                                <td class="center">'.date("Y-m-d H:i:s",$rows[0]['time']).'</td>
                               
                            </tr>';
                        $i++;
                    }
                ?>

            </table>
        </div>
        <br><br>
        <div id="handlelist" align="right">
            <select id="role" class="select-handle">
                <option value="0">--</option>
                <option value="1">机器人</option>
                <option value="2">用户</option>
            </select>
            <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">

        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>时间</th>
                    <th>角色</th>
                    <th>聊天内容</th>
                </tr>
                <?php
                if(!empty($rows)){

                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        $role="机器人";
                        if($row['role']!=1){
                            $role="用户";

                        }
                        $user_community="";
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.date("Y-m-d H:i:s",$row['time']).'</td>
                                 <td class="center">'.$role.'</td>
                                <td class="center">'.$row['content'].'</td>
                               
                            
                             
                            </tr>';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="7">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <br>
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