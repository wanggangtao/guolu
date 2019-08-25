<?php
/**
 * 文件上传 file_upload.php
 *
 */



try {



    $allowext = array ( 'png', 'jpg', 'jpeg', 'gif','mp4','doc','txt','xls','xlsx','docx','pdf','ppt','pptx');
    $fileElement = 'file';




    $filepath_rel = 'userfiles/upload/chatfile/'.date("Ymd")."/"; // 相对路径
    $filepath_abs = $FILE_PATH . $filepath_rel;     // 绝对路径


    if(!file_exists($filepath_abs))
    {
        mkdir($filepath_abs,0777,true);
    }


    $fup = new FileUpload ( '100M', $allowext );
    $r = $fup->upload ( $fileElement, $filepath_abs, '', true );

    $name_abs = $filepath_abs . $r;
    $name_rel = $filepath_rel . $r;

    //图片等比例压缩
    $pic = $fup->getThumb($FILE_PATH,$name_rel,300,300);
    $resultData = array("url" => $name_rel,"url_show" => $HTTP_PATH.$name_rel,"url_extra"=>$pic,"extra_show"=>$HTTP_PATH.$pic);

    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);

} catch ( Exception $e ) {

    $api->ApiError($e->getCode(), $e->getMessage());

}

?>