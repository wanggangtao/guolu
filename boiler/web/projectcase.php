<?php
/**
 * 项目案例 projectcase.php
 *
 * @version       v0.01
 * @create time   2018/12/8
 * @update time   2018/12/8
 * @author        王卓然
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
$params=array();
$bannerlist = Picture::getPageList(1, 99, 1, 3, 1);
$TOP_MENU = "projectcase";
$page = (isset($_GET['page']))? safeCheck($_GET['page']): 1;
$if_change=isset($_SESSION['if_change'])? safeCheck($_SESSION['if_change']): 0;
if(!isset($_GET['page'])&&$if_change==0)
    $type=0;
else{
    $type=isset($_SESSION['type'])? safeCheck($_SESSION['type']): 0;
}
if($if_change==1)
{
    $page = 1;
}
unset($_SESSION['if_change']);
unset($_SESSION['page']);
$params['type']=$type;
$totalcount= Web_projectcase::getAllCount($params);
$shownum   =12;
$pagecount = ceil($totalcount / $shownum);
//$page      = getPage($pagecount);
//echo $pagecount;
$params["page"] = $page;
$params["pageSize"] = $shownum;
$rows=Web_projectcase::getList($params);
$typess      = Projectcase_type::getList();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>项目案例</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" href="css/swiper.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/query.css">
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</head>
<style>
    #none{
    position: relative;
    left: 550px;
    top: 150px;
    color: #818E96;
    }
</style>
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
            $bannerlist=Picture::getPageList(1, 99, 1, 3, -1);
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        ?>
    </div>

    <div class="dynamics_body">
        <div class="body_head">
            <span>项目案例</span>
<!--            <img src="imgs/business_word.png" class="width200"/>-->
            <span style="display: inline-block;margin-left: 14px !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(213,213,213,1);">BUSINESS CASE</span>
            <p><?php
                if($type!=0){
$name= Projectcase_type::getInfoById($type);
echo $name[0]['name'];}
else echo "全部"
                ?></p>
            <p>></p>
            <p>项目案例</p>
            <p>></p>
            <p>首页</p>
        </div>
        <div class="body_left">
            <div class="body_left_menu">


                 <a href="javascript:void(0)" onclick="change(0)">
                    <div <?php if($type==0)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?> >
                 <span>全部</span>
                    <p>ALL</p>
                </div>
                </a>
                <?php foreach ($typess as $types){ ?>
                    <a href="javascript:void(0)" onclick="change(<?php echo $types['id']?>)"> <div <?php if($type!=0&&$types['id']==$name[0]['id'])echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                            <span><?php echo $types['name']?></span>
                            <p><?php echo $types['english_name']?></p>
                        </div></a>
                <?php } ?>


            </div>
            <img src="imgs/global_menu_line.png"/>
        </div>

        <div class="industry_right">


            <!--0103新版项目案例样式  -->
<!--            <div class="company-example-item" style="width: 258px;margin:0 20px 20px 0;">-->
<!--                <img class="img-top" src="imgs/img-top.png" alt="典型工程案例1">-->
<!--                <div class="company-example-text">锅炉燃气炉</div>-->
<!--                <a href="">MORE</a></div>-->
<!--            <div class="company-example-item" style="width: 258px;margin:0 20px 20px 0;">-->
<!--                <img class="img-top" src="imgs/img-top2.png" alt="典型工程案例2">-->
<!--                <div class="company-example-text">锅炉燃气炉</div>-->
<!--                <a href="">MORE</a></div>-->
<!--            <div class="company-example-item" style="width: 258px;margin:0 20px 20px 0;">-->
<!--                <img class="img-top" src="imgs/img-top3.png" alt="典型工程案例3">-->
<!--                <div class="company-example-text">锅炉燃气炉</div>-->
<!--                <a href="">MORE</a></div>-->
<!--            <div class="company-example-item" style="width: 258px;margin:0 20px 20px 0;">-->
<!--                <img class="img-top" src="imgs/img-top4.png" alt="典型工程案例4">-->
<!--                <div class="company-example-text">锅炉燃气炉</div>-->
<!--                <a href="">MORE</a></div>-->
            <!--0103新版项目案例样式  -->
            <?php

            if(!empty($rows)){foreach ($rows as $row){

          ?>
                <div class="company-example-item" style="width: 260px;margin:0 16px 20px 0;">
                <a href="projectcase_detail.php?id=<?php echo $row['id']?>&&type=<?php echo $type?>">
                    <img class="img-top" src="<?php if(!empty($row['picurl']))echo $HTTP_PATH.$row['picurl'];else echo $HTTP_PATH."userfiles/upload/webpic/201811221141526940.png"?>" alt="典型工程案例1">
                    <div class="company-example-text"><?php echo subStrContent($row['title'],8,1)?></div>
                    <a class="link" href="projectcase_detail.php?id=<?php echo $row['id']?>&&type=<?php echo $type?>">MORE</a>
                    </a>
                </div>



<!--          <a href="projectcase_detail.php?id=--><?php //echo $row['id']?><!--&&type=--><?php //echo $type?><!--">  <div class="engineering_box">-->
<!--                <div class="engineering_product_img">-->
<!--                    <img src="--><?php //if(!empty($row['picurl']))echo $HTTP_PATH.$row['picurl'];else echo $HTTP_PATH."userfiles/upload/webpic/201811221141526940.png"?><!--"/>-->
<!--                </div>-->
<!--                <div class="engineering_product_hover" >-->
<!--                    <img src="imgs/case_btn_add.png" alt="case_btn_add_img"/>-->
<!--                </div>-->
<!--                <p class="engineering_product_des">--><?php //echo subStrContent($row['title'],10,1)?><!--</p>-->
<!--            </div></a>-->

            <?php
            }}
else { ?>

    <p style="color: #818E96;position:relative;left: 360px;">暂无数据</p>
           <?php } ?>
            <?php
            if($pagecount>1){
            echo dspPagesForMin(getPageUrl(),$page,$shownum,$totalcount, $pagecount);}
            else{ ?>
               <nav class="PageBox"><ul class="pagination"> </ul> </nav>
                <?php }
            ?>


        </div>

    </div>

</div>

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
                if_change:if_change
            },
            dataType :    'json',
            url :         'projectcase_do.php',
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




<?php require_once('foot.inc.php')?>