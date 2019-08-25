<?php
/**
 * 选型 selection.php
 *
 * @version       v0.01
 * @create time   2018/07/18
 * @update time   2018/07/18
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "history";
$id = isset($_GET['id'])?safeCheck($_GET['id']):0;
$info = Selection_history::getInfoById($id);
if(empty($info)){
    echo '非法操作！';
    die();
} else {
    /*if ($info['user'] != $USERId) {
        echo '没有权限操作！';
        die();
    }*/
}
$highflag = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>选型历史</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
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
<!--    <div class="guolumain">-->
<!--        <div class="guolumain_1">当前位置：选型历史<span>选型详细信息</span></div><div class="clear"></div>-->
<!--    </div>-->
    <div class="XXHdetails">
        <div class="XXHdetails_1">
            <div class="XXHdetails_2">时间</div>
            <div class="XXHdetails_3"><?php echo date('Y年m月d日 H:i:s',$info['addtime']);?></div>
        </div>
        <div class="XXHdetails_1">
            <div class="XXHdetails_2">客户名称</div>
            <div class="XXHdetails_3"><?php echo $info['customer'];?></div>
        </div>
        <div class="XXHdetails_1">
            <div class="XXHdetails_2">锅炉房预留位置</div>
            <div class="XXHdetails_3" style="width: 200px;"><?php echo $ARRAY_selection_guoluf_position[$info['guolu_position']];?></div>
            <?php if($info['guolu_position'] == 1){
                echo '<div class="XXHdetails_10" style="width: 40px;line-height: 50px;">地下</div>
                      <div class="XXHdetails_12" style="line-height: 50px;">'.$info['underground_unm'].'层</div>';
            }
            ?>
        </div>
        <div class="XXHdetails_1">
            <div class="XXHdetails_2">预计锅炉房高度</div>
            <div class="XXHdetails_3"><?php echo $info['guolu_height'];?>m</div>
        </div>
        <div class="XXHdetails_1">
            <div class="XXHdetails_2">是否冷凝</div>
            <div class="XXHdetails_3"><?php $condensateinfo = Dict::getInfoById($info['is_condensate']); echo $condensateinfo['name'];?></div>
        </div>
        <div class="XXHdetails_1">
            <div class="XXHdetails_2">是否低氮</div>
            <div class="XXHdetails_3"><?php $lownitrogeninfo = Dict::getInfoById($info['is_lownitrogen']); echo $lownitrogeninfo['name'];?></div>
        </div>
        <?php
        if($info['heating_type']){
        ?>
            <div class="XXHdetails_1">
                <div class="XXHdetails_2">采暖锅炉形式</div>
                <div class="XXHdetails_3"><?php echo $ARRAY_selection_application[0]['type'][$info['heating_type']];?></div>
            </div>
        <?php
        }
        if($info['water_type']){
            ?>
            <div class="XXHdetails_1">
                <div class="XXHdetails_2">热水锅炉形式</div>
                <div class="XXHdetails_3"><?php echo $ARRAY_selection_application[1]['type'][$info['water_type']];?></div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    if($info['application'] == 0 || $info['application'] == 2){
        $heatinglist = Selection_heating_attr::getInfoByHistoryId($id);
    ?>
    <div class="XXHdetails_4">
        <div class="XXHdetails_4_1">用途1</div>
        <span class="XXHdetails_4_2">采暖</span>
    </div>
    <div class="XXHdetails_5">
        <div class="XXHdetails_6">参数</div>
        <?php
        if($heatinglist){
            $i = 0;
            foreach ($heatinglist as $thisheat){
                $i ++;
                $hbtypeinfo = Selection_build::getInfoById($thisheat['build_type']);
                if($thisheat['floor_high'] > 15 && ($info['heating_type'] == 3 || $info['heating_type'] == 4)){
                    $highflag = 1;
                }
        ?>
        <div class="XXHdetails_7"><span>分区<?php echo $i; ?></span></div>
        <div class="XXHdetails_8">
            <div class="XXHdetails_9">
                <div class="XXHdetails_10">建筑类别：</div>
                <div class="XXHdetails_11"><?php echo $hbtypeinfo['name']; ?></div>
                <div class="XXHdetails_10">采暖面积：</div>
                <div class="XXHdetails_12"><?php echo $thisheat['area']; ?>m²</div>
            </div>
            <div class="XXHdetails_9">
                <div class="XXHdetails_10">采暖楼层：</div>
                <div class="XXHdetails_11"><?php echo $thisheat['floor_low']; ?>F~<?php echo $thisheat['floor_high']; ?>F</div>
                <div class="XXHdetails_10">采暖形式：</div>
                <div class="XXHdetails_12"><?php echo $ARRAY_selection_heating_type[$thisheat['type']]; ?></div>
            </div>
            <div class="XXHdetails_9">
                <div class="XXHdetails_10">单层高度：</div>
                <div class="XXHdetails_11"><?php echo $thisheat['floor_height']; ?>m</div>
                <div class="XXHdetails_10">使用时间：</div>
                <div class="XXHdetails_12"><?php echo $ARRAY_selection_usetime_type[$thisheat['usetime_type']]; ?></div>
            </div>
        </div>
        <?php
            }
        } ?>
    </div>
    <?php
    }
    if($info['application'] == 1 || $info['application'] == 2){
    ?>
    <div class="XXHdetails_4">
        <div class="XXHdetails_4_1">用途2</div>
        <span class="XXHdetails_4_2">热水</span>
    </div>
    <div class="XXHdetails_5">
        <div class="XXHdetails_6">参数</div>
        <?php
        $hotwaterParamlist = Selection_hotwater_attr::getParamByHistoryId($id);
        if($hotwaterParamlist){
            $j = 0;
            foreach ($hotwaterParamlist as $thisparam){
                echo '<div class="XXHdetails_7"><span>分区'.$thisparam['hotwater_param_id'].'</span></div>';
                echo '<div class="XXHdetails_8">';
                $hotattrlist = Selection_hotwater_attr::getInfoByHistoryId($id, $thisparam['hotwater_param_id']);

                if($hotattrlist){
                    $hotbuildtype = Selection_build::getInfoById($hotattrlist[0]['build_type']);
                    echo '<div class="XXHdetails_9">
                        <div class="XXHdetails_10">供水方式：</div>
                        <div class="XXHdetails_11">'.$ARRAY_hotwater_usetime_type[$hotattrlist[0]['use_type']].'</div>
                        <div class="XXHdetails_10">建筑类别：</div>
                        <div class="XXHdetails_12">'.$hotbuildtype['name'].'</div>
                    </div>';
                    echo '<div class="XXHdetails_9">';
                    foreach ($hotattrlist as $hotwater){
                        $hotattrinfo = Selection_build::getInfoById($hotwater['buildattr_id']);
                        $attr_num_str = "";
                        $childlist = Selection_build::getListByParentid($hotwater['buildattr_id']);
                        if($childlist){
                            $childinfo = Selection_build::getInfoById($hotwater['attr_num']);
                            $attr_num_str = $childinfo['name'];
                        }else{
                            $attr_num_str = $hotwater['attr_num'];
                        }
                        echo '<div class="XXHdetails_10">'.$hotattrinfo['name'].'：</div>';
                        echo '<div class="XXHdetails_12">'.$attr_num_str.'</div>';
                    }
                    echo '</div>';
                    if($hotattrlist[0]['use_type'] == 32){
                    	echo '<div class="XXHdetails_9">
                        <div class="XXHdetails_10">卫生器具同时使用百分数b：</div>
                        <div class="XXHdetails_12">'.$hotattrlist[0]['same_use'].'</div>
                    </div>';
                    }
                }
                echo '</div>';
            }
        }
        ?>
    </div>
    <?php
    }
    $guoluinfo = Guolu_attr::getInfoById($info['guolu_id']?$info['guolu_id']:0);
    $vendername = "";
    if($guoluinfo){
        $venderinfo = Dict::getInfoById($guoluinfo['vender']);
        $vendername = $venderinfo['name'];
    }
    ?>
    <div class="XXHdetails_4">
        <div class="XXHdetails_4_1">锅炉</div>
    </div>
    <div class="XXHdetails_5">
        <div class="XXHdetails_8">
            <div class="XXHdetails_9">
                <div class="XXHdetails_10">厂商：</div>
                <div class="XXHdetails_11"><?php echo $vendername; ?></div>
            </div>
            <div class="XXHdetails_9">
                <div class="XXHdetails_10">型号：</div>
                <div class="XXHdetails_11" style="width: 600px"><?php echo $guoluinfo?$guoluinfo['version']:''; ?></div>
            </div>
            <div class="XXHdetails_9">
                <div class="XXHdetails_10">数量：</div>
                <div class="XXHdetails_11" style="width: 600px"><?php echo $info['guolu_num']; ?></div>
            </div>
        </div>
    </div>
    <?php
    $heat_fujilist = Selection_fuji::getInfoByHistoryId($id, 0);
    if($heat_fujilist){
    ?>
    <div class="XXHdetails_4" style="margin-top: 0">
        <div class="XXHdetails_4_1">采暖辅机</div>
    </div>
    <table class="XXHdetails_13">
        <tr class="XXHdetails_13_check">
            <td>设备名称</td>
            <td>数量</td>
            <td>厂家</td>
            <td>规格型号</td>
        </tr>
        <?php
        foreach ($heat_fujilist as $item){
            if($item['data_type'] == 1){
                echo '
                    <tr>
                        <td>'.$item['name'].'</td>
                        <td>'.$item['num'].'台</td>';
                switch ($item['modelid']){
                    case 2:
                        if($item['value']){
                            $detailinfo = Burner_attr::getInfoById($item['value']);
                            $vendername = Dict::getInfoById($detailinfo['vender']);
                            echo '<td>'.$vendername['name'].'</td>';
                            echo '<td>'.$detailinfo['version'].'</td>';
                        }else{
                            echo '<td>没有找到合适的燃烧机</td>';
                            echo '<td></td>';
                        }
                        break;
                    case 3:
                        if($item['value']){
                            $detailinfo = Hdys_attr::getInfoById($item['value']);
                            $vendername = Dict::getInfoById($detailinfo['vender']);
                            echo '<td>'.$vendername['name'].'</td>';
                            //echo '<td>'.$detailinfo['outwater'].'m³/L</td>';
                            echo '<td>'.$detailinfo['version'].'</td>';
                        }else{
                            echo '<td>没有找到合适的软水器</td>';
                            echo '<td></td>';
                        }
                        break;
                    case 4:
                        if($item['value']){
                            $detailinfo = Pipeline_attr::getInfoById($item['value']);
                            $vendername = Dict::getInfoById($detailinfo['vender']);
                            echo '<td>'.$vendername['name'].'</td>';
                            //echo '<td>Q='.$detailinfo['flow_min'].'m³/h，H='.$detailinfo['lift_min'].'m，P='.$detailinfo['motorpower'].'kW</td>';
                            echo '<td>'.$detailinfo['version'].'</td>';
                        }else{
                            echo '<td>没有找到合适的循环泵</td>';
                            echo '<td></td>';
                        }
                        break;
                    case 5:
                        if($item['value']){
                            $detailinfo = Syswater_pump_attr::getInfoById($item['value']);
                            $vendername = Dict::getInfoById($detailinfo['vender']);
                            echo '<td>'.$vendername['name'].'</td>';
                            //echo '<td>Q='.$detailinfo['flow_min'].'m³/h，H='.$detailinfo['lift_min'].'m，P='.$detailinfo['motorpower'].'kW</td>';
                            echo '<td>'.$detailinfo['version'].'</td>';
                        }else{
                            echo '<td>没有找到合适的补水泵</td>';
                            echo '<td></td>';
                        }
                        break;
                    case 8:
                        if($item['value']){
                            $detailinfo = Water_box_attr::getInfoById($item['value']);
                            echo '<td>现场制作</td>';
                            //echo '<td>V='.$detailinfo['available_capacity'].'m³   ' . $detailinfo['length'] . '×' . $detailinfo['width'] . '×' . $detailinfo['height'] . 'm</td>';
                            echo '<td>'.$detailinfo['version'].'</td>';
                        }else{
                            echo '<td>没有找到合适的水箱</td>';
                            echo '<td></td>';
                        }
                        break;
                    case 9:
                        if($item['value']){
                            $detailinfo = Dirt_separator_attr::getInfoById($item['value']);
                            echo '<td></td>';
                            //echo '<td>DN'.$detailinfo['diameter'].'</td>';
                            echo '<td>DN'.$detailinfo['diameter'].'&nbsp;PN1.6</td>';
                        }else{
                            echo '<td>没有找到合适的除污器</td>';
                            echo '<td></td>';
                        }
                        break;
                }
                echo '</tr>';
            }else{
                $numdesc = "";
                if($item['num']){
                    $numdesc = $item['num'].'套';
                }
                echo '
                    <tr>
                        <td>'.$item['name'].'</td>
                        <td>'.$numdesc.'</td>
                        <td></td>
                        <td>'.$item['value'].'</td>
                    </tr>
                ';
            }

        }
        ?>
    </table>
    <?php
    }
    ?>
    <?php
    $water_fujilist = Selection_fuji::getInfoByHistoryId($id, 1);
    if($water_fujilist){
        ?>
        <div class="XXHdetails_4" style="margin-top: 0">
            <div class="XXHdetails_4_1">热水辅机</div>
        </div>
        <table class="XXHdetails_13">
            <tr class="XXHdetails_13_check">
                <td>设备名称</td>
                <td>数量</td>
                <td>厂家</td>
                <td>规格型号</td>
            </tr>
            <?php
            foreach ($water_fujilist as $item){
                if($item['data_type'] == 1){
                    echo '
                    <tr>
                        <td>'.$item['name'].'</td>
                        <td>'.$item['num'].'台</td>';
                    switch ($item['modelid']){
                        case 2:
                            if($item['value']){
                                $detailinfo = Burner_attr::getInfoById($item['value']);
                                $vendername = Dict::getInfoById($detailinfo['vender']);
                                echo '<td>'.$vendername['name'].'</td>';
                                echo '<td>'.$detailinfo['version'].'</td>';
                            }else{
                                echo '<td>没有找到合适的燃烧机</td>';
                                echo '<td></td>';
                            }
                            break;
                        case 3:
                            if($item['value']){
                                $detailinfo = Hdys_attr::getInfoById($item['value']);
                                $vendername = Dict::getInfoById($detailinfo['vender']);
                                echo '<td>'.$vendername['name'].'</td>';
                                //echo '<td>'.$detailinfo['outwater'].'m³/L</td>';
                                echo '<td>'.$detailinfo['version'].'</td>';
                            }else{
                                echo '<td>没有找到合适的软水器</td>';
                                echo '<td></td>';
                            }
                            break;
                        case 4:
                            if($item['value']){
                                $detailinfo = Pipeline_attr::getInfoById($item['value']);
                                $vendername = Dict::getInfoById($detailinfo['vender']);
                                echo '<td>'.$vendername['name'].'</td>';
                                //echo '<td>Q='.$detailinfo['flow_min'].'m³/h，H='.$detailinfo['lift_min'].'m，P='.$detailinfo['motorpower'].'kW</td>';
                                echo '<td>'.$detailinfo['version'].'</td>';
                            }else{
                                echo '<td>没有找到合适的循环泵</td>';
                                echo '<td></td>';
                            }
                            break;
                        case 5:
                            if($item['value']){
                                $detailinfo = Syswater_pump_attr::getInfoById($item['value']);
                                $vendername = Dict::getInfoById($detailinfo['vender']);
                                echo '<td>'.$vendername['name'].'</td>';
                                //echo '<td>Q='.$detailinfo['flow_min'].'m³/h，H='.$detailinfo['lift_min'].'m，P='.$detailinfo['motorpower'].'kW</td>';
                                echo '<td>'.$detailinfo['version'].'</td>';
                            }else{
                                echo '<td>没有找到合适的补水泵</td>';
                                echo '<td></td>';
                            }
                            break;
                        case 8:
                            if($item['value']){
                                $detailinfo = Water_box_attr::getInfoById($item['value']);
                                echo '<td>现场制作</td>';
                                //echo '<td>V='.$detailinfo['available_capacity'].'m³   ' . $detailinfo['length'] . '×' . $detailinfo['width'] . '×' . $detailinfo['height'] . 'm</td>';
                                echo '<td>'.$detailinfo['version'].'</td>';
                            }else{
                                echo '<td>没有找到合适的水箱</td>';
                                echo '<td></td>';
                            }
                            break;
                        case 9:
                            if($item['value']){
                                $detailinfo = Dirt_separator_attr::getInfoById($item['value']);
                                echo '<td></td>';
                                //echo '<td>DN'.$detailinfo['diameter'].'</td>';
                                echo '<td>'.$detailinfo['version'].'</td>';
                            }else{
                                echo '<td>没有找到合适的除污器</td>';
                                echo '<td></td>';
                            }
                            break;
                    }
                    echo '</tr>';
                }else{
                    $numdesc = "";
                    if($item['num']){
                        $numdesc = $item['num'].'套';
                    }
                    echo '
                    <tr>
                        <td>'.$item['name'].'</td>
                        <td>'.$numdesc.'</td>
                        <td></td>
                        <td>'.$item['value'].'</td>
                    </tr>
                ';
                }

            }
            ?>
        </table>
        <?php
    }
    ?>
    <div class="XXHdetails_4">
        <div class="XXHdetails_4_1">备注</div>
    </div>
    <div class="XXHdetails_14"><?php echo $info['remark'];?></div>
</body>
</html>