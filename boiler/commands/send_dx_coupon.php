<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/26
 * Time: 18:52
 */
$CURRENT_PATH = str_replace('\\','/',dirname(__FILE__)).'/'; //网站根目录路径
require_once($CURRENT_PATH."../init.php");


$nowTime = time();



$isExist = Weixin_coupon_dx_rule::getRuleByAttrs(array('no_send' => 1));


if(!empty($isExist)){
      //如果发送时间在当前时间的一分钟范围内，则视为发送时间已到。
        $nowTimeStar = $isExist['send_time'] - 60;
        $nowTimeEnd = $isExist['send_time'] + 60;
        if($nowTimeStar <= $nowTime && $nowTime <= $nowTimeEnd){
            $coupon_list = Weixin_dx_coupon_item::getCouponByRuleId($isExist['id']);
            $community_array = Weixin_dx_community_item::getCommunityByRuleId($isExist['id']);

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
                foreach ($user_array as $user_item){
                    $my_coupon = "";
                    foreach ($coupon_list as $key => $type_item){

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
                            "uid" => $user_item['id'],
                            "cid" => $type_item,
                            "addtime" => time(),
                            "starttime"=>$starttime ,
                            "endtime"=>$endtime ,
                        );

                        weixin_user_coupon::add($attrs);

                        $newReceived = $coupon_info['received']+ 1;
                        Weixin_coupon::update($type_item,array("received"=>$newReceived));
                        $my_coupon .= $coupon_info['money']."元 ".$coupon_info['name'];
                        if(count($coupon_list) -1 != $key){
                            $my_coupon .="、";
                        }

                    }
                    if(!empty($user_item['openid'])){
                        $url =  $HTTP_PATH."weixin/weixin_coupon_have.php";
                        $my_coupon = json_decode(json_encode($my_coupon,JSON_UNESCAPED_UNICODE));
                        $data = array(
                            "time"=>date("Y-m-d H:i:s"),
                            "type"=>$my_coupon,
                        );
                        $weixin = new weixin();
                        $weixin->send_coupon_by_template($url,$user_item['openid'],$data,$coupon_template_id);
                    }
                }
            }
            Weixin_coupon_dx_rule::update($isExist['id'],array("is_send"=> 1 , "send_time" => time()));
            echo  "优惠券 ".$isExist['name']." 发送成功";
        }else{
            echo "规则 ".$isExist['name']." 未到发送时间,发送时间为：".date('Y-m-d H:i:s',$isExist['send_time'])."\n";
        }
}else{
    print_r("未发现定时发送规则"."\n") ;
}



?>