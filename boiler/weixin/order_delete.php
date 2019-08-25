<?php
/**
 * Created by PhpStorm.
 * User: wanggangtao
 *用户取消订单
 * Date: 2019/8/13
 * Time: 14:18
 */
require_once "admin_init.php";
$userOpenId = "";
if($isWeixin)
{
    $userOpenId = common::getOpenId();//获取到用户的id
}
$user_info = user_account::getInfoByOpenid($userOpenId);//查找出用户的信息
$type = 1;
if(isset($_GET['type'])){
    $type = $_GET['type'];
}

if(empty($user_info)){
    header("Location: weixin_login.php?type=".$type);
    exit();
}
$id=$_GET["id"];//订单的id
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
    <title>删除订单</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
    <script type="text/javascript">
        function  showTable(id){
            alert(id);
            location.href="order_detail.php?id="+id;
        }
    </script>
</head>
<body>



</body>
</html>
