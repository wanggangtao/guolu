<?php
/**
 * 文件上传  all_upload.php
 *
 * @version       v0.01
 * @create time   2018/6/4
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

require($LIB_COMMON_PATH.'fileupload.class.php');
$type = safeCheck($_GET['type'], 0);
switch($type){
    case 'dictlogo'://厂家logo
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'file';
        $filepath_rel    = 'userfiles/upload/dictlogo/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);
            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;
            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'guoluvideodetail'://锅炉详细视频
        $allowext        = array('mp4');
        $fileElement     = 'video_file';
        $filepath_rel    = 'userfiles/upload/guluimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径
        try{
            $fup = new FileUpload('10M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);
            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;
            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'guoludetail'://锅炉详细图片
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'image_file';
        $filepath_rel    = 'userfiles/upload/guluimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);
            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;
            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'guluimg'://锅炉产品图片
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'file';
        $filepath_rel    = 'userfiles/upload/guluimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;
            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;


    case 'headimg'://用户头像
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'file';
        $filepath_rel    = 'userfiles/upload/userimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径
        try{
            $fup = new FileUpload('2M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'appfile'://app文件
        //上传
        $allowext        = array('apk');
        $fileElement     = 'file';
        $filepath_rel    = 'userfiles/upload/appflie/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径


        if(!file_exists($filepath_abs))
        {
            mkdir($filepath_abs);
        }

        try{
            $fup = new FileUpload('100M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;
    case 'indexpic'://首页轮播图
        $id = safeCheck($_GET['id'],0);
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'upload_file'.$id;
        $filepath_rel    = 'userfiles/upload/webpic/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;
    case 'logo'://企业logo
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'file';
        $filepath_rel    = 'userfiles/upload/userimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'aftersalepic'://公司介绍的售后板块

        $id = safeCheck($_GET['id'],0);
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'upload_file'.$id;
        $filepath_rel    = 'userfiles/upload/comintro/aftersale/'. date('Ymd') . '/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        if (!file_exists($filepath_abs)) {
            mkdir($filepath_abs,0777,true);
        }

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'projectpic'://公司介绍的售后板块

        $id = safeCheck($_GET['id'],0);
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'upload_file'.$id;
        $filepath_rel    = 'userfiles/upload/comintro/projectpic/'. date('Ymd') . '/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        if (!file_exists($filepath_abs)) {
            mkdir($filepath_abs,0777,true);
        }

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;

    case 'erweima'://公司介绍的售后板块

        $id = safeCheck($_GET['id'],0);
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'upload_file'.$id;
        $filepath_rel    = 'userfiles/upload/web/erweima/'. date('Ymd') . '/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        if (!file_exists($filepath_abs)) {
            mkdir($filepath_abs,0777,true);
        }

        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;
    case 'service_file'://厂家logo
        //上传
        $id = safeCheck($_GET['id'],0);
        $allowext        = array('png','gif','jpg','jpeg','mp4','avi','mp3', 'flv', 'swf');
        $fileElement     = 'upload_file'.$id;
        $filepath_rel    = 'userfiles/upload/userimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径

        try{
            $fup = new FileUpload('50M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);
            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;
            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;
    case 'newspic'://
        //上传
        $allowext        = array('png','gif','jpg','jpeg');
        $fileElement     = 'file';
        $filepath_rel    = 'userfiles/upload/userimg/';//相对路径
        $filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径


        if(!file_exists($filepath_rel))
        {
            mkdir($filepath_rel,0777,true);
        }


        try{
            $fup = new FileUpload('5M', $allowext);
            $r = $fup->upload($fileElement, $filepath_abs, '', true);

            $name_abs = $filepath_abs.$r;
            $name_rel = $filepath_rel.$r;

            //上传成功
            echo action_msg($name_rel, 1);
        }catch(Exception $e){
            echo action_msg($e->getMessage(), $e->getCode());
        }
        break;
    default:
        break;
}

?>