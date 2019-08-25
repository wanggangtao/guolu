<?php
/**
 * Created by kjb.
 * Date: 2018/12/8
 * Time: 10:43
 */
require_once('web_init.php');

$TOP_MENU = "company_situation";
$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
$if_change=isset($_SESSION['if_change_situation'])? safeCheck($_SESSION['if_change_situation']): 0;
if(!isset($_GET['page'])&&$if_change==0&&isset($_SESSION['type_s'])){
    if(isset($_SESSION['detail'])&&$_SESSION['detail']==1&&$_SESSION['type_s']==2) {
        $type = 2;
        unset($_SESSION["detail"]);
    }
    else
    {
        $type=1;
    }
}
else
{
    $type=isset($_SESSION['type_s'])? safeCheck($_SESSION['type_s']): 1;
}
if($if_change==1) {$page = 1;}
unset($_SESSION['if_change_situation']);

$bannerlist = Picture::getPageList(1, 99, 1, 2, 1);
$pagesize = 10;

$listcount = Web_situation::getPageListWeb(1, 10, 0, $type);
$pagecount = ceil($listcount/$pagesize);

$situationlist = Web_situation::getPageListWeb($page, $pagesize, 1, $type);

?>
<!DOCTYPE html>
<html>
<head>
    <title>公司动态</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <style type="text/css">
        a:link,a:visited{
            text-decoration:none;  /*超链接无下划线*/
        }
    </style>
    <script type="text/javascript">
        function change(type){
           var now_type=<?php echo $type; ?>;
           var if_change;
           if(now_type!=type){
               if_change=1;
           }

            $.ajax({
                type        : 'POST',
                data        : {
                    type   : type,
                    if_change :if_change
                },
                dataType :    'json',
                url :         'company_situation_change.php',
                success :     function(data){
                   location.reload();
                    var page = location.search.substring(1);
                    if(page!=""&&if_change==1){
                        history.replaceState(null,null,'?page=1');
                    }
                }
            });
        }
    </script>
</head>
<body>
<div class="container">
    <?php require_once ('top.inc.php')?>
    <div class="dynamics_bannner">
        <?php
        if($bannerlist[0]){
                echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        else{
            $bannerlist=Picture::getPageList(1, 99, 1, 2, -1);
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        ?>
    </div>

    <div class="dynamics_body" >
        <div class="body_head">
            <span id="situation">新闻中心</span>
<!--            <img src="imgs/dynamics_word.png"/>-->
            <span style="display: inline-block;margin-left: 14px !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(213,213,213,1);">NEWS</span>
            <?php if($type==1) echo' <p>行业动态</p> ';

            else echo ' <p>公司新闻</p> ';?>
             <p>></p>
            <p>首页</p>
        </div>
        <div class="body_left">
            <div class="body_left_menu">
                <a href="javascript:void(0)" onclick="change(1)"><div <?php if($type == 1) echo ' class="menu_second menu_first"'; else echo 'class="menu_second"'?>>
                <span>行业动态</span>
                    <p>INDUSTRY NEWS</p>
                </div> </a>
                <a href="javascript:void(0)" onclick="change(2)">
                <div <?php if($type == 2) echo ' class="menu_second menu_first"'; else echo 'class="menu_second"'?>>
                    <span>公司新闻</span>
                    <p>COMPANY NEWS</p>
                </div>
                </a>
            </div>
            <img src="imgs/global_menu_line.png"/>
        </div>
        <div class="body_right" >
            <?php
            if($situationlist) {
                foreach ($situationlist as $situation) {
                    $time = "";
                    if (!empty($situation['addtime'])) {
                        $time = intval(((time() - $situation['addtime']) / 86400));
                    }
                    if(isset($situation['content'])){
                        $string = $situation["content"];
                        $html_string = htmlspecialchars_decode($string);
                        $content = str_replace(" ", "", $html_string);
                        $contents = strip_tags($string);
                        $qian=array(" ","　","\t","\n","\r");
                        $contentss=str_replace($qian,'',$contents);
                        $contentsss=preg_replace("/&[a-z]+\;/i",'',$contentss);
                    }
                    echo '
                 <div class="body_item">
                    <img src="' . $HTTP_PATH . $situation['picurl'] . '"/>
                    <div class="body_item_content">
                        <a href="company_situation_detail.php?id='.$situation['id'].'" style="display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">' . $situation['title'] . '</a>  
                        <p>'.$contentsss .'</p>   
                        <div class="body_item_bottom">
                            <p> 浏览量：<span style="margin-right:10px">' . $situation['count'] . '</span> 发布于：<span>' . date('Y/m/d',$situation['addtime']) . '</span></p>                
                        </div>
                    </div>
                 </div>
                ';
                }
            }
            else{
                echo '<span class="font_p" style="margin: auto;">暂无公司动态</span>';
            }
            ?>
            <?php
            if($pagecount>1)echo dspPagesForMin(getPageUrl(),$page,$pagesize,$listcount, $pagecount);
            ?>
        </div>

    </div>

</div>

<?php require_once('foot.inc.php')?>