<?php
/**
 * 辅机 fuji_details.php
 *
 * @version       v0.01
 * @create time   2018/06/05
 * @update time   2018/06/05
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$modelid = isset($_GET['model'])?safeCheck($_GET['model']):'0';
$param =  isset($_GET['param'])?safeCheck($_GET['param'],0):'';
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
$pagesize = isset($_GET['pagesize'])?safeCheck($_GET['pagesize']):5;
$start = ($page - 1)*$pagesize;

$vender = 0;
$is_lownigtrogen = 0;
$paramarr = explode(',',$param);
if(count($paramarr) > 1){
    $vender = $paramarr[0];
    $is_lownigtrogen = $paramarr[1];
}elseif(count($paramarr) == 1){
    $vender = $paramarr[0];
}
$modelinfo = Products_model::getInfoById($modelid);
if(empty($modelinfo)){
    echo '非法操作！';
    die();
}
$searchlist  = Dict::getPageList(0, 0, 1, 0, 1, $modelinfo['category']);

if($modelid == 4){
    $unpagecount = 0;
    $unpage = 0;
    $productcount1 = Products::getList(1, 5, 0, $modelid, $modelinfo['attrname'], $vender, $is_lownigtrogen);
    $productcount2 = Products::getList(1, 5, 0, 5, "syswater_pump_attr", $vender, $is_lownigtrogen);
    $productcount = $productcount1 + $productcount2;
    if(($page * $pagesize) <= $productcount1){
        $productlist = Products::getList($start, $pagesize, 1, $modelid, $modelinfo['attrname'], $vender, $is_lownigtrogen);
    }else{
        if(($page * $pagesize) - $productcount1 < $pagesize){
            $start1 = 0;
            $unpage = $page;
            $unpagecount = ($page * $pagesize) - $productcount1;
            $productlist = Products::getList($start, $pagesize, 1, $modelid, $modelinfo['attrname'], $vender, $is_lownigtrogen);
            $productpump = Products::getList($start1, $unpagecount, 1, 5, "syswater_pump_attr", $vender, $is_lownigtrogen);
            if($productpump){
                $productlist = array_merge($productlist,$productpump);
            }
        }elseif(($page * $pagesize) - $productcount1 >= $pagesize){
            $start1 = ($page - ceil($productcount1 / $pagesize) - 1)*$pagesize + (ceil($productcount1 / $pagesize)* $pagesize - $productcount1);
            $productlist = Products::getList($start1, $pagesize, 1, 5, "syswater_pump_attr", $vender, $is_lownigtrogen);
        }

    }
}else{
    $productcount = Products::getList(1, 5, 0, $modelid, $modelinfo['attrname'], $vender, $is_lownigtrogen);
    $productlist = Products::getList($start, $pagesize, 1, $modelid, $modelinfo['attrname'], $vender, $is_lownigtrogen);
}
$modelname = "";
switch ($modelid){
    case 3:
        $modelname = "水处理设备";
        break;
    case 4:
        $modelname = "水泵";
        break;
    default :
        $modelname = $modelinfo['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>辅机</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="js/layui/css/layui.css">
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
        <div class="guolumain_1">当前位置：产品 ><a href="fuji_details.php?model=<?php echo $modelid;?>"><span>辅机（<?php echo $modelname;?>）</span></a></div><div class="clear"></div>
        <div class="guolumain_2">
            <?php
            if($searchlist){
                $i = 0;
                foreach ($searchlist as $search){
                    echo '<div class="guolumain_3">'.$search['name'].'</div>';
                    echo '
                        <div class="guolumain_4">
                            <div class="select_1"><span class="condition" data-dictid="'.$paramarr[$i].'">全部</span><img src="images/selectup.png" class="guolumain_4_1"></div>
                            <div class="guolumain_4_2">
                                <div data-value="0" class="guolumain_4_3">全部</div>
                    ';
                    $selectlist = Dict::getListByParentid($search['id']);
                    if($modelid == 4){
                        $selectlist1 = Dict::getListByParentid(8);
                        $selectlist = array_merge($selectlist,$selectlist1);
                    }
                    if($selectlist){
                        foreach ($selectlist as $select){
                            echo '<div data-value="'.$select['id'].'" class="guolumain_4_3">'.$select['name'].'</div>';
                        }
                    }
                    echo '
                        </div>
                    </div>';
                    $i++;
                }
            }
            ?>
            <button class="selectbtn">查询</button>
        </div>
        <div class="GLtwo">
            <table class="GLDetils_10" border="0" cellspacing="2">
                <tr class="GLDetils9_fir">
                    <td>厂家</td>
                    <td>型号</td>
                    <td>主要参数</td>
                </tr>
                <?php
                if($productlist){
                    foreach ($productlist as $product){
                        $promodel = Products_model::getInfoById($product['modelid']);
                        $attrinfo = $promodel['attrname']::getInfoByProid($product['id']);
                        $provender = Dict::getInfoById($attrinfo['vender']);
                        $pramstr = "";
                        switch ($product['modelid']){
                            case 2://燃烧器
                                $lownitrogen = Dict::getInfoById($attrinfo['is_lownitrogen']);
                                $pramstr = "是否低氮：".$lownitrogen['name']."；功率：".$attrinfo['power']."KW；对应锅炉热功率：".$attrinfo['boilerpower']."KW";
                                break;
                            case 3://全自动软水器
                                $pramstr = "额定出水量：".$attrinfo['outwater']."m³/L；重量：".$attrinfo['weight']."kg";
                                break;
                            case 4://管道泵
                                $pramstr = "流量最小值：".$attrinfo['flow_min']."m³/h；流量中值：".$attrinfo['flow_mid']."m³/h；流量最大值：".$attrinfo['flow_max']."m³/h；扬程最小值：".$attrinfo['lift_min']."m；扬程中值：".$attrinfo['lift_mid']."m；扬程最大值：".$attrinfo['lift_max']."m；转速：".$attrinfo['speed']."r/min；电机功率：".$attrinfo['motorpower']."KW；必需汽蚀余量：".$attrinfo['npsh']."m；重量：".$attrinfo['weight']."kg；直径：".$attrinfo['diameter']."m";
                                break;
                            case 5://系统补水泵
                                $pramstr = "流量最小值：".$attrinfo['flow_min']."m³/h；流量中值：".$attrinfo['flow_mid']."m³/h；流量最大值：".$attrinfo['flow_max']."m³/h；扬程最小值：".$attrinfo['lift_min']."m；扬程中值：".$attrinfo['lift_mid']."m；扬程最大值：".$attrinfo['lift_max']."m；转速：".$attrinfo['speed']."r/min；电机功率：".$attrinfo['motorpower']."KW；必需汽蚀余量：".$attrinfo['npsh']."m；重量：".$attrinfo['weight']."kg";
                                break;
                            case 6://换热器
                                $pramstr = "换热面积：".$attrinfo['heat_surface']."㎡；一次侧进出水管径：".$attrinfo['first_r']."m；二次侧进出水管径：".$attrinfo['second_r']."m；长：".$attrinfo['length']."m；宽：".$attrinfo['width']."m；高：".$attrinfo['height']."m；重量：".$attrinfo['weight']."kg";
                                break;
                            case 7://分集水器
                                $pramstr = "筒体直径：".$attrinfo['dgmm']."mm；封头高度：".$attrinfo['head_height']."mm；排污管规格：".$attrinfo['blowdown_pipe']."mm";
                                break;
                        }
                        echo '
                                    <tr>
                                        <td>'.$provender['name'].'</td>
                                        <td>'.$attrinfo['version'].'</td>
                                        <td>'.$pramstr.'</td>
                                    </tr>';
                    }
                }
                ?>
            </table>
            <div id="test1" class="GLthree"></div>
            <script src="js/layui/layui.js"></script>
            <script>
                layui.use('laypage', function(){
                    var laypage = layui.laypage;

                    //执行一个laypage实例
                    laypage.render({
                        elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
                        ,count: <?php echo $productcount; ?> //数据总数，从服务端得到
                        ,curr:<?php echo $page; ?>
                        ,limit:5
                        ,groups:3
                        ,layout:['count','prev','page','next']
                        ,jump: function(obj, first){
                            //obj包含了当前分页的所有参数，比如：
                            //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                            //console.log(obj.limit); //得到每页显示的条数
                            //首次不执行
                            if(!first){
                                //do something
                                location.href  = "fuji_details.php?model="+"<?php echo $modelid; ?>"+"&param="+"<?php echo $param; ?>"+"&page="+obj.curr+"&pagesize="+obj.limit;
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>

<script>
    $(function () {
        selectInit();
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
            var param = "";
            $('.condition').each(function(){
                param =  param + $(this).attr("data-dictid") + ',';
            });
            param = param.substring(0, param.lastIndexOf(','));
            location.href  = "fuji_details.php?model="+"<?php echo $modelid; ?>"+"&param="+param;
        });
    });
    function selectInit() {
        var mycars=new Array();
        mycars[0]="<?php echo $vender; ?>";
        mycars[1]="<?php echo $is_lownigtrogen; ?>";
        $('.condition').each(function(i){
            var textstr = "全部";
            $(this).parent().next('.guolumain_4_2').find('div').each(function(){
                if($(this).attr("data-value") == mycars[i]){
                    textstr = $(this).text();
                }
            });
            $(this).text(textstr)
        });
    }
</script>
</body>
</html>