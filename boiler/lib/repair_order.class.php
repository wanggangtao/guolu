<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:52
 */

class repair_order extends Table {


    static public function add($attrs){

        return Table_repair_order::add($attrs);
    }

    static public function getList($params){
        if(!empty($params['value'])){


            $person_info =  repair_person::getIdByLikeName($params['value']);

            $person_array = array_column($person_info,'repair_id');
            if(!empty($person_array)){
                $person_str = implode(",", $person_array);
        
                $params['rpName'] = $person_str;
            }

        }


        return Table_repair_order::getList($params);

    }

    static public function getCount($params){
        if(!empty($params['value'])){
            $person_info =  repair_person::getIdByLikeName($params['value']);
            $person_array = array_column($person_info,'repair_id');
            if(!empty($person_array)){
                $person_str = implode(",", $person_array);
                $params['rpName'] = $person_str;
            }

        }

      return Table_repair_order::getCount($params);

    }
    static public function getInfoById($factId){
        return Table_repair_order:: getInfoById($factId);
    }

    static public function del($factId){
        return Table_repair_order::del($factId);
    }
    static public function update($id,$attrs)
    {
        return Table_repair_order::update($id,$attrs);
    }
    static public function getInfoByCode($code,$status = ""){

        return Table_repair_order::getInfoByCode($code,$status);
    }
    static public function getListByCode($params=array()){
        return Table_repair_order::getListByCode($params);
    }
    static public function getCountByCode($params=array()){
        return Table_repair_order::getCountByCode($params);
    }
    static public function postOrderToCustomer($params,$myOrderId){
        global  $HTTP_PATH;
        global  $kf_template_id;
        $service_name = "";
        $service_type = $params['service_type'];
        $service_info = Service_type::getInfoById($service_type);
        if(isset($service_info['name'])){
            $service_name =$service_info['name'];
        }
        $myPath =$HTTP_PATH."weixin/myorder_detail.php?id=".$myOrderId;


        $weixin = new weixin();
//        $weixin->getMsg();
        $all_customer = weixin_customer::getList(array());

        $data = array(
            "register_person"=>$params["register_person"],
            "type"=>$service_name,
            "register_phone"=>$params["register_phone"],
            "link_phone"=>$params["phone"],
            "address"=>$params['address_all'],

        );

        if(!empty($all_customer)){
            foreach ($all_customer as $item){
                if(empty($item['openid'])) continue;
                $index = 0;
                while($index < 3){
                    $res =  $weixin-> send_user_by_template($myPath,$item['openid'],$data,$kf_template_id);
                    $res = json_decode($res, true);
                    if($res['errcode'] == 0 ){
                        break;
                    }
                    $index ++;
                    sleep(1);
                }
//                exit();
            }
        }

    }

     //推送派单信息的函数
     //user sxx
    static public function postOrderToCustomers($myOrderId){

        global  $HTTP_PATH;
        global  $coupon_template_id;
        $myPath =$HTTP_PATH."weixin/myorder_detail.php?id=".$myOrderId;


        $a=time();
        $b=date("Y-m-d H:i:s", $a);

        $row = repair_order::getInfoById($myOrderId);
//      print_r($row);      
        $service_name = "";
        $service_type = $row['service_type'];
        $service_info = Service_type::getInfoById($service_type);
        if(isset($service_info['name'])){
            $service_name =$service_info['name'];
        }

        $weixin = new weixin();
//      $weixin->getMsg();
//      $all_customer = weixin_customer::getList(array());


        $data = array(
            "type"=>$service_name,
            "time"=>$b,

        );

        //通过id拿到openid
        $row = repair_order::getInfoById($myOrderId);
        $code = $row['bar_code'];
        $rows=User_account::getInfoByBarCode($code);
        $openid=$rows['openid'];
        $item['openid']=$openid;

       // echo $item['openid'];
        $res =  $weixin->send_coupon_by_templates($myPath,$item['openid'],$data,$coupon_template_id);
        //echo "string";

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

    }


