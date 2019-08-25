<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/4/25
 * Time: 12:38
 */


require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act) {
    case 'miyu_add':

        $name = weixin_customer::BASECONFIG_NAME;
        $key = weixin_customer::BASECONFIG_KEY;

        $value = safeCheck($_POST['name'],0);

        try {
            $rs = Baseconfig::add($key, $value, $name);
            echo action_msg("添加成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'miyu_edit':

        $value = safeCheck($_POST['name'],0);
        $id = safeCheck($_POST['id'],0);

        $update_info =array('value' => $value ,'lastupdate' => time() );

        try {
            $rs = Baseconfig::update($id,$update_info);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'edit':
        try {
            $id = safeCheck($_POST['id'],1);
            $nickname = safeCheck($_POST['name'],0);
            $remark = safeCheck($_POST['remark'],0);

            $attrs = array(
                "nickname" =>$nickname,
                "remark" =>$remark
            );
//            print_r($attrs);
//            exit();


            $rs = weixin_customer::update($id,$attrs);
            echo action_msg("修改成功",1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }

        break;
    case 'dels':
        $id = safeCheck($_POST['id']);

        try {
            $rs = weixin_customer::dels($id);
            if(isset($rs['phone'])){
                echo action_msg($rs['phone'],1);
            }else{
                echo action_msg("删除成功",1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }

        break;

}


?>