<?php

	require_once('admin_init.php');
	require_once('admincheck.php');

    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('PRC');
	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'add'://添加用户

			$name   =  safeCheck($_POST['name'], 0);
			$phone  =  safeCheck($_POST['phone'], 0);
			$code     =  safeCheck($_POST['code'],0);
			$province_id      =  safeCheck($_POST['province_id'],0);
			$city_id   =  safeCheck($_POST['city_id'], 0);
			$area_id      =  safeCheck($_POST['area_id'],0);
			$community_id     =  safeCheck($_POST['community_id'],0);
            $address     =  safeCheck($_POST['address'],0);
            $contact_address     =  safeCheck($_POST['contact_address'],0);

            $user_now = User_account::getInfoByPhone($phone);
            if(!empty($user_now)){
                echo  action_msg("该手机号码已被注册", 109);
                exit();
            }

            if(!ParamCheck::is_mobile($phone)){
                echo  action_msg("请输入正确的联系方式", 0);
                exit();
            }
            $attrs=array();
            $attrs['unfinish']=1;
            $attrs['code']=$code;
            $order_list=repair_order::getListByCode($attrs);
            if($order_list){
                $msg="此条码处于报修状态，不能操作！";
                echo  action_msg($msg, 2);
                exit();
            }
			$params=array();
            $param=array();
            $params['name']=$name;
            $params['phone']=$phone;
            $params['province_id']=$province_id;
            $params['city_id']=$city_id;
            $params['area_id']=$area_id;
            $params['product_code']=$code ;
            $params['community_id']=$community_id;
            $params['detail_addres']=$address;
            $params['contact_address']=$contact_address;
            $params['addtime']=time();

            $param['province_id']=$province_id;
            $param['city_id']=$city_id;
            $param['area_id']=$area_id;
            $param['community_id']=$community_id;
            $param['detail_addres']=$address;
            $param['all_address']=$contact_address;
            try {
                $pro=Product_info::getInfoByBarCode($code);
                if(empty($pro)){
                    echo action_msg("未搜到该条码对应的产品信息，请先在产品管理中录入产品信息",0);
                    exit();
                }
                $cinfo=Community::getInfoById($community_id);
                if($cinfo){
                    $param['period']=$cinfo['period'];
                }
                Product_info::updateByCode($code,$param);
                User_account::updataBarCode($code);
                User_account::add($params);
                echo  action_msg("添加成功", 1);;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'edit'://编辑小区
            $id   =  safeCheck($_POST['id'], 0);
            $name   =  safeCheck($_POST['name'], 0);
            $phone  =  safeCheck($_POST['phone'], 0);
            $code     =  safeCheck($_POST['code'],0);
            $province_id      =  safeCheck($_POST['province_id'],0);
            $city_id   =  safeCheck($_POST['city_id'], 0);
            $area_id      =  safeCheck($_POST['area_id'],0);
            $community_id     =  safeCheck($_POST['community_id'],0);
            $address     =  safeCheck($_POST['address'],0);
            $contact_address     =  safeCheck($_POST['contact_address'],0);
            if(!ParamCheck::is_mobile($phone)){
                echo  action_msg("请输入正确的联系方式", 0);
                exit();
            }
            $attrs=array();
            $attrs['unfinish']=1;
            $attrs['code']=$code;
            $order_list=repair_order::getListByCode($attrs);
            if($order_list){
                $msg="此条码处于报修状态，不能操作！";
                echo  action_msg($msg, 2);
                exit();
            }
            $params=array();

            $param=array();
            $params['name']=$name;
            $params['phone']=$phone;
            $params['province_id']=$province_id;
            $params['city_id']=$city_id;
            $params['area_id']=$area_id;
            $params['product_code']=$code ;
            $params['community_id ']=$community_id;
            $params['detail_addres']=$address;
            $params['contact_address']=$contact_address;
            $params['addtime']=time();

            $param['province_id']=$province_id;
            $param['city_id']=$city_id;
            $param['area_id']=$area_id;
            $param['community_id']=$community_id;
            $param['detail_addres']=$address;
            $param['all_address']=$contact_address;
            try {
                $pro=Product_info::getInfoByBarCode($code);
                if(empty($pro)){
                    echo action_msg("未搜到该条码对应的产品信息，请先在产品管理中录入产品信息",0);
                    exit();
                }
                $cinfo=Community::getInfoById($community_id);
                if($cinfo){
                    $param['period']=$cinfo['period'];
                }
                Product_info::updateByCode($code,$param);
                User_account::updataBarCode($code);
                User_account::update($id,$params);
                echo  action_msg("修改成功", 1);;
            }catch (MyException $e){
                echo $e->jsonMsg();
            }
            break;
            
		case 'del'://删除管理员
			$id = safeCheck($_POST['id']);
            try {
                $user_info=User_account::getInfoById($id);
                if($user_info){
                    $msg="删除成功";
                    $params=array();
                    if($user_info['product_code']){
                        $code=$user_info['product_code'];
                        $params['unfinish']=1;
                        $params['code']=$code;
                        $order_list=repair_order::getListByCode($params);
                        if($order_list){
                            $msg="此条码处于报修状态，不能操作！";
                            echo  action_msg($msg, 2);
                            exit();
                        }else{
                            User_account::dels($id);
                            echo  action_msg($msg, 1);
                            exit();
                        }
                    }else{
                        User_account::dels($id);
                        echo  action_msg($msg, 1);
                        exit();
                    }

                }

            }catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;

        case 'export':

            $table = array();
            $table[0] = ["id", "姓名","昵称", "联系电话", "联系地址", "条码","品牌","型号"];
            try {

                $content_list = User_account::getList();
                $i = 1;
                if (!empty($content_list)) {
                    foreach ($content_list as $value) {

                        $table[$i]['id'] = " ".$i;
                        $table[$i]['name'] = $value['name'];
                        $table[$i]['nickname'] = $value['nickname'];
                        $table[$i]['phone'] = " ".$value['phone'];
                        $table[$i]['contact_address'] = $value['contact_address'];

                        $code="暂无";
                        $brand="暂无";
                        $version="暂无";
                        if($value['product_code']){
                            $code=$value['product_code'];
                            $pro_info=Product_info::getInfoByBarCode($value['product_code']);
                            if($pro_info){
                                $brand=Dict::getInfoById($pro_info['brand'])['name'];
                                $temp = Smallguolu::getInfoById($pro_info['version']);
                                if($temp != null)
                                {
                                    $version=$temp['version'];
                                }
                            }
                        }

                        $table[$i]['product_code'] = ' '.$code;
                        $table[$i]['brand'] = $brand;
                        $table[$i]['version'] = $version;
                        $i++;

                    }
                }
                $filename = date("YmdHis") . ".xlsx";
                to_excle($table);
                echo action_msg("导出成功,$filename", 1);
            } catch (Exception $e) {
                echo action_msg("导出失败,$filename", 2);
            }
            break;

        case 'code':
            try{
                $code = $_POST['code'];
                $product_info = product_info::getInfoLikeCode($code);
                if(empty($product_info)){
                    echo action_msg("查询无此条码",109);
                }else{
                    echo action_msg($product_info,1);
                }

            }catch(MyException $e){
                echo $e->jsonMsg();

            }

        break;

        case 'code_info':
            try{
                $code = $_POST['code'];
                $product_info = product_info::getInfoByBarCode($code);

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

                }else{
                    echo action_msg("未搜到该条码对应的产品信息，请先在产品管理中录入产品信息",0);
                    exit();
                }


            }catch(MyException $e){
                echo $e->jsonMsg();

            }

            break;

    }
