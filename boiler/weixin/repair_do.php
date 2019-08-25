<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/20
 * Time: 22:13
 */
require_once "admin_init.php";

if(isset($_GET['act']) && $_GET['act'] == "select_type")
{

    $userid = safeCheck($_POST['id']);
    $type = safeCheck($_POST['type']);
    $str_array = array();

    $my_coupon = Weixin_user_coupon::getInfoByUid($userid);
    if(!empty($my_coupon)){
        foreach ($my_coupon as $item){
            $coupon_info = Weixin_coupon::getInfoByIdAndType($item['cid'],$type);
            if(!empty($coupon_info)){
                $str_array[] = $coupon_info['money']."元 ".$coupon_info['name'];
            }
        }
    }
    if(!empty($str_array)){
        echo action_msg($str_array[0],1);
    }else{
        echo action_msg("",1);
    }


}else{
    global $mypdo;

    $mypdo->pdo->beginTransaction();

    try{

        $openId = safeCheck($_POST['openId'],0);
        $linkPhone = safeCheck($_POST['linkPhone'],0);
        $result = safeCheck($_POST['result'],0);
        $urlStr = safeCheck($_POST['urlStr'],0);
        $server_type = safeCheck($_POST['server_type'],1);
        $id = safeCheck($_POST['id'],1);


        $user_info = user_account::getInfoByOpenid($openId);
        if(!isset($user_info['product_code'])){
            echo action_msg("您注册的条码已失效，请联系客服400-9665890",109);
            exit();
        }
        $info_product= product_info::getInfoByBarCode($user_info['product_code']);

        if(empty($info_product)){
            echo action_msg("您注册的条码所对应的产品未找到，请联系客服400-9665890",109);
            exit();
        }

        //user sxx
        //判断该产品是否正在报修

        $attrs=array();
        $attrs['unfinish']=1;
        $attrs['code']=$user_info['product_code'];
        $order_list=repair_order::getListByCode($attrs);
        if($order_list){
            echo action_msg("此用户正在报修，不能操作！",109);
            exit();
        }


        $brandName = "";
        $brand=Dict::getInfoById($info_product['brand']);
        if(isset($brand['name'])) {
            $brandName =  $brand['name'];
        }

        $guolu_attr = Smallguolu::getInfoById($info_product['version']);

        $guolu_version = "";
        if(isset( $guolu_attr['version'])){
            $guolu_version =  $guolu_attr['version'];
        }

        $attrs =array();
        $attrs =array(
            "bar_code" =>$user_info['product_code'],
            "phone" =>$linkPhone,
            "failure_cause" =>$result,
            "picture_url" =>$urlStr,
            "addtime" =>time(),
            "status" =>1,

            "register_person"=> $user_info['name'],
            "register_phone"=> $user_info['phone'],
            "child_status" => Table_repair_order::child_status11,
            "address_all"=> $user_info['contact_address'],
            "brand"=> "$brandName",
            "model"=> "$guolu_version",
            "service_type" => $server_type,
            "uid" => $id,
            "pay_num" =>Boiler_repair_order::getChargeNum()

        );


        $url_array = explode(",",$urlStr);
        $url_array = array_filter($url_array);
        foreach ($url_array as $item) {
            imageUpdatesize($FILE_PATH . $item,300,300,"s_");
        }

        $myOrderId = repair_order::add($attrs);

        //给订单流程表中添加下单成功的记录

        $rs = Boiler_order_process::add($myOrderId, Table_order_process::$OPERATION1,Table_order_process::$ORDER_STATUS1,"","","");


        $mypdo->pdo->commit();

        repair_order::postOrderToCustomer($attrs,$myOrderId);


        echo action_msg("下单成功",1);




    }catch(MyException $e){
        $mypdo->pdo->rollBack();
        $e->jsonMsg();
    }
}






?>