<?php
/**
 * Created by PhpStorm.
 * User: sxx
 * Date: 2019/8/23
 * Time: 18:15
 */

require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);

switch($act) {
    

    case 'herf1':
            try{

              $id = safeCheck($_GET['id'], 0);
            
              $row = repair_order::getInfoById($id);
              $child_status = $row['child_status'];
              if ($child_status==23) {
               header("Location:weixin_repair_notchange.php"."?id=$id");
               exit();

                } 
                header("Location:weixin_repair_untreated_detail.php"."?id=$id"); 

            }catch(MyException $e){

                 echo $e->jsonMsg();

             }

            break;
    case 'herf':
            try{

              $id = safeCheck($_GET['id'], 0);
            
              $row = repair_order::getInfoById($id);
              $child_status = $row['child_status'];
              if ($child_status==31) {
               header("Location:weixin_repair_cancel.php"."?id=$id");
               exit();

                } 
                header("Location:weixin_repair_notchange.php"."?id=$id"); 

            }catch(MyException $e){

                 echo $e->jsonMsg();

             }

            break;


    case 'phone_info':
            try{
                $phone = $_POST['phone'];
                $data = User_account::getInfoByPhone($phone);//通过注册电话拿到用户注册表信息
                //$brand=Dict::getInfoById($row['brand'])['name'];
                
                if ($data) {
                $name= $data['name'];//用户注册名
                $code= $data['product_code'];//用户注册的产品条码


                $product_info = product_info::getInfoByBarCode($code);//用产品条码拿到产品注册表信息

                $product_info['name']=$name;//将用户名添加到数组$product_info中


                //通过产品注册信息中的锅炉品牌id去 地址信息表中获取锅炉品牌名称
                $brand= $product_info['brand'];
                $brands=Dict::getInfoById($brand)['name'];
                $product_info['brands']=$brands;


                //通过产品注册信息中的锅炉型号id去锅炉型号表中获取型号名称
                $version= $product_info['version'];
                $guolu_version=Smallguolu::getInfoById($version);
                $versions="无";
                if($guolu_version){
                    $versions=$guolu_version['version'];
                 }
                 $product_info['versions']=$versions;
                   

                //通过产品注册信息中的锅炉地址id去地址表中获取相对应的地址信息   
                if($product_info){
                    $product_info['province_name']="请选择省份";
                    $product_info['city_name']="请选择市";
                    $product_info['area_name']="请选择区";
                    $product_info['community_name']="请选择小区";
                    if($product_info['province_id']){
                        $product_info['province_name']=Table_district::getInfoById($product_info['province_id'])['name'];
                    }
                    if($product_info['city_id']){
                        $product_info['city_name']=Table_district::getInfoById($product_info['city_id'])['name'];
                    }
                    if($product_info['area_id']){
                        $product_info['area_name']=Table_district::getInfoById($product_info['area_id'])['name'];
                    }
                    if($product_info['community_id'] and $product_info['community_id']!=566 ){
                        if($product_info['community_id']==-1 ){
                            $product_info['community_name']="其他";
                        }else{
                            $product_info['community_name']=Community::getInfoById($product_info['community_id'])['name'];
                        }

                    }

                    //判断用户注册电话是否在保修订单表中（判断是否正在保修）
                    $attrs=array();
                    $attrs['unfinish']=1;
                    $attrs['code']=$code;
                    $order_list=repair_order::getListByCode($attrs);
                    if($order_list){
                        $msg="此用户正在报修，不能操作！";
                        echo  action_msg($msg, 2);
                        exit();
                    }

                    echo action_msg($product_info,1);

                 }
                 }else{
                    echo action_msg("用户未注册，请先注册",0);
                     exit();
                 }


            }catch(MyException $e){

                 echo $e->jsonMsg();

             }

            break;

            case 'person_info':
            try{

                $service_person1 = $_POST['service_person1'];
                $phone = repair_person::getPhoneByLikeName($service_person1);
                echo action_msg($phone,1);
 

            }catch(MyException $e){

                 echo $e->jsonMsg();

             }

            break;



    case 'del':

        $id = safeCheck($_POST['id']);

        try {
            $rs = repair_order::del($id);
            echo action_msg("删除成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'add':

        $status= safeCheck($_POST['status'],0);
        $name = safeCheck($_POST['name'],0);
        $WXphone = safeCheck($_POST['WXphone'],0);
        $model = safeCheck($_POST['model'],0);
        $brand = safeCheck($_POST['brand'],0);
        $phone = safeCheck($_POST['phone'],0);
        $code = safeCheck($_POST['code'],0);
        $service_type = safeCheck($_POST['service_type'],0);
        $failure_cause = safeCheck($_POST['failure_cause'],0);
        $child_status = safeCheck($_POST['child_status'],0);
        $contact_address = safeCheck($_POST['contact_address'],0);
        $Num= Boiler_repair_order::getChargeNum();
        $row = User_account::getInfoByPhone($phone);
        $uid = $row['id'];
        try {
                $attrs = array();
                $attrs['register_person'] = $name;
                $attrs['status'] = $status;
                $attrs['phone'] = $WXphone;
                $attrs['model'] = $model;
                $attrs['brand'] = $brand;
                $attrs['register_phone'] = $phone;
                $attrs['bar_code'] = $code;
                $attrs['service_type'] = $service_type;
                $attrs['failure_cause'] = $failure_cause;
                $attrs['child_status'] = $child_status;
                $attrs['address_all'] = $contact_address;
                $attrs['addtime']=time();
                $attrs['pay_num']=$Num;
                $attrs['uid']=$uid;
                

                

                $rs = repair_order::add($attrs);
                


                //添加流程记录
                $row = repair_order::getInfoByCode($code,$status);
                $order_id = $row['0']['id'];
                $operation=1;
                $order_status=$row['0']['child_status'];
                // $order_id='23'; 
                // $operation="2";
                // $order_status='3';
                 $service_person='0';
                 $person_phone='0';
                 $person_reason='0';

                 $rs = Boiler_order_process::add($order_id, $operation,$order_status,$service_person,$person_phone,$person_reason);

                 echo action_msg("添加成功",1);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }

        break;



    case 'edit':

        $status= safeCheck($_POST['status']);
        $remarks = safeCheck($_POST['remarks'],0);
        $repair_check = safeCheck($_POST['repair_check']);
        $linkPone = safeCheck($_POST['linkPone'],0);
        $id = safeCheck($_POST['id']);
        $phone = safeCheck($_POST['phone'],0);
        $content = safeCheck($_POST['content'],0);
        $result = safeCheck($_POST['result'],0);
        $finish_time = safeCheck($_POST['finish_time'],0);
        $repair_status = safeCheck($_POST['repair_status'],1);
        $solve_type = safeCheck($_POST['solve_type'],0);
        $coupon_id = safeCheck($_POST['coupon_id'],1);
        $uid = safeCheck($_POST['uid'],1);

        try {
            $attrs = array(
                "status" => $status,
                "remarks" => $remarks,
                "phone"=>$phone,
            );
            if($status == 2){
                $attrs['linkphone'] = $linkPone;
                $attrs['person'] = $repair_check;

            }else if ($status == 3){
                $attrs['linkphone'] = $linkPone;
                $attrs['person'] = $repair_check;
                $attrs['content'] = $content;
                $attrs['result'] = $result;
                $attrs['finish_time'] = strtotime($finish_time) ? strtotime($finish_time) : 0;
                $attrs['solutions'] = $solve_type;
                if($solve_type == 2){
                    if($coupon_id != -1){
                        $my_coupon =  Weixin_user_coupon::getInfoByCidAndUid($uid,$coupon_id);
                        if(!empty($my_coupon)){
                            Weixin_user_coupon::update($my_coupon['id'] , array("status" =>1));
                        }
                    }
                }
            }
//            print_r($attrs);
//            exit();
            $rs = repair_order::update($id,$attrs);
            echo action_msg("提交成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }

        break;
    case 'select_repair':
        $repair = safeCheck($_POST['repair_id']);

        if($repair == -1){
            echo action_msg("",1);
            exit();
        }
        try {
            $rs = repair_person::getInfoById($repair);
            if(isset($rs['phone'])){
                echo action_msg($rs['phone'],1);
            }else{
                echo action_msg("",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }

        break;
    /**
     * reedit 派单按钮
     * @author zhh_fu
     */
    case 'reedit':

        $id = safeCheck($_POST['id'],0);
        $remarks = safeCheck($_POST['remarks'],0);
        $service_phone = safeCheck($_POST['service_phone'],0);
        $service_type = safeCheck($_POST['service_type'],0);
        $service_person = safeCheck($_POST['service_person'],0);
        $persons = Repair_person::getIdByLikeName($service_person);
        $person = $persons['0']['repair_id'];
        $solutions = 0 ;

        if (!empty($service_type)){
            if ($service_type == "上门服务"){
                $solutions = 1;
        try{
            $attrs = array(
                "solutions" => $solutions,
                "remarks" => $remarks,
                "linkphone"=>$service_phone,
                "person"=>$person,
                "status"=>2,
                "child_status"=>21,
                "treating_time"=>time()
            );
        //print_r($attrs);
               $rs=repair_order::update($id,$attrs);//派单
            

               $row = repair_order::getInfoById($id);//添加流程记录

                $order_id = $row['id'];
                $operation=2;
                $order_status=$row['child_status'];
                //$service_person=$service_person;
                $person_phone=$service_phone;
                $person_reason='0';

                $rs = Boiler_order_process::add($order_id, $operation,$order_status,$service_person,$person_phone,$person_reason);

                $r = repair_order::postOrderToCustomers($id);//推送派单消息给用户微信

                 echo action_msg("派单成功",1);

        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        }
            
             if ($service_type == "电话服务"){
                 $solutions = 2;
        try{
            $attrs = array(
                "solutions" => $solutions,
                "remarks" => $remarks,
                "linkphone"=>$service_phone,
                "person"=>$person,
                "status"=>3,
                "child_status"=>32,
                "treating_time"=>time()
            );
        //print_r($attrs);
            $rs=repair_order::update($id,$attrs);
            

            $row = repair_order::getInfoById($id);

                $order_id = $row['id'];
                $operation=10;
                $order_status=$row['child_status'];

                //$service_person=$service_person;
                 $person_phone=$service_phone;
                 $person_reason='0';

                 $rs = Boiler_order_process::add($order_id, $operation,$order_status,$service_person,$person_phone,$person_reason);

                 echo action_msg("派单成功",1);

        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        }
        $param = array();
        $show_info = repair_order::getInfoById($id);
        $param["register_person"] = $show_info['register_person'];
        $param['service_type'] = $show_info['service_type'];
        $param["register_phone"] = $show_info['register_phone'];
        $param["phone"] = $show_info['phone'];
        $param['address_all'] = $show_info['address_all'];
        repair_order::postOrderToCustomer($param,$id);
    }
        break;

    /**
     * retreat 重派按钮
     * @author zhh_fu
     */
    case 'retreat':
        $id = safeCheck($_POST['id']);
        $service_phone = safeCheck($_POST['service_phone']);
        $service_person = safeCheck($_POST['service_person'],0);
        $persons = Repair_person::getIdByLikeName($service_person);
        $person = $persons['0']['repair_id'];

        try{
            $attrs = array(
                "linkphone"=>$service_phone,
                "person"=>$person,
                "status"=>2,
                "child_status"=>21
            );
            $rs=repair_order::update($id,$attrs);
            

             $row = repair_order::getInfoById($id);
                 $order_id = $row['id'];
                 $operation=9;
                 $order_status=$row['child_status'];

                 //$service_person=$service_person;
                  $person_phone=$service_phone;
                  $person_reason='0';

                  $rs = Boiler_order_process::add($order_id, $operation,$order_status,$service_person,$person_phone,$person_reason);

                  echo action_msg("重派单成功",1);
            
        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        break;

    /**
     * verify 审核按钮
     * @author zhh_fu
     */
    case 'verify':
        $id = safeCheck($_POST['id']);
        $user_feedback = safeCheck($_POST['user_feedback']);
        $feedback = safeCheck($_POST['feedback'],0);

        try{
            $attr = array(
                "id"=> $id,
                "client_satisfy" => $user_feedback,
                "client_evaluation" => $feedback,
                "child_status" => 33,
                "verify_time"=>time()
            );
            $rs = repair_order::update($id,$attr);
            $order = repair_order::getInfoById($id);
            $service_person = $order['person'];
            $person_name = Repair_person::getInfoById($service_person);
            $person_phone = $order['linkphone'];
            $person_reason =  null;
            Boiler_order_process::add($id,6,33,$person_name,$person_phone,$person_reason);
            echo action_msg("审核成功",1);
        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        break;

    /**
     * cancel 取消按钮
     * @author zhh_fu
     */
    case 'cancel':
        $id = safeCheck($_POST['id']);
        try{
            $attrs = array(
                "status"=> 3,
                "child_status"=> 31
            );
            $rs=repair_order::update($id,$attrs);
            

            $row = repair_order::getInfoById($id);
                $order_id = $row['id'];
                $operation=7;
                $order_status=$row['child_status'];

                 $service_person='0';
                 $person_phone='0';
                 $person_reason='0';

                 $rs = Boiler_order_process::add($order_id, $operation,$order_status,$service_person,$person_phone,$person_reason);

                 echo action_msg("取消成功",1);
                 
        }
        catch (MyException $ex){
            echo $ex->jsonMsg();
        }
        break;
}

