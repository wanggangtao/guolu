<?php
/**
 * Created by PhpStorm.
 * User: wanggangtao
 * 师傅端待接单
 * Date: 2019/8/15
 * Time: 17:35
 */
require_once "admin_init.php";
//$userOpenId = "";
//if($isWeixin)
//{
//    $userOpenId = common::getOpenId();
//}
//$user_info = user_account::getInfoByOpenid($userOpenId);
//
//$weixin = new weixin();
//
//$personal_info = $weixin->getUserInfo($userOpenId);
//
//if(empty($user_info)){
//    header("Location: weixin_login.php");
//
//    exit;
//}
//if(empty($user_info['nickname'])){
//    $nickname = filter($personal_info['nickname']);
//    user_account::update($user_info['id'],array('nickname'=>$nickname));
//}

//
//if($isWeixin)
//{
//    $userOpenId = common::getOpenId();
//}
///**提交代码的时候将下边的注释，,现在是将条码写死了，为了调试方便，将上边的放开*/
////$userOpenId = "om86uuMzFWIytR_S5SHbMZTCVw0A";
//$user_info = user_account::getInfoByOpenid($userOpenId);
//var_dump($user_info);
/**** 这里将师傅的id写死了，为了便于调试，在上传代码的时候，将上边的打开，下边的关闭 */
$master_id=9;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>我的工单</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/jquery.min.js"></script>
    <script src="static/layer/layer.js"></script>
    <script type="text/javascript">
        function  showTable1(master_id){
            alert(master_id);//维修人员的id
            location.href="master_watting_accept.php?master_id="+master_id;
        }
        function  showTable2(master_id){
            alert(master_id);//维修人员的id
            location.href="master_already_accept.php?master_id="+master_id;
        }
        function  showTable3(master_id){
            alert(master_id);//维修人员的id
            location.href="master_unpay_money.php?master_id="+master_id;
        }
        function  showTable4(master_id){
            alert(master_id);//维修人员的id
            location.href="master_already_complete.php?master_id="+master_id;
        }
    </script>
</head>
<body>
<a href="javascript:void(0)" onclick="showTable1(<?php echo $master_id?>)"> 待接单</a>
<a href="javascript:void(0)" onclick="showTable2(<?php echo $master_id?>)" >已接单</a>
<a href="javascript:void(0)" onclick="showTable3(<?php echo $master_id?>)"> 待支付</a>
<a href="javascript:void(0)" onclick="showTable4(<?php echo $master_id?>)" >已完工</a>

</body>
</html>