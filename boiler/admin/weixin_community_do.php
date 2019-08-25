<?php

require_once('admin_init.php');
require_once('admincheck.php');


$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加小区

        $brand   =  safeCheck($_POST['brand'], 0);
        $name  =  safeCheck($_POST['name'], 0);
        $province_id     =  safeCheck($_POST['province_id'],0);
        $city_id   =  safeCheck($_POST['city_id'], 0);
        $area_id      =  safeCheck($_POST['area_id'],0);
        $province_name     =  safeCheck($_POST['province_name'],0);
        $city_name   =  safeCheck($_POST['city_name'], 0);
        $area_name      =  safeCheck($_POST['area_name'],0);
        $period      =  safeCheck($_POST['period'],0);
        if($period and $period!="过保"){
            $period=strval(strtotime($period));
        }
        $params=array();
        $params['brand']=$brand;
        $params['name']=$name;
        $params['provice_id']=$province_id;
        $params['city_id']=$city_id;
        $params['area_id']=$area_id;
        $params['provice_name']=$province_name ;
        $params['city_name']=$city_name;
        $params['area_name']=$area_name;
        $params['first_charter']=getFirstCharter($name);
        $params['period']       = $period;

        $param=array();
        $param['period']       = $period;
        try {
            $community_info=Community::getInfoByName($name);
            if($community_info){
                echo  action_msg("添加失败，该小区存在", 0);
                exit();
            }

            $c_id=Community::add($params);
            Product_info::updateByCommunity($c_id,$param);
            echo  action_msg("添加成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit'://编辑小区
        $id   =  safeCheck($_POST['id'], 0);
        $brand   =  safeCheck($_POST['brand'], 0);
        $name  =  safeCheck($_POST['name'], 0);
        $province_id     =  safeCheck($_POST['province_id'],0);
        $city_id   =  safeCheck($_POST['city_id'], 0);
        $area_id      =  safeCheck($_POST['area_id'],0);
        $province_name     =  safeCheck($_POST['province_name'],0);
        $city_name   =  safeCheck($_POST['city_name'], 0);
        $area_name      =  safeCheck($_POST['area_name'],0);
        $period      =  safeCheck($_POST['period'],0);
        if($period and $period!="过保"){
            $period=strval(strtotime($period));
        }
        $params=array();
        $params['brand']=$brand;
        $params['name']=$name;
        $params['provice_id']=$province_id;
        $params['city_id']=$city_id;
        $params['area_id']=$area_id;
        $params['provice_name']=$province_name ;
        $params['city_name']=$city_name;
        $params['area_name']=$area_name;
        $params['first_charter']=getFirstCharter($name);
        $params['period']       = $period;
        $param=array();
        $param['period']       = $period;
        try {
            $community_info=Community::getInfoByName($name);
            if($community_info){
                if($community_info['id']!=$id){
                    echo  action_msg("修改失败，该小区存在", 0);
                    exit();
                }

            }
            Community::update($id,$params);
            Product_info::updateByCommunity($id,$param);
            echo  action_msg("修改成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del'://删除管理员
        $id = safeCheck($_POST['id']);

        try {
            Community::dels($id);
            echo  action_msg("删除成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'getCommunity'://得到小区
        $id   =  safeCheck($_POST['id'], 0);
        $params=array();
        $params['area_id']=$id;
        $params["type"]=-1;
        try {
            $list=Community::getList($params);
            echo  action_msg($list, 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'edittype'://删除管理员
        $id = safeCheck($_POST['id']);

        try {
            Community::updateType($id);
            echo  action_msg("修改成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

}
?>