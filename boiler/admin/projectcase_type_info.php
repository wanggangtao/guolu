<?php
/**
 * 修改用户类型  common_edit.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/27
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
//$name="";
//$english_name="";
//$order="";
if($id != 0)
{
    $usertype=Projectcase_type::getInfoById($id);
//    print_r($usertype);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">

    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label>名称</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $usertype[0]['name'];?>"  readonly/>
    </p>
    <p>
        <label>英文名称</label>
        <input type="text" class="text-input input-length-30" name="english_name" id="english_name" value="<?php echo $usertype[0]['english_name'];?>" readonly />
    </p>
    <p>
        <label>排序</label>
        <input type="text" class="text-input input-length-30" name="order" id="order" value="<?php echo $usertype[0]['order'];?>" readonly/>
    </p>
</div>
</body>
</html>