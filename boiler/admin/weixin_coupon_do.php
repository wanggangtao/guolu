<?php

require_once('admin_init.php');
require_once('admincheck.php');
$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add':
        $name         =  safeCheck($_POST['name'], 0);
        $checkID      =  safeCheck($_POST['checkID'], 0);
        $money        =  safeCheck($_POST['money'], 1);
        $num          =  safeCheck($_POST['num'], 1);
        $startTime    =  $_POST['startTime'];
        if($startTime){
            $startTime    =  strtotime($startTime);
        }

        $stopTime     =  $_POST['stopTime'];
        if($stopTime){
            $stopTime     =  strtotime($stopTime);
        }

        $validity_period     =  safeCheck($_POST['validity_period'], 1);
        $params=array();
        $params['name']=$name;
        $params['type']=$checkID;
        $params['money']=$money;
        $params['total']=$num;
        $params['starttime']=$startTime;
        $params['endtime']=$stopTime;

        $params['validity_period']=$validity_period;
        $params['addtime']=time();

        try {
            Weixin_coupon::add($params);
            echo  action_msg("添加成功", 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del':
        $id = safeCheck($_POST['id']);

        try {
            $res = Weixin_coupon::dels($id);

           if($res!=-2){
               echo  action_msg("删除成功", 1);
           }else{
               echo  action_msg("尚有优惠券发放规则包含该优惠券<br>如需删除请先禁用相关规则！", 105);;
           }

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;



}
?>