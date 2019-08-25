<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2019/1/2 0002
 * Time: 下午 9:33
 */
require_once('web_init.php');

$TOP_MENU="distribution";
$bannerlist = Picture::getPageList(1, 99, 1, 4, 1);
$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = Web_distribution::getInfoById($id);
$attr['count']=$info["count"]+1;
Web_distribution::update($id,$attr);

//if(empty($info)){
//    echo '非法操作';
//    die();
//}

$situation=$info;
//print_r($situation);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>渠道分销-子页面</title>
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
            $bannerlist=Picture::getPageList(1, 99, 1, 4, -1);
            echo '
                                <a href="'.$bannerlist[0]['http'].'"><img src="'.$HTTP_PATH.$bannerlist[0]['url'].'"></a>    
                        ';
        }
        ?>
    </div>

    <div class="dynamics_body">
        <div class="body_head">
            <span id="situation">渠道分销</span>
<!--            <img src="imgs/distribution_word.png">-->
            <span style="display: inline-block;margin-left: 14px !important;font-size:18px;font-family:PingFangSC-Regular;font-weight:400;color:rgba(213,213,213,1);">DISTRIBUTION CHANNEL</span>
            <p>渠道分销</p>
            <p>></p>
            <p>首页</p>
        </div>
        <?php if($situation){?>
        <div class="body_right_detail distri-detail" style="display: block !important;">
            <p class="body_right_detail_name" style="text-align: center"><?php echo $situation['title']?></p>
            <p class="label-title" style="text-align: center">查看：<span class="body_right_detail_date"><?php echo $situation['count'];?></span> 发布日期：<span class="body_right_detail_date"><?php echo date("Y/m/d H:i:s",$situation['time'])?></span></p>
<!--            <p class="center"><img src="--><?php //if (!empty($situation['picurl']))echo $HTTP_PATH.$situation['picurl'];else echo $HTTP_PATH."userfiles/upload/webpic/201811221141526940.png";?><!--" width="300px" height="600px"/></p>-->
            <?php echo htmlspecialchars_decode($situation['detail']);?>
        </div>
        <?php }?>
    </div>
</div>
<?php require_once('foot.inc.php')?>
