<?php
/**
 * 换热器  heat_exchanger_info.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('web_init.php');
require_once('usercheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Heat_exchanger_attr::getInfoById($id);
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
        <label style="width: 140px;">厂家</label>
        <?php
        $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-10" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
    </p>
    <!--<p>
        <label style="width: 140px;">一次侧进出水管径(m)</label>
        <input type="text" class="text-input input-length-10" name="first_r" id="first_r" value="<?php /*//echo $info['first_r'];*/?>" readonly/>
        <label style="width: 140px;">二次侧进出水管径(m)</label>
        <input type="text" class="text-input input-length-10" name="second_r" id="second_r" value="<?php /*//echo $info['second_r'];*/?>" readonly/>
        <label style="width: 140px;">长(m)</label>
        <input type="text" class="text-input input-length-10" name="length" id="length" value="<?php /*//echo $info['length'];*/?>" readonly/>
    </p>-->
    <p>
        <!--<label style="width: 140px;">宽(m)</label>
        <input type="text" class="text-input input-length-10" name="width" id="width" value="<?php /*//echo $info['width'];*/?>" readonly/>
        <label style="width: 140px;">高(m)</label>
        <input type="text" class="text-input input-length-10" name="height" id="height" value="<?php /*//echo $info['height'];*/?>" readonly/>-->
        <label style="width: 140px;">换热面积(㎡)</label>
        <input type="text" class="text-input input-length-10" name="heat_surface" id="heat_surface" value="<?php echo $info['heat_surface'];?>" readonly/>
        <label style="width: 140px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>" readonly/>
        <!-- <label style="width: 140px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>" readonly/> -->
    </p>
</div>
</body>
</html>