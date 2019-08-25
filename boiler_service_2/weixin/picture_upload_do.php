<?php
/**
 * Created by kjb.
 * Date: 2018/7/19
 * Time: 11:42
 */
require_once('admin_init.php');
//require_once('admincheck.php');
//$POWERID = '1,2,3,4';//权限
//$act = $_POST['act'];
$act = $_GET['act'];
//print_r("进来了");
//exit();
switch ($act) {

    case 'file_upload':

        $allowext = array('zip', 'rar' , '7z' , 'txt',);//设置上传文件的类型
        $fileElement = 'file_hold';//文件上传控件的id属性
        $filepath_rel = './upload_file/' . date('Ymd') . '/';//保存图片的相对路径
        $filepath_abs = $FILE_PATH . $filepath_rel;//保存文件的绝对路径，不包含文件名
        //    print_r($filepath_abs);exit;
        if (!file_exists($filepath_abs)) {
            mkdir($filepath_abs, 0777, true);//创建路径
        }
        try {
            $fup = new FileUpload('3M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);//开始上传，返回新的文件名
            //文件的id,文件的绝对路径，文件名为空，为真
            $name_rel = $filepath_rel . $r;//相对路径+文件名


            tongbu::runSync($filepath_rel, $filepath_abs . $r);//文件的相对路径和该文件

            //上传成功
            echo action_msg($name_rel, 1);//返回相对路径+文件名
        } catch (Exception $e) {
            echo action_msg($e->getMessage(), $e->getCode());
        }

        break;
    case 'picture_upload':
//        var_dump("jinlaile ");
        $allowext = array('jpg', 'jpeg', 'gif', 'png');//设置上传文件的类型
        $fileElement = 'file';//文件上传控件的id属性
        $filepath_rel = 'upload_picture/' . date('Ymd') . '/';//保存图片的相对路径
//        $filepath_rel = './upload_picture/' . date('Ymd') . '/';//保存图片的相对路径,浏览器回自动改错
        $filepath_abs = $FILE_PATH . $filepath_rel;//保存文件的绝对路径，不包含文件名
        if (!file_exists($filepath_abs)) {
            mkdir($filepath_abs, 0777, true);//创建路径
        }
        try {
            $fup = new FileUpload('3M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);//开始上传，返回新的文件名
            //文件的id,文件的绝对路径，文件名为空，为真
            $name_rel = $filepath_rel . $r;//相对路径+文件名
//            var_dump($name_rel);
            tongbu::runSync($filepath_rel, $filepath_abs . $r);//文件的相对路径和该文件

            //上传成功
            echo action_msg($name_rel, 1);//返回相对路径+文件名
        } catch (Exception $e) {
            echo action_msg($e->getMessage(), $e->getCode());
        }

        break;




}
?>
