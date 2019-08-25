<?php
/**
 * 优惠券管理  weixin_coupon_list.php
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
$FLAG_LEFTMENU  = 'weixin_coupon_list';
$params=array();


//初始化
$count     = Weixin_coupon::getCount();
$pageSize  = 10;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Weixin_coupon::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>优惠券列表</title>
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
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '创建优惠券',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['680px', '570px'],
                    content: 'weixin_coupon_add.php'
                });
            });

            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该优惠券吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_coupon_do.php?act=del',
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
        <div id="position">当前位置：<a href="weixin_coupon_list.php">优惠券管理</a>&gt; 添加优惠券 </div>
        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>优惠券名称</th>
                    <th>优惠金额</th>
                    <th>发放数量</th>
                    <th>已领取</th>
                    <th>使用类型</th>
                    <th>使用限制</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        $arrty = explode(',',$row['type']);
                        $usetype = "";
                        foreach ($arrty as $k=>$item){
                            $usetype .=Service_type::getNameById($item);
                            if($k!=count($arrty)-1){
                                $usetype .="、";
                            }
                        }
                        $times = "";
                        $addtime =date("Y-m-d h:i:s",$row['addtime']);
                        if($row['starttime']&&$row['endtime']){
                            $row['starttime'] = date("Y-m-d H:i:s",$row['starttime']);
                            $row['endtime']= date("Y-m-d H:i:s",$row['endtime']);
                            $times=$row['starttime'].'—<br>'.$row['endtime']."&nbsp";
                        }else{
                            $times=$row['validity_period']."天";
                        }
                            echo '<tr>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.$row['money'].'元</td>';
                           if($row['total']==-1){
                               echo '<td class="center">不限</td>';
                           }else{
                               echo '<td class="center">'.$row['total'].'张</td>';
                           }
                          echo '<td class="center">'.$row['received'].'张</td>
                                <td class="center">'.$usetype.'</td>
                                <td class="center">'.$times.'</td>
                                <td class="center">'.$addtime.'</td> 
                                <td class="center">
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