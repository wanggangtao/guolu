<?php
/**
 * 系统补水泵  syswater_pump_info.php
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
</head>
<body>
<div id="formlist">
    <p>
        <label style="width: 140px;">型号</label>
        <input type="text" class="text-input input-length-30" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
        <label style="width: 120px;">厂家</label>
        <?php
        $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-10" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">流量最小值(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="flow_min" id="flow_min" value="<?php echo $info['flow_min'];?>" readonly/>
        <label style="width: 140px;">流量中值(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="flow_mid" id="flow_mid" value="<?php echo $info['flow_mid'];?>" readonly/>
        <label style="width: 140px;">流量最大值(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="flow_max" id="flow_max" value="<?php echo $info['flow_max'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">扬程最小值(m)</label>
        <input type="text" class="text-input input-length-10" name="lift_min" id="lift_min" value="<?php echo $info['lift_min'];?>" readonly/>
        <label style="width: 140px;">扬程中值(m)</label>
        <input type="text" class="text-input input-length-10" name="lift_mid" id="lift_mid" value="<?php echo $info['lift_mid'];?>" readonly/>
        <label style="width: 140px;">扬程最大值(m)</label>
        <input type="text" class="text-input input-length-10" name="lift_max" id="lift_max" value="<?php echo $info['lift_max'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">转速(r/min)</label>
        <input type="text" class="text-input input-length-10" name="speed" id="speed" value="<?php echo $info['speed'];?>" readonly/>
        <label style="width: 140px;">电机功率(kW)</label>
        <input type="text" class="text-input input-length-10" name="motorpower" id="motorpower" value="<?php echo $info['motorpower'];?>" readonly/>
        <label style="width: 140px;">必需汽蚀余量(m)</label>
        <input type="text" class="text-input input-length-10" name="npsh" id="npsh" value="<?php echo $info['npsh'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>" readonly/>
        <!-- <label style="width: 140px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>" readonly/> -->
    </p>
</div>
</body>
</html>