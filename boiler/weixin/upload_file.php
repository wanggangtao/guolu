<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/20
 * Time: 14:43
 */

require_once "admin_init.php";

$allowext     = array('jpg', 'png', 'gif','jpeg');
$fileElement  = 'file';
$filepath_rel = 'userfiles/weixin/upload/' . date('Ymd') . '/';//相对路径
$filepath_abs = $FILE_PATH . $filepath_rel;//绝对路径
if (!file_exists($filepath_abs)) {
    mkdir($filepath_abs, 0777,true);
    chmod($filepath_abs, 0777);

}
try {
    $fup = new FileUpload('8M',$allowext);
    $r   = $fup->upload($fileElement, $filepath_abs, '', true);
    $name_rel = $filepath_rel . $r;


    echo action_msg($name_rel, 1);
} catch (Exception $e) {
    echo action_msg($e->getMessage(), $e->getCode());
}

?>