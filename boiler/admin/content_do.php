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

        case 'content_del'://删除内容
            $id = safeCheck($_POST['id'],1);
            $rs = Webcontent::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
            break;
		case 'picdel'://删除图片
			$id = safeCheck($_POST['id'],1);
            $rs = Picture::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
		break;
            
		case 'picadd':
			$url = safeCheck($_POST['url'],0);
			$http = safeCheck($_POST['http'],0);
            $type = safeCheck($_POST['type']);
            $attrs = array(
                "url"=>$url,
                "http"=>$http,
                "type"=>$type
            );
			$rs = Picture::add($attrs);
			if($rs > 0){
			    echo action_msg("添加成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
		break;
		
		case 'picedit':
			$url = safeCheck($_POST['url'],0);
			$http = safeCheck($_POST['http'],0);
			$id = safeCheck($_POST['id'],1);
			$type=safeCheck($_POST['type'],1);
            $attrs = array(
                "url"=>$url,
                "http"=>$http,
                "type"=>$type,
            );
			$rs = Picture::update($id, $attrs);
			echo $rs;
		break;
			
		case 'picstatus':
			$id = safeCheck($_POST['id'],1);
			$status = safeCheck($_POST['status'],1);
			if($status==1) {
                $r = Picture::getInfoById($id);
                if ($r['type'] != 1) {
                    $rs=Picture::getPageList(0,0,1,$r['type'],1);
                    if(isset($rs)){
                        foreach ($rs as $val){
                            $attrs = array(
                                "status"=>0
                            );
                            $rs = Picture::update($val['id'], $attrs);
                        }
                    }
                }
            }
            $attrs = array(
                "status"=>$status
            );
			$rs = Picture::update($id, $attrs);
			echo $rs;
		break;

		case 'picorder':
			$code = 0;
			foreach ($_POST['list'] as $value) {
                $attrs = array(
                    "order"=>$value['order']
                );
                $rs = Picture::update($value['id'], $attrs);
				$rs = json_decode($rs);
				$code = $rs->code;
			}
			if($code == 1){
				echo action_msg('更新成功', 1);
			}else{
				echo action_msg('更新失败', 101);
			}
			break;

        case 'content_add':
            $title = safeCheck($_POST['title'],0);

            $title_supplement = "";
            if(isset($_POST['title_supplement']))
            {
                $title_supplement = safeCheck($_POST['title_supplement'],0);
            }
            $subtitle = "";
            if(isset($_POST['subtitle']))
            {
                $subtitle = safeCheck($_POST['subtitle'],0);
            }


            $content = safeCheck($_POST['content'],0);
            $type = safeCheck($_POST['type']);
            $attrs = array(
                "title"=>$title,
                "subtitle"=>$subtitle,
                "title_supplement"=>$title_supplement,
                "content"=>$content,
                "type"=>$type
            );
            $rs = Webcontent::add($attrs);
            echo $rs;
            break;

        case 'content_edit':
            $id = safeCheck($_POST['id']);
            $title = safeCheck($_POST['title'],0);
            $title_supplement = "";
            if(isset($_POST['title_supplement']))
            {
                $title_supplement = safeCheck($_POST['title_supplement'],0);
            }
            $subtitle = "";
            if(isset($_POST['subtitle']))
            {
                $subtitle = safeCheck($_POST['subtitle'],0);
            }

            $content = safeCheck($_POST['content'],0);
            $attrs = array(
                "title"=>$title,
                "subtitle"=>$subtitle,
                "title_supplement"=>$title_supplement,

                "content"=>$content
            );
            $rs = Webcontent::update($id, $attrs);
            echo $rs;
            break;

        case 'content_edit_about':
            $id = safeCheck($_POST['id']);
            $content = $_POST['content'];
            $attrs = array(
                "content"=>$content
            );
            $rs = Webcontent::update($id, $attrs);
            echo $rs;
            break;

        case 'content_order':
            $id = safeCheck($_POST['id'],1);
            $order = safeCheck($_POST['order'],1);

            $attrs = array(
                "order"=>$order
            );
            $rs = Webcontent::update($id, $attrs);

            echo $rs;
            break;
	}
?>