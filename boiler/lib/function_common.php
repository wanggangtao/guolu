<?php
error_reporting(0);
/**
 * function_common.php 常用函数文件，与业务无关的函数
 *
 * @version       v0.02
 * @createtime    2014/7/24
 * @updatetime    2016/3/19 2016/7/27
 * @author        jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 * 2016/7/27 将一些功能相近的函数整理成类
 */

/**
 * safeCheck() 参数检查，并防XSS 和 SQL注入
 * 
 * @param mixed $str   
 * @param bool $number 是否做数字检查 1-（默认）数字 0-不是数字
 * @param bool $script 是否过滤script 1-（默认）过滤；0-不过滤
 * @return
 */
function safeCheck($str, $number = 1, $script = 1){
	$str = trim($str);
	//防止SQL注入
	if(!get_magic_quotes_gpc()){
		$str = addslashes($str);
	}
	//数字检查
	if($number == 1){
		$isint   = preg_match('/^-?\d+$/',$str);
		$isfloat = preg_match('/^(-?\d+)(\.\d+)?$/',$str);
		if(!$isint && !$isfloat){
			die('参数'.$str.'必须为数字');
		}
	}else{
		//过滤script、防XSS
		if($script == 1){
			$str = str_replace(array(">", "<"), array("&gt;", "&lt;"), $str);
		}
	}
	return $str;
}
function strCheck($str, $number = 1, $script = 1){
    $str = trim($str);
    //防止SQL注入
    if(!get_magic_quotes_gpc()){
        $str = addslashes($str);
    }
    //数字检查
    if($number == 1){
        $isint   = preg_match('/^-?\d+$/',$str);
        $isfloat = preg_match('/^(-?\d+)(\.\d+)?/',$str);
        if(!$isint && !$isfloat){
            die('参数必须为数字');
//            throw new MyException("参数必须为数字", 110);
        }
    }else{
        //过滤script、防XSS
        if($script == 1){
            $str = str_replace(array(">", "<"), array("&gt;", "&lt;"), $str);
        }
    }
    return $str;
}
/**
 * ckReplace()  ckEditor编辑器内容处理
 * 
 * @param mixed $content
 * @return
 */
function ckReplace($content){
	if (!empty($content)){
		$content = str_replace("'", "&#39;", $content);
        $content = str_replace("<br />", "</p><p>", $content);
	}
	return $content;
}

/**
 * HTMLEncode()将特殊字符转成HTML格式，主要用于textarea获取值 
 * 
 * @param mixed $str
 * @return
 */
function HTMLEncode($str){
	if (!empty($str)){
		$str = str_replace("&","&amp;",$str);
		$str = str_replace(">","&gt;",$str);
		$str = str_replace("<","&lt;",$str);
		$str = str_replace(CHR(32),"&nbsp;",$str);
		$str = str_replace(CHR(9),"&nbsp;&nbsp;&nbsp;&nbsp;",$str);
		$str = str_replace(CHR(9),"&#160;&#160;&#160;&#160;",$str);
		$str = str_replace(CHR(34),"&quot;",$str);
		$str = str_replace("'","&#39;",$str);
		$str = str_replace(CHR(39),"&#39;",$str);
		$str = str_replace(CHR(13),"",$str);
		$str = str_replace(CHR(10),"<br/>",$str);
	}
	return $str;
}

/**
 * HTMLDecode()将HTMLEncode的数据还原 
 * 
 * @param mixed $str
 * @return
 */
Function HTMLDecode($str){
	if (!empty($str)){
		$str = str_replace("&amp;","&",$str);
		$str = str_replace("&gt;",">",$str);
		$str = str_replace("&lt;","<",$str);
		$str = str_replace("&nbsp;",CHR(32),$str);
		$str = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;",CHR(9),$str);
		$str = str_replace("&#160;&#160;&#160;&#160;",CHR(9),$str);
		$str = str_replace("&quot;",CHR(34),$str);
		$str = str_replace("&#39;",CHR(39),$str);
		$str = str_replace("",CHR(13),$str);
		$str = str_replace("<br/>",CHR(10),$str);
		$str = str_replace("<br />",CHR(10),$str);
		$str = str_replace("<br>",CHR(10),$str);
	}
	return $str;
}

/**
 * 生成随机数randcode()
 * 
 * @param mixed $len
 * @param integer $mode
 * @return
 */
function randcode($len, $mode = 2){
	$rcode = '';

	switch($mode){
		case 1: //去除0、o、O、l等易混淆字符
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghijkmnpqrstuvwxyz';
			break;
		case 2: //纯数字
			$chars = '0123456789';
			break;
		case 3: //全数字+大小写字母
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			break;
		case 4: //全数字+大小写字母+一些特殊字符
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz~!@#$%^&*()';
			break;
	}
	
	$count = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $len; $i++) {
		$rcode .= $chars[mt_rand(0, $count)];
	}
	
	return $rcode;
}

