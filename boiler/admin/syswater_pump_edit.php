<?php
/**
 * 修改系统补水泵  syswater_pump_edit.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Syswater_pump_attr::getInfoById($id);
if(empty($info))
    die();
$pInfo = Products::getInfoById($info['proid']);
if(empty($pInfo))
    die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#btn_sumit').click(function(){
                var version = $('#version').val();
                var vender = $('#vender').val();
                var flow_min = $('#flow_min').val();
                var flow_mid = $('#flow_mid').val();
                var flow_max = $('#flow_max').val();
                var lift_min = $('#lift_min').val();
                var lift_mid = $('#lift_mid').val();
                var lift_max = $('#lift_max').val();
                var speed = $('#speed').val();
                var motorpower = $('#motorpower').val();
                var npsh = $('#npsh').val();
                var weight = $('#weight').val();
                var price = 0;
                var reg = /^(-?\d+)(\.\d+)?$/;
                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    return false;
                }
                if(vender == ''){
                    layer.tips('厂家不能为空', '#vender');
                    return false;
                }
                if (!(reg.test(vender))) {
                    layer.tips('厂家应为数字', '#vender');
                    return false;
                }

                if(flow_min == ''){
                    layer.tips('流量最小值不能为空', '#flow_min');
                    return false;
                }
                if (!(reg.test(flow_min))) {
                    layer.tips('流量最小值应为数字', '#flow_min');
                    return false;
                }
                if(flow_mid == ''){
                    layer.tips('流量中值不能为空', '#flow_mid');
                    return false;
                }
                if (!(reg.test(flow_mid))) {
                    layer.tips('流量中值应为数字', '#flow_mid');
                    return false;
                }
                if(flow_max == ''){
                    layer.tips('流量最大值不能为空', '#flow_max');
                    return false;
                }
                if (!(reg.test(flow_max))) {
                    layer.tips('流量最大值应为数字', '#flow_max');
                    return false;
                }
                if(lift_min == ''){
                    layer.tips('扬程最小值不能为空', '#lift_min');
                    return false;
                }
                if (!(reg.test(lift_min))) {
                    layer.tips('扬程最小值应为数字', '#lift_min');
                    return false;
                }
                if(lift_mid == ''){
                    layer.tips('扬程中值不能为空', '#lift_mid');
                    return false;
                }
                if (!(reg.test(lift_mid))) {
                    layer.tips('扬程中值应为数字', '#lift_mid');
                    return false;
                }
                if(lift_max == ''){
                    layer.tips('扬程最大值不能为空', '#lift_max');
                    return false;
                }
                if (!(reg.test(lift_max))) {
                    layer.tips('扬程最大值应为数字', '#lift_max');
                    return false;
                }
                if(speed == ''){
                    layer.tips('转速不能为空', '#speed');
                    return false;
                }
                if (!(reg.test(speed))) {
                    layer.tips('转速应为数字', '#speed');
                    return false;
                }
                if(motorpower == ''){
                    layer.tips('电机功率不能为空', '#motorpower');
                    return false;
                }
                if (!(reg.test(motorpower))) {
                    layer.tips('电机功率应为数字', '#motorpower');
                    return false;
                }
                if(npsh == ''){
                    layer.tips('必需汽蚀余量不能为空', '#npsh');
                    return false;
                }
                if (!(reg.test(npsh))) {
                    layer.tips('必需汽蚀余量应为数字', '#npsh');
                    return false;
                }
                if(weight == ''){
                    layer.tips('重量不能为空', '#weight');
                    return false;
                }
                if (!(reg.test(weight))) {
                    layer.tips('重量应为数字', '#weight');
                    return false;
                }
                /* if(price != ''){
                    if (!(reg.test(price))) {
                        layer.tips('价格应为数字', '#price');
                        return false;
                    }
                }else
                    price = 0; */
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        vender  : vender,
                        flow_min  : flow_min,
                        flow_mid  : flow_mid,
                        flow_max  : flow_max,
                        lift_min  : lift_min,
                        lift_mid  : lift_mid,
                        lift_max  : lift_max,
                        speed  : speed,
                        motorpower  : motorpower,
                        npsh  : npsh,
                        weight  : weight,
                        price  : price
                    },
                    dataType :    'json',
                    url :         'syswater_pump_do.php?act=edit',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label style="width: 140px;">型号</label>
        <input type="text" class="text-input input-length-30" name="version" id="version" value="<?php echo $info['version'];?>"/>
        <label style="width: 120px;">厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByParentid(8);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
    </p>
    <p>
        <label style="width: 140px;">流量最小值(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="flow_min" id="flow_min" value="<?php echo $info['flow_min'];?>"/>
        <label style="width: 140px;">流量中值(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="flow_mid" id="flow_mid" value="<?php echo $info['flow_mid'];?>"/>
        <label style="width: 140px;">流量最大值(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="flow_max" id="flow_max" value="<?php echo $info['flow_max'];?>"/>
    </p>
    <p>
        <label style="width: 140px;">扬程最小值(m)</label>
        <input type="text" class="text-input input-length-10" name="lift_min" id="lift_min" value="<?php echo $info['lift_min'];?>"/>
        <label style="width: 140px;">扬程中值(m)</label>
        <input type="text" class="text-input input-length-10" name="lift_mid" id="lift_mid" value="<?php echo $info['lift_mid'];?>"/>
        <label style="width: 140px;">扬程最大值(m)</label>
        <input type="text" class="text-input input-length-10" name="lift_max" id="lift_max" value="<?php echo $info['lift_max'];?>"/>
    </p>
    <p>
        <label style="width: 140px;">转速(r/min)</label>
        <input type="text" class="text-input input-length-10" name="speed" id="speed" value="<?php echo $info['speed'];?>"/>
        <label style="width: 140px;">电机功率(kW)</label>
        <input type="text" class="text-input input-length-10" name="motorpower" id="motorpower" value="<?php echo $info['motorpower'];?>"/>
        <label style="width: 140px;">必需汽蚀余量(m)</label>
        <input type="text" class="text-input input-length-10" name="npsh" id="npsh" value="<?php echo $info['npsh'];?>"/>
    </p>
    <p>
        <label style="width: 140px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>"/>
        <!-- <label style="width: 140px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>"/> -->
    </p>
    <p>
        <label style="width: 350px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>