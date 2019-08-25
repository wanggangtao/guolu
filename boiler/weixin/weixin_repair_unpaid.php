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
//$orderList=Boiler_repair_order::getListrepair($id ,23);//使用用户的来获取维修记录
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
$orderList=Boiler_repair_order::getListrepair($id ,2,23);//使用用户的来获取维修记录
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
        function  showTable(id,person,bar_code){

            alert(id);//无订单
            alert(person);//维修人员的id
            alert(bar_code);//维修人员的id

            location.href="waitting_pay_detail.php?id="+id+'&person='+person+'&bar_code='+bar_code;
        }
        function gopay(id) {
            alert(id);
            location.href = "order_gopay.php?id=" + id;
        }
    </script>
</head>
<body>
<div id="app">
    <div class="coupon" style="background-color: #f6f6f6">
        <div class="coupon_tab">
            <a href="weixin_my_subscribe.php?id=<?php echo $id;?>" >全部</a>
            <a href="weixin_repair_ordered.php?id=<?php echo $id;?>">已下单</a>
            <a href="weixin_repair_already_accetp.php?id=<?php echo $id;?>"  >已接单</a>
            <a href="weixin_repair_unpaid.php?id=<?php echo $id;?>" class="active">待支付</a>
            <a href="weixin_repair_completed.php?id=<?php echo $id;?>">已完工</a>
        </div>
        <div class="coupon_con">
            <?php if(!empty($orderList)){
                foreach ($orderList as $coupon){
                    print_r($coupon["id"]);//所需要的信息
                    echo " <br />";
                    print_r($coupon["child_status"]);
                    echo " <br />";
                    print_r($coupon["failure_cause"]);
                    echo " <br />";
                    $coupon["addtime"]=date('Y-m-d H:i:s', $coupon["addtime"]);
                    print_r($coupon["addtime"]);
                    ?>
                    <div class="coupon_item_top">
                            <div class="coupon_item_left">
                                <a href="javascript:void(0)" onclick="showTable(<?php echo $coupon["id"]?>,<?php echo $coupon["person"]?>,'<?php echo $coupon["bar_code"]?>')">项目详情 </a>
                                <a href="javascript:void(0)" onclick="gopay(<?php echo $coupon["id"]?>)" >去付款</a>
                            </div>
                        </div>
                    <?php
                }
            } ?>
        </div>
    </div>
</div>
</body>
</html>
