<?php
/**
 * 水箱详细信息  water_box_ddetail.php
 *
 * @version       v0.01
 * @create time   2018/5/30
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');
if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Water_box_attr::getInfoById($id);
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
        <input type="text" class="text-input input-length-20" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
        <label style="width: 120px;">公称容积(m³)</label>
        <input type="text" class="text-input input-length-10" name="nominal_capacity" id="nominal_capacity" value="<?php echo $info['nominal_capacity'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">有效容积(m³)</label>
        <input type="text" class="text-input input-length-10" name="available_capacity" id="available_capacity" value="<?php echo $info['available_capacity'];?>" readonly/>
        <label style="width: 230px;">箱体长(mm)</label>
        <input type="text" class="text-input input-length-10" name="length" id="length" value="<?php echo $info['length'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">箱体宽(mm)</label>
        <input type="text" class="text-input input-length-10" name="width" id="width" value="<?php echo $info['width'];?>" readonly/>
        <label style="width: 230px;">箱体高(mm)</label>
        <input type="text" class="text-input input-length-10" name="height" id="height" value="<?php echo $info['height'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php echo $info['weight'];?>" readonly/>
        <!-- <label style="width: 230px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>" readonly/> -->
    </p>
</div>
</body>
</html>