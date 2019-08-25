<?php
/**
 * 除污器处理  dirt_separator_do.php
 *
 * @version       v0.01
 * @create time   2018/5/30
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $version   =  safeCheck($_POST['version'],0);
        $diameter   =  safeCheck($_POST['diameter']);
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
                "modelid" => 9,
            );
            $rs = Products::add($attrsPro);
            if($rs > 0){
                $attrs= array(
                    "version" => $version,
                    "diameter" => $diameter,
                    "proid" => $rs
                );
                Dirt_separator_attr::add($attrs);
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
        $diameter   =  safeCheck($_POST['diameter']);
        $price   =  safeCheck($_POST['price']);

        $attrs= array(
            "version" => $version,
            "diameter" => $diameter
        );
        $rs = Dirt_separator_attr::update($id, $attrs);

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

        $rs = Dirt_separator_attr::del($id);
        if($rs){
            Products::del($pid);
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);

        break;


}
?>