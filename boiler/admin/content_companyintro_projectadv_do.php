<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/28
 * Time: 15:36
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'order':
        $id = safeCheck($_POST['id'],1);
        $weight = safeCheck($_POST['weight'],1);
        $attrs = array(
            "weight"=>$weight
        );
        $rs = web_intro_advantage::update($id, $attrs);
        echo $rs;
        break;






}