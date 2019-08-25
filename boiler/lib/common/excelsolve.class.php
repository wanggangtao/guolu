<?php
/**
 * excelsolve.class.php excell处理文件
 *
 * @version       v0.01
 * @create time   2016/4/23
 * @update time   
 * @author        tq
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
class Excelsolve{
    public function __construct() {
    }
	/**
    * 读取excel $filename 路径文件名 $encode 返回数据的编码 默认为utf8
    * xls结尾格式
    */
	public function read($filename,$encode='utf-8'){
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objReader->setReadDataOnly(true);
        PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;

        $objPHPExcel = PHPExcel_IOFactory::load($filename);

		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 2; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {

				$excelData[$row-2][] =trim((string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue());

			}
		}
		return $excelData;
	}
	
	
	/**
	 *普通导出
	 *$data  array 数据集合
	 *$title array 标题集合
	 *$path 路径
	 *
	 */
	static public function export($data,$title,$path,$filename)
	{		
		$objPHPExcel = new PHPExcel();

		for($i=0,$j='A';$i<count($title);$i++,$j++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($j.'1', $title[$i]);
			$objPHPExcel->getActiveSheet()->getDefaultColumnDimension($j)->setWidth(15);
		}

		for($i=0;$i<count($data);$i++)
		{
			for($j=0,$m="A";$j<count($data[$i]);$j++,$m++)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($m.($i+2), $data[$i][$j]);

			}
		}

		 $objPHPExcel->getActiveSheet()->setTitle('User');
		 $objPHPExcel->setActiveSheetIndex(0);
		 $file=$path.$filename;

		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		 $objWriter->save($file);

         $response = array(
			"status"=>1,
			"url"=>$file

		);

		 return json_encode($response);
	}
    
	/**
	 * 存在某一列合并单元格的导出
	 *$data  array 数据集合
	 *$title array 标题集合
	 *$filename string 文件名
	 *$column array
	 *
	 */
	public function mergeExport($data,$title,$filename,$column)
	{	
		$objPHPExcel = new PHPExcel();	    
		for($i=0,$j='A';$i<count($title);$i++,$j++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($j.'1', $title[$i]);
			$objPHPExcel->getActiveSheet()->getDefaultColumnDimension($j)->setWidth(15);
			$objPHPExcel->getActiveSheet()->getStyle($j.'1')->applyFromArray(
			           array(
			                'font' => array (
			                    'bold' => true
			                )
			            )
			
			        );
		}

		$k=0;
	   for($i=0;$i<count($data);$i++)
	   {

		   for($j=0,$m='A';$j<count($data[$i])-1;$j++,$m++)
		   {
			   if($j<$column)
			   {
				$objPHPExcel->getActiveSheet()->setCellValue($m.($k+$i+2), $data[$i][$j]);
			   }
			   else
			   {
				   $childCount = $data[$i][$j];
				   
				   for($j=$column;$j<count($data[$i])-1;$j++)
				   {
					   $objPHPExcel->getActiveSheet()->setCellValue($m.($i+$k+2), $data[$i][$j]);
					   $objPHPExcel->getActiveSheet()->mergeCells( $m.($i+$k+2).":".$m.($i+$k+1+$childCount) );
					   $m++;
				   }
				
				//   $objPHPExcel->getActiveSheet()->setCellValue((++$m).($i+$k+2), $data[$i][++$j]);
				 //  $objPHPExcel->getActiveSheet()->mergeCells( $m.($i+$k+2).":".$m.($i+$k+1+$childCount) );
				   if($childCount>1)
				   {
					   $child = $data[$i]["child"];
						if($child[0][0]!="")
						{
							for($childI=0;$childI<count($child);$childI++)
							{
								$k++;
								for($childJ=0,$childM="A";$childJ<count($child[$childI]);$childJ++,$childM++)
								{
									$objPHPExcel->getActiveSheet()->setCellValue($childM.($k+$i+2), $child[$childI][$childJ]);
								}
							}
						}
				   }

			   }
		   }
	   }
		 $objPHPExcel->getActiveSheet()->setTitle('User');
		 $objPHPExcel->setActiveSheetIndex(0);
		 $file='../userfiles/export/'.$filename;  
		 try
		 {
		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		 $objWriter->save($file);
		 }catch(exception $e)
		 {
           
		 }
		 $response = array(
			"status"=>1,
			"url"=>$file

		);
		 return json_encode($response);
	}
}
?>
