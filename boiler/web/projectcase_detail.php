<?php
/**
 * Created by PhpStorm.
 * User: 王卓然
 * Date: 2018/12/8
 * Time: 19:42
 */
require_once('web_init.php');
$TOP_MENU="projectcase";
$id=0;
$type=0;
if (!empty($_GET['id'])){
    $id=safeCheck($_GET['id']);

}
if (!empty($_GET['type'])){
    $type=safeCheck($_GET['type']);

}
$update=Web_projectcase::UpdateCountInfo($id);
$rows=Web_projectcase::getInfoById($id);
$bannerlist = Picture::getPageList(1, 99, 1, 3, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>项目案例-子页面</title>
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
<!--    <div class="dynamics_bannner">-->
<!--        <img src="imgs/dynamics_banner.png"></img>-->
<!--    </div>-->
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
            <p>详情</p>
            <p>></p>
            <p>项目案例</p>
            <p>></p>
            <p>首页</p>
        </div>
        <div class="body_left">
            <div class="body_left_menu">
                <a href="javascript:void(0)" onclick="change(0)">
                    <div <?php if($type==0)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?> >
                        <span id="all">全部</span>
                        <p>ALL</p>
                    </div>
                </a>
                <a href="javascript:void(0)" onclick="change(1)"> <div <?php if($type==1)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>酒店</span>
                        <p>HOTEL</p>
                    </div></a>
                <a href="javascript:void(0)" onclick="change(2)"> <div <?php if($type==2)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>医院</span>
                        <p>HOSPITAL</p>
                    </div></a>
                <a href="javascript:void(0)" onclick="change(3)"> <div <?php if($type==3)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>工厂</span>
                        <p>FACTORY</p>
                    </div></a>
                <a href="javascript:void(0)" onclick="change(4)"> <div <?php if($type==4)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>住宅</span>
                        <p>APARTMENT</p>
                    </div></a>
                <a href="javascript:void(0)" onclick="change(5)"> <div <?php if($type==5)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>商场</span>
                        <p>MARKET</p>
                    </div></a>
                <a href="javascript:void(0)" onclick="change(6)"> <div <?php if($type==6)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>学校</span>
                        <p>SCHOOL</p>
                    </div></a>
                <a href="javascript:void(0)" onclick="change(7)"> <div <?php if($type==7)echo'class="menu_second menu_first"' ;else echo'class="menu_second"' ?>>
                        <span>其他</span>
                        <p>OTHER</p>
                    </div></a>
            </div>
            <img src="imgs/global_menu_line.png"/>
        </div>

        <div class="body_right_detail">
            <p class="body_right_detail_name"><?php echo $rows['title']?></p>
            <p class="label-title">查看：<span class="body_right_detail_date"><?php echo $rows['count']?></span class="body_right_detail_date"> 发布日期：<span class="body_right_detail_date"><?php echo  date("Y-m-d H:i:s",$rows['addtime'])?></span></p>
            <?php echo $rows['content']?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function change(type){
        var now_type=<?php echo $type; ?>;
        var if_change=1;

        $.ajax({
            type        : 'POST',
            data        : {
                type   : type,
                if_change:if_change
            },
            dataType :    'json',
            url :         'projectcase_do.php',
            success :     function(data){
                window.location.href = "projectcase.php?type="+data;
              //  history.back(-1);
            }
        });
    }
</script>





<?php require_once('foot.inc.php')?>
