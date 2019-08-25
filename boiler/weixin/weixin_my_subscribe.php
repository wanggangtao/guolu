<?php
/**
 * Created by PhpStorm.
 * User: 王刚涛
 * Date: 2019/8/12
 * Time: 10:14
 */
require_once "admin_init.php";
if(isset($_GET['id'])){
    $id = safeCheck($_GET['id']);
    echo $id;
}else{
    echo "未发现用户";
    exit;
}
$orderList=Boiler_repair_order::getListrepair($id);//使用用户的来获取维修记录
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>我的预约</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/jquery.min.js"></script>
    <script src="static/layer/layer.js"></script>
    <script type="text/javascript">
        function  del(id) {
            alert(id);
            layer.confirm('确认删除该项目吗？', {
                    btn: ['确认', '取消']
                }
                , function () {
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        url: 'review_do.php?act=del',
                        success: function (data) {
                            layer.close(index);
                            var code = data.code;
                            var msg = data.msg;
                            switch (code) {
                                case 1:
                                    layer.alert(msg, {icon: 6}, function (index) {
                                        location.reload();
                                    });
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    });
                }
            );
        }
        function showTable(  id, status,child_status, person,  bar_code) {
            alert(id);//无订单
            alert(status);//无订单
            alert(child_status);//维修人员的id
          if(status===2 && child_status===22)//已经接单
            {
                location.href = "already_accept_detail.php?id=" + id + '&person=' + person + '&bar_code=' + bar_code;
            }
          else   if(status===3 && child_status===31) //取消
          {
              location.href = "already_valid_detail.php?id=" + id + '&person=' + person + '&bar_code=' + bar_code;
          }
            else if(status===2 && child_status===23)//待支付
            {
                location.href = "waitting_pay_detail.php?id=" + id + '&person=' + person + '&bar_code=' + bar_code;
            }
          else  if((child_status===3  && child_status===33)  ||(child_status===3  && child_status===32))//已经完工
            {
                location.href = "order_detail.php?id=" + id + '&person=' + person + '&bar_code=' + bar_code;
            }
          else if((child_status===1 && child_status===11) || (child_status===1 && child_status===12)||(child_status===2 && child_status===21))  //已经下单
            {
                location.href = "already_order_detail.php?id=" + id + '&person=' + person + '&bar_code=' + bar_code;
            }
       }
        function review( id) {
                alert(id);//订单的
                location.href = "order_review.php?id=" + id;
            }
        function gopay(id) {
                alert(id);
                location.href = "order_gopay.php?id=" + id;
            }
        function validTable(order_id) {
                alert(order_id);//无订单
               $.ajax({
                type: 'POST',
                data: {
                    order_id: order_id,
                    reason:"没时间"
                },
                dataType: 'json',
                url: 'review_do.php?act=valid',
                success: function (data) {
                    layer.close(index);
                    var code = data.code;
                    var msg = data.msg;
                    switch (code) {
                        case 1:
                            layer.alert(msg, {icon: 6}, function (index) {
                                location.reload();
                            });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
            }
    </script>
</head>
<body>
<div id="app">
    <div class="coupon" style="background-color: #f6f6f6">
        <div class="coupon_tab">
            <a href="weixin_my_subscribe.php?id=<?php echo $id;?>" class="active">全部</a>
            <a href="weixin_repair_ordered.php?id=<?php echo $id;?>">已下单</a>
            <a href="weixin_repair_already_accetp.php?id=<?php echo $id;?>">已接单</a>
            <a href="weixin_repair_unpaid.php?id=<?php echo $id;?>">待支付</a>
            <a href="weixin_repair_completed.php?id=<?php echo $id;?>">已完工</a>
        </div>
        <div class="coupon_con">
            <?php if(!empty($orderList)){
                foreach ($orderList as $coupon){
                    echo "项目编号".$coupon["id"];//所需要的信息
                    echo " <br />";
                    echo "第一状态".$coupon["status"];
                    echo " <br />";
                    echo "第二状态".$coupon["child_status"];
                    echo " <br />";
                    echo "故障原因".$coupon["failure_cause"];
                    echo " <br />";
                    $coupon["addtime"]=date('Y-m-d H:i:s', $coupon["addtime"]);
                    echo "添加时间".$coupon["addtime"];
                    ?>
                        <div class="coupon_item_top">
                            <div class="coupon_item_left">
                                <a href="javascript:void(0)" onclick="showTable(<?php echo $coupon["id"]?>,<?php echo $coupon["status"]?>,<?php echo $coupon["child_status"]?>,<?php echo $coupon["person"]?>,'<?php echo $coupon["bar_code"]?>')">项目详情 </a>

                                <?php
                                   if(($coupon["child_status"]==11 && $coupon["status"]==1) || ($coupon["status"]==1 && $coupon["child_status"]==12)||($coupon["status"]==2 && $coupon["child_status"]==21))//已下单
                                   {?>
                                        <a href="javascript:void(0)" onclick="validTable(<?php echo $coupon["id"]?>)" >取消订单</a>
                                   <?php }?>
                                <?php
                                if($coupon["child_status"]==32 && $coupon["status"]==2)//待支付
                                {?>
                                    <a href="javascript:void(0)" onclick="gopay(<?php echo $coupon["id"]?>)" >去付款</a>
                                <?php }?>


                                <?php
                                if($coupon["child_status"]==31 && $coupon["status"]==3)//已取消
                                {?>
                                    <a href="javascript:void(0)" onclick="del(<?php echo $coupon["id"]?>)" >删除订单</a>
                                <?php }?>

                                <?php
                                if(($coupon["status"]==3 && $coupon["child_status"]==33)|| ($coupon["status"]==3 && $coupon["child_status"]==32))//已完成
                                {?>
                                    <a href="javascript:void(0)" onclick="review(<?php echo $coupon["id"]?>)" >去评价</a>
                                <?php }?>

                            </div>
                        </div>
<!--                    </a>-->
                    <?php
                }
            } ?>
        </div>
    </div>
</div>
</body>
</html>
