<?php

// ini_set('display_errors',1);            //错误信息
// ini_set('display_startup_errors',1);    //php启动错误信息
// error_reporting(-1);                    //打印出所有的 错误信息

require_once('web_init.php');
require_once('../lib/common/fpdf/fpdf.php');
require_once('../lib/common/fpdi/chinese.php');
//require_once('../lib/common/fpdi/fpdi.php');
if(empty($_GET['filepath'])){
    echo '请先上传文件！';
    exit();
}
$filepath = safeCheck($_GET['filepath'], 0);//收到文件相对路径
$username = safeCheck($_GET['username'], 0);//收到用户账号
//userfiles/upload/file/20180803/201808031555353811.docx
$str 	  = trim(strrchr($filepath,'/'), '/');//获取无路径的文件名
$type     = substr($str, strpos($str, '.')); //获取文件类型
$preview_file      = substr($str, 0, strpos($str, '.'));
$filepath_rel      = 'userfiles/upload/file/'. date("Ymd"). '/';//相对路径
$infile_path       = $FILE_PATH. $filepath; //源文件的绝对路径

$jodconverter_path = '/opt/jodconverter/lib/jodconverter-cli-2.2.2.jar';//转换器所在位置
$type = strtolower($type);
$pic_types = ['.png', '.jpg', '.jpeg'];
if(in_array($type, $pic_types)){//图片类型
    $outfile_path1      = $filepath_rel. $preview_file.'2'. $type;//加水印后的文件的相对路径，用于显示
    $outfile_path2      = $FILE_PATH. $filepath_rel. $preview_file.'2'. $type;//加水印后的图片的绝对路径
    $font = '../lib/common/fpdf/font/simsun.ttc';
    $text = mb_convert_encoding('西安元聚 '.$username,"utf-8");
    img_text($infile_path, $outfile_path2, $text.date(' Ymd'), 15, 0, 60, 70, $font, 200, 200, 200);
}else{//文档
    $tmp_path          = $FILE_PATH. $filepath_rel.$preview_file. 'a.pdf';//初始pdf文件绝对路径
    $outfile_path1     = $filepath_rel. $preview_file.'.pdf';//加水印后的pdf文件相对路径
    $outfile_path2     = $FILE_PATH. $filepath_rel. $preview_file.'.pdf';//加水印后的pdf文件绝对路径
    if($type != '.pdf'){
        word2pdf($infile_path, $tmp_path, $jodconverter_path);//转换为pdf
        watermark($tmp_path, $outfile_path2, $username);//添加水印
    }else{
        watermark($infile_path, $outfile_path2, $username);//添加水印
    }
    
}



//$file = fopen($outfile_path2,"r"); // 打开文件

//显示文件内容
// Header("Content-type: application/pdf");
// xiazai :header("Content-Disposition:attachment;filename='2.pdf'");
// echo fread($file,filesize($outfile_path2));
// fclose($file);


// Header("Content-type: application/pdf");// 文件将被称为 2.pdf
// header("Content-Disposition:inline;filename=download.pdf");
// readfile($outfile_path2);



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件预览</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){

            var win = window.open(
                '<?php echo 'http://boiler.xazhima.com/'. $outfile_path1;?>',
                '文件预览','height=500,width=611,scrollbars=yes,status=yes');
            //settimeout(function(){win.document.title = "标题";},6000);

        });

    </script>
</head>
<body class="body_2">


</body>
</html>