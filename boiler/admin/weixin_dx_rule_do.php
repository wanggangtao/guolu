<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/26
 * Time: 12:18
 */


require_once('admin_init.php');
require_once('admincheck.php');
$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add':
        global $mypdo;

        $mypdo->pdo->beginTransaction();

        try {

            $name         =  safeCheck($_POST['name'], 0);
            $send_time    =  $_POST['send_time'];
            if($send_time != 0 ){
                $send_time    =  strtotime($send_time);
            }

            $type_array     =  $_POST['type_array'];
            $community_array     =  $_POST['community_array'];
            $send_type         =  safeCheck($_POST['send_type'], 1);

            $params=array(
                "name"=>$name,
                "addtime"=>time(),
                "send_type"=>$send_type,
                "send_time" => $send_time,
            );

            $isExist = Weixin_coupon_dx_rule::getRuleByAttrs();


            if(!empty($isExist)){
                $mypdo->pdo->commit();
                echo  action_msg("该活动时间内已有规则，请禁用已存在规则！", 105);
            }else{

                $rule_id = Weixin_coupon_dx_rule::add($params);
                foreach ($type_array as $item){
                    if(empty($item)) continue;
                    $coupon_info = Weixin_coupon::getInfoById($item);
                    if(empty($coupon_info)) continue;
                    $attrs =array(
                        "rule_id"=>$rule_id,
                        "coupon_id"=>$item,
                    );
                    Weixin_dx_coupon_item::add($attrs);
                }
                $community_str = implode(",",$community_array);
                if($community_str === "-1"){
                    $attrs =array(
                        "rule_id"=>$rule_id,
                        "community_id"=>-1,
                    );
                    Weixin_dx_community_item::add($attrs);

                }else{
                    foreach ($community_array as $item){
                        if(empty($item) || $item == -1) continue;
                        $community_info = Community::getInfoById($item);
                        if(empty($community_info)) continue;
                        $attrs =array(
                            "rule_id"=>$rule_id,
                            "community_id"=>$item,
                        );
                        Weixin_dx_community_item::add($attrs);
                    }
                }


                if($send_type == 1){
                    $key = array_search(-1, $community_array);
                    if ($key !== false){
                        array_splice($community_array, $key, 1);
                    }
                    $community_str = implode(",",$community_array);

                    if(empty($community_str)){
                        $community_info  = Community::getList();
                        $community_id = array_column($community_info,"id");
                        $community_str = implode(",",$community_id);
                    }

                    $user_array =  user_account::getUserIdByCommunity($community_str);


                    if(!empty($user_array)){
                        foreach ($user_array as $item){

                            $my_coupon = "";
                            foreach ($type_array as $key => $type_item){

                                $coupon_info  = Weixin_coupon::getInfoById($type_item);


                                if($coupon_info['total'] - $coupon_info['received'] <= 0 && $coupon_info['total'] != -1 ){
                                    continue;
                                }
                                if($coupon_info['validity_period'] != 0 &&  ( time() - $coupon_info['endtime']) <0){
                                    continue;
                                }


                                if($coupon_info['validity_period'] != 0){

                                    $day = $coupon_info['validity_period'];
                                    $starttime =time();
                                    $endtime   = strtotime(date("Y-m-d",strtotime("+$day day"))) + 86399;
                                }else{
                                    $starttime =$coupon_info['starttime'];
                                    $endtime   = $coupon_info['endtime'];
                                }

                                $attrs =array(
                                    "uid" => $item['id'],
                                    "cid" => $type_item,
                                    "addtime" => time(),
                                    "starttime"=>$starttime ,
                                    "endtime"=>$endtime ,
                                );
                                weixin_user_coupon::add($attrs);
                                $newReceived = $coupon_info['received']+ 1;
                                Weixin_coupon::update($type_item,array("received"=>$newReceived));
                                $my_coupon .= $coupon_info['money']."元 ".$coupon_info['name'];
                                if(count($type_array) -1 != $key){
                                    $my_coupon .="、";
                                }

                            }


                            if(!empty($item['openid'])){
                                $url =  $HTTP_PATH."weixin/weixin_coupon_have.php";

                                $my_coupon = json_decode(json_encode($my_coupon,JSON_UNESCAPED_UNICODE));

                                $data = array(
                                    "time"=>date("Y-m-d H:i:s"),
                                    "type"=> $my_coupon,
                                );
                                $weixin = new weixin();
                                $weixin->send_coupon_by_template($url,$item['openid'],$data,$coupon_template_id) ;
                            }
                        }
                    }

                    Weixin_coupon_dx_rule::update($rule_id,array("is_send"=> 1 , "send_time" => time()));
                }

                $mypdo->pdo->commit();

                echo action_msg("发送成功",1);

            }


        }catch (MyException $e){
            $mypdo->pdo->rollBack();
            echo $e->jsonMsg();
        }
        break;

    case 'del':
        $id = safeCheck($_POST['id']);

        try {
            Weixin_coupon_dx_rule::dels($id);
            echo  action_msg("删除成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'unallow':
        $id = safeCheck($_POST['id']);

        try {
            Weixin_coupon_dx_rule::termination($id);
            echo  action_msg("终止成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;



}
?>