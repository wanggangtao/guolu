<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/3/11
 * Time: 12:29
 */

require_once('admin_init.php');
require_once('admincheck.php');



//上传附件
$level=$_POST['level'];

try {
    $fileElement = "file";
    $allowext = array( 'xls', 'xlsx',);

    $date = date("Ymdhmss");
    $filepath_rel = 'userfiles/upload/file/';//相对路径
    $filepath_abs = $FILE_PATH . $filepath_rel;//绝对路径

    if (!file_exists($filepath_abs)) {
        mkdir($filepath_abs, 0777, true);
    }

    $fup = new FileUpload('10M', $allowext);

    $res = $fup->upload($fileElement, $filepath_abs, '', true);
    $full_url = $filepath_abs. $res;

    $annex_url =stripslashes($filepath_rel . $res);
}catch (Exception $e){
    echo action_msg($e->getMessage(), $e->getCode());
    exit();
}
switch($level){
    case 1:

        $objPHPExcel = PHPExcel_IOFactory::load($full_url);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $i=1;
        $j=1;
       try{
           for ($row = 2; $row <= $highestRow; $row++) {

               $data = array();
               for ($col = 0; $col < $highestColumnIndex; $col++) {
                   $data[] = trim((string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
               }
               if (!empty($data) and $data) {
                   $j++;
                    if($data[0]){
                        $params=array();
                        $params['code']=$data[0];
                        $pro_info=Product_info::getInfoByBarCode($params['code']);
                        if($pro_info){
                            continue;
                        }

                        $brand=Dict::getInfoByName($data[1]);
                        $params['brand']=0;
                        if($brand){
                            $params['brand']=$brand['id'];
                        }
                        $version=Smallguolu::getInfoByName($data[2]);
                        $params['version']=0;
                        if($version){
                            $params['version']=$version['id'];
                        }
                        $params['period']="";
                        if($data[3]){
                            if(is_numeric($data[3])){
                                $params['period']=strval(strtotime(excelTime($data[3])));
                            }else{
                                $params['period']="过保";
                            }
                        }
                        $params['province_id']=0;
                        $params['city_id']=0;
                        $params['area_id']=0;
                        $params['community_id']=0;
//                       $params['detail_addres']=$commnunity['provice_id'];
                        $params['all_address']="";
                        if($data[4]){
                            $commnunity=Community::getInfoByName($data[4]);
                            if($commnunity){
                                $params['province_id']=$commnunity['provice_id'];
                                $params['city_id']=$commnunity['city_id'];
                                $params['area_id']=$commnunity['area_id'];
                                $params['community_id']=$commnunity['id'];
                                $params['period']=$commnunity['period'];
                                $params['all_address']=$commnunity['provice_name']." ".$commnunity['city_name']." ".$commnunity['area_name']." ".$commnunity['name'];
                            }

                        }
                        $params['detail_addres']="";
                        $params['all_address'].="";
                        if($data[5]){
                            $params['detail_addres']=$data[5];
                            $params['all_address'].=" ".$data[5];
                        }


                        $params['addtime']=time();
                        $i++;
                        Product_info::add($params);
                    }

               }
           }
           echo action_msg("共".($j-1)."条数据，导入成功".($i-1)."条", 1);
       }catch (Exception $e){
           echo action_msg('请重试！',118);
           return;
       }

        break;
    case 2 :
        $objPHPExcel = PHPExcel_IOFactory::load($full_url);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
//        $data = array();
        $i=1;
        $j=1;
        try{
            for ($row = 2; $row <= $highestRow; $row++) {
                $data = array();
                for ($col = 0; $col < $highestColumnIndex; $col++) {

                    $data[] = trim((string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue());

                }
                if (!empty($data) and $data) {
                    $j++;
                    if($data[0]){
                        $params=array();
                        $param=array();
                        $params['name']=$data[0];
                        $params['product_code']=$data[1] ;
                        $params['phone']=$data[2];
                        $params['province_id']=0;
                        $params['city_id']=0;
                        $params['area_id']=0;
                        $params['community_id']=0;
                        $param['period']="";
                        $params['contact_address']="";
                        if($data[3]){
                            $commnunity=Community::getInfoByName($data[3]);
                            if($commnunity){
                                $params['province_id']=$commnunity['provice_id'];
                                $params['city_id']=$commnunity['city_id'];
                                $params['area_id']=$commnunity['area_id'];
                                $params['community_id']=$commnunity['id'];
                                $param['period']=$commnunity['period'];
                                $params['contact_address']=$commnunity['provice_name']." ".$commnunity['city_name']." ".$commnunity['area_name']." ".$commnunity['name'];
                            }


                        }
                        $params['detail_addres']="";
                        $params['contact_address'].="";
                        if($data[4]){
                            $params['detail_addres']=$data[4];
                            $params['contact_address'].=" ".$data[4];
                        }



                        $pro=Product_info::getInfoByBarCode($data[1]);
                        if(empty($pro)){
                            continue;
                        }
                        $user_pro=User_account::getInfoByBarCode($data[1]);
                        if($user_pro){
                            continue;
                        }
                        $param['province_id']=$params['province_id'];
                        $param['city_id']=$params['city_id'];
                        $param['area_id']=$params['area_id'];
                        $param['community_id']=$params['community_id'];
                        $param['detail_addres']=$params['detail_addres'];
                        $param['all_address']=$params['contact_address'];

                        Product_info::updateByCode($params['product_code'],$param);

//                        User_account::updataBarCode($params['product_code']);
                        $i++;
                        User_account::add($params);

                    }
                }
            }
            echo action_msg("共".($j-1)."条数据，导入成功".($i-1)."条", 1);
        }catch (Exception $e){
            echo action_msg("数据存在",118);
            return;
        }

        break;
}

function excelTime($date, $time = false) {
    if(function_exists('GregorianToJD')){
        if (is_numeric( $date )) {
            $jd = GregorianToJD( 1, 1, 1970 );
            $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );
            $date = explode( '/', $gregorian );
            $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
                . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    }else{
        $date=$date>25568?$date+1:25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs=(70 * 365 + 17+2) * 86400;
        $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
    }
    return $date;
}
?>