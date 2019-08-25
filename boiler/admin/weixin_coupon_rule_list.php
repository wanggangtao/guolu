<?php
/**
 * 优惠券管理  weixin_coupon_rule_list.php
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
$FLAG_LEFTMENU  = 'weixin_coupon_rule_list';


$dx_type  = 0;
if(isset($_GET['dx_type'])){
    $dx_type = safeCheck($_GET['dx_type']);
}
$params=array();


//初始化
$count     = Weixin_coupon_register_rule::getCount();
$pageSize  = 10;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = Weixin_coupon_register_rule::getList($params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>优惠券使用规则</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <style>
        .click{
            font-size: 15px;
            font-weight: bold;
            background-color: #17568B;
        }
    </style>
    <script type="text/javascript">

//        window.onload = function () {
//            var $dx_type = <?php //echo $dx_type?>//;
//            if($dx_type){
//                $('#other').addClass('click');
//                $('#one').removeClass('click');
//
//                $(".show_other").css('display','block');
//                $(".show_one").css('display','none');
//            }
//        }

        $(function(){

            //显示切换
            $('#one').click(function () {
//                $(this).addClass('click');
//                $('#other').removeClass('click');
//
//                $(".show_other").css('display','none');
//                $(".show_one").css('display','block');
                window.location.href = "weixin_coupon_rule_list.php";

            });

            $('#other').click(function () {

                window.location.href = "weixin_coupon_rule_list.php?dx_type=1";

            });


            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['480px', '520px'],
                    content: 'weixin_coupon_rule_add.php'
                });
            });

            //详情
            $('.detail').click(function(){
                var thisid = $(this).parent('td').find('#rid').val();
                layer.open({
                    type: 2,
                    title: '查看规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['480px', '380px'],
                    content: 'weixin_coupon_rule_detail.php?id='+thisid
                });
            });
            //终止
            $(".unallowable").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认终止该优惠券规则吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_coupon_rule_do.php?act=unallow',
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

            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该优惠券规则吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_coupon_rule_do.php?act=del',
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


            $('#add_other').click(function(){
                layer.open({
                    type: 2,
                    title: '添加定向规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['510px', '490px'],
                    content: 'weixin_dx_rule_add.php'
                });
            });

            $('.detail_other').click(function(){
                var thisid = $(this).parent('td').find('#rid').val();
                layer.open({
                    type: 2,
                    title: '查看规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['480px', '380px'],
                    content: 'weixin_dx_rule_detail.php?id='+thisid
                });
            });
            //终止
            $(".unallowable_other").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认终止该优惠券规则吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_dx_rule_do.php?act=unallow',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            parent.location.href = "weixin_coupon_rule_list.php?dx_type=1";
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

            //删除
            $(".delete_other").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该优惠券规则吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_dx_rule_do.php?act=del',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){

                                            parent.location.href = "weixin_coupon_rule_list.php?dx_type=1";
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
        <div id="position">当前位置：<a href="weixin_coupon_rule_list.php">优惠券管理</a>&gt; 优惠券使用规则 </div>
        <div>
            <label id="one" class="btn-handle  <?php if($dx_type == 0) echo ' click'?>" >注册券</label>
            <label id="other" class="btn-handle <?php if($dx_type == 1) echo ' click'?>">定向发送券</label>
        </div>

        <div class="show_one" <?php if($dx_type == 1) echo 'style="display: none"'?>>

        <div id="handlelist">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>规则名称</th>
                    <th>发放开始时间</th>
                    <th>发放结束时间</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        $starttime = "";
                        $endtime = "";
                        if($row['starttime'] && $row['endtime']){
                            $row['starttime'] = date("Y-m-d H:i:s",$row['starttime']);
                            $row['endtime']= date("Y-m-d H:i:s",$row['endtime']);
                            $starttime=$row['starttime'];
                            $endtime=$row['endtime'];
                        }
                           echo '<tr>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.$starttime.'</td>
                                <td class="center">'.$endtime.'</td>
                                <td class="center">
                                <a class="detail" href="javascript:void(0)">详情</a>
                                <input type="hidden" id="rid" value="'.$row['id'].'"/>';
                               
                                if($row['status']==1) {
                                   echo ' <a class="unallowable" href="javascript:void(0)">终止</a>  ';
                                }else{
                                    echo '终止';
                                }
                                   echo ' <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'" />
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
        <?php

        $params_other=array();


        //初始化
        $count_other     = Weixin_coupon_dx_rule::getCount($params_other);
        $pageSize_other  = 10;
        $pagecount_other = ceil($count_other / $pageSize_other);
        $page_other      = getPage($pagecount_other);

        $params_other['page']=$page_other;
        $params_other['pageSize']=$pageSize_other;
        $rows_other      = Weixin_coupon_dx_rule::getList($params_other);

        ?>

        <div class="show_other" <?php if($dx_type == 0) echo 'style="display: none"'?>>

            <div id="handlelist">
                <input type="button" class="btn-handle fr" href="javascript:" id="add_other" value="添加">
            </div>
            <div class="tablelist">
                <table>
                    <tr>
                        <th>规则名称</th>
                        <th>发送时间</th>
                        <th>发送方式</th>
<!--                        <th>是否发送</th>-->
                        <th>操作</th>
                    </tr>
                    <?php
                    if(!empty($rows_other)){

                        foreach($rows_other as $row){
                            $send_time = date("Y-m-d H:i:s",$row['send_time']);
                            $send_type = "";
                            if($row['send_type'] == 1){
                                $send_type = "立即发送";
                            }elseif ($row['send_type'] == 2){
                                $send_type = "定时发送";
                            }
//                            $is_send = "否";
//                            if($row['is_send']){
//                                $is_send = "是";
//                            }
                            echo '<tr>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">'.$send_time.'</td>
                                <td class="center">'.$send_type.'</td>

                                <td class="center">
                                <a class="detail_other" href="javascript:void(0)">详情</a>
                                <input type="hidden" id="rid" value="'.$row['id'].'"/>';

                            if($row['status']==1) {
                                echo ' <a class="unallowable_other" href="javascript:void(0)">终止</a>  ';
                            }else{
                                echo '终止';
                            }
                            echo ' <a class="delete_other" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'" />
                                </td>
                            </tr>';

                        }
                    }else{
                        echo '<tr><td class="center" colspan="7">没有数据</td></tr>';
                    }
                    ?>

                </table>
            </div>
            <div id="pagelist">
                <div class="pageinfo">
                    <span class="table_info">共<?php echo $count_other;?>条数据，共<?php echo $pagecount_other;?>页</span>
                </div>
                <?php
                if($pagecount_other>1){
                    echo dspPages(getPageUrl(), $page_other, $pageSize_other, $count_other, $pagecount_other);
                }
                ?>
            </div>
        </div>

    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>