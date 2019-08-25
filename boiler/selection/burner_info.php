<?php
/**
 * 燃烧器详情  burner_info.php
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
$info = Burner_attr::getInfoById($id);
if(empty($info))
    die();
$pInfo = Products::getInfoById($info['proid']);
if(empty($pInfo))
    die();


$guoluinfo = Guolu_attr::getInfoById($info['guoluid']);

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
        <input type="text" class="text-input input-length-30" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
        <label style="width: 100px;">厂家</label>
        <?php
            $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-10" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
    </p>
    <p>
        <label style="width: 130px;">是否低氮</label>
        <?php
        $infos = Dict::getInfoById($info['is_lownitrogen']);
        ?>
        <input type="text" class="text-input input-length-10" name="is_lownitrogen" id="is_lownitrogen" value="<?php echo $infos['name'];?>" readonly/>
        <label style="width: 100px;">功率(KW)</label>
        <input type="text" class="text-input input-length-10" name="power" id="power" value="<?php echo $info['power'];?>" readonly/>
    </p>
    <p>
        <label style="width: 130px;">对应锅炉热功率(KW)</label>
        <input type="text" class="text-input input-length-10" name="boilerpower" id="boilerpower" value="<?php echo $info['boilerpower'];?>" readonly/>
        <!-- <label style="width: 100px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>" readonly/> -->
    </p>
    <p>
        <label style="width: 130px;">对应锅炉型号</label>
        <input type="text" class="text-input input-length-30" name="boilerpower" id="boilerpower" value="<?php echo $guoluinfo['version'];?>" readonly/>
    </p>
</div>
</body>
</html>