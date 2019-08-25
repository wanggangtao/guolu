<?php

require_once('admin_init.php');
require_once('admincheck.php');
$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add':

        try {

            $name         =  safeCheck($_POST['name'], 0);
            $startTime    =  $_POST['startTime'];
            $startTime    =  strtotime($startTime);
            $stopTime     =  $_POST['stopTime'];
            $stopTime     =  strtotime($stopTime);

            $type_array     =  $_POST['type_array'];
            $community_array     =  $_POST['community_array'];


            if($startTime == $stopTime){
                echo  action_msg("活动开始时间不能和活动结束时间不能相同", 102);
                exit;

            }

            $params=array();
            $param=array();
            $params['name']=$name;
            $params['starttime']=$startTime;
            $params['endtime']=$stopTime;

            $params['updatetime']=time();


            $isExist = Weixin_coupon_register_rule::isRuleExist($startTime,$stopTime);
            if($isExist){
                echo  action_msg("该活动时间内已有规则，请重新选择活动时间<br>或禁用已存在规则！", 105);
            }else{
                $rule_id = Weixin_coupon_register_rule::add($params);

                foreach ($type_array as $item){
                    if(empty($item)) continue;
                    $coupon_info = Weixin_coupon::getInfoById($item);
                    if(empty($coupon_info)) continue;


                    $attrs =array(
                        "rule_id"=>$rule_id,
                        "coupon_id"=>$item,

                    );


                    Weixin_register_rule_item::add($attrs);
                }
                $community_str = implode(",",$community_array);


                if($community_str == "-1"){
                    $attrs =array(
                        "rule_id"=>$rule_id,
                        "community_id"=>-1,
                    );
                    Weixin_community_item::add($attrs);

                }else{
                    foreach ($community_array as $item){
                        if(empty($item) || $item == -1) continue;
                        $community_info = Community::getInfoById($item);
                        if(empty($community_info)) continue;
                        $attrs =array(
                            "rule_id"=>$rule_id,
                            "community_id"=>$item,
                        );
                        Weixin_community_item::add($attrs);
                    }
                }



                echo  action_msg("添加成功", 1);;
            }



        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del':
        $id = safeCheck($_POST['id']);

        try {
            Weixin_coupon_register_rule::dels($id);
            echo  action_msg("删除成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'unallow':
        $id = safeCheck($_POST['id']);

        try {
            Weixin_coupon_register_rule::termination($id);
            echo  action_msg("终止成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;



}
?>