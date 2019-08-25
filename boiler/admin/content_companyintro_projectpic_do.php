<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/27
 * Time: 16:25
 */

?>
<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 15:38
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加修改
        $http   =  safeCheck($_POST['http'], 0);
        $picurl   = safeCheck($_POST['picurl'],0);
        $attrs = array(
            "http"=>$http,
            "picurl"=>$picurl,
        );

        $rs =web_intro_projectpic::add($attrs);
        if($rs > 0){
            echo action_msg("添加成功！", 1);
        }else{
            echo action_msg("添加失败！", 101);
        }
        break;
    case 'del'://删除
        $id = safeCheck($_POST['id'],1);
        $rs = web_intro_projectpic::del($id);
        if($rs >= 0){
            echo action_msg("删除成功！", 1);
        }else{
            echo action_msg("删除失败！", 101);
        }
        break;

    case 'edit':
        $id = safeCheck($_POST['id']);
        $picurl = safeCheck($_POST['picurl'],0);
        $http = safeCheck($_POST['http'],0);
        $attrs = array(
            "picurl"=>$picurl,
            "http"=>$http,
        );
        $rs = web_intro_projectpic::update($id, $attrs);
        echo $rs;
        break;
//显示图片
    case 'display_status':
        $id = safeCheck($_POST['id'],1);
        $status = web_intro_projectpic::getInfoById($id)["display"];
        if($status == 1){
            $change_status  = -1;
        }else{
            $change_status  = 1;
        }

        $attrs = array(
            "display"=>$change_status
        );
        $rs = web_intro_projectpic::update($id, $attrs);
        echo $rs;
        break;
//排序
    case 'order':
        $id = safeCheck($_POST['id'],1);
        $weight = safeCheck($_POST['weight'],1);
        $attrs = array(
            "weight"=>$weight
        );
        $rs = web_intro_projectpic::update($id, $attrs);

        echo $rs;
        break;

}
?>
