<?php
///**
// * Created by PhpStorm.
// * User: 王刚涛
// * Date: 2019/8/12
// * Time: 10:14
// */
//require_once "admin_init.php";
//$userOpenId = "";
//if($isWeixin)
//{
//    $userOpenId = common::getOpenId();//获取到用户的id
//}
//$user_info = user_account::getInfoByOpenid($userOpenId);//查找出用户的信息
//$type = 1;
//if(isset($_GET['type'])){
//    $type = $_GET['type'];
//}
//
//if(empty($user_info)){
//    header("Location: weixin_login.php?type=".$type);
//    exit();
//}
//$id=$user_info["id"];
//$orderList=Boiler_repair_order::getListrepair($id ,11);//使用用户的来获取维修记录
//?>
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
}else{
    echo "未发现用户ID";
    exit;
}
$orderList=Boiler_repair_order::getListrepair($id ,1,11);//使用用户的来获取维修记录
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
    <script src="static/js/query.js"></script>
    <script type="text/javascript">
        /***
         王刚涛
         * 详情展示
         * @param id
         * @param person
         */
        function  showTable(id,person,bar_code){

            alert(id);//无订单
            alert(person);//维修人员的id
            alert(bar_code);//维修人员的id

            location.href="already_order_detail.php?id="+id+'&person='+person+'&bar_code='+bar_code;

        }
        /***
         王刚涛
         * 取消订单的原因
         * @param id
         * @param person
         */
        function  validTable(id,person){
            alert(id);//无订单
            alert(person);//维修人员的id
            location.href="order_valid.php?id="+id+'&person='+person;
        }
    </script>
</head>
<body>
<div id="app">
    <div class="coupon" style="background-color: #f6f6f6">
        <div class="coupon_tab">
            <a href="weixin_my_subscribe.php?id=<?php echo $id;?>" >全部</a>
            <a href="weixin_repair_ordered.php?id=<?php echo $id;?>" class="active">已下单</a>
            <a href="weixin_repair_already_accetp.php?id=<?php echo $id;?>">已接单</a>
            <a href="weixin_repair_unpaid.php?id=<?php echo $id;?>">待支付</a>
            <a href="weixin_repair_completed.php?id=<?php echo $id;?>">已完工</a>
        </div>
    <div class="coupon" style="background-color: #f6f6f6">
        <div class="coupon_con">
            <?php if(!empty($orderList)){
                foreach ($orderList as $coupon){
                    echo "ID" .($coupon["id"]);//所需要的信息
                    echo " <br />";
                    echo "子状态" .($coupon["child_status"]);
                   echo "<br />";
                    echo "状态" .($coupon["status"]);
                    echo " <br />";
                    echo "原因" .($coupon["failure_cause"]);
                    echo " <br />";
                    $coupon["addtime"]=date('Y-m-d H:i:s', $coupon["addtime"]);
                    echo "时间" .($coupon["addtime"]);
                    ?>
<!--                    <a href="weixin_coupon_detail.php?start=--><?php //echo date('Y.m.d',$coupon['coupon_starttime'])?><!--&end=--><?php //echo date('Y.m.d',$coupon['coupon_endtime'])?><!--" class="coupon_item">-->
                        <div class="coupon_item_top">
                            <div class="coupon_item_left">
                                <a href="javascript:void(0)" onclick="showTable(<?php echo $coupon["id"]?>,<?php echo $coupon["person"]?>,'<?php echo $coupon["bar_code"]?>')">项目详情 </a>
                                <a href="javascript:void(0)" onclick="validTable(<?php echo $coupon["id"]?>,<?php echo $coupon["person"]?>)" >取消订单</a>
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