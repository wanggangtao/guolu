<?php
/**
	 * 文件上传 file_upload.php
	 *
	 * @version       v0.01
	 * @create time   2016/9/7
	 * @update time   
	 * @author        wzp
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
require_once ('web_init.php');
require ($LIB_COMMON_PATH . 'fileupload.class.php');

// 上传
$allowext = array ('png', 'jpg', 'jpeg', 'gif', 'bmp');
$fileElement = 'file';
$filepath_rel = 'userfiles/upload/projectPic/'.date("Ymd")."/"; // 相对路径
$filepath_abs = $FILE_PATH . $filepath_rel; // 绝对路径

if(!file_exists($filepath_abs))
{
    mkdir($filepath_abs,0777);
}

try {
	$fup = new FileUpload ( '10M', $allowext );
	$r = $fup->upload ( $fileElement, $filepath_abs, '', true );
	
	$name_abs = $filepath_abs . $r;
	$name_rel = $filepath_rel . $r;
	
	// 上传成功
	echo action_msg ( $name_rel, 1 );
} catch ( Exception $e ) {
	echo action_msg ( $e->getMessage (), $e->getCode () );
}

?>