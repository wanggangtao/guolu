<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/12/6
 * Time: 22:03
 */
	require_once('admin_init.php');
	require_once('admincheck.php');

	$act = safeCheck($_GET['act'], 0);
	switch($act){
        case 'introductiondel'://删除
            $id = safeCheck($_POST['id'],1);
            $rs = Web_introduction::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
            break;

        case 'introductionadd':
        //    $kind = safeCheck($_POST['kind'],0);
            $content = $_POST['content'];
            $attrs = array(
       //         "kind"=>$kind,
                "content"=>$content,
            );
            $rs = Web_introduction::add($attrs);
            if($rs > 0){
                echo action_msg("添加成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
            break;

        case 'introductionedit':
            $id = safeCheck($_POST['id']);
   //         $kind = safeCheck($_POST['kind'],0);
            $content = $_POST['content'];
            $tel =safeCheck($_POST['tel'],0) ;
            $attrs = array(
       //         "kind"=>$kind,
                "content"=>$content,
                "tel"=>$tel,
            );
            $rs = Web_introduction::update($id, $attrs);
            echo $rs;
            break;

    }
?>