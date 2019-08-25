<?php
/**
 * 管道泵处理  pipeline_do.php
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
        $flow_min   =  safeCheck($_POST['flow_min']);
        $flow_mid   =  safeCheck($_POST['flow_mid']);
        $flow_max   =  safeCheck($_POST['flow_max']);
        $lift_min   =  safeCheck($_POST['lift_min']);
        $lift_mid   =  safeCheck($_POST['lift_mid']);
        $lift_max   =  safeCheck($_POST['lift_max']);
        $speed   =  safeCheck($_POST['speed']);
        $motorpower   =  safeCheck($_POST['motorpower']);
        $npsh   =  safeCheck($_POST['npsh']);
        $weight   =  safeCheck($_POST['weight']);
        $diametert   =  safeCheck($_POST['diameter']);
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
                "modelid" => 4,
            );

            $rs = Products::add($attrsPro);
            if($rs > 0){
                $attrs= array(
                    "version" => $version,
                    "vender" => $vender,
                    "flow_min" => $flow_min,
                    "flow_mid" => $flow_mid,
                    "flow_max" => $flow_max,
                    "lift_min" => $lift_min,
                    "lift_mid" => $lift_mid,
                    "lift_max" => $lift_max,
                    "speed" => $speed,
                    "motorpower" => $motorpower,
                    "npsh" => $npsh,
                    "weight" => $weight,
                    "diameter" => $diametert,
                    "proid" => $rs
                );
                Pipeline_attr::add($attrs);
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
        $flow_min   =  safeCheck($_POST['flow_min']);
        $flow_mid   =  safeCheck($_POST['flow_mid']);
        $flow_max   =  safeCheck($_POST['flow_max']);
        $lift_min   =  safeCheck($_POST['lift_min']);
        $lift_mid   =  safeCheck($_POST['lift_mid']);
        $lift_max   =  safeCheck($_POST['lift_max']);
        $speed   =  safeCheck($_POST['speed']);
        $motorpower   =  safeCheck($_POST['motorpower']);
        $npsh   =  safeCheck($_POST['npsh']);
        $weight   =  safeCheck($_POST['weight']);
        $diameter   =  safeCheck($_POST['diameter']);
        $price   =  safeCheck($_POST['price']);

        $attrs= array(
            "version" => $version,
            "vender" => $vender,
            "flow_min" => $flow_min,
            "flow_mid" => $flow_mid,
            "flow_max" => $flow_max,
            "lift_min" => $lift_min,
            "lift_mid" => $lift_mid,
            "lift_max" => $lift_max,
            "speed" => $speed,
            "motorpower" => $motorpower,
            "npsh" => $npsh,
            "weight" => $weight,
            "diameter" => $diameter
        );
        $rs = Pipeline_attr::update($id, $attrs);

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

        $rs = Pipeline_attr::del($id);
        if($rs){
            Products::del($pid);
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);

        break;


}
?>