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

                while(true){
                    $index = 0;
                    $res =  $weixin-> send_user_by_template($myPath,$item['openid'],$data,$kf_template_id);
                    $res = json_decode($res, true);

                    if($res['errcode'] == 0 || $index >3){
                        break;
                    }
                    $index ++;
                    sleep(1);
                }

//                exit();
            }
        }

    }

    }
?>