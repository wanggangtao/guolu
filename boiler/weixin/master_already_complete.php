<?php
/**
 * Created by PhpStorm.
 * User: wanggangtao
 * 师傅端待接单
 * Date: 2019/8/15
 * Time: 17:35
 */

    require_once "admin_init.php";

if(isset($_GET['master_id'])){
    $master_id = safeCheck($_GET['master_id']);
}else{
    echo "未发现师傅的ID";
    exit;
}
//$master_id=9;

$orderList=Boiler_repair_order::getList_repair_master($master_id ,3,33);//使用师傅的ID的来获取维修记录
//var_dump($orderList);
echo "订单的编号".$orderList[0]["id"]."<br/>";
echo "服务的类型".$orderList[0]["service_type"]."<br/>";
echo "联系人".$orderList[0]["register_person"]."<br/>";
echo "联系电话".$orderList[0]["linkphone"]."<br/>";
echo "联系地址".$orderList[0]["address_all"]."<br/>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>待接单</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
    <script type="text/javascript">
        function  showTable(id,person){
            alert(id);//维修人员的id
            alert(person);
            location.href="master_complete_detail.php?id="+id+"&person="+person;
        }
    </script>
</head>
<body>
<a href="javascript:void(0)" onclick="showTable(<?php echo $orderList[0]["id"]?>,<?php echo $orderList[0]["person"]?>)">项目详情 </a>
</body>
</html>
