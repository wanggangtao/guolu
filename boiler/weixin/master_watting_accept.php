<?php
/**
 * Created by PhpStorm.
 * User: wanggangtao
 * 师傅端待接单
 * Date: 2019/8/15
 * Time: 17:35
 */
    require_once "admin_init.php";

if($_GET['master_id']){
    $master_id = safeCheck($_GET['master_id'],1);

}else{
    echo "未发现师傅的ID";
    exit;
}
/**** 这里将师傅的id写死了，为了便于调试，在上传代码的时候，将上边的打开，下边的关闭 */
//$master_id=9;
$orderList=Boiler_repair_order::getList_repair_master($master_id ,2,21);//使用师傅的ID的来获取维修记录
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
    <script src="static/js/jquery.min.js"></script>
    <script src="static/layer/layer.js"></script>
    <script type="text/javascript">
        function  accept(id) {
            alert(id);
            layer.confirm('确认接单吗？', {
                    btn: ['确认', '取消']
                }
                , function () {
                    var index = layer.load(0, {shade: false});
                    $.ajax({
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        url: 'review_do.php?act=accept',
                        success: function (data) {
                            layer.close(index);
                            var code = data.code;
                            var msg = data.msg;
                            switch (code) {
                                case 1:
                                    layer.alert(msg, {icon: 6}, function (index) {
                                        location.reload();
                                    });
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    });
                }
            );
        }
        function  showTable(id){
                 alert(id);//维修人员的id
                  location.href="mater_waitting_accept_detail.php?id="+id;
        }
    </script>
</head>
<body>
<a href="javascript:void(0)" onclick="showTable(<?php echo $orderList[0]["id"]?>)">订单详情 </a>
<a href="javascript:void(0)" onclick="accept(<?php echo $orderList[0]["id"]?>)" >接单</a>

</body>
</html>
