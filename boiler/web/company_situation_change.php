<?php
/**
 * Created by kjb.
 * Date: 2018/12/11
 * Time: 20:52
 */
require_once('web_init.php');
$type=safeCheck($_POST['type'],1);
$if_change=safeCheck($_POST['if_change'],1);
$detail=0;
if(isset($_POST['detail']))
$detail=safeCheck($_POST['detail'],1);
if(isset($type))
$_SESSION['type_s']=$type;
if($if_change==1)
    $_SESSION['if_change_situation']=1;
if($detail==1)
    $_SESSION['detail']=1;
echo $type;
