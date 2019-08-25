<?php
/**
 * 换热器处理  heat_exchanger_do.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $version   =  safeCheck($_POST['version'],0);
        $vender   =  safeCheck($_POST['vender']);
        $heat_surface   =  safeCheck($_POST['heat_surface']);
        /*$first_r   =  safeCheck($_POST['first_r']);
        $second_r   =  safeCheck($_POST['second_r']);
        $length   =  safeCheck($_POST['length']);
        $width   =  safeCheck($_POST['width']);
        $height   =  safeCheck($_POST['height']);*/
        $weight   =  safeCheck($_POST['weight']);
        $price   =  safeCheck($_POST['price']);

        try {

            $attrsPro = array(
                "name" => '',
                "img" => '',
                "brief" => '',
                "desc" => '',
                "price" => $price,
                "addtime" => time(),
                "lastupdate" => 0,
                "weight" => 0,
                "modelid" => 6,
            );

            $rs = Products::add($attrsPro);
            if($rs > 0){
                $attrs= array(
                    "version" => $version,
                    "vender" => $vender,
                    "heat_surface" => $heat_surface,
                    "weight" => $weight,
                    "proid" => $rs
                );
                Heat_exchanger_attr::add($attrs);
                echo action_msg('添加成功', 1);
            }else
                echo action_msg('添加失败', 101);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit'://修改
        $id   =  safeCheck($_POST['id']);
        $pid   =  safeCheck($_POST['pid']);
        $version   =  safeCheck($_POST['version'],0);
        $vender   =  safeCheck($_POST['vender']);
        $heat_surface   =  safeCheck($_POST['heat_surface']);
        /*$first_r   =  safeCheck($_POST['first_r']);
        $second_r   =  safeCheck($_POST['second_r']);
        $length   =  safeCheck($_POST['length']);
        $width   =  safeCheck($_POST['width']);
        $height   =  safeCheck($_POST['height']);*/
        $weight   =  safeCheck($_POST['weight']);
        $price   =  safeCheck($_POST['price']);

        $attrs= array(
            "version" => $version,
            "vender" => $vender,
            "heat_surface" => $heat_surface,
            "weight" => $weight,
        );
        $rs = Heat_exchanger_attr::update($id, $attrs);

        if($rs >= 0){
            /* $attrsPro = array(
                "price" => $price,
                "lastupdate" => time()
            );
            $rs = Products::update($pid, $attrsPro); */
            echo action_msg('修改成功', 1);
        }else
            echo action_msg('修改失败', 101);

        break;
    case 'del'://删除
        $id = safeCheck($_POST['id']);
        $pid = safeCheck($_POST['pid']);

        $rs = Heat_exchanger_attr::del($id);
        if($rs){
            Products::del($pid);
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);

        break;


}
?>