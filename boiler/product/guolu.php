<?php
/**
 * 锅炉 guolu.php
 *
 * @version       v0.01
 * @create time   2018/06/05
 * @update time   2018/06/05
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$venderid = isset($_GET['vender'])?safeCheck($_GET['vender']):'0';
$venderinfo = Dict::getInfoById($venderid);
if(empty($venderinfo)){
    echo '非法操作！';
    die();
}
$searchlist  = Dict::getPageList(0, 0, 1, 0, 1, 4);

$type = isset($_GET['type'])?safeCheck($_GET['type']):0;
$is_condensate = isset($_GET['is_condensate'])?safeCheck($_GET['is_condensate']):0;
$is_lownigtrogen = isset($_GET['is_lownigtrogen'])?safeCheck($_GET['is_lownigtrogen']):0;

$productlist = Guolu_attr::getList(0, 0, 0, $venderid, $type, $is_condensate, $is_lownigtrogen);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/basic.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script>
        $(function () {
            $('.indexTop_2').click(function(){
                $('.indexTop_2').removeClass('indexTop_checked');
                $(this).addClass('indexTop_checked');
            })
            $('.indexMtwo_1').hover(function () {
                $(this).find('.mouseset').slideDown('fast');
                var name = $(this).find('.indexMtwo_1_2').text();
                $(this).find('.mouseset').find('span').text(name);
            },function () {
                $(this).find('.mouseset').slideUp(100);
            })
        })
    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
    <div class="guolumain">
        <div class="guolumain_1">当前位置：产品 ><a href="guolu.php?vender=<?php echo $venderid; ?>"> <span>锅炉（<?php echo $venderinfo['name']; ?>）</span></a></div><div class="clear"></div>
        <div class="guolumain_2">
            <?php
            /*if($searchlist){
                foreach ($searchlist as $search){
                    if($search['id'] != 1){
                        echo '<div class="guolumain_3">'.$search['name'].'</div>';
                        echo '
                            <div class="guolumain_4">
                                <div class="select_1"><span id="condition'.$search['id'].'" data-dictid="0">全部</span><img src="images/selectup.png" class="guolumain_4_1"></div>
                                <div class="guolumain_4_2">
                                    <div data-value="0" class="guolumain_4_3">全部</div>
                        ';
                            $selectlist = Dict::getListByParentid($search['id']);
                            if($selectlist){
                                foreach ($selectlist as $select){
                                    echo '<div data-value="'.$select['id'].'" class="guolumain_4_3">'.$select['name'].'</div>';
                                }
                            }
                            echo '
                                </div>
                            </div>';
                    }

                }
            }*/
            ?>
            <div class="guolumain_3">类型</div>
            <div class="guolumain_4">
                <div class="select_1"><span id="condition2" data-dictid="<?php echo $type; ?>"><?php $typeinfo = Dict::getInfoById($type); echo empty($typeinfo)?"全部":$typeinfo['name']; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                <div class="guolumain_4_2">
                    <div data-value="0" class="guolumain_4_3">全部</div>
                    <?php
                    $selectlist = Dict::getListByParentid(2);
                    if($selectlist){
                        foreach ($selectlist as $select){
                            echo '<div data-value="'.$select['id'].'" class="guolumain_4_3">'.$select['name'].'</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="guolumain_3" style="margin-left: 60px">是否冷凝</div>
            <div class="guolumain_4">
                <div class="select_1"><span id="condition3" data-dictid="<?php echo $is_condensate; ?>"><?php $condensateinfo = Dict::getInfoById($is_condensate); echo empty($condensateinfo)?"全部":$condensateinfo['name']; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                <div class="guolumain_4_2">
                    <div data-value="0" class="guolumain_4_3">全部</div>
                    <?php
                    $selectlist = Dict::getListByParentid(3);
                    if($selectlist){
                        foreach ($selectlist as $select){
                            echo '<div data-value="'.$select['id'].'" class="guolumain_4_3">'.$select['name'].'</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="guolumain_3" style="margin-left: 60px">是否低氮</div>
            <div class="guolumain_4">
                <div class="select_1"><span id="condition4" data-dictid="<?php echo $is_lownigtrogen; ?>"><?php $lownigtrogeninfo = Dict::getInfoById($is_lownigtrogen); echo empty($lownigtrogeninfo)?"全部":$lownigtrogeninfo['name']; ?></span><img src="images/selectup.png" class="guolumain_4_1"></div>
                <div class="guolumain_4_2">
                    <div data-value="0" class="guolumain_4_3">全部</div>
                    <?php
                    $selectlist = Dict::getListByParentid(4);
                    if($selectlist){
                        foreach ($selectlist as $select){
                            echo '<div data-value="'.$select['id'].'" class="guolumain_4_3">'.$select['name'].'</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <button class="selectbtn">查询</button>
        </div>
        <div class="indexMtwo">
            <?php
            if($productlist){
                foreach ($productlist as $product){
                    echo '
                    <a href="guolu_details.php?id='.$product['guolu_id'].'">
                        <div class="guolumain_6">
                            <img src="'.$HTTP_PATH.$product['products_img'].'" class="guolumain_6_1">
                            <div class="guolumain_6_2">'.$product['guolu_version'].'</div>
                            <!--<img src="images/new.png" class="guolumain_new">-->
                        </div>
                    </a>
                    ';
                }
            }
            ?>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.select_1').click(function () {
            $(this).next('.guolumain_4_2').slideDown('fast');
            $(this).find('img').addClass('rotate');
        });

        $('.guolumain_4_3').click(function () {
            var Newtext = $(this).text();
            var dictid = $(this).attr("data-value");
            $(this).parent().prev('.select_1').find('span').text(Newtext);
            $(this).parent().prev('.select_1').find('span').attr("data-dictid",dictid);
            $(this).parent().slideUp(100);
            $(this).parent().prev('.select_1').find('img').removeClass('rotate');
        });

        //查找
        $('.selectbtn').click(function(){
            var type = $('#condition2').attr("data-dictid");
            var is_condensate = $('#condition3').attr("data-dictid");
            var is_lownigtrogen = $('#condition4').attr("data-dictid");
            location.href  = "guolu.php?vender="+"<?php echo $venderid; ?>"+"&type="+type+"&is_condensate="+is_condensate+"&is_lownigtrogen="+is_lownigtrogen;
        });
    });
</script>
</body>
</html>