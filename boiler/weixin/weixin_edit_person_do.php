<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/27
 * Time: 19:52
 */
require_once ("admin_init.php");

try{
    $act = safeCheck($_POST['type'],0);
    $id = safeCheck($_POST['id'],1);
    switch ($act){
        case "name":
            $name = $_POST['name'];
            $attr = array(
                "name" =>$name
            );
            if(!empty($attr)){

                $rs = user_account::update($id,$attr);
                echo action_msg("修改成功",1);
            }else{
                echo action_msg("修改异常！",109);

            }
            break;
        case "tel":
            $phone = $_POST['phone'];
            $verifyCode = $_POST['verifyCode'];
            //            if(mobile_code::checkCode($phone,$verifyCode) ){
            if($verifyCode == "123456"){
                $attr = array(
                    "phone" =>$phone
                );
            }else{
                echo action_msg("验证码不正确",109);
                exit();
            }

            if(!empty($attr)){

                $rs = user_account::update($id,$attr);
                echo action_msg("修改成功",1);
            }else{
                echo action_msg("修改异常！",109);

            }
            break;
        case "code":

            $code = $_POST['inpt_code'];
            $nowcode = $_POST['nowcode'];
            if($code == $nowcode){
                echo action_msg("输入条码与当前条码一致",108);
                exit();
            }

            $now_repair = repair_order::getCountByCode(array('unfinish' => 1,'code' => $nowcode));
            if(!empty($now_repair)){
                echo action_msg("当前条码还存在未处理完成报修单不能修当前条码",108);
                exit();
            }

           $after_repair = repair_order::getCountByCode(array('unfinish' => 1,'code' => $code));
            if(!empty($after_repair)){
                echo action_msg("输入的条码还存在未处理完成报修单不能修改为该条码",108);
                exit();
            }

            $province_id = 0;
            $city_id = 0;
            $area_id = 0;
            $community_id = 0;
            $detail_addres = "";
            $contact_address = "";

            $code_info = user_account::getInfoByBarCode($code);
            if(isset($code_info['city_id']) and isset($code_info['city_id']) and isset($code_info['area_id']) and
                isset($code_info['community_id']) and isset($code_info['detail_addres'])and isset($code_info['contact_address'])) {

                $province_id = $code_info['province_id'];
                $city_id = $code_info['city_id'];
                $area_id =$code_info['area_id'];
                $community_id = $code_info['community_id'];
                $detail_addres =$code_info['detail_addres'];
                $contact_address = $code_info['contact_address'];
                user_account::updataBarCode($code);
            }

            $attr = array(
                "product_code" =>$code,
                "province_id" =>$province_id,
                "city_id" =>$city_id,
                "area_id" =>$area_id,
                "community_id" =>$community_id,
                "detail_addres" =>$detail_addres,
                "contact_address" =>$contact_address,
            );

            if(!empty($attr)){
                $rs = user_account::update($id,$attr);
                echo action_msg("修改成功",1);
            }else{
                echo action_msg("修改异常！",109);

            }
            break;

    }




}catch (MyException $e){

   $e->jsonMsg();

}