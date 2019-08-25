<?php
/**
 * 全自动软水器详情  hdys_info.php
 *
 * @version       v0.01
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('web_init.php');
require_once('usercheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Hdys_attr::getInfoById($id);
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
        <label style="width: 130px;">型号</label>
        <input type="text" class="text-input input-length-50" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
    </p>
    <p>
        <label style="width: 100px;">厂家</label>
        <?php
            $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-10" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
        <label style="width: 130px;">额定出水量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="outwater" id="outwater" value="<?php echo $info['outwater'];?>" readonly/>
    </p>
    <p style="display: none;">
        <!--<label style="width: 130px;">重量(kg)</label>
        <input type="text" class="text-input input-length-10" name="weight" id="weight" value="<?php /*//echo $info['weight'];*/?>" readonly/>-->
        <label style="width: 100px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>" readonly/>
    </p>
</div>
</body>
</html>