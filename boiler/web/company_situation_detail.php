<?php
/**
 * Created by kjb.
 * Date: 2018/12/8
 * Time: 19:44
 */
require_once('web_init.php');


$TOP_MENU = "company_situation";
$bannerlist = Picture::getPageList(1, 99, 1, 2, 1);
$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = Web_situation::getInfoById($id);
$attr['count']=$info["count"]+1;
Web_situation::update($id,$attr);
if(empty($info)){
    echo '非法操作';
    die();
}
$situation=$info;
$type=$situation['type'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>公司动态-子页面</title>
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
            var if_change=0;
            var detail=1;
            if(now_type!=type){
                if_change=1;
            }
            $.ajax({
                type        : 'POST',
                data        : {
                    type   : type,
                    if_change : if_change,
                    detail :  detail
                },
                dataType :    'json',
                url :         'company_situation_change.php',
                success :     function(data){
                    history.back(-1);
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

    <div class="dynamics_body">
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
                        <p>INDUSTRY DYNAMICS</p>
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
        <div class="body_right_detail">
            <p class="body_right_detail_name"><?php echo $situation['title']?></p>
            <p class="label-title">查看：<span class="body_right_detail_date"><?php echo $situation['count']?></span> 发布日期：<span class="body_right_detail_date"><?php echo  date("Y-m-d H:i:s",$situation['addtime'])?></span></p>
            <?php echo $situation['content']?>
        </div>
    </div>
</div>
<?php require_once('foot.inc.php')?>