/**
 * Json_encode的Unicode中文(\u4e2d\u56fd)问题
 * 
 * @param mixed $array
 * @return
 */
//function json_encode_cn($array){
//	$str = json_encode($array);
//	$os  = Env::getOSType();
//	if($os == 'windows')
//		$ucs = 'UCS-2';
//	else
//		$ucs = 'UCS-2BE';
//
//	if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
//		$str = preg_replace_callback("/\\\\u([0-9a-f]{4})/i", create_function('$matches', 'return iconv("'.$ucs.'", "UTF-8", pack("H*", $matches[1]));'), $str);
//	}else{
//		$str = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('".$ucs."', 'UTF-8', pack('H4', '\\1'))", $str);
//	}
//	return $str;
//}
function json_encode_cn($array){
    $str = json_encode($array);
    $os  = Env::getOSType();
    if($os == 'windows')
        $ucs = 'UCS-2';
    else
        $ucs = 'UCS-2BE';
    if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
        $str = preg_replace_callback("/\\\\u([0-9a-f]{4})/i", function ($matches) use ($ucs){ return iconv($ucs, "UTF-8", pack("H*", $matches[1])); }, $str);
    }else{
        $str = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('".$ucs."', 'UTF-8', pack('H4', '\\1'))", $str);
    }
    return $str;
}


/**
 * 操作响应通知(默认json格式)
 * 
 * @param $msg  消息内容
 * @param $code 消息代码
 * @return
 */
function action_msg($msg, $code, $json = true){
	$r = array(
		'code' => $code,
		'msg'  => $msg
	);
	if($json) 
		return json_encode_cn($r);
	else
		return $r;
}



/**
 * 操作响应通知(默认json格式)
 *
 * @param $msg  消息内容
 * @param $code 消息代码
 * @return
 */
function action_msg_data($msg, $code,$data=array(), $json = true){
    $r = array(
        'code' => $code,
        'message'  => $msg,
        'data'=>$data
    );
    if($json)
        return json_encode_cn($r);
    else
        return $r;
}



function  subStrContent($content,$len,$dian=0)
{

    if(mb_strlen($content,"UTF-8")>$len)
    {

        $content = mb_substr($content,0,$len,"utf-8");
        if($dian)
        {
            $content .="...";
        }

    }


    return $content;
}

/**
 * getDateStrS()    时间格式转换
 */
function getDateStrS($time){

    if(empty($time)) return "";

    $todayBegin = strtotime(date("Y-m-d"));

    $diff = $time - $todayBegin;
    if($diff>=0)
        return date('H:i', $time);
    else if($diff<0&&$diff>-86400)
        return '昨天';
    else
        return date('Y/m/d H:i:s', $time);
}

/**
 * getDateStr()  时间格式转换
 */
function getDateStr($time){
    if($time)
        return date('Y-m-d H:i:s', $time);
    else
        return '';
}

/**
 * getDateStrI()  时间格式转换
 */
function getDateStrI($time){
    if($time)
        return date('Y-m-d H:i', $time);
    else
        return '';
}
/**
 * getDateStrI()  时间格式转换
 */
function getDateStrC($time){
    if($time)
        return date('Y-m-d', $time);
    else
        return '';
}
function wordCode($parameter){
    $arr = explode('<br/>',$parameter);
    if(!is_array($arr)){
        $arr = explode('<br />',$parameter);
    }
    $str='';
    foreach($arr as $val){
        //这里是先去掉空格等一般样式
        $val = HTMLDecode($val);
        $val = str_replace("<", "＜", $val);
        $val = str_replace(">", "＞", $val);
        $str.= '<w:p><w:r><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="10.5"/></w:rPr><w:t>'.htmlspecialchars($val, ENT_COMPAT).'</w:t></w:r></w:p>';
    }
    //这里是转换上面的<w:p>等
    $str = HTMLDecode($str);
    return $str;
}

/**
 * wordPrintReplace()  写入word特殊字符替换
 *
 * @param mixed $content
 * @return
 */
function wordPrintReplace($content){
    if (!empty($content)){
        $content = str_replace("&", "＆", $content);
    }
    return $content;
}


/**
 * 获取首字母
 * @param $str
 * @return null|string
 */
