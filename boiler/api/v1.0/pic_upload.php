<?php
/**
 * 文件上传 file_upload.php
 *
 */
require ($LIB_COMMON_PATH . 'fileupload.class.php');


$allowext = array ( 'png', 'jpg', 'jpeg', 'gif');
$fileElement = 'file';
$filepath_rel = 'userfiles/upload/projectPic/'; // 相对路径
$filepath_abs = $FILE_PATH . $filepath_rel;     // 绝对路径

try {
	$fup = new FileUpload ( '50M', $allowext );
	$r = $fup->upload ( $fileElement, $filepath_abs, '', true );
	
	$name_abs = $filepath_abs . $r;
	$name_rel = $filepath_rel . $r;

    $resultData = array("url" => $name_rel,"url_show" => $HTTP_PATH.$name_rel);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);

} catch ( Exception $e ) {

    $api->ApiError($e->getCode(), $e->getMessage());

}

?>