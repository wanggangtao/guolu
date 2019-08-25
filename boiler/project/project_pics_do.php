<?php
/**
 *
 * @version       v0.01
 * @create time   2018/6/29
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $id       = safeCheck($_POST['id']);
        $type     = safeCheck($_POST['type']);
        $url      = safeCheck($_POST['url'], 0);

        try {
            $attrs = array(
                "projectid"=>$id,
                "type"=>$type,
                "url"=>$url,
                "time"=>time()
            );
            $thisid = Project_pictures::add($attrs);
            echo action_msg($thisid, 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del'://删除
        $id                = safeCheck($_POST['id']);

        try {
            $rs = Project_pictures::del($id);
            if($rs > 0)
                echo action_msg('删除成功', 1);
            else
                echo action_msg('删除失败', 101);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

}
?>