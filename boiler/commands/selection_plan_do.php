<?php
//以上内容会保存在WORD文件中


require_once ("../init.php");

require_once('../lib/common/fpdf/fpdf.php');
require_once('../lib/common/fpdi/chinese.php');


$needSolve = selection_plan_front::getUnSolve();


if(empty($needSolve)) exit("没有需要处理的文件");



selection_plan_front::update($needSolve["id"],array("status"=>selection_plan_front::SOLVing));


$htmlpath = "{$HTTP_PATH}selection/selection_plan_print.php?planId={$needSolve["id"]}";



/******************开始生成word***********************************/




$content = file_get_contents($htmlpath);

$filecontent = getWordDocument($content,$FILE_PATH);


$date = date("Ymd");
$filepath_rel = "userfiles/upload/plan/".$date."/";
$filepath_abs    = $FILE_PATH.$filepath_rel;//绝对路径


if (!file_exists($filepath_abs)){
    mkdir($filepath_abs,0777,true);
}


$tempFileName = date("YmdHis").randcode(4);


$fileName = "{$tempFileName}.doc";



word::createWord($filecontent,$filepath_abs.$fileName);





/******************开始生成pdf***********************************/

$pdf_filepath_rel = "userfiles/upload/plan/".$date."/pdf/";
$pdf_filepath_abs    = $FILE_PATH.$pdf_filepath_rel;//绝对路径


if (!file_exists($pdf_filepath_abs)){
    mkdir($pdf_filepath_abs,0777,true);
}

$pdfName = "{$tempFileName}.pdf";
word::createPdf($htmlpath,$pdf_filepath_abs.$pdfName);

selection_plan_front::update($needSolve["id"],array("status"=>selection_plan_front::SOLVED,"url"=>$filepath_rel.$fileName,"pdf_url"=>$pdf_filepath_rel.$pdfName));

?>