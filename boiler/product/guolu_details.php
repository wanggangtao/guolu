<?php
/**
 * 锅炉详情 guolu_details.php
 *
 * @version       v0.01
 * @create time   2018/06/05
 * @update time   2018/06/05
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$guoluinfo = Guolu_attr::getInfoById($id);
if(empty($guoluinfo)){
    echo '非法操作！';
    die();
}

$venderinfo = Dict::getInfoById($guoluinfo['vender']);
if(empty($venderinfo)){
    echo '非法操作！';
    die();
}
$productinfo = Products::getInfoById($guoluinfo['proid']);
if(empty($productinfo)){
    echo '非法操作！';
    die();
}
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
        <div class="guolumain_1">当前位置：产品 > <a href="guolu.php?vender=<?php echo $guoluinfo['vender']; ?>"> 锅炉（<?php echo $venderinfo['name']; ?>）</a> > <span>锅炉详细信息</span></div><div class="clear"></div>
        <div class="GLDetils">
            <div class="GLDetils_3"></div>
            <img src="images/1.png" class="GLDetils_1">
            <div class="GLDetils_2">
                <div class="GLDetils_4">此产品介绍的详情</div>
                <div class="GLDetils_5"><?php echo $guoluinfo['version']; ?></div>
                <div class="GLDetils_6">
                    <?php echo $productinfo['desc']; ?>
                </div>
            </div>
        </div>
    </div>
<div class="GLDetils_7"><div class="GLDetils_7_1"></div><div class="GLDetils_7_2">技术参数</div></div>
<div class="GLDetils_8">
    <table class="GLDetils_9" border="0" cellspacing="0">
        <tr class="GLDetils9_fir">
            <td>设备型号</td>
            <td>类型</td>
            <td>是否冷凝</td>
            <td>是否低氮</td>
        </tr>
        <tr>
            <td><?php echo $guoluinfo['version']; ?></td>
            <td><?php $infos = Dict::getInfoById($guoluinfo['type']); echo $infos['name']; ?></td>
            <td><?php $infos = Dict::getInfoById($guoluinfo['is_condensate']); echo $infos['name']; ?></td>
            <td><?php $infos = Dict::getInfoById($guoluinfo['is_lownitrogen']); echo $infos['name']; ?></td>
        </tr>
    </table>
    <table class="GLDetils_9" border="0" cellspacing="0">
        <tr class="GLDetils9_fir">
            <td>额定热功率</td>
            <td>进水温度</td>
            <td>出水温度</td>
            <td>工作压力</td>
            <td>燃料类型</td>
            <td>燃气消耗量</td>
            <td>燃油消耗量</td>
        </tr>
        <tr>
            <td>KW</td>
            <td>℃</td>
            <td>℃</td>
            <td>MPa</td>
            <td></td>
            <td>Nm³/h</td>
            <td>Nm³/h</td>
        </tr>
        <tr>
            <td><?php echo $guoluinfo['ratedpower']; ?></td>
            <td><?php echo $guoluinfo['inwater_t']; ?></td>
            <td><?php echo $guoluinfo['outwater_t']; ?></td>
            <td><?php echo $guoluinfo['pressure']; ?></td>
            <td><?php echo $guoluinfo['fueltype']; ?></td>
            <td><?php echo $guoluinfo['gas_consumption']; ?></td>
            <td><?php echo $guoluinfo['fuel_consumption']; ?></td>
        </tr>
        <tr class="GLDetils9_fir">
            <td>烟道口径</td>
            <td>锅炉运输重量</td>
            <td>热水流量</td>
            <td>接口管径</td>
            <td>压力降</td>
            <td>锅炉长度</td>
            <td>锅炉宽度</td>
        </tr>
        <tr>
            <td>mm</td>
            <td>t</td>
            <td>m³/h</td>
            <td>mm</td>
            <td>kPa</td>
            <td>mm</td>
            <td>mm</td>
        </tr>
        <tr>
            <td><?php echo $guoluinfo['flue_caliber']; ?></td>
            <td><?php echo $guoluinfo['hauled_weight']; ?></td>
            <td><?php echo $guoluinfo['hot_flow']; ?></td>
            <td><?php echo $guoluinfo['interface_diameter']; ?></td>
            <td><?php echo $guoluinfo['pressure_drop']; ?></td>
            <td><?php echo $guoluinfo['length']; ?></td>
            <td><?php echo $guoluinfo['width']; ?></td>
        </tr>
        <tr class="GLDetils9_fir">
            <td>锅炉总高度</td>
            <td>出烟口中心高度</td>
            <td>循环水量</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>mm</td>
            <td>mm</td>
            <td>L</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><?php echo $guoluinfo['height']; ?></td>
            <td><?php echo $guoluinfo['smoke_height']; ?></td>
            <td><?php echo $guoluinfo['water']; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
</body>
</html>