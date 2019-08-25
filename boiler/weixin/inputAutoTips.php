<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/19
 * Time: 22:18
 */
require_once "admin_init.php";

$str = $_GET['oData'];

$product_info = product_info::getList();

$str_array =  array_column($product_info,'code');

$str1 = "";
$Counts = count($str_array);
for($i=0;$i<$Counts;$i++){

    if(empty($str_array[$i])) continue;

    if(preg_match("/^$str/",$str_array[$i])){
        $str1 .= $str_array[$i];
        if($i != $Counts-1)
            $str1 .= ",";
    }
}
echo $str1;


?>