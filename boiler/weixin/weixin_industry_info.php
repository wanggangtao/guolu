<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/17
 * Time: 22:02
 *
 * 行业资讯
 *
 */
require_once('../init.php');
$situationlist = weixin_situation::getListWeiXin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>锅炉常识</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
</head>
<body>
<div id="app">
    <div class="industry">
        <?php
        if($situationlist) {
            foreach ($situationlist as $situation) {
                $time = "";
                if (!empty($situation['addtime'])) {
                    $time = intval(((time() - $situation['addtime']) / 86400));
                }
                echo '<a href="weixin_industry_detail.php?id='.$situation['id'].'" class="industry-item flex">
                    <div class="industry-item-left flex">
                        <p>'.$situation['title'].'</p>';
                        $situation['contents'] = HTMLDecode($situation['content']?$situation['content']:'');
                        $situation['contentss'] = strip_tags($situation['contents']);
                        $situation['content'] = mb_substr($situation['contentss'],0,25);
                        echo '<span>'.date('Y/m/d',$situation['addtime']) .'</span>
                    </div>';
                    if($situation['picurl']){
                        echo '<div class="industry-item-right" style="border:0px;">
                        <img src="'.$HTTP_PATH . $situation['picurl'].'" alt="" />
                        </div>';
                    }

                echo '</a>';
            }
        }
        else{
            echo '<div class="industry-item-left flex">
                <span >暂无公司动态</span>
                </div>';
        }
        ?>
    </div>
</div>
</body>
<script src="static/js/jquery.min.js"></script>
<script>
    $(function () {

    })
</script>

<!--if($pagecount>1){
$dispage = dspPagesForMin(getPageUrl(),$page,$pagesize,$listcount, $pagecount);
echo '<div class="industry-item-left flex"><span>'.$dispage.'</span></div>';
}-->
<!--if(isset($situation['content'])){
$string = $situation["content"];
$html_string = htmlspecialchars_decode($string);
$content = str_replace(" ", "", $html_string);
$contents = strip_tags($string);
$qian=array(" ","　","\t","\n","\r");
$contentss=str_replace($qian,'',$contents);
$contentsss=preg_replace("/&[a-z]+\;/i",'',$contentss);
}-->
</html>


