<?php
/****
 * wanggantao
 * 全部用户信息的接口
 */
require_once('api_init.php');
try {
//    $uid=1;
    $uid=isset($_POST['uid'])?safeCheck($_POST['uid'],0):'0';
    if(empty($uid)) throw new MyException("缺少uid参数",101);
    $orderList=Boiler_repair_order::getListrepair($uid);//使用用户的来获取维修记录
    $params=array();
    if(!empty($orderList)) {
        foreach ($orderList as $key=>$coupon) {
            if($coupon["service_type"]==1)
            {
                $coupon["service_type"]="报修故障";
            }
            else if($coupon["service_type"]==2)
            {
                $coupon["service_type"]="锅炉保养";
            }
            else if($coupon["service_type"]==3)
            {
                $coupon["service_type"]="地暖冲洗";
            }
            else if($coupon["service_type"]==46)
            {
                $coupon["service_type"]="地暖清洗";
            }
            else if($coupon["service_type"]==47)
            {
                $coupon["service_type"]="安全检查";
            }
            else if($coupon["service_type"]==48)
            {
                $coupon["service_type"]="以旧换新";
            }
            if(empty($coupon["picture_url"]))
            {
                $coupon["picture_url"]="/userfiles/weixin/upload/20190516/201905161603086033.jpeg";
            }

            $params[$key] = array(
                'id' => $coupon["id"],
                'picture_url' => $coupon["picture_url"],
                'pay_num' => $coupon["pay_num"],
                'status' => $coupon["status"],
                'child_status' => $coupon["child_status"],
                'failure_cause' => $coupon["failure_cause"],
                'user_evaluation' => $coupon["user_evaluation"],
                'service_type' => $coupon["service_type"],
                'addtime' => date('Y-m-d H:i:s', $coupon["addtime"]),
            );
        }
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $params);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}
