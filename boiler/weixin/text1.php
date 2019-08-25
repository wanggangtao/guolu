<!-- <!DOCTYPE html>
<html lang="en"> -->
<?php
require_once('admin_init.php');
 function postOrderToCustomer($params,$myOrderId){
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
       // $all_customer = weixin_customer::getList(array());

        $data = array(
            "register_person"=>$params["register_person"],
            "type"=>$service_name,
            "register_phone"=>$params["register_phone"],
            "link_phone"=>$params["phone"],
            "address"=>$params['address_all'],
        );
        $item['openid']='om86uuM-gDAt9g4ZrO4F-ogwjaDQ';
        $res =  $weixin-> send_user_by_template($myPath,$item['openid'],$data,$kf_template_id);
var_dump($res);
    }
 //测试第一个模板函数
 $params["register_person"]="www";
 $params["register_phone"]='12345678765';
 $params["phone"]='1234567';
 $params['address_all']="asdf";
 $params['service_type']='1';
 $myOrderId=3;
postOrderToCustomer($params,$myOrderId);

?>
