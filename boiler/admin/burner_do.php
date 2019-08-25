<?php
/**
 * 燃烧器处理  burner_do.php
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
        $is_lownitrogen   =  safeCheck($_POST['is_lownitrogen']);
        $power   =  safeCheck($_POST['power']);
        $boilerpower   =  safeCheck($_POST['boilerpower']);
        $price   =  safeCheck($_POST['price']);
        $guoluid   =  safeCheck($_POST['guoluid']);

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
                "modelid" => 2,
            );
            $rs = Products::add($attrsPro);
            if($rs > 0){
                $attrs= array(
                    "version" => $version,
                    "vender" => $vender,
                    "is_lownitrogen" => $is_lownitrogen,
                    "power" => $power,
                    "boilerpower" => $boilerpower,
                    "proid" => $rs,
                    "guoluid" => $guoluid
                );
                Burner_attr::add($attrs);
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
        $is_lownitrogen   =  safeCheck($_POST['is_lownitrogen']);
        $power   =  safeCheck($_POST['power']);
        $boilerpower   =  safeCheck($_POST['boilerpower']);
        $price   =  safeCheck($_POST['price']);
        $guoluid   =  safeCheck($_POST['guoluid']);

        $attrs= array(
            "version" => $version,
            "vender" => $vender,
            "is_lownitrogen" => $is_lownitrogen,
            "power" => $power,
            "boilerpower" => $boilerpower,
            "guoluid" => $guoluid
        );
        $rs = Burner_attr::update($id, $attrs);

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

        $rs = Burner_attr::del($id);
        if($rs){
            Products::del($pid);
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);

        break;
    case 'getGuoluList'://根据厂家取得锅炉数据
        $vender = safeCheck($_POST['vender']);

        $guolulist = Guolu_attr::getList(0, 0, 0, $vender, 0, 0, 0);
        $str = "";
        if($guolulist){
            foreach ($guolulist as $thisguolu){
                $str .= '<option value="'.$thisguolu['guolu_id'].'">'.$thisguolu['guolu_version'].'</option>';
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        }else
            echo json_encode_cn(array("msg" => '', "code" => 101));

        break;


}
?>