function to_excle($table){
    global $FILE_PATH;
    global $filename;
    $arr = array(0 => "A", 1 => "B", 2 => "C", 3 => "D", 4 => "E", 5 => "F", 6 => "G", 7 => "H", 8 => "I", 9 => "J", 10 => "K", 11 => "L", 12 => "M", 13 => "N", 14 => "O", 15 => "P", 16 => "Q", 17 => "R");
    $i = 1;
    $objPHPExcel = new PHPExcel();
    $objActSheet = $objPHPExcel->getActiveSheet();
    $width_array = array('A' => 7.25,'B'=>20, 'C' => 20, 'D' => 50, 'E' => 20, 'F' => 20, 'G' => 20, 'H' => 20);//列宽度
    foreach ($width_array as $k => $v) {
        $objActSheet->getColumnDimension($k)->setWidth($v);
    }
    foreach ($table as $ary) {
        $j = 0;
        foreach ($ary as $value) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($arr[$j] . $i, $value);
            $j++;
        }
        $i++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $date = date("Ymd");
    $filepath_rel = 'userfiles/upload/' . $date . '/';//相对路径
    $filepath_abs = $FILE_PATH . $filepath_rel;//绝对路径
    if (!file_exists($filepath_abs)) {
        mkdir($filepath_abs, 0777, true);
    }
    $objWriter->save($filepath_abs . $filename);
}
?>