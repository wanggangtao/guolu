<?php
/****
 * wanggantao
 * 全部用户信息的接口
 */
require_once('api_init.php');
try {
//    $id=1;//项目的id
    $id = isset($_POST['id'])?safeCheck($_POST['id'],1):'0';//为数字
    if(empty($id)) throw new MyException("缺少项目id参数",101);
    $orderList=Boiler_repair_order::getrepair_detail($id);//使用用户的来获取维修记录
        if($orderList["service_type"]==1)
        {
            $orderList["service_type"]="报修故障";
        }
        else if($orderList["service_type"]==2)
        {
            $orderList["service_type"]="锅炉保养";
        }
        else if($orderList["service_type"]==3)
        {
            $orderList["service_type"]="地暖冲洗";
        }
        else if($orderList["service_type"]==46)
        {
            $orderList["service_type"]="地暖清洗";
        }
        else if($orderList["service_type"]==47)
        {
            $orderList["service_type"]="安全检查";
        }
        else if($orderList["service_type"]==48)
        {
            $orderList["service_type"]="以旧换新";
        }
    $orderList["addtime"]=date('Y-m-d H:i:s', $orderList["addtime"]);
    $order_person=Boiler_repair_order::getrepair_person($orderList["person"]);//使用维修工的的来获取工人信息
    $order_repair_part=Boiler_repair_parts::getrepair_part($id);//使用的维修的的来获取零件信息
    $bar_code_info=Product_info::getInfoBycode($orderList["bar_code"]);//用产品的id来获取产品的保修信息
    if(!empty($order_person)) {
        $params=array();
        foreach ($order_person as $k=>$v)
        {
            $params["name"]=$order_person[$k]["name"];
            $params["id"]=$order_person[$k]["id"];
        }
    }
    if(!empty($order_repair_part)) {
        $params2=array();
        foreach ($order_repair_part as $k=>$v)
        {
            $params2[$k] = array(
                'name' => $order_repair_part[$k]["Info_part"],
                'num' => $order_repair_part[$k]["part_num"],
                'price' => $order_repair_part[$k]["part_money"],
            );
        }
    }
    if(!empty($bar_code_info)) {
        $params3=array();
        foreach ($bar_code_info as $k=>$v)
        {
            $params3["period"]=$bar_code_info[$k]["period"];
            if( $params3["period"]!="过保")
            {
                $params3["period"]=date('Y-m-d H:i:s',$params3["period"]);
            }
        }
    }
    $orderList["part"]=$params2;
    $orderList["person"]=$params;
    $orderList["prodect"]=$params3;
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $orderList);
}catch (MyException $e){
    print_r("失败");
    $api->ApiError($e->getCode(), $e->getMessage());
}
