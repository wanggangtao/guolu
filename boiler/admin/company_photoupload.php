<?php
/**
 * 企业logo上传  company_logo_pload.php
 *
 * @version       v0.01
 * @create time   2017/10/06
 * @update time
 * @author        xp
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');



require($LIB_COMMON_PATH . 'fileupload.class.php');
//require($LOCAL_FILE_PATH . 'fileupload.class.php'); // 本地测试用


//测试Loading效果
//sleep(10);

//上传
$allowext = array('jpg', 'png', 'gif'
//    //office
//    'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
//    //文档
//    'txt', 'pdf' ,
//    //音频视频
//    'mp3', 'mp4', 'flv', 'swf'
);
$fileElement = 'logo';
$filepath_rel = 'userfiles/upload/userimg/';//相对路径
$filepath_abs = $FILE_PATH . $filepath_rel;//绝对路径
//$filepath_abs = $LOCAL_FILE_PATH_UPLOAD . $filepath_rel;//本地测试用绝对路径
try {
    $fup = new FileUpload('40M', $allowext);
    $r = $fup->upload($fileElement, $filepath_abs, '', true);

    $name_abs = $filepath_abs . $r;
    $name_rel = $filepath_rel . $r;

    //检查图片尺寸
    $imgsize = getimagesize($name_abs);
    $imgwidth = $imgsize[0];
    $imgheight = $imgsize[1];

//    if($imgwidth !== 200 || $imgheight !== 150){
//        echo action_msg('图片尺寸大小不正确', 1001);
//        exit();
//    }

    //上传成功
    echo action_msg($name_rel, 1);
//    echo action_msg($name_abs, 1);
} catch (Exception $e) {
    echo action_msg($e->getMessage(), $e->getCode());
}

?>