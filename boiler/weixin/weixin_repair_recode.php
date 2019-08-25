<!DOCTYPE html>
<html lang="en">
<?php
require_once("admin_init.php");

if(isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    echo "未发现该产品";
    exit();
}
$repir_info = repair_order::getInfoByCode($code,3);
//print_r($repir_info);
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>质保/服务</title>
    <link rel="stylesheet" href="static/weui/css/weui.min.css" />
    <link rel="stylesheet" href="static/weui/css/jquery-weui.min.css" />
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <link rel="stylesheet" href="static/css/swiper.min.css">
    <script src="static/js/query.js"></script>
</head>

<body>
<div id="app">
    <div class="person-wrap">
        <div class="personal-center-head flex">
            <a href="weixin_personal_detail.php"><img class="person-img" src="static/images/icon-bark.png" alt=""></a>
            <span class="person-name">质保/服务</span>
            <span></span>
        </div>
        <div class="person-form" style="margin-top: 20px;">
            <div class="form-title flex">
                <p></p>
                <span>服务记录</span>
            </div>
            <?php
            if(!empty($repir_info)){
                foreach ($repir_info as $key => $value){
                    $index = $key + 1;
                    $finish_time = "";
                    if(!empty($value['finish_time']) and is_numeric($value['finish_time'])){
                        $finish_time = date("Y-m-d H:i:s", $value['finish_time']);
                    }
                    $repair_person = "";
                    if(!empty($value['person'])){
                        $user_info = Repair_person::getInfoById($value['person']);
                        if(!empty($user_info['name'])) $repair_person .= $user_info['name']."  ";
                    }
                    if(!empty($value['linkphone'])){
                        $repair_person .= $value['linkphone'];
                    }
                    if(empty($repair_person)){
                        $repair_person = "暂无";
                    }

                    $content = "暂无";
                    if(!empty($value['content'])){
                        $content =  $value['content'];
                    }

//                    $type_name = "暂无";
//                    if(!empty($value['failure_cause'])){
//                        $type_name = $value['failure_cause'];
//                    }

                    $service_type = "";
                    if(isset($value['service_type'])){
                        $service_info = Service_type::getInfoById($value['service_type']);
                        if(isset($service_info['name'])){
                            $service_type = $service_info['name'];
                        }
                    }

                    $result = "暂无";
                    if(!empty($value['result'])){
                        $result = $value['result'];
                    }

                    echo '
                  <div class="repair-item">
                <span>'.$index.'、</span>
                <div>
                    <p>服务类型：'.$service_type.'</p>
                    <p>服务时间：'.$finish_time.'</p>
                    <p>服务人员：'.$repair_person.'</p>
                    <p>服务结果：'.$result.'</p>
                </div>
            </div>
                ';
                }
            }else{
                echo '<div class="repair-item">
                    <span>暂无</span>
                </div>';
            }


            ?>

        </div>
        <div class="person-form" style="margin-top: 20px;">
            <div class="form-title flex">
                <p></p>
                <span>质保期限</span>
            </div>
            <div class="repair-time">保修截止日期：<?php
                $period = "";
                if(isset($code)){
                    $product_info =  product_info::getInfoByBarCode($code);
                    if(isset($product_info['period']) ){
                        if(is_numeric($product_info['period'])){
                            $period =  date("Y-m-d ", $product_info['period']);
                        }else{
                            $period = $product_info['period'];
                        }
                    }
                }
                if(empty($period)){
                    echo "暂无";
                }else{
                    echo $period;
                }

                ?></div>
            <div class="repair-tip">本订单由西安元聚提供售后保障服务。服务完成后针对故障点质保90天。</div>
        </div>
    </div>

</div>
</body>
<script src="static/js/jquery.min.js"></script>
<script src="static/weui/js/jquery-weui.min.js"></script>
<script src="static/js/swiper.min.js"></script>
<script src="static/js/common.js"></script>
<script>
    $(function () {



    })
</script>

</html>