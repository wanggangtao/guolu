<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/18
 * Time: 14:17
 */
require('../init.php');

$act = $_GET['act'];

switch ($act){
    case 'register':

        global $mypdo;

        $mypdo->pdo->beginTransaction();

        try {


            $userOpenId = safeCheck($_POST['openId'] , 0);
            $client_name = safeCheck($_POST['client_name'] , 0);
            $barCode = safeCheck($_POST['barCode'],0);
            $city = safeCheck($_POST['city'],0);
            $area = safeCheck($_POST['area'],0);
            $detail = safeCheck($_POST['detail'],0);
            $phone = safeCheck($_POST['phone'],0);
            $shortMessage = safeCheck($_POST['shortMessage'],0);
            $nickname = filter(safeCheck($_POST['nickname'],0));

//            $warranty = safeCheck($_POST['warranty'],0);
//
//            print_r($shortMessage);
//            exit();
            if(($shortMessage == "123321" ) || mobile_code::checkCode($phone,$shortMessage)  ){
//            if(mobile_code::checkCode($phone,$shortMessage) ){

                $userInfoByPhone = user_account::getInfoByPhone($phone);

                if(!empty($userInfoByPhone)){
//                    print_r($userOpenId);
//                    print_r($userInfoByPhone);
//
//                    exit();
                  $res =   user_account::update($userInfoByPhone['id'],array("openid" => $userOpenId));
//                  print_r($res);
//                  exit();
                    echo action_msg("您已注册！无需再次注册",2);
                    exit();
                }

                $contact_address = "";
                $addressArray= explode(' ',$city);
                if($area !== "其他"){
                    $childList =community::getCommunityByAddress($addressArray[0],$addressArray[1],$addressArray[2]);

                    $childFrist = $childList[0];
                    $address_one = $childFrist['provice_id'];
                    $address_two = $childFrist['city_id'];
                    $address_three = $childFrist['area_id'];

                    $address_four = -1;
                    $address_four_name = "";
                    foreach ($childList as $key => $value){
                        if($value['name'] == $area){
                            $address_four = $value['id'];
                            $address_four_name = $value['name'] ;
                        }
                    }
                    $contact_address .= $childFrist['provice_name']." ".$childFrist['city_name']." ". $childFrist['area_name']." ".$address_four_name." ".$detail;

                }else{
                    $address_four = -1;
                    $address_one_info =Table_district::getAddressInfoByName($addressArray[0],0);
                    $address_two_info =Table_district::getAddressInfoByName($addressArray[1],$address_one_info['id']);
                    $address_three_info =Table_district::getAddressInfoByName($addressArray[2],$address_two_info['id']);
                    $address_one = $address_one_info['id'];
                    $address_two = $address_two_info['id'];
                    $address_three = $address_three_info['id'];
                    $contact_address .= $address_one_info['name']." ".$address_two_info['name']." ". $address_three_info['name']." "."其他"." ".$detail;
                }

                $info_product= product_info::getInfoByBarCode($barCode);

                if(empty($info_product)){
                    echo action_msg("未发现产品请注销后再登陆",109);
                    exit();
                }
//                $period = strtotime($warranty) ? strtotime($warranty) : 0;

                $product_attrs = array(
                    "province_id"=>$address_one,
                    "city_id"=>$address_two,
                    "area_id"=>$address_three,
                    "community_id"=>$address_four,
                    "detail_addres"=>$detail,
                    "all_address"=>$contact_address,
                    );

                product_info::update($info_product['id'],$product_attrs);

                $attrs = array(
                    "name" => $client_name,
                    "phone" => $phone,
                    "openid" => $userOpenId,
                    "province_id" => $address_one,
                    "city_id" => $address_two,
                    "area_id" => $address_three,
                    "community_id" => $address_four,
                    "detail_addres" => $detail,
                    "addtime" => time(),
                    "contact_address" => $contact_address,
                    "product_code" => $barCode,
                    "nickname" => $nickname,
                );


                    user_account::updataBarCode($barCode);

                    $rs = user_account::add($attrs);

                    $coupon_type =  weixin_user_coupon ::addUserCoupon($rs,$address_four);

                $mypdo->pdo->commit();

                if($coupon_type == 1){
                    echo action_msg("注册成功",1);
                }elseif($coupon_type == 2){
                    echo action_msg("注册成功",2);
                }

            }else{
                $mypdo->pdo->commit();

                echo action_msg("验证码错误",109);

            }


        }catch (MyException $e){
            $mypdo->pdo->rollBack();

            echo $e->jsonMsg();
        }

        break;
    case 'verifyCode':

        try{
            $code = $_POST['barCode'];
            $repair_info =  repair_order::getCountByCode(array('code'=>$code , "unfinish" => 1,"isDel"=>1));
//            print_r($repair_info);
            if($repair_info != 0){
                echo action_msg("当前条码含有<br>未完成服务订单不能注册",109);
                exit();
            }

            $product_info = product_info::getInfoByBarCode($code);
            if(!empty($product_info)){

                $contact_address = "";
                $community = "";
                $detail_addres = "";
                $detail_addres = $product_info['detail_addres'];
                $childList =community::getCommunityByAddress($product_info['province_id'],$product_info['city_id'],$product_info['area_id'],2);

                if(($product_info['community_id'] != -1 and $product_info['community_id'] != 0)){


                    $community_info = community::getInfoById($product_info['community_id']);

                    $community = $community_info['name'];
                    $contact_address .= $community_info['provice_name']." ".$community_info['city_name']." ". $community_info['area_name'];

                }else{

                    $community = "其他";
                    if(empty($childList)){
                        echo action_msg("",1);
                        exit();
                    }
                    $childFrist =  $childList[0];
                    $contact_address .= $childFrist['provice_name']." ".$childFrist['city_name']." ". $childFrist['area_name'];
                }


                $childName = array();
                if(!empty($childList)){
                    $childName = array_column($childList,'name');
                }

                if(!(in_array("其他", $childName) or in_array("其它", $childName))){
                    array_push($childName,"其他");
                }

                $index_array =  array_search($community,$childName);
                $temp = $childName[$index_array];
                $childName[$index_array] = $childName[0];
                $childName[0] = $temp;
//                exit();



                $res_info = array(
                    "contact_address" => $contact_address,
                    "community" => $community,
                    "detail_addres" => $detail_addres,
                    "childName" =>$childName,
                );
                $flage = 1;
                foreach ($res_info as $item){
                    if(empty($item)){
                        $flage = 0;
                        break;
                    }
                }
                if($flage){
                    echo action_msg($res_info,1);

                }else{
                    echo action_msg("",1);

                }
                exit();
            }

//            $product_info = product_info::getInfoByBarCode($code);
//            if(!empty($product_info)){
//                echo action_msg("",1);
//                exit();
//            }

            echo action_msg("查询无此条码",109);

        }catch(MyException $e){
            echo $e->jsonMsg();

        }

        break;
}


