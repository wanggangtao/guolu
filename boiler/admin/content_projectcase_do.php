<?php
	/**
	 * 图片处理  picture_do.php
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
		case 'casedel'://删除
			$id = safeCheck($_POST['id'],1);
            $rs = Web_projectcase::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
		break;

		case 'caseadd':
			$title = safeCheck($_POST['title'],0);
			$picurl = safeCheck($_POST['picurl'],0);
			$http = safeCheck($_POST['http'],0);
            $type = safeCheck($_POST['type']);
            $content = $_POST['content'];
            $addtime = time();
            $attrs = array(
                "title"=>$title,
                "picurl"=>$picurl,
                "content"=>$content,
                "http"=>$http,
                "type"=>$type,
                "order"=>0,
                "addtime"=>$addtime
            );
			$rs = Web_projectcase::add($attrs);
			if($rs > 0){
			    echo action_msg("添加成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
		break;
		
		case 'caseedit':
			$id = safeCheck($_POST['id']);
			$title = safeCheck($_POST['title'],0);
			$picurl = safeCheck($_POST['picurl'],0);
			$http = safeCheck($_POST['http'],0);
            $type = safeCheck($_POST['type']);
            $content = $_POST['content'];
            $attrs = array(
                "title"=>$title,
                "picurl"=>$picurl,
                "content"=>$content,
                "http"=>$http,
                "type"=>$type,
            );
			$rs = Web_projectcase::update($id, $attrs);
			echo $rs;
		break;

		case 'caseorder':
			$code = 0;
			foreach ($_POST['list'] as $value) {
                $attrs = array(
                    "order"=>$value['order']
                );
                $rs = Web_projectcase::update($value['id'], $attrs);
				$rs = json_decode($rs);
				$code = $rs->code;
			}
			if($code == 1){
				echo action_msg('更新成功', 1);
			}else{
				echo action_msg('更新失败', 101);
			}
			break;

        case 'good_status':
            $id = safeCheck($_POST['id'],1);
            $is_good = Web_projectcase::getInfoById($id)["is_good"];

            if($is_good == 1){
                $is_good = -1;
            }
            else{
                $is_good = 1;
            }
            $attrs = array(
                "is_good"=>$is_good
            );
            $rs = Web_projectcase::update($id, $attrs);
            echo $rs;
            break;

	}
?>