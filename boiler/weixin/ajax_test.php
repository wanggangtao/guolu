<?php
//        $params ='[{"part":[{"name":"A配件","price":"30","num":"21"},{"name":"配件B","price":"30","num":"20"},{"name":"配件B","price":"30","num":"20"}],"hand_money":200,"pay_style":1,"id":1,"remark":"芝麻","resove_style":1234,"content":"2012-10-30 17:6:9","picture":"000000000000000","period":1351587969902}]';
        $params1=array("name"=>"A配件","price"=>"30","num"=>"21",);
        $params2=array("name"=>"配件B","price"=>"30","num"=>"21",);
        $params["parts"][0]=$params1;
        $params["parts"][1]=$params2;
        $params["pay_style"]=1;
        $params["id"]=1;
        $params["remark"]="芝麻";
        $params["resove_style"]=4;
        $params["content"]="200";
        $params["hand_money"]=200;
        $params["picture"]="000000000000000";
        $params["period"]=1351587969902;
        $params = json_encode($params,JSON_UNESCAPED_UNICODE );
//        var_dump($params);
//$params1=2;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>我的预约</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/jquery.min.js"></script>
    <script src="static/layer/layer.js"></script>
    <script type="text/javascript">
        function reset_reason() {
            $.ajax({
                type: 'POST',
                data: {
                    order_id: 5,
                    reason:"不不不不不"
                },
                dataType: 'json',
                url: 'review_do.php?act=reset_reason',
                success: function (data) {
                    var code = data.code;
                    var msg = data.msg;
                    switch (code) {
                        case 1:
                            layer.alert(msg, {icon: 6}, function () {
                                location.reload();
                            });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        }
        function submit() {
//            var params={"parts":[{"name":"A配件","price":"30","num":"21"},
//                {"name":"配件B","price":"30","num":"21"}],
//                "pay_style":1,"id":1,"remark":"芝麻","resove_style":4,
//                "content":"200","hand_money":200,"picture":"000000000000000","period":1351587969902};

            $.ajax({
                type: 'POST',
                data: {
                    params:{"parts":[{"name":"A配件","price":"30","num":"21"},
                        {"name":"配件B","price":"30","num":"21"}],
                        "pay_style":1,"id":1,"remark":"芝麻","resove_style":4,
                        "content":"200","hand_money":200,"picture":"000000000000000","period":1351587969902}
                },
                dataType: 'json',
                url: 'review_do.php?act=submit',
                success: function (data) {
                    var code = data.code;
                    var msg = data.msg;
                    switch (code) {
                        case 1:
                            layer.alert(msg, {icon: 6}, function () {
                                location.reload();
                            });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        }
    </script>
</head>
<body>
<a href="javascript:void(0)" onclick="reset_reason()" >重派</a>
<a href="javascript:void(0)" onclick="submit()" >提交数据 </a>
</body>
</html>
