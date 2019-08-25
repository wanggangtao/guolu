<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 2019/08/11
 * Time: 16:41
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){

    case 'add_ourservice':

        $title = safeCheck($_POST['title'],0);
        $content = safeCheck($_POST['content'],0);
        $picture = safeCheck($_POST['picture'],0);
        $attrs = array(
//            "id"=>"null",
            "weight"=>0,
            "title"=>$title,
            "content"=>$content,
            "picture"=>$picture,
            "state"=>1,
            "sign"=>2
        );

        $rs = afterservice::add($attrs);

        if($rs > 0){
            echo action_msg("添加成功！", 1);
        }else{
            echo action_msg("添加失败！", 101);
        }
        break;

    case 'add_sketch':
        $title = safeCheck($_POST['title'],0);
        $picture = safeCheck($_POST['picture'],0);
        $attrs = array(
//            "id"=>"null",
            "weight"=>0,
            "title"=>$title,
            "content"=>"",
            "picture"=>$picture,
            "state"=>1,
            "sign"=>6
        );
        $rs = afterservice::add($attrs);
        if($rs > 0){
            echo action_msg("添加成功！", 1);
        }else{
            echo action_msg("添加失败！", 101);
        }
        break;

    case 'order_advantage':
        $id = safeCheck($_POST['id'],1);
        $weight = safeCheck($_POST['weight'],1);
        $attrs = array(
            "weight"=>$weight
        );
        $rs = afterservice::update($id, $attrs);

        echo $rs;
        break;

    case 'del_ourservice_info'://删除我们的服务

        $id = safeCheck($_POST['id']);
        $attrs =array(
            "state" => -1
        );
        $rs = afterservice::update($id,$attrs);

        if($rs >= 0){
            echo action_msg("删除成功！", 1);
        }else{
            echo action_msg("删除失败！", 101);
        }
        break;

    case 'del_sketch_info'://删除服务剪影
        $id = safeCheck($_POST['id'],1);
        $attrs = array(
            "state"=>-1,
        );
        $rs = afterservice::update($id,$attrs);
        if($rs >= 0){
            echo action_msg("删除成功！", 1);
        }else{
            echo action_msg("删除失败！", 101);
        }
        break;

    case 'edit_ourservice'://修改我们的服务/专业、快捷、透明服务
//        echo $_POST['id'];
        $id  = safeCheck($_POST['id']);
        $title = safeCheck($_POST['title'],0);
        $content = safeCheck($_POST['content'],0);
        $picture = safeCheck($_POST['picture'],0);
        $attrs = array(
            "title"=>$title,
            "content"=>$content,
            "picture"=>$picture
        );
        $rs = afterservice::update($id, $attrs);
        echo $rs;
        break;

    case 'edit_sketch'://修改服务剪影
        $id  = safeCheck($_POST['id']);
        $title = safeCheck($_POST['title'],0);
        $picture = safeCheck($_POST['picture'],0);
        $attrs = array(
            "title"=>$title,
            "picture"=>$picture
        );
        $rs = afterservice::update($id, $attrs);
        echo $rs;
        break;

    case 'edit_Tips'://修改温馨提示
        $id  = 1;
        $content = safeCheck($_POST['content'],0);
        $attrs = array(
            "content"=>$content,
        );
        $rs = afterservice::update($id, $attrs);
        echo $rs;
        break;
    case 'edit_tel'://修改温馨提示
        $id  = 2;
        $content = safeCheck($_POST['content'],0);
        $tel1 = safeCheck($_POST['tel1'],0);
        $tel2 = safeCheck($_POST['tel2'],0);
        $attrs = array(
            "title"=>$content,
            "content"=>$tel1.','.$tel2,
        );
        $rs = afterservice::update($id, $attrs);
        echo $rs;
        break;

    case 'edit_Wechat'://修改温馨提示
        $id  = 3;
        $content = safeCheck($_POST['content'],0);
        $title = safeCheck($_POST['title'],0);
        $img_url = safeCheck($_POST['img_url'],0);
        $attrs = array(
            "title"=>$title,
            "content"=>$content,
            "picture"=>$img_url
        );
        $rs = afterservice::update($id, $attrs);
        echo $rs;
        break;

    case 'upload':

        $allowext     = array('jpg', 'jpeg', 'gif', 'png');
        $fileElement  = 'file';
        $filepath_rel = 'userfiles/upload/AfterSale/' . date('Ymd') . '/';//相对路径
        $filepath_abs =  $FILE_PATH.$filepath_rel;//绝对路径

        //    print_r($filepath_abs);exit;
        if (!file_exists($filepath_abs)) {
            mkdir($filepath_abs,0777,true);
        }
        try {
            $fup = new FileUpload('3M', $allowext);
            $r   = $fup->upload($fileElement, $filepath_abs, '', true);
            $name_rel = $filepath_rel . $r;
//            tongbu::runSync($filepath_rel,$filepath_abs.$r);

            //上传成功
            echo action_msg($name_rel, 1);
        } catch (Exception $e) {
            echo action_msg($e->getMessage(), $e->getCode());
        }

        break;
}
?>
