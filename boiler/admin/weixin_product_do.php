<?php

	require_once('admin_init.php');
	require_once('admincheck.php');


	$act = safeCheck($_GET['act'], 0);
	switch($act){
        case 'get_version'://
            $id   =  safeCheck($_POST['id'], 0);
//            $type   =  safeCheck($_POST['type'], 0);
            try {
                //$list=Guolu_attr::getList(0, 0, 0, $id, $type, 0, 0);
                $list=Smallguolu::getList(0,0,$id);
//                $list=Guolu_attr::getList(0, 0, 0, $id,  0, 0,0);
//                print_r($list);
                echo  action_msg($list, 1);;
            }catch (MyException $e){
                echo $e->jsonMsg();
            }
            break;
		case 'add'://添加产品

			$brand  =  safeCheck($_POST['brand'], 0);
			$code  =  safeCheck($_POST['code'], 0);
//			$type =  safeCheck($_POST['type'], 0);
			$version  =  safeCheck($_POST['version'], 0);
//            $period  =  safeCheck($_POST['period'], 0);
			$params=array();
            $params['brand']=$brand;
            $params['code']=$code;
            $params['type']=0;
            $params['version']=$version;
//            if($period){
//                $params['period']=strval(strtotime($period));
//            }
//            print_r($params);
//            exit;
            $params['addtime']=time();
            try {
                $pro_info=Product_info::getInfoByBarCode($code);
                if($pro_info){
                    echo  action_msg("此条码已经添加，请勿重复操作", 0);
                    exit();
                }
                Product_info::add($params);
                echo  action_msg("添加成功", 1);;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'edit'://编辑
            $id   =  safeCheck($_POST['id'], 0);
            $brand  =  safeCheck($_POST['brand'], 0);
            $code  =  safeCheck($_POST['code'], 0);
//            $type =  safeCheck($_POST['type'], 0);
            $version  =  safeCheck($_POST['version'], 0);
            $period  =  safeCheck($_POST['period'], 0);
            $params=array();
            $params['brand']=$brand;
            $params['code']=$code;
            $params['type']=0;
            $params['version']=$version;
            if($period and $period!="过保"){
                $period=strval(strtotime($period));
            }
            $params['period']=$period;
            try {
                $pro_info=Product_info::getInfoByBarCode($code);
                if($pro_info){
                    if($pro_info['id']!=$id){
                        echo  action_msg("此条码已经添加，请勿重复操作", 0);
                        exit();
                    }
                }
                $product=Product_info::getInfoById($id);
                if($product) {
                    $user_info = User_account::getInfoByBarCode($product['code']);
                }
                $attrs=array();
                if($user_info){
                        $attrs['product_code']=$code;
                        User_account::update($user_info['id'],$attrs);
                    }
                Product_info::update($id,$params);
                echo  action_msg("修改成功", 1);;
            }catch (MyException $e){
                echo $e->jsonMsg();
            }
            break;
            
		case 'del'://删除
			$id = safeCheck($_POST['id']);
            try {
                $pro_info=Product_info::getInfoById($id);
                if($pro_info){
                    $msg="删除成功";
                    $params=array();
                    $code=$pro_info['code'];
                    $params['unfinish']=1;
                    $params['code']=$code;
                    $order_list=repair_order::getListByCode($params);
                    if($order_list){
                        $msg="此条码处于报修状态，不能操作！";
                        echo  action_msg($msg, 2);
                        exit();
                    }else{
                        Product_info::dels($id);
                        echo  action_msg($msg, 1);
                        exit();
                    }
                }


			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;

        case 'code_info':
            try{
                $code = $_POST['code'];
                $product_info = product_info::getInfoByBarCode($code);

                if($product_info){
                    echo action_msg("此条码已经添加，请勿重复操作",0);
                    exit();
                }else{
                    $attrs=array();
                    $attrs['unfinish']=1;
                    $attrs['code']=$code;
                    $order_list=repair_order::getListByCode($attrs);
                    if($order_list){
                        $msg="此条码处于报修状态，不能操作！";
                        echo  action_msg($msg, 2);
                        exit();
                    }
                    echo action_msg($product_info,1);
                }


            }catch(MyException $e){
                echo $e->jsonMsg();

            }

            break;
	}
?>