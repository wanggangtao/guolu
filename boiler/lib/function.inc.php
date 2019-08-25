<?php

/**
 * function.inc.php  与业务有关的函数
 *
 * @version       v0.01
 * @create time   2014/9/1
 * @update time   
 * @author        jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

/**
 * 获得执行程序的时间(秒)
 * 
 * @param $starttime 
 * @param $endtime
 *
 * @return
 */
function getRunTime($starttime = 0, $endtime = 0){
	global $PageStartTime;
	if(empty($starttime)){
		$starttime = $PageStartTime;
	}
	if(empty($endtime)){
		$PageEndTime = explode(' ',microtime());
		$PageEndTime = $PageEndTime[1] + $PageEndTime[0];
		$endtime = $PageEndTime;
	}
	
	$runtime = number_format(($endtime - $starttime), 3);
	return $runtime;
}

/**
 * 分页参数page传递后的处理
 * 
 * @param mixed $pagecount 页数
 * @return
 */
function getPage($pagecount){

	$page = empty($_GET['page']) ? 1 : safeCheck(trim($_GET['page']));
	if(!is_numeric($page)) $page = 1;
	if($page < 1) $page = 1;
    if(empty($pagecount)) 
        $page = 1;
	elseif($page > $pagecount) 
        $page = $pagecount;

	return $page;
}
/**
 * 分页显示 dspPages()--具体样式再通过CSS控制
 * 形如：
 * 1 2 3 × × × 98 99 100
 * 1 × × × 7 8 9 × × × 100
 *
 * @param $url       链接URL
 * @param $page      当前页数
 * @param $pagesize  页数
 * @param $rscount   记录总数
 * @param $pagecount 总页数
 * @return
 */
