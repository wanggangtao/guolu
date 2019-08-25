<!-- <!DOCTYPE html>
<html lang="en"> -->
<?php
require_once('admin_init.php');

  //$row = repair_order::getInfoById('2');
  //print_r($row);

 // $order_id='23'; 
 // $operation="2";
 // $order_status='3';
 // $service_person='3';
 // $person_phone='0';
 // $person_reason='0';
 //$row = repair_order::getInfoByCode('521013','1');
 // $row = repair_order::getInfoById('385');
 // print_r($row['child_status']);

//$rs = Boiler_order_process::add($order_id, $operation,$order_status,$service_person,$person_phone,$person_reason);

//$rs = Boiler_order_process::getInfoById($order_id);
//var_dump($rs);
// $order_process = "";
// $order_status = "";
// $service_person_phone = "";
// if (!empty($rs)){
//     foreach ($rs as $row){
//         if ($row['operation']!=-1){
//             if ($row['operation'] == 1){
//                 $order_process = "客户下单预约";
//             }
//             if ($row['operation'] == 2){
//                 $order_process = "管理员已派单";
//             }
//             if ($row['operation'] == 3){
//                 $order_process = "维修师傅已接单";
//             }
//             if ($row['operation'] == 4){
//                 $order_process = "维修师傅维修完成";
//             }
//             if ($row['operation'] == 5){
//                 $order_process = "客户支付完成";
//             }
//             if ($row['operation'] == 6){
//                 $order_process = "管理员进行审核";
//             }
//             if ($row['operation'] == 7){
//                 $order_process = "客户取消订单";
//             }
//             if (($row['operation'] == 8)&&($row['order_status']==12)){
//                 $order_process = "维修师傅申请重派.重派原因：".$row['person_reason'];
//             }
//         }
//         else{
//             $order_status ="";
//         }

//         if ($row['order_status']!=-1){
//             if ($row['order_status'] == 11){
//                 $order_status = "订单状态：正常待派单";
//             }
//             if ($row['order_status'] == 12){
//                 $order_status = "订单状态：重派单";
//             }
//             if ($row['order_status'] == 21){
//                 $order_status = "订单状态：待结单";
//             }
//             if ($row['order_status'] == 22){
//                 $order_status = "订单状态：已接单";
//             }
//             if ($row['order_status'] == 23){
//                 $order_status = "订单状态：待支付";
//             }
//             if ($row['order_status'] == 31){
//                 $order_status = "订单状态：已支付";
//             }
//             if ($row['order_status'] == 32){
//                 $order_status = "订单状态：待审核";
//             }
//             if ($row['order_status'] == 33){
//                 $order_status = "订单状态：已审核";
//             }
//         }
//         else{
//             $order_status = "";
//         }
//         if ((!empty($row['service_person']))&&(!empty($row['person_phone']))&&(($row['order_status']==21)||($row['order_status']==22))){
//             $service_person_phone = "服务人员: ".$row['service_person']."联系电话：".$row['person_phone'];
//         }
//         echo date("Y-m-d H:i:s",$row['addtime'])." ".$order_process." ".$order_status." ".$service_person_phone."<br>";
//     }
// }
//$re = Product_info::getInfoBycode("XAKD21010101");
//$rs = Boiler_order_process::getCount(3);
//$s = Boiler_order_process::getInfoById(3);
//print_r($s);
//print_r($s[$rs-1]);
// $t = time();
// echo(date("Y-m-d",$t)).PHP_EOL;
// echo(date("Y-m-d",$t+604800))
// $W=123456789;
//  $rs = Boiler_order_process::add(103, 2,22,"张三",$W,"经济价");
//  print_r($rs);
// $Num= Boiler_repair_order::getChargeNum();
// print_r($q);
// $params["child_status"] = 23;

// $rows = repair_order::getList($params);
// $q = Table_repair_order
//var_dump(Table_repair_order::status1);
//


//$userOpenId = "";
// $userOpenId = common::getOpenId();
// var_dump($userOpenId);
// 
// 
// 判断是否为微信环境
// if($isWeixin)
// {
//     $userOpenId = common::getOpenId();
// }
    
    //发短信 
    // $code = randcode(4);
    // $phone = 13289379922;
    // $rt = sms::send_code($phone,$code);
//     
// $order_id=407;
// $row = repair_order::getInfoById($order_id);
// $code = $row['bar_code'];
// $rows=User_account::getInfoByBarCode($code);
// $openid=$rows['openid'];
// $a=time();
// $b=date("Y-m-d H:i:s", $a);
// print_r($b);
//repair_order::postOrderToCustomers('报修故障','408');
// $w=weixin::getAccessToken();
// var_dump($w);
// 
// 
//  function postOrderToCustomer($params,$myOrderId){
//         global  $HTTP_PATH;
//         global  $kf_template_id;
//         $service_name = "";
//         $service_type = $params['service_type'];
//         $service_info = Service_type::getInfoById($service_type);
//         if(isset($service_info['name'])){
//             $service_name =$service_info['name'];
//         }
//         $myPath =$HTTP_PATH."weixin/myorder_detail.php?id=".$myOrderId;
//         //echo $service_name;exit();

//         $weixin = new weixin();
// //        $weixin->getMsg();
//         //$all_customer = weixin_customer::getList(array());

//         $data = array(
//             "register_person"=>$params["register_person"],
//             "type"=>$service_name,
//             "register_phone"=>$params["register_phone"],
//             "link_phone"=>$params["phone"],
//             "address"=>$params['address_all'],

//         );
//         print_r($data);
//         $item['openid']='om86uuM-gDAt9g4ZrO4F-ogwjaDQ';
//         $res =  $weixin-> send_user_by_template($myPath,$item['openid'],$data,$kf_template_id);

//         if(!empty($all_customer)){
//             foreach ($all_customer as $item){

//                 if(empty($item['openid'])) continue;

//                 while(true){
//                     $index = 0;
//                     $res =  $weixin-> send_user_by_template($myPath,$item['openid'],$data,$kf_template_id);
//                     $res = json_decode($res, true);

//                     if($res['errcode'] == 0 || $index >3){
//                         break;
//                     }
//                     $index ++;
//                     sleep(1);
//                 }

// //                exit();
//             }
//         }

//     }

//  $params["register_person"]="www";
//  $params["register_phone"]='12345678765';
//  $params["phone"]='1234567';
//  $params['address_all']="asdf";
//  $params['service_type']='1';
//  $myOrderId=408;
// postOrderToCustomer($params,$myOrderId);



 //
 //repair_order::postOrderToCustomers('报修故障',408);
 //$row = repair_order::getInfoById(408);
 //repair_order::postOrderToCustomerss(408);
 // $c=2;
 // $a='$c'."元";
 // // $b='3';
 // // $c=$a+$b;
 // var_dump($a);
//repair_order::postOrderToCustomerpay(408);
//print_r($row);
//repair_order::postOrderToCustomerfinsh('报修故障',408);
$row = User_account::getInfoByPhone(13289379922);
print_r($row);
?>
