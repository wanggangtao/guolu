<?php

require_once('admin_init.php');
require_once('admincheck.php');


$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add':
        $name  =  safeCheck($_POST['name'], 0);
        $params=array();
        $params['name']=$name;
        $params['add_time']=time();
        try {
            Service_type::add($params);
            echo  action_msg("添加成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit':
        $id   =  safeCheck($_POST['id'], 0);
        $name  =  safeCheck($_POST['name'], 0);
        $params=array();
        $params['name']=$name;
        $params['update_time']=time();
        try {
            Service_type::update($id,$params);
            echo  action_msg("修改成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'del':
        $id = safeCheck($_POST['id']);

        try {
            Service_type::dels($id);
            echo  action_msg("删除成功", 1);;
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'order':
        $code = 0;
        $list=$_POST['list'];
        foreach ($list as $value) {
            $attrs = array(
                "sort"=>$value['sort']
            );
            $rs = Service_type::update($value['id'], $attrs);
            if($rs>=0){
                $code=1;
            }

        }

        if($code == 1){
            echo action_msg('更新成功', 1);
        }else{
            echo action_msg('更新失败', 101);
        }
        break;



}
?>