<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/16
 * Time: 12:29
 */


require_once('admin_init.php');
require_once('admincheck.php');



//上传附件

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

    $objPHPExcel = PHPExcel_IOFactory::load($full_url);

    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
//        $data = array();
    $i=1;
    $j=1;


    $no_code_array = "";
    for ($row = 2; $row <= $highestRow; $row++) {
        $data = array();
        for ($col = 0; $col < $highestColumnIndex; $col++) {

            $data[] = trim((string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue());

        }
//            print_r($data);
        $solutions = -1;
        $server_type = Service_type::getInfoByName($data[2]);
        if(isset($server_type['id'])){
            $type_id = $server_type['id'];
        }else{
            $type_id = -1;
        }
        if($data[10] === "上门服务"){
            $solutions = 1;
        }elseif($data[10] === "电话服务"){
        $solutions = 2;
        }

        $info_product= product_info::getInfoByBarCode($data[1]);
        if(empty($info_product)){
            $no_code_array .= " ".$data[1];
            continue;
        }

        $brandName = "";
        $brand=Dict::getInfoById($info_product['brand']);
        if(isset($brand['name'])) {
            $brandName =  $brand['name'];
        }

        $guolu_attr = Smallguolu::getInfoById($info_product['version']);

        $guolu_version = "";
        if(isset( $guolu_attr['version'])){
            $guolu_version =  $guolu_attr['version'];
        }
        $repirName = "";
        $repirInfo = Repair_person::getIdByLikeName($data[8]);

        if(isset($repirInfo[0]['repair_id'])){
           $repirId =  $repirInfo[0]['repair_id'];
        }else{
            $repirId = -1;
        }

        if(!empty($data[7])){
            $aadtime = strtotime(str_replace('.','-',$data[7]));
        }else{
            $aadtime = 0;
        }
        if(!empty($data[12])){
            $finish_time = strtotime(str_replace('.','-',$data[12]));
        }else{
            $finish_time = 0;
        }
        if(empty($data[4])){
            $register_phone = $data[5];
        }else{
            $register_phone = $data[4];
        }

        $attrs =array(
            "bar_code" =>$data[1],
            "phone" =>$data[5],
            "addtime" =>$aadtime,
            "register_person"=> $data[3],
            "register_phone"=> $register_phone,
            "address_all"=> $data[6],
            "brand"=> $brandName,
            "model"=> $guolu_version,
            "service_type" => $type_id ,
            "status" => 3,
            "remarks" => $data[13],
            "result" =>$data[11],
            "finish_time" => $finish_time,
            "person" => $repirId,
            "linkphone" =>$data[9],
            "solutions" => $solutions,
            "coupon_id" => -1,

        );
//        print_r($attrs);

        repair_order::add($attrs);
//        exit();
        $j ++;
        $i ++;

    }
    echo action_msg("共".($j-1)."条数据，导入成功".($i-1)."条 未发现产品条码：".$no_code_array, 1);


?>