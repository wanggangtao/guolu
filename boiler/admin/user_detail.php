<?php
/**
 * 用户详情  user_edit.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$userinfo = User::getInfoById($id);
if(empty($userinfo)){
    echo "非法操作！";
    die();
}
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

    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>账号</label>
        <input type="text" class="text-input input-length-30" name="account" id="account" value="<?php echo $userinfo['account'];?>"  readonly/>
    </p>
    <p>
        <label>姓名</label>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $userinfo['name'];?>"  readonly/>
    </p>
    <p>
        <label>部门</label>
        <?php
        $departments = User_department::getInfoById($userinfo['department']);
        ?>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $departments?$departments['name']:"";?>"  readonly/>
    </p>
    <p>
        <label>角色</label>
        <?php
        $roles = User_role::getInfoById($userinfo['role']);
        ?>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $roles?$roles['name']:"";?>"  readonly/>
    </p>
    <p>
        <label>用户主管人</label>
        <?php
        $users = User::getInfoById($userinfo['parent']);
        ?>
        <input type="text" class="text-input input-length-30" name="name" id="name" value="<?php echo $users?$users['name']:"";?>"  readonly/>
    </p>
    </p>
    <p>
        <label>生日</label>
        <input type="text" class="text-input input-length-30" name="birthday" id="birthday" value="<?php echo $userinfo['birthday'];?>" readonly/>
    </p>
    <p>
        <label>用户头像</label>
    </p>
    <p style="padding-left:150px;"><img id="val1" src="<?php echo $HTTP_PATH.$userinfo['headimg'];?>"  /> </p>
</div>
</body>
</html>