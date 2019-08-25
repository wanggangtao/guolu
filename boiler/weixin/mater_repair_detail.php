<?php
/**
 * Created by PhpStorm.
 * User: TF
 * Date: 2019/8/17
 * Time: 22:33
 */
require_once "admin_init.php";
print_r("维修详情展示");
if(isset($_GET['id'])){
    $id =$_GET['id'];

}else{
    echo "未发现项目的ID";
    exit;
}