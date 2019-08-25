<?php
/**
 * Created by wzr
 * Date: 2018/12/11
 * Time: 20:52
 */
require_once('web_init.php');
$type=safeCheck($_POST['type'],1);
$if_change=safeCheck($_POST['if_change'],1);
if(isset($type))
    $_SESSION['type']=$type;
$SESSION['page']=1;
if($if_change==1)
    $_SESSION['if_change']=1;
echo $type;
?>