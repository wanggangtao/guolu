<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/12
 * Time: 15:10
 */
require_once ('admin_init.php');
$act=$_GET['act'];

switch ($act){

    case 'getChild':

        $upid = safeCheck($_POST['id']);

        $type = safeCheck($_POST['type']);

        try {

            $childList =Table_district::getAddressType($type,$upid);

            echo action_msg($childList,1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

}
?>