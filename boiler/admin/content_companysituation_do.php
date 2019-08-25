<?php
/**
 * Created by kjb.
 * Date: 2018/12/5
 * Time: 14:47
 */
	require_once('admin_init.php');
	require_once('admincheck.php');

	$act = safeCheck($_GET['act'], 0);
	switch($act){
        case 'situationdel'://删除
            $id = safeCheck($_POST['id'],1);
            $rs = Web_situation::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
            break;

        case 'situationadd':
            $title = safeCheck($_POST['title'],0);
            $picurl = safeCheck($_POST['picurl'],0);
            $http = safeCheck($_POST['http'],0);
            $type = safeCheck($_POST['type']);
            $if_top=0;
            $content = $_POST['content'];
            $addtime = time();
            $attrs = array(
                "title"=>$title,
                "picurl"=>$picurl,
                "content"=>$content,
                "http"=>$http,
                "type"=>$type,
                "if_top"=>$if_top,
                "addtime"=>$addtime
            );
            $rs = Web_situation::add($attrs);
            if($rs > 0){
                echo action_msg("添加成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
            break;

        case 'situationedit':
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
            $rs = Web_situation::update($id, $attrs);
            echo $rs;
            break;

        case 'set_if_top':
            $id = safeCheck($_POST['id'],1);
            $if_top = safeCheck($_POST['if_top'],1);
            $attrs = array(
                "if_top"=>$if_top
            );
            $rs = Web_situation::update($id, $attrs);
            echo $rs;
            break;

        case 'good_status':
            $id = safeCheck($_POST['id'],1);
            $is_good = Web_situation::getInfoById($id)["is_good"];

            if($is_good == 1){
                $is_good = -1;
            }
            else{
                $is_good = 1;
            }
            $attrs = array(
                "is_good"=>$is_good
            );
            $rs = Web_situation::update($id, $attrs);
            echo $rs;
            break;
    }
?>