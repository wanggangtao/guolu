<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/13
 * Time: 10:27
 */
require_once ('web_init.php');
$province=$_POST['province'];

if(isset($province))
    $_SESSION['province']=$province;
$city=$_POST['city'];
if(isset($city))
    $_SESSION['city']=$city;


$if_change=safeCheck($_POST['if_change'],1);
if($if_change==1)
    $_SESSION['distribution_if_change']=1;
$this_change=safeCheck($_POST['this_change'],1);
if($this_change==1)
    $_SESSION['this_change']=1;
echo $city;
?>