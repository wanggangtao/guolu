<?php
/**
 * Created by PhpStorm.
 * User: sxx
 * Date: 2019/4/28
 * Time: 21:38
 */

require_once('admin_init.php');

if(isset($_POST['data'])){
    $data = $_POST['data'];
}else{
    $data = "";
}
if(isset($_POST['ids'])){
    $ids = $_POST['ids'];
}
//print_r($ids);

if(isset($data['page'])){
    unset($data['page']);
}
if(isset($data['pageSize'])){
    unset($data['pageSize']);
}


$rows = repair_order::getList($data);
//print_r($rows);
//if(empty($rows)){
//    echo action_msg("导出项为空",109);
//    exit();
//}
$resultHanding = array();
if(!empty($rows)){
    foreach ($rows as $row){
        $name = $row['register_person'];
        $phone = $row['phone'];
        $address_all = $row['address_all'];
        $bar_code = $row['bar_code'];
        $data = date("Y-m-d H:i:s", $row['addtime']);

        if ($row['child_status']==21) {
          $status="待接单";
        }elseif ($row['child_status']==22) {
          $status="已接单";
        }else{
          $status="待支付";
        }

        $service_type = "";
        if(isset($row['service_type'])){
            $service_info = Service_type::getInfoById($row['service_type']);
            if(isset($service_info['name'])){
                $service_type = $service_info['name'];
            }
        }

        $failure_cause = $row['failure_cause'];
        if(empty($failure_cause)){
            $failure_cause ="暂无";
        }

        $coupon_name = "暂无";
        if(isset($row['coupon_id'])){
            if($row['coupon_id'] == -1){
                $coupon_name = "暂无";
            }else{
                $coupon_info = Weixin_coupon::getInfoById($row['coupon_id']);
                if(isset($coupon_info['name'])){
                    $coupon_name = $coupon_info['name'];
                }
            }
        }
        $remarks = $row['remarks'];
        if(empty($remarks)){
            $remarks ="暂无";
        }
        $resultHanding [] = array($bar_code,$name ,$phone,$address_all,$service_type,$failure_cause,$data,$coupon_name,$remarks,$status);
    }
}

$resultHanding = array_merge($resultHanding);
//print_r($resultHanding);
//exit();

$date = date("Ymd");

$filepath_rel = 'userfiles/export/' . $date . '/';//相对路径

$filepath_abs = $FILE_PATH . $filepath_rel;//绝对路径

if (!file_exists($filepath_abs)) {
    mkdir($filepath_abs, 0777, true);
}

$filename = date('YmdHis').rand(1000,9999).".xls";

$title = array("条码","姓名","联系电话","联系地址","服务类型","故障描述","提交时间","优惠券","备注信息","订单状态");


Excelsolve::export($resultHanding,$title,$filepath_abs,$filename);

$filename=$filepath_rel.$filename;

echo action_msg("$filename",1);

?>