function getFirstCharter($str)
{
    if (empty($str)) {
        return '';
    }

    $fchar = ord($str{0});
    if ($fchar >= ord('A') && $fchar <= ord('z'))
        return strtoupper($str{0});

    $s1 = iconv('UTF-8','gbk//ignore', $str);
    $s2 = iconv('gbk', 'UTF-8//ignore', $s1);
    $s = $s2 == $str ? $s1 : $str;

    if($s{1}){
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    }else{
        return null;
    }


    if ($asc >= -20319 && $asc <= -20284)
        return 'A';

    if ($asc >= -20283 && $asc <= -19776)
        return 'B';

    if ($asc >= -19775 && $asc <= -19219)
        return 'C';

    if ($asc >= -19218 && $asc <= -18711)
        return 'D';

    if ($asc >= -18710 && $asc <= -18527)
        return 'E';

    if ($asc >= -18526 && $asc <= -18240)
        return 'F';

    if ($asc >= -18239 && $asc <= -17923)
        return 'G';

    if ($asc >= -17922 && $asc <= -17418)
        return 'H';

    if ($asc >= -17417 && $asc <= -16475)
        return 'J';

    if ($asc >= -16474 && $asc <= -16213)
        return 'K';

    if ($asc >= -16212 && $asc <= -15641)
        return 'L';

    if ($asc >= -15640 && $asc <= -15166)
        return 'M';

    if ($asc >= -15165 && $asc <= -14923)
        return 'N';

    if ($asc >= -14922 && $asc <= -14915)
        return 'O';

    if ($asc >= -14914 && $asc <= -14631)
        return 'P';

    if ($asc >= -14630 && $asc <= -14150)
        return 'Q';

    if ($asc >= -14149 && $asc <= -14091)
        return 'R';

    if ($asc >= -14090 && $asc <= -13319)
        return 'S';

    if ($asc >= -13318 && $asc <= -12839)
        return 'T';

    if ($asc >= -12838 && $asc <= -12557)
        return 'W';

    if ($asc >= -12556 && $asc <= -11848)
        return 'X';

    if ($asc >= -11847 && $asc <= -11056)
        return 'Y';

    if ($asc >= -11055 && $asc <= -10247)
        return 'Z';

    return null;

}








function img_text($img, $new_img, $text, $text_size, $text_angle, $text_x, $text_y, $text_font, $r, $g, $b){

    //$text=iconv("gb2312","UTF-8",$text);

    if(file_exists($new_img))
        return;
    $img_info = getimagesize($img);
    $col = $img_info[0] / 300;
    $row = $img_info[1] / 100;
    $im = @imagecreatefromstring(file_get_contents($img)) or die ("打开图片失败!");
    $color = ImageColorAllocate($im, $r,$g,$b);
    for ($i=0; $i <= $col; $i++) {
        for ($j=0; $j < $row; $j++) {
            ImageTTFText($im, $text_size, $text_angle, $text_x+$i*300, $text_y+100*$j, $color, $text_font, $text);
        }

        //ImageTTFText($im, $text_size, $text_angle, $text_x+300*$i, $text_y+100*$j, $color, $text_font, $text);
    }


    if ($new_img==""):
        ImageGif($im); // 不保存图片,只显示
    else:
        ImageGif($im,$new_img); // 保存图片,但不显示
    endif;

    ImageDestroy($im); //结束图形，释放内存空间
}



/**
 * 实现转pdf功能
 * @param string $wordpath  要转换为PDF的word文件路径，全路径
 * @param string $outPdfPath  转换成功后的PDF文件路径
 * @param string $jodconverterPath 安装的jodconverter的jodconverter-cli-2.2.2.jar所在路径，服务器
 *
 * $jodconverter_path = '/opt/jodconverter/lib/jodconverter-cli-2.2.2.jar';
 * @return
 */
function word2pdf ($infile_path, $outfile_path, $jodconverter_path='/opt/jodconverter/lib/jodconverter-cli-2.2.2.jar') {
    if (empty($infile_path)) return false;
    if(file_exists($outfile_path))
        return;
    try {
        $p = "/opt/java/jdk1.8.0_181/bin/java -jar ". $jodconverter_path. ' '. $infile_path. ' '. $outfile_path;



        echo exec($p, $out, $status);
        //print_r($status);
        return $out;
    } catch (Exception $e) {
        return false;
    }
}


/**
 *  添加水印
 * @param string $infile_path 要添加水印的源PDF文件路径，全路径
 * @param string $outfile_path  转换成功后的PDF文件路径
 * @return
 */
function watermark ($infile_path, $outfile_path, $username) {
    //$pdf = new FPDI();
    $pdf = new PDF_Chinese();
    //获取pdf页数
    $pageCount = $pdf->setSourceFile($infile_path);
    $pdf->AddGBFont('msyh','微软雅黑');
    //遍历所有页数
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++){
        // import a page
        $templateId = $pdf->importPage($pageNo);

        // get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);

        // create a page (landscape or portrait depending on the imported page size)
        if ($size['w'] > $size['h']) $pdf->AddPage('L', array($size['w'], $size['h']));
        else $pdf->AddPage('P', array($size['w'], $size['h']));


        //$pdf->AddGBFont('simhei', '黑体');
        //$pdf-> AddFont ('simhei');
        $pdf->SetFont('msyh','I','16');
        $pdf->SetTextColor(210,210,210);
        // $pdf->SetFillColor(100,255,10);
        // sign with current date
        for ($i=0; $i < 9; $i++) {
            $pdf->SetXY(10, $i * 30);
            $pdf->Write(10, iconv('utf-8', 'gbk', '西安元聚   '. $username).'   '. date('Y-m-d'));
            $pdf->SetXY(120, $i * 30);
            $pdf->Write(10, iconv('utf-8', 'gbk', '西安元聚   '. $username).'   '. date('Y-m-d'));
        }
        // use the imported page
        $pdf->useTemplate($templateId);


    }
    $pdf->Output('F',  $outfile_path);
}


//图像等比例缩放函数
/**
    *等比例缩放函数（以保存新图片的方式实现）
     *@param string $picname  被缩放的处理图片源
    *@param int $maxx 缩放后图片的最大宽度
     *@param int $maxy 缩放后图片的最大高度
     *@param string $pre 缩放后图片的前缀名
     *@return $string 返回后的图片名称（） 如a.jpg->s.jpg
     *
 **/
      function imageUpdatesize($picname,$maxx=100,$maxy=100,$pre="s_"){
//          print_r($picname);exit();

          $info=getimageSize($picname);//获取图片的基本信息
         $w=$info[0];//获取宽度
         $h=$info[1];//获取高度
         //获取图片的类型并为此创建对应图片资源
//          print_r($info);exit();
         switch($info[2]){
            case 1://gif
                   $im=imagecreatefromgif($picname);
                   break;
             case 2://jpg
                   $im=imagecreatefromjpeg($picname);
                   break;
             case 3://png
                   $im=imagecreatefrompng($picname);
                   break;
             default:
                   die("图像类型错误");
         }
         //计算缩放比例
         if(($maxx/$w)>($maxy/$h)){
                    $b=$maxy/$h;
         }else{
                     $b=$maxx/$w;
        }
         //计算出缩放后的尺寸
         $nw=floor($w*$b);
         $nh=floor($h*$b);
         //创建一个新的图像源（目标图像）
         $nim=imagecreatetruecolor($nw,$nh);
         //执行等比缩放
         imagecopyresampled($nim,$im,0,0,0,0,$nw,$nh,$w,$h);
         //输出图像（根据源图像的类型，输出为对应的类型）
         $picinfo=pathinfo($picname);//解析源图像的名字和路径信息
         $newpicname=$picinfo["dirname"]."/".$pre.$picinfo["basename"];
         switch($info[2]){
                case 1:
                    imagegif($nim,$newpicname);
                 break;
             case 2:
                 imagejpeg($nim,$newpicname);
                 break;
             case 3:
                 imagepng($nim,$newpicname);
                break;

         }
         //释放图片资源
         imagedestroy($im);
         imagedestroy($nim);
        //返回结果
         return $newpicname;
      }

 function filter($str) {
    if($str){
        $name = $str;
        $name = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $name);
        $name = preg_replace('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S','?', $name);
        $return = json_decode(preg_replace("#(\\\ud[0-9a-f]{3})#ie","",json_encode($name)));
        if(!$return){
            return $return;
        }
    }else{
        $return = '';
    }
    return $return;

}

/***
 * @param $html
 * 富文本框中内容
 * @param $length
 * 输出最终结果的字符串长度
 * @return string
 * 去除字符串中的 HTML、XML 以及 PHP 的标签,纯文本内容
 *
 * 去除富文本框中内容中字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
 *
 */

function htmlToTxt($html , $length = -1){

    $html_string = HTMLDecode($html);
    $html_string = htmlspecialchars_decode($html_string);
//将空格替换成空
    $oldchar=array(" ","　","\t","\n","\r");
    $newchar=array("","","","","");
    $content =str_replace($oldchar,$newchar,$html_string);

//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
    $contents = strip_tags($content);


    $text = mb_substr($contents, 0, $length, "utf-8");

    $contents_len = mb_strlen($contents,'UTF-8');

    if($contents_len>$length)
    {
        $text .= "…";
    }
    return $text;
}



?>