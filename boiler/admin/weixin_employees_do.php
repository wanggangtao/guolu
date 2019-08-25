<?php

	require_once('admin_init.php');
	require_once('admincheck.php');


	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'add'://添加小区

			$phone   =  safeCheck($_POST['phone'], 0);
			$name  =  safeCheck($_POST['name'], 0);
			$params=array();
            $params['phone']=$phone;
            $params['name']=$name;
            $params['addtime']=time();
            if(!ParamCheck::is_mobile($phone)){
                echo  action_msg("请输入正确的联系方式", 0);
                exit();
            }
            try {
                Repair_person::add($params);
                echo  action_msg("添加成功", 1);;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'edit'://编辑小区
            $id   =  safeCheck($_POST['id'], 0);
            $phone   =  safeCheck($_POST['phone'], 0);
            $name  =  safeCheck($_POST['name'], 0);
            if(!ParamCheck::is_mobile($phone)){
                echo  action_msg("请输入正确的联系方式", 0);
                exit();
            }
            $params=array();
            $params['phone']=$phone;
            $params['name']=$name;
//            $params['addtime']=time();
            try {
                Repair_person::update($id,$params);
                echo  action_msg("修改成功", 1);;
            }catch (MyException $e){
                echo $e->jsonMsg();
            }
            break;
            
		case 'del'://删除管理员
			$id = safeCheck($_POST['id']);

            try {
                Repair_person::dels($id);
                echo  action_msg("删除成功", 1);;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;


            

	}
?>