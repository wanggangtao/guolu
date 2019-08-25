<?php
	/**
	 *
	 * @version       v0.01
	 * @create time   2018/09/13
	 * @update time  
	 * @author       dlk
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'del'://删除
			$id = safeCheck($_POST['id'],1);
            $rs = Web_recruit::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
		break;

		case 'add':
			$station = safeCheck($_POST['type'],0);
			$education = safeCheck($_POST['education'],0);
			$number = safeCheck($_POST['number'],0);
            $salary = safeCheck($_POST['salary'],0);
            $content = $_POST['content'];
            $need = $_POST['need'];
            $experience = safeCheck($_POST['experience'],0);

            $attrs = array(
                "station"=>$station,
                "education"=>$education,
                "number"=>$number,
                "salary"=>$salary,
                "describe"=>$content,
                "need"=>$need,
                "experience"=>$experience,
            );
			$rs = Web_recruit::add($attrs);
			if($rs > 0){
			    echo action_msg("添加成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
		break;
		
		case 'edit':
            $id = safeCheck($_POST['id']);
            $station = safeCheck($_POST['type'],0);
            $education = safeCheck($_POST['education'],0);
            $number = safeCheck($_POST['number'],0);
            $salary = safeCheck($_POST['salary'],0);
            $content = $_POST['content'];
            $need = $_POST['need'];
            $experience = safeCheck($_POST['experience'],0);
            $attrs = array(
                "station"=>$station,
                "education"=>$education,
                "number"=>$number,
                "salary"=>$salary,
                "describe"=>$content,
                "need"=>$need,
                "experience"=>$experience,
            );
			$rs = Web_recruit::update($id, $attrs);
			echo $rs;
		break;

	}
?>