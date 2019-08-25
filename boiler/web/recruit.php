<?php
/**
 * Created by PhpStorm.
 * User: forever
 * Date: 2018/12/8
 * Time: 20:49
 */
require_once ('web_init.php');
$TOP_MENU = "recruit";
error_reporting(0);
$params=array();

$bannerlist = Picture::getPageList(1, 99, 1, 7, 1);

$totalcount= Web_recruit::getCount();



$shownum   = 3;
$pagecount = ceil($totalcount / $shownum);
$page      = getPage($pagecount);

$params["page"] = $page;
$params["pageSize"] = $shownum;
$rows=Web_recruit::getList($params);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>人才招聘</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
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
            $bannerlist=Picture::getPageList(1, 99, 1, 7, -1);
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        ?>
    </div>

    <div class="dynamics_body">
        <div class="body_head">
            <span>人才招聘</span>
            <span style="display: inline-block;margin-left: 14px !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(213,213,213,1);">CAREER</span>
            <p>人才招聘</p>
            <p>></p>
            <p>首页</p>
        </div>
        <?php foreach ($rows as $row){?>
        <table  class="body_table_container" >
            <tr>
                <th colspan="2">招聘岗位</th>
                <th colspan="2">学历要求</th>
                <th colspan="2">招聘人数</th>
                <th colspan="2">工作经验</th>
            </tr>
            <tr class="secont_tr">
                <td colspan="2"><?php print_r($row['station'])?></td>
                <td colspan="2"><?php print_r($ARRAY_Educition_type[$row['education']]) ?></td>
                <td colspan="2"><?php echo $row['number']?></td>
                <td colspan="2"><?php print_r($ARRAY_Experience_type[$row['experience']])?></td>
            </tr>
            <tr class="other_tr">
                <td class="width1_8">薪&nbsp&nbsp&nbsp&nbsp资</td>
                <td colspan="7" style="padding:0px 60px"><?php print_r($ARRAY_Salary_type[$row['salary']]) ?></td>
            </tr>
            <tr class="other_tr">
                <td valign="top">职位描述</td>
                <td colspan="7">
                    <?php echo $row['describe']?></td>
            </tr>
            <tr class="other_tr">
                <td valign="top">任职要求</td>
                <td colspan="7">
                    <?php echo $row['need']?></td>
            </tr>
        </table>
        <?php }?>




    </div>
    <?php
    echo dspPagesForMin(getPageUrl(),$page,$shownum,$totalcount, $pagecount)
    ?>
    <?php
    $html1=getPageUrl();
    $html=getUrlExcludePage($html1)."?page=";
    if(!strpos($html1, "?") === false){
        $html=str_replace("?page=", "&page=", $html);
        if(!strpos($html, "p&p") === false)
            $html=str_replace("&page=", "?page=", $html);}
    ?>
    <script>

        $('#page').click(function(){
            var page = $("#page1").val();
            window.location.href = "<?php echo $html?>"+page;

        })
    </script>
    <nav class="PageBox martop80">
<!--        <ul class="pagination">-->
<!--            <li class="edgetPage disable"><a href="#"><span>首页</span></a></li>-->
<!--            <li class="active"><a href="#">1</a></li>-->
<!--            <li><a href="#" class="active">2</a></li>-->
<!--            <li><a href="#">3</a></li>-->
<!--            <li><a href="#">4</a></li>-->
<!--            <li><a href="#">5</a></li>-->
<!--            <li class="edgetPage"><a href="#"><span>末页</span></a></li>-->

<!--        </ul>-->
    </nav>
</div>





<?php require_once('foot.inc.php')?>