    //推送已接单信息的函数
     //user sxx
    static public function postOrderToCustomerss($myOrderId){

        global  $HTTP_PATH;
        global  $kf1_template_id;
        $myPath =$HTTP_PATH."weixin/myorder_detail.php?id=".$myOrderId;
        $a=time();
        $b=date("Y-m-d H:i:s", $a);
        $weixin = new weixin();
        $row = repair_order::getInfoById($myOrderId);
        $service_name = "";
        $service_type = $row['service_type'];
        $service_info = Service_type::getInfoById($service_type);
        if(isset($service_info['name'])){
            $service_name =$service_info['name'];
        }

        $repair_info =   repair_person::getInfoById($row['person']);
        if(!empty($repair_info)){
             $name=$repair_info['name'];
        }
        $data = array(
            "type"=>$service_name,
            "time"=>$b,
            "linkphone"=>$row['linkphone'],
            "name"=>$name,
        );
        //通过id拿到openid
        $uid = $row['uid'];
//        var_dump($uid);
        $rows=User_account::getInfoById($uid);
//        var_dump($rows);
        $openid=$rows['openid'];
//        var_dump($openid);
        $item['openid']=$openid;
        $res =  $weixin->send_coupon_by_templatess($myPath,$item['openid'],$data,$kf1_template_id);
       var_dump($res);
    }


    //推送待支付信息的函数
    //user sxx
    static public function postOrderToCustomerpay($myOrderId){

        global  $HTTP_PATH;
        global  $kf2_template_id;

        $myPath =$HTTP_PATH."weixin/myorder_detail.php?id=".$myOrderId;

        $a=time();
        $b=date("Y-m-d H:i:s", $a);

        $weixin = new weixin();        
//      $weixin->getMsg();
//      $all_customer = weixin_customer::getList(array());
        $row = repair_order::getInfoById($myOrderId);
//        print_r($row);
      
        // $service_name = "";
        // $service_type = $row['service_type'];
        // $service_info = Service_type::getInfoById($service_type);
        // if(isset($service_info['name'])){
        //     $service_name =$service_info['name'];
        // }

        $repair_info =   repair_person::getInfoById($row['person']);
        if(!empty($repair_info)){
             $name=$repair_info['name'];
        }
        $repair_hand_money=$row['repair_hand_money'];
        $repair_part_money=$row['repair_part_money'];
        $money1=$repair_hand_money+$repair_part_money;



        $data = array(
            "time"=>$b,
            "name"=>$name,
            "money1"=>$money1."元",
            "money2"=>$row['sum_money']."元",
            "money3"=>$row['sum_money']."元",

        );
        //print_r($data);

        //通过id拿到openid
        $code = $row['bar_code'];
        $row = repair_order::getInfoById($myOrderId);
        $code = $row['bar_code'];
        $rows=User_account::getInfoByBarCode($code);
        $openid=$rows['openid'];
        $item['openid']=$openid;
        $res =  $weixin->send_coupon_by_pay($myPath,$item['openid'],$data,$kf2_template_id);


    }


    //推送支付成功信息的函数
    //user sxx
    static public function postOrderToCustomerfinsh($service_name,$myOrderId){

        global  $HTTP_PATH;
        global  $coupon_template_id;
        $myPath =$HTTP_PATH."weixin/myorder_detail.php?id=".$myOrderId;
        $a=time();
        $b=date("Y-m-d H:i:s", $a);

        $weixin = new weixin();
//      $weixin->getMsg();
//      $all_customer = weixin_customer::getList(array());
        $row = repair_order::getInfoById($myOrderId);

        $data = array(
            "type"=>$service_name,
            "time"=>$b,
            "num"=>$row['sum_money']."元",

        );

        //通过id拿到openid
        $code = $row['bar_code'];
        $rows=User_account::getInfoByBarCode($code);
        $openid=$rows['openid'];
        $item['openid']=$openid;

       // echo $item['openid'];
        $res =  $weixin->send_coupon_by_finsh($myPath,$item['openid'],$data,$coupon_template_id);
        //echo "string";

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

    }



    }
?>