function dspPages($url, $page, $pagesize, $rscount, $pagecount){
		
		//参数安全处理
		$url  = str_replace(array(">", "<"), array("&gt;", "&lt;"), $url);
		if(!is_numeric($page))       $page = 0;
		if(!is_numeric($pagesize))   $pagesize = 0;
		if(!is_numeric($rscount))    $rscount = 0;
		if(!is_numeric($pagecount))  $pagecount = 0;
		
		//处理Page参数
		$p1 = strpos($url, '?page=');
        if($p1) $url = substr($url, 0, $p1);
        
        $p2 = strpos($url, '&page=');
        if($p2) $url = substr($url, 0, $p2);
		
		//构建显示
		$temppage="";
		$temppage.="<div class=\"pagenum\">";

		if($page>1){
			$temppage.="<div class=\"page_prev\"><a href=\"".$url."?page=".($page-1)."\">上一页</a></div>";
		}else{
			$temppage.="<div class=\"page_prev\">上一页</div>";
		}
		
		If($pagecount<9){
			for($p=1;$p<=$pagecount;$p++){
				if($p!=$page)
					$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				else
					$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
			}
		}else{
			if($page<=3){
				for($p=1;$p<=5;$p++){
					if($p!=$page)
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					else
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
				$temppage.=" <div class=\"pager\">...</div>";
				for($p=$pagecount-3;$p<=$pagecount;$p++){
					if($p!=$page)
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					else
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
			}else if($pagecount-$page<=3){
				for($p=1;$p<=3;$p++){
					$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
				}
				$temppage.="<div class=\"pager\">...</div>";
				for($p=$pagecount-4;$p<=$pagecount;$p++){
					if($p!=$page){
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}else{
						$temppage.=" <div class=\"pager active\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}
				}
			}
			else{
				$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=1\">1</a></div>";
				$temppage.=" <div class=\"pager\">...</div>";
				for($p=$page-2;$p<=$page+2;$p++){
					if($p!=$page){
						$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$p."\">".$p."</a></div>";
					}else{
						$temppage.=" <div class=\"pager active\">".$p."</div>";
					}
				}
				$temppage.="<div class=\"pager\">...</div>";
				$temppage.=" <div class=\"pager\"><a href=\"".$url."?page=".$pagecount."\">".$pagecount."</a></div>";
			}
		}

		if($page<=$pagecount-1){
			$temppage.="<div class=\"page_prev\"><a href=\"".$url."?page=".($page+1)."\">下一页</a></div>";
		}else{
			$temppage.="<div class=\"page_prev\">下一页</div>";
		}
		
		$temppage .="</div>";		


		if(!strpos($url, "?") === false)
			$temppage=str_replace("?page=", "&page=", $temppage);

		return $temppage;
}

/**
 * 分页显示 dspPages()--具体样式再通过CSS控制
 * 形如：
 * 1 2 3 × × × 98 99 100
 * 1 × × × 7 8 9 × × × 100
 *
 * @param $url       链接URL
 * @param $page      当前页数
 * @param $pagesize  页数
 * @param $rscount   记录总数
 * @param $pagecount 总页数
 * @return
 */
function getUrlExcludePage($url)
{
    $parsed_url = parse_url($url);
    if(!array_key_exists("query", $parsed_url)){
        return $url;
    }

    parse_str($parsed_url["query"], $query_array);
    if(array_key_exists("page", $query_array)) {
        unset($query_array["page"]);
    }
    $query_str = http_build_query($query_array);

    if(!empty($query_str))
    {
        $return_url = $parsed_url["path"]."?$query_str";

    }
    else
    {
        $return_url = $parsed_url["path"];
    }

    return $return_url;
}



/**
 * 方案池前端分页界面
 * @param $url
 * @param $page
 * @param $pagesize
 * @param $rscount
 * @param $pagecount
 * @return string
 */
function dspPagesForPool($url, $page, $pagesize, $rscount, $pagecount){

    //参数安全处理
    $url  = str_replace(array(">", "<"), array("&gt;", "&lt;"), $url);
    if(!is_numeric($page))       $page = 0;
    if(!is_numeric($pagesize))   $pagesize = 0;
    if(!is_numeric($rscount))    $rscount = 0;
    if(!is_numeric($pagecount))  $pagecount = 0;

    $url = getUrlExcludePage($url);


    $temppage="";
    $temppage.="<div class=\"pagination\">";


    $temppage.="<span class=\"table_info\" style='float: left'>共 $rscount 条数据，共 $pagecount 页</span>";
    if($page>1){
        $temppage.="<a href=\"".$url."?page=1\" >首页</span></a>";
    }else{
        $temppage.="<a href=\"#\" >首页</span></a>";
    }

    If($pagecount<9){

        for($p=1;$p<=$pagecount;$p++){
            if($p!=$page)
                $temppage.=" <a href=\"".$url."?page=".$p."\">".$p."</a>";
            else
                $temppage.=" <a href=\"#\"  class=\"hover\">".$p."</a>";
        }
    }else{

        if($page<=3){
            for($p=1;$p<=5;$p++){
                if($p!=$page)
                    $temppage.=" <a href=\"".$url."?page=".$p."\">".$p."</a>";
                else
                    $temppage.="<a href=\"#\" class=\"hover\">".$p."</a>";
            }
            $temppage.=" <span class=\"more\"></span>";
            for($p=$pagecount-3;$p<=$pagecount;$p++){
                if($p!=$page)
                    $temppage.=" <a href=\"".$url."?page=".$p."\">".$p."</a>";
                else
                    $temppage.="<a href=\"#\" class=\"hover\">".$p."</a>";
            }
        }else if($pagecount-$page<=3){

            for($p=1;$p<=3;$p++){
                $temppage.=" <a href=\"".$url."?page=".$p."\">".$p."</a>";
            }
            $temppage.="<span class=\"more\"></span>";
            for($p=$pagecount-4;$p<=$pagecount;$p++){
                if($p!=$page){
                    $temppage.=" <a href=\"".$url."?page=".$p."\">".$p."</a>";
                }else{
                    $temppage.=" <a href=\"#\" class=\"hover\">".$p."</a>";
                }
            }
        }
        else{

            $temppage.=" <a href=\"".$url."?page=1\">1</a>";
            $temppage.=" <span class=\"more\"></span>";
            for($p=$page-2;$p<=$page+2;$p++){
                if($p!=$page){
                    $temppage.=" <a href=\"".$url."?page=".$p."\">".$p."</a>";
                }else{
                    $temppage.=" <a href=\"#\" class=\"hover\">".$p."</a>";

                }
            }
            $temppage.="<span class=\"more\"></span>";
            $temppage.=" <a href=\"".$url."?page=".$pagecount."\">".$pagecount."</a>";
        }
    }

    if($page<=$pagecount){
        $temppage.="<a href=\"".$url."?page=".$pagecount."\" >末页</a> 
          
                <span style=\"line-height:32px;font-size:14px;color:#686868\">跳转到第<input style=\"width:35px;padding:0px 2px;margin:0px 5px;\" type=\"text\"   id=\"page1\"  />页</span>
                <button class=\"form_btn\" id=\"page\"  >跳转</button> 
                 <script>
                $('#page').click(function(){
                    var page = $('#page1').val();
                    window.location.href = '{$url}?page='+page;
                })
               </script>  
                ";
    }else{
        $temppage.="<a href=\"#\" >末页</a>";
    }

    $temppage .="</div>";


    if(!strpos($url, "?") === false)
        $temppage=str_replace("?page=", "&page=", $temppage);

    return $temppage;
}


/**
 * 获得当前页面的URL地址
 *
 * @return
 */
function getPageUrl(){
    $url = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : urlencode($_SERVER['PHP_SELF']) . '?' . urlencode($_SERVER['QUERY_STRING']);
    return $url;
}



function explodeNew ($info)
{
    $results=array();
    $find = array(
        ",",
        '，',
        "~",
        "～",
        " ",
    );

    $re = "#";
    $info = str_replace($find,$re,trim($info));
    foreach(explode("#",$info) as $item)
    {
        if(!empty($item)) $results[] = $item;
    }

    return $results;

}


/**
 * 负责导出word
 * @param $content
 * @param string $absolutePath
 * @param bool $isEraseLink
 * @return string
 */
function getWordDocument( $content , $absolutePath = "" , $isEraseLink = true ){
    $mht = new MhtFileMaker();
    if ($isEraseLink)
        $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i' , '$1' , $content);   //去掉链接


    $images = array();
    $files = array();
    $matches = array();
    //这个算法要求src后的属性值必须使用引号括起来
    if ( preg_match_all('/src=\"([^\"]+)\"/iU',$content ,$matches ) ) {

        $arrPath = $matches[1];
        for ( $i=0;$i<count($arrPath);$i++) {
            $path = $arrPath[$i];
            $imgPath = trim( $path );
            if ( $imgPath != "" ) {
                $files[] = $imgPath;
                if( substr($imgPath,0,7) == 'http://') {
                    //绝对链接，不加前缀
                } else {
                    $imgPath = $absolutePath.$imgPath;
                }
                $images[] = $imgPath;
            }
        }
    }

    $mht->AddContents("tmp.html",$mht->GetMimeType("tmp.html"),$content);


    for ( $i=0;$i<count($images);$i++) {
        $image = $images[$i];
        if ( @fopen($image , 'r') ) {
            $imgcontent = @file_get_contents( $image );
            if ( $content )
                $mht->AddContents($files[$i],$mht->GetMimeType($image),$imgcontent);
        }
        else {
            echo "file:".$image." not exist!<br />";
        }
    }


    return $mht->GetFile();
}

/**
 * 锅炉前端分页
 */
function dspPagesForMin($url, $page, $pagesize, $rscount, $pagecount)
{
    if ($pagecount > 1) {
        //参数安全处理
        $url = str_replace(array(">", "<"), array("&gt;", "&lt;"), $url);
        if (!is_numeric($page)) $page = 0;
        if (!is_numeric($pagesize)) $pagesize = 0;
        if (!is_numeric($rscount)) $rscount = 0;
        if (!is_numeric($pagecount)) $pagecount = 0;

        $url = getUrlExcludePage($url);


        $temppage = "";
        $temppage .= "<nav class=\"PageBox martop80\"><ul class=\"pagination\">";

        if ($page > 1) {
            $temppage .= "<li class=\"edgetPage\"><a href=\"" . $url . "?page=1\"><span>首页</span></a></li>";
        } else {
            $temppage .= "<li class=\"edgetPage disable\"><a href=\"#\"><span >首页</span></a></li>";
        }

        If ($pagecount < 9) {

            for ($p = 1; $p <= $pagecount; $p++) {
                if ($p != $page)
                    $temppage .= " <li><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                else
                    $temppage .= " <li class=\"active\"><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
            }
        } else {

            if ($page <= 3) {
                for ($p = 1; $p <= 5; $p++) {
                    if ($p != $page)
                        $temppage .= " <li><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                    else
                        $temppage .= "<li class=\"active\"><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                }
                $temppage .= " <li><a href='#'>...</a></li>";
                for ($p = $pagecount - 3; $p <= $pagecount; $p++) {
                    if ($p != $page)
                        $temppage .= " <li><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                    else
                        $temppage .= "<li class=\"active\"><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                }
            } else if ($pagecount - $page <= 3) {

                for ($p = 1; $p <= 3; $p++) {
                    $temppage .= " <li><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                }
                $temppage .= "<li><a href='#'>...</a></li>";
                for ($p = $pagecount - 4; $p <= $pagecount; $p++) {
                    if ($p != $page) {
                        $temppage .= " <li><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                    } else {
                        $temppage .= " <li class=\"active\"><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                    }
                }
            } else {
                $temppage .= " <li><a href=\"" . $url . "?page=1\">1</a></li>";
                $temppage .= " <li><a href='#'>...</a></li>";
                for ($p = $page - 2; $p <= $page + 2; $p++) {
                    if ($p != $page) {
                        $temppage .= " <li><a href=\"" . $url . "?page=" . $p . "\">" . $p . "</a></li>";
                    } else {
                        $temppage .= " <li class=\"active\"><a href=\"#\">" . $p . "</a></li>";

                    }
                }
                $temppage .= "<li><a href='#'>...</a></li>";
                $temppage .= " <li><a href=\"" . $url . "?page=" . $pagecount . "\">" . $pagecount . "</a></li>";
            }
        }


        if ($page <= $pagecount - 1) {
            $temppage .= "<li class=\"edgetPage\"><a href=\"" . $url . "?page=" . $pagecount . "\">末页</a></li>";
        } else {
            $temppage .= "<li class=\"edgetPage disable\"><a href=\"#\">末页</a></li>";
        }


        $temppage .= "<p>共{$rscount}条数据</p>";
        $temppage .= "</ul></nav>";


        if (!strpos($url, "?") === false)
            $temppage = str_replace("?page=", "&page=", $temppage);

        return $temppage;
    }
}

?>

