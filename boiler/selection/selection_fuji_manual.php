<?php
/**
 * 手动选型-辅机 selection_fuji_manual.php
 *
 * @version       v0.01
 * @create time   2018/12/11
 * @update time   2018/12/11
 * @author        ozqowen
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";

$history_id = isset($_GET['id'])?safeCheck($_GET['id']):0;
$history_info = Selection_history::getInfoById($history_id);
if(empty($history_info)){
    echo '非法操作！';
    die();
}

$guolu_id_list = explode(",",$history_info['guolu_id']);
$guoluinfo = Guolu_attr::getInfoById(reset($guolu_id_list));


$guolu_application = $history_info['application'];// 锅炉用途 采暖0，热水1，采暖+热水2
$heating_type = $history_info['heating_type'];// 采暖锅炉形式
$area_num_nuan_qi = $history_info['area_num_nuan_qi'];// 采暖分区数
$water_type =  $history_info['water_type'];// 热水锅炉形式
$area_num_water = $history_info['area_num_water'];// 热水分区数
$area_num_total = $area_num_nuan_qi + $area_num_water; // 分区数总量

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉选型</title>
    <link rel="stylesheet" href="css/main.css?v=6">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>

    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/GLXX2.css" />
    <script type="text/javascript" src="js/nav.js" ></script>
    <link rel="stylesheet" href="css/Tc.css" />
    <script type="text/javascript" src="js/2.0.0/jquery.min.js" ></script>
    <script type="text/javascript" src="js/layer.js" ></script>

    <script type="text/javascript" src="layer/layer.js"></script>
    <script>
        layer.config({
            extend: 'extend/layer.ext.js'
        });
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
<body class="body_2">
<?php include('top.inc.php');?>
<!--<div class="guolumain">-->
<!--    <div class="guolumain_1">当前位置：锅炉选型 ><span>选型结果</span></div><div class="clear"></div>-->
<!--</div>-->
<div class="XXresult">
    <div id="next" class="XXRmain">
        <?php
        if($heating_type != 0){
            ?>
            <div id="heatingDiv" style="display: <?php if($history_info['application'] == 0 || $history_info['application'] == 2 ) echo 'block'; else echo 'none'; ?>">
                <div class="XXRmain_1">采暖辅机</div>
                <table class="XXRmain_7" border="1">
                    <tr class="GLDetils9_fir">
                        <td width="30%">设备名称</td>
                        <td width="10%">数量</td>
                        <td width="20%">厂家</td>
                        <td width="20%">规格型号</td>
                        <td width="10%">查看详情</td>
                        <td width="10%">是否保留</td>
                    </tr>

                    <!--燃烧器-->
                    <?php
                    if($guoluinfo['vender'] != 43) {
                        ?>
                        <tr>
                            <td id="heat_burner_name">燃气燃烧器</td>
                            <td> 1 台</td>
                            <td>
                                <select id="heat_burner_vender" class="GLXXmain_3" style="width: 100px;text-align: center;margin: 0 auto" type="text">
                                    <option value="0">请选择厂家</option>
                                    <?php
                                    $heat_burner_vender_list = Dict::getListByParentid(5);
                                    if($heat_burner_vender_list) {
                                        foreach ($heat_burner_vender_list as $heat_burner_vender) {
                                            $selected = '';
                                            echo '<option value="' . $heat_burner_vender['id'] . '" ' . $selected . '>' . $heat_burner_vender['name'] . '</option>';
                                        }
                                    }else{
                                        echo "没有找到合适的燃气燃烧器";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td >
                                <select id="heat_burner_version" class="GLXXmain_3" style="width: 240px" type="text">
                                    <option value="0">请选择型号</option>
                                </select>
                            </td>
                            <td><a href="javascript:void(0);" onclick="burner_detail(0)">详情</a></td>
                            <td><input type="checkbox" id="heat_burner_check_btn" name="check_btn" value="heat_burner_check_btn"></td>
                        </tr>
                        <?php
                    }
                    ?>

                    <!-- 全自动软水器 -->
                    <tr>
                        <td id="heat_hdys_name">全自动软水器</td>
                        <td> 1 台</td>
                        <td>
                            <select id="heat_hyds_vender" class="GLXXmain_3" style="width: 100px;text-align: center;margin: 0 auto" type="text">
                                <?php
                                $heat_hyds_vender_list = Dict::getListByParentid(31);
                                if($heat_hyds_vender_list) {
                                    foreach ($heat_hyds_vender_list as $heat_hyds_vender) {
                                        $selected = '';
                                        echo '<option value="' . $heat_hyds_vender['id'] . '" ' . $selected . '>' . $heat_hyds_vender['name'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的全自动软水器";
                                }
                                ?>
                            </select>
                        </td>
                        <td >
                            <select id="heat_hyds_version" class="GLXXmain_3" style="width: 240px" type="text">
                                <?php
                                $heat_hyds_version_list = Hdys_attr::getList('','',0);
                                if($heat_hyds_version_list) {
                                    foreach ($heat_hyds_version_list as $heat_hyds_version) {
                                        $selected = '';
                                        echo '<option value="' . $heat_hyds_version['hdys_id'] . '" ' . $selected . '>' . $heat_hyds_version['hdys_version'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的型号";
                                }
                                ?>
                            </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="hdys_detail(0)">详情</a></td>
                        <td><input type="checkbox" id="heat_hdys_check_btn" name="check_btn" value="heat_hdys_check_btn"></td>
                    </tr>

                    <!-- 膨胀/软化水箱 -->
                    <tr  >
                        <td id="heat_water_box_name"> <?php if($history_info['heating_type'] == 2 || $history_info['heating_type'] == 3 ) echo '膨胀'; else echo '软化'; ?>水箱</td>
                        <td> 1 台</td>
                        <td> 现场制作 </td>
                        <td >
                            <select id="heat_water_box_version" class="GLXXmain_3" style="width: 240px" type="text">
                                <?php
                                $heat_water_box_version_list = Water_box_attr::getList('', '', 0);
                                if($heat_water_box_version_list) {
                                    foreach ($heat_water_box_version_list as $heat_water_box_hyds_version) {
                                        $selected = '';
                                        echo '<option value="' . $heat_water_box_hyds_version['box_id'] . '" ' . $selected . '>' . $heat_water_box_hyds_version['box_version'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的型号";
                                }
                                ?>
                            </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="water_box_detail(0)">详情</a></td>
                        <td><input type="checkbox" id="heat_water_box_check_btn"  name="check_btn" value="heat_water_box_check_btn"></td>
                    </tr>

                    <!-- 类型2 锅炉循环泵 / 类型3 一次侧循环泵 -->
                    <?php
                    if($history_info['heating_type'] == 2 || $history_info['heating_type'] == 3) {
                        echo '
                            <tr>
                                <td id="heat_pipeline_pump_name">'?> <?php
                                    if ($history_info['heating_type'] == 2){
                                        echo '锅炉';
                                    }else if ($history_info['heating_type'] == 3) {
                                        echo '一次侧';
                                    }
                                ?> <?php echo '循环泵</td>
                                <td> 2 台</td>
                                <td>
                                    <select id="heat_pipeline_pump_vender" class="GLXXmain_3 heat_pipeline_pump_vender" style="width: 100px" type="text">' ?>
                        <option value="0">请选择厂家</option>
                        <?php
                        $pipeline_vender_list = Dict::getListByParentid(7);
                        if ($pipeline_vender_list) {
                            foreach ($pipeline_vender_list as $pipeline_vender) {
                                $selected = '';
                                echo '<option value="' . $pipeline_vender['id'] . '" ' . $selected . '>' . $pipeline_vender['name'] . '</option>';
                            }
                        } else {
                            echo "没有找到合适的管道泵";
                        }
                        ?>
                        <?php echo '
                                    </select>
                                </td>
                                <td >
                                    <select id="heat_pipeline_pump_version" class="GLXXmain_3 heat_pipeline_pump_version" style="width: 240px" type="text">
                                        <option value="0">请选择型号</option>
                                    </select>
                                </td>
                                <td><a href="javascript:void(0);" onclick="pipeline_detail(0)">详情</a></td>
                                <td><input type="checkbox" id="heat_pipeline_pump_check_btn"  name="check_btn" value="heat_pipeline_pump_check_btn"></td>
                            </tr>     
                        '?>
                        <?php
                    }
                    ?>

                    <!--采暖循环泵-->
                    <?php
                    if($history_info['heating_type'] == 1){
                            echo '
                                <tr>
                                    <td id="heat_pipeline_name">采暖循环泵</td>
                                    <td> 2 台</td>
                                    <td>
                                        <select id="heat_pipeline_vender" class="GLXXmain_3 heat_pipeline_vender" style="width: 100px" type="text">'?>
                                            <option value="0">请选择厂家</option>
                                            <?php
                                            $pipeline_vender_list = Dict::getListByParentid(7);
                                            if($pipeline_vender_list) {
                                                foreach ($pipeline_vender_list as $pipeline_vender) {
                                                    $selected = '';
                                                    echo '<option value="' . $pipeline_vender['id'] . '" ' . $selected . '>' . $pipeline_vender['name'] . '</option>';
                                                }
                                            }else{
                                                echo "没有找到合适的管道泵";
                                            }
                                            ?>
                                        <?php echo '
                                        </select>
                                    </td>
                                    <td >
                                        <select id="heat_pipeline_version" class="GLXXmain_3 heat_pipeline_version" name="heating_pump0" style="width: 240px" type="text">
                                            <option value="0">请选择型号</option>
                                        </select>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="pipeline_detail(0)">详情</a></td>
                                    <td><input type="checkbox" id="heat_pipeline_check_btn"  name="heat_pipeline_check" value="heat_pipeline_check_btn"></td>
                                </tr>
                                '?>
                                <?php
                            }else if($history_info['heating_type'] == 3 || $history_info['heating_type'] == 4){
                            $i = 1;
                            while($i <= $area_num_nuan_qi){
                                echo '
                                  <tr>
                                    <td id="heat_pipeline_name">分区'?> <?php echo $i ?> <?php echo '采暖循环泵</td>
                                    <td> 2 台</td>
                                    <td>
                                        <select id="heat_pipeline_vender" class="GLXXmain_3 heat_pipeline_vender" style="width: 100px" type="text">'?>
                                            <option value="0">请选择厂家</option>
                                            <?php
                                            $pipeline_vender_list = Dict::getListByParentid(7);
                                            if($pipeline_vender_list) {
                                                foreach ($pipeline_vender_list as $pipeline_vender) {
                                                    $selected = '';
                                                    echo '<option value="' . $pipeline_vender['id'] . '" ' . $selected . '>' . $pipeline_vender['name'] . '</option>';
                                                }
                                            }else{
                                                echo "没有找到合适的管道泵";
                                            }
                                            ?>
                                        <?php echo '
                                        </select>
                                    </td>
                                    <td >
                                        <select id="heat_pipeline_version" class="GLXXmain_3 heat_pipeline_version" name="heating_pump'.$i.'" style="width: 240px" type="text">
                                            <option value="0">请选择型号</option>
                                        </select>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="pipeline_detail(0)">详情</a></td>
                                    <td><input type="checkbox" id="heat_pipeline_check_btn"  name="heat_pipeline_check" value="heat_pipeline_check_btn"></td>
                                </tr>
                                '?>
                                <?php
                                $i++;
                            }
                    }
                    ?>

                    <!-- 系统补水泵 -->
                    <?php
                    if($history_info['heating_type'] == 1){
                            echo '
                            <tr>
                                <td id="heat_syswater_pump_name">系统补水泵</td>
                                <td> 2 台</td>
                                    <td>
                                        <select id="heat_syswater_pump_vender" class="GLXXmain_3 heat_syswater_pump_vender" style="width: 100px" type="text">'?>
                                            <option value="0">请选择厂家</option>
                                            <?php
                                            $syswater_pump_vender_list = Dict::getListByParentid(8);
                                            if($syswater_pump_vender_list) {
                                                foreach ($syswater_pump_vender_list as $syswater_pump_vender) {
                                                    $selected = '';
                                                    echo '<option value="' . $syswater_pump_vender['id'] . '" ' . $selected . '>' . $syswater_pump_vender['name'] . '</option>';
                                                }
                                            }else{
                                                echo "没有找到合适的补水泵";
                                            }
                                            ?>
                                    <?php echo '
                                        </select>
                                    </td>
                                    <td >
                                        <select id="heat_syswater_pump_version" class="GLXXmain_3 heat_syswater_pump_version" style="width: 240px" type="text">
                                            <option value="0">请选择型号</option>
                                        </select>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="syswater_pump_detail(0)">详情</a></td>
                                    <td><input type="checkbox" id="heat_water_pump_check_btn" name="heat_water_pump_check" value="wheat_ater_pump_check_btn"></td>
                                </tr>
                            '?>
                            <?php
                            }
                    else if($history_info['heating_type'] == 3 || $history_info['heating_type'] == 4){
                                $i = 1;
                                while($i <= $area_num_nuan_qi){
                                    echo '
                                    <tr>
                                        <td id="heat_syswater_pump_name">分区'?> <?php echo $i ?> <?php echo '系统补水泵</td>
                                        <td> 2 台</td>
                                        <td>
                                            <select id="heat_syswater_pump_vender" class="GLXXmain_3 heat_syswater_pump_vender" style="width: 100px" type="text">'?>
                                                <option value="0">请选择厂家</option>
                                                <?php
                                                $syswater_pump_vender_list = Dict::getListByParentid(8);
                                                if($syswater_pump_vender_list) {
                                                    foreach ($syswater_pump_vender_list as $syswater_pump_vender) {
                                                        $selected = '';
                                                        echo '<option value="' . $syswater_pump_vender['id'] . '" ' . $selected . '>' . $syswater_pump_vender['name'] . '</option>';
                                                    }
                                                }else{
                                                    echo "没有找到合适的补水泵";
                                                }
                                                ?>
                                        <?php echo '
                                            </select>
                                        </td>
                                        <td >
                                            <select id="heat_syswater_pump_version" class="GLXXmain_3 heat_syswater_pump_version" style="width: 240px" type="text">
                                                <option value="0">请选择型号</option>
                                            </select>
                                        </td>
                                        <td><a href="javascript:void(0);" onclick="syswater_pump_detail(0)">详情</a></td>
                                        <td><input type="checkbox" id="heat_water_pump_check_btn" name="heat_water_pump_check" value="wheat_ater_pump_check_btn"></td>
                                    </tr>
                                    '?>
                                    <?php
                                    $i++;
                                }
                        }
                    ?>

                    <!-- 热水板换 -->
                    <?php
                    if($history_info['heating_type'] == 3){
                            $i = 1;
                            while($i <= $area_num_nuan_qi){
                                echo '
                                <tr>
                                    <td id="heat_board_name">分区'?> <?php echo $i ?> <?php echo '采暖板换</td>
                                    <td> 1 台</td>
                                    <td></td>
                                    <td >
                                        <div class="XXRmain_15">
                                                <div class="XXRmain_16"><span id="heat_exchange_Q_name">换热量</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="heat_exchange_Q"/><span id="heat_exchange_Q_unit">KW</span>
                                                </div>
                                            
                                                <div class="XXRmain_16" ><span id="heat_once_sarwt_name">一次侧供回水温度</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="heat_once_sarwt"/><span id="heat_once_sarwt_unit">℃</span>
                                                </div>
                                            </div>
                                        <div class="XXRmain_15">
                                                <div class="XXRmain_16"><span id="heat_twice_sarwt_name">二次侧供回水温度</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="heat_twice_sarwt"/><span id="heat_twice_sarwt_unit">℃</span>
                                                </div>
                                            
                                                <div class="XXRmain_16"><span id="heat_pressure_bearing">承压 = 1.6MPa</span>
                                                
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="heat_exchanger_detail(0)">详情</a></td>
                                    <td>
                                        <input type="checkbox" id="heat_board_check_btn" name="heat_board_check"
                                               value="heat_board_check_btn" class="heat_board_check">
                                    </td>
                                </tr>
                                '?>
                                <?php
                                $i++;
                            }
                        }
                        ?>

                    <!-- 锅炉除污器 -->
                    <tr>
                        <td id="heat_dirt_separater_name"></td>锅炉除污器</td>
                        <td> 1 台</td>
                        <td></td>
                        <td >
                            <select id="heat_dirt_separator_version" class="GLXXmain_3" style="width: 240px" type="text">
                                <?php
                                $heat_dirt_separator_version_list = Dirt_separator_attr::getList('', '', 0);
                                if($heat_dirt_separator_version_list) {
                                    foreach ($heat_dirt_separator_version_list as $heat_dirt_separator_version) {
                                        $selected = '';
                                        echo '<option value="' . $heat_dirt_separator_version['separator_id'] . '" ' . $selected . '>' . $heat_dirt_separator_version['separator_version'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的型号";
                                }
                                ?>
                                </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="dirt_separator_detail(0)">详情</a></td>
                        <td><input type="checkbox" id="heat_dirt_separater_check_btn" name="check_btn" value="heat_dirt_separater_check_btn"></td>
                    </tr>

                    <!-- 分区除污器 -->
                    <?php
                    if($history_info['heating_type'] == 3){
                            $i = 1;
                            while($i <= $area_num_nuan_qi){
                                echo '
                                <tr>
                                    <td id="heat_dirt_separator_multi_name">分区'?> <?php echo $i ?> <?php echo '除污器</td>
                                    <td> 1 台</td>
                                    <td></td>
                                    <td >
                                        <select id="heat_dirt_separator_version_multi" class="GLXXmain_3" style="width: 240px" type="text">'?>
                                            <?php
                                            $heat_dirt_separator_version_list = Dirt_separator_attr::getList('', '', 0);
                                            if($heat_dirt_separator_version_list) {
                                                foreach ($heat_dirt_separator_version_list as $heat_dirt_separator_version) {
                                                    $selected = '';
                                                    echo '<option value="' . $heat_dirt_separator_version['separator_id'] . '" ' . $selected . '>' . $heat_dirt_separator_version['separator_version'] . '</option>';
                                                }
                                            }else{
                                                echo "没有找到合适的型号";
                                            }
                                            ?>
                                    <?php echo '
                                        </select>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="dirt_separator_detail(0)">详情</a></td>
                                    <td><input type="checkbox" id="heat_dirt_separater_multi_check_btn" name="heat_dirt_separater_multi_check" value="heat_dirt_separater_multi_check_btn"></td>
                                </tr>
                                '?>
                                <?php
                                $i++;
                        }
                    }
                    ?>

                    <!-- 水泵控制柜 -->
                    <?php
                        if ($history_info['heating_type'] != 4){
                            echo '
                                <tr>
                                    <td id="heat_pump_control_name">水泵控制柜</td>
                                    <td>1套</td>
                                    <td></td>
                                    <td >电负荷<span id="heat_pump_control_power" data-value="0"></span>kW</td>
                                    <td></td>
                                    <td><input type="checkbox" id="heat_pump_control_check_btn" name="heat_pump_control_check"
                                    value="heat_pump_control_check_btn" class="heat_pump_control_check"></td>
                                </tr>
                                '?>
                            <?php
                        }else{
                            $i = 1;
                            while($i <= $area_num_nuan_qi){
                                echo '
                                    <tr>
                                        <td id="heat_pump_control_name">分区'?> <?php echo $i ?> <?php echo '水泵控制柜</td>
                                        <td>1套</td>
                                        <td></td>
                                        <td >电负荷<span id="heat_pump_control_power" data-value="0"></span>kW</td>
                                        <td></td>
                                        <td><input type="checkbox" id="heat_pump_control_check_btn" name="heat_pump_control_check" value="heat_pump_control_check_btn"></td>
                                    </tr>
                                '?>
                                <?php
                                $i++;
                            }
                        }
                    ?>

                    <!-- 钢制烟囱 -->
                    <tr>
                        <td id="heat_chimney_name">钢制烟囱</td>
                        <td>1套</td>
                        <td></td>
                        <td >
                            <div class="XXRmain_12">
                                <span id="heat_chimney_height_name">高度</span><input id="heat_chimney_height" class="XXRmain_13" type="number" value="18"/><span id="heat_chimney_height_unit" style="margin-right: 16px;">m</span>
                                <span id="heat_chimney_diameter_name">直径</span><input id="heat_chimney_diameter" class="XXRmain_13" type="number" value="2000"/><span id="heat_chimney_diameter_unit">mm</span>
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" id="heat_chimney_check_btn" name="check_btn" value="heat_chimney_check_btn"></td>
                    </tr>

                    <!-- 分集水器 -->
                    <tr>
                        <td id="heat_diversity_water_name">分集水器</td>
                        <td></td>
                        <td></td>
                        <td >
                            <div class="XXRmain_12">
                                <span id="diversity_water_num_name">接口数量</span><input class="XXRmain_13" type="number" value="0" id="diversity_water_num" /><span id="diversity_water_num_unit">个</span>
                                <span id="diversity_water_length_name">直径</span><input style="margin-top:8px" class="XXRmain_13" type="number" value="" id="diversity_water_length" /><span id="diversity_water_length_unit">mm</span>
                                <span id="diversity_water_diameter_name">长度</span><input style="margin-top:8px" class="XXRmain_13" type="number" value="" id="diversity_water_diameter" /><span id="diversity_water_diameter_unit">m</span>
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" id="diversity_water_check_btn" name="check_btn" value="diversity_water_check_btn"></td>
                    </tr>

                    <!-- 配电柜 -->
                    <tr>
                        <td id="heat_power_box_name">配电柜</td>
                        <td> 1 台</td>
                        <td>&nbsp;</td>
                        <td >电负荷<span id="heat_power_box_power" data-value="0"></span>kW</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" id="heat_power_box_check_btn"  name="check_btn" value="heat_power_box_check_btn"></td>
                    </tr>

                </table>
            </div>
            <?php
        }



        if($water_type != 0){
            ?>
            <div id="waterDiv" style="display: <?php if($history_info['application'] == 1 || $history_info['application'] == 2 ) echo 'block'; else echo 'none'; ?>">
                <div class="XXRmain_1">热水辅机</div>
                <table class="XXRmain_7" border="1">
                    <tr class="GLDetils9_fir">
                        <td width="30%">设备名称</td>
                        <td width="10%">数量</td>
                        <td width="20%">厂家</td>
                        <td width="20%">规格型号</td>
                        <td width="10%">查看详情</td>
                        <td width="10%">是否保留</td>
                    </tr>

                    <!--燃烧器-->
                    <?php
                    if($guoluinfo['vender'] != 43) {
                        ?>
                        <tr>
                            <!-- 燃气燃烧器 -->
                            <td id="water_burner_name">燃气燃烧器</td>
                            <td> 1 台</td>
                            <td>
                                <select id="water_burner_vender" class="GLXXmain_3" style="width: 100px" type="text">
                                    <?php
                                    $water_burner_vender_list = Dict::getListByParentid(5);
                                    if($water_burner_vender_list) {
                                        foreach ($water_burner_vender_list as $water_burner_vender) {
                                            $selected = '';
                                            echo '<option value="' . $water_burner_vender['id'] . '" ' . $selected . '>' . $water_burner_vender['name'] . '</option>';
                                        }
                                    }else{
                                        echo "没有找到合适的燃气燃烧器";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td >
                                <select id="water_burner_version" class="GLXXmain_3" style="width: 240px" type="text"></select>
                            </td>
                            <td><a href="javascript:void(0);" onclick="burner_detail(0)">详情</a></td>
                            <td><input type="checkbox" id="water_burner_check_btn" name="check_btn" value="water_burner_check_btn"></td>
                        </tr>
                        <?php
                    }
                    ?>

                    <!-- 全自动软水器 -->
                    <tr>
                        <td id="water_hyds_name">全自动软水器</td>
                        <td> 1 台</td>
                        <td>
                            <select id="water_hyds_vender" class="GLXXmain_3" style="width: 100px" type="text">
                                <?php
                                $water_hyds_vender_list = Dict::getListByParentid(31);
                                if($water_hyds_vender_list) {
                                    foreach ($water_hyds_vender_list as $water_hyds_vender) {
                                        $selected = '';
                                        echo '<option value="' . $water_hyds_vender['id'] . '" ' . $selected . '>' . $water_hyds_vender['name'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的全自动软水器";
                                }
                                ?>
                            </select>
                        </td>
                        <td >
                            <select id="water_hyds_version" class="GLXXmain_3" type="text">
                                <?php
                                $water_hyds_version_list = Hdys_attr::getList('','',0);
                                if($water_hyds_version_list) {
                                    foreach ($water_hyds_version_list as $water_hyds_version) {
                                        $selected = '';
                                        echo '<option value="' . $water_hyds_version['hdys_id'] . '" ' . $selected . '>' . $water_hyds_version['hdys_version'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的型号";
                                }
                                ?>
                            </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="hdys_detail(0)">详情</a></td>
                        <td><input type="checkbox" id="water_hdys_check_btn" name="check_btn" value="water_hdys_check_btn"></td>
                    </tr>

                    <!-- 软化水箱 -->
                    <tr  >
                        <td id="water_box_name"> 软化水箱</td>
                        <td> 1 台</td>
                        <td> 现场制作 </td>
                        <td >
                            <select id="water_box_version" class="GLXXmain_3"  type="text">
                                <?php
                                $water_box_version_list = Water_box_attr::getList('', '', 0);
                                if($water_box_version_list) {
                                    foreach ($water_box_version_list as $water_box_hyds_version) {
                                        $selected = '';
                                        echo '<option value="' . $water_box_hyds_version['box_id'] . '" ' . $selected . '>' . $water_box_hyds_version['box_version'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的型号";
                                }
                                ?>
                            </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="water_box_detail(0)">详情</a></td>
                        <td><input type="checkbox" id="water_box_check_btn"  name="check_btn" value="water_box_check_btn"></td>
                    </tr>

                    <!--锅炉循环泵-->
                    <tr>
                        <td id="water_pipeline_name">锅炉循环泵</td>
                        <td> 2 台</td>
                        <td>
                            <select id="water_pipeline_vender" class="GLXXmain_3 water_pipeline_vender" type="text">
                                <option value="0">请选择厂家</option>
                                <?php
                                $pipeline_vender_list = Dict::getListByParentid(7);
                                if($pipeline_vender_list) {
                                    foreach ($pipeline_vender_list as $pipeline_vender) {
                                        $selected = '';
                                        echo '<option value="' . $pipeline_vender['id'] . '" ' . $selected . '>' . $pipeline_vender['name'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的管道泵";
                                }
                                ?>
                            </select>
                        </td>
                        <td >
                            <select id="water_pipeline_version" class="GLXXmain_3 water_pipeline_version"  type="text">
                                <option value="0">请选择型号</option>
                            </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="pipeline_detail(0)">详情</a></td>
                        <td><input type="checkbox" id="water_pipeline_pump_check_btn"  name="check_btn" value="water_pipeline_pump_check_btn"></td>
                    </tr>

                    <!-- 热水循环泵 -->
                    <?php
                    if($history_info['water_type'] == 3 || $history_info['water_type'] == 4) {
                            $i = 1;
                            $hotwater_pump_vender_list = Dict::getListByParentid(7);

                            while ($i <= $history_info['area_num_water']) {
                                echo '
                                 <tr>
                                    <td id="hotwater_pump_name">分区'?><?php echo $i ?> <?php echo '热水循环泵</td>
                                    <td> 2 台</td>
                                    <td>
                                        <select id="hotwater_pump_vender" class="GLXXmain_3 hotwater_pump_vender" type="text">' ?>
                                        <option value="0">请选择厂家</option>
                                        <?php
                                        if ($hotwater_pump_vender_list) {
                                            foreach ($hotwater_pump_vender_list as $hotwater_pump_vender) {
                                                $selected = '';
                                                echo '<option value="' . $hotwater_pump_vender['id'] . '" ' . $selected . '>' . $hotwater_pump_vender['name'] . '</option>';
                                            }
                                        } else {
                                            echo "没有找到合适的管道泵";
                                        }
                                        ?>
                                <?php echo '    
                                        </select>
                                    </td>
                                    <td >
                                        <select id="hotwater_pump_version" class="GLXXmain_3 hotwater_pump_version"  type="text">
                                            <option value="0">请选择型号</option>
                                        </select>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="pipeline_detail(0)">详情</a></td>
                                    <td><input type="checkbox" id="hotwater_pump_check_btn"  name="hotwater_pump_check" value="hotwater_pump_check_btn"></td>
                                </tr>
                                ' ?>
                                <?php
                                $i++;
                            }
                        }
                    ?>

                    <!-- 变频供水泵 -->
                    <?php
                    if($history_info['water_type'] == 3 || $history_info['water_type'] == 4){
                            $i = 1;
                            while ($i <= $history_info['area_num_water']){
                                echo '
                                 <tr>
                                    <td id="dynamic_water_pump_name">分区'?><?php echo $i ?> <?php echo '变频供水泵</td>
                                    <td> 2 台</td>
                                    <td>
                                        <select id="dynamic_water_pump_vender" class="GLXXmain_3 dynamic_water_pump_vender"  type="text">'?>
                                            <option value="0">请选择厂家</option>
                                            <?php
                                            $dynamic_water_pump_vender_list = Dict::getListByParentid(7);
                                            if($dynamic_water_pump_vender_list) {
                                                foreach ($dynamic_water_pump_vender_list as $dynamic_water_pump_vender) {
                                                    $selected = '';
                                                    echo '<option value="' . $dynamic_water_pump_vender['id'] . '" ' . $selected . '>' . $dynamic_water_pump_vender['name'] . '</option>';
                                                }
                                            }else{
                                                echo "没有找到合适的管道泵";
                                            }
                                            ?>
                                            <?php echo '
                                        </select>
                                    </td>
                                    <td >
                                        <select id="dynamic_water_pump_version" class="GLXXmain_3 dynamic_water_pump_version"  type="text">
                                            <option value="0">请选择型号</option>
                                        </select>
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="pipeline_detail(0)">详情</a></td>
                                    <td><input type="checkbox" id="dynamic_water_pump_check_btn"  name="dynamic_water_pump_check" value="dynamic_water_pump_check_btn"></td>
                                </tr>
                                '?>
                                <?php
                                $i++;
                            }
                        }
                    ?>

                    <!-- 除污器 -->
                    <tr>
                        <td id="water_dirt_separator_name">除污器</td>
                        <td> 1 台</td>
                        <td> </td>
                        <td >
                            <select id="water_dirt_separator_version" class="GLXXmain_3" type="text">
                                <?php
                                $water_dirt_separator_version_list = Dirt_separator_attr::getList('', '', 0);
                                if($water_dirt_separator_version_list) {
                                    foreach ($water_dirt_separator_version_list as $water_dirt_separator_version) {
                                        $selected = '';
                                        echo '<option value="' . $water_dirt_separator_version['separator_id'] . '" ' . $selected . '>' . $water_dirt_separator_version['separator_version'] . '</option>';
                                    }
                                }else{
                                    echo "没有找到合适的型号";
                                }
                                ?>
                            </select>
                        </td>
                        <td><a href="javascript:void(0);" onclick="dirt_separator_detail(1)">详情</a></td>
                        <td><input type="checkbox" id="water_dirt_separater_check_btn" name="check_btn" value="water_dirt_separater_check_btn"></td>
                    </tr>

                    <!-- 热水板换 -->
                    <?php
                    if( $history_info['water_type'] == 1 || $history_info['water_type'] == 3){
                            $i = 1;
                            while($i <= $area_num_water){
                                echo '
                                <tr>
                                    <td id="water_board_name">分区'?><?php echo $i ?> <?php echo '热水板换</td>
                                    <td> 1 台</td>
                                    <td></td>
                                    <td >
                                        <div class="XXRmain_15">
                                            
                                                <div class="XXRmain_16"><span>换热量</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="water_exchange_Q"/><span>KW</span>
                                                </div>
                                            
                                                <div class="XXRmain_16"><span>一次侧供回水温度</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="water_once_sarwt"/><span>℃</span>
                                                </div>
                                            </div>
                                        <div class="XXRmain_15">
                                                <div class="XXRmain_16"><span>二次侧供回水温度</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="water_twice_sarwt"/><span>℃</span>
                                                </div>
                                            
                                                <div class="XXRmain_16"><span id="water_pressure_bearing">承压 = 1.6MPa</span>
                                                
                                            </div>
                                        </div>       
                                    </td>
                                    <td><a href="javascript:void(0);" onclick="heat_exchanger_detail(0)">详情</a></td>
                                    <td>
                                       <input type="checkbox" id="water_board_check_btn" name="water_board_check" value="water_board_check_btn">
                                    </td>                
                                    </tr>'?>
                                <?php
                                $i++;
                            }
                    }
                    else{
                        echo '<tr>
                                        <td id="water_board_name">热水板换</td>
                                        <td> 1 台</td>
                                        <td></td>
                                        <td >
                                            <div class="XXRmain_15">
                                          
                                                <div class="XXRmain_16"><span>换热量</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="water_exchange_Q"/><span>KW</span>
                                                </div>
                                            
                                                <div class="XXRmain_16"><span>一次侧供回水温度</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="water_once_sarwt"/><span>℃</span>
                                                </div>
                                            </div>
                                            <div class="XXRmain_15">
                                                <div class="XXRmain_16"><span>二次侧供回水温度</span>
                                                    <input style="margin-top:8px" class="XXRmain_13" type="number" value=""
                                                           id="water_twice_sarwt"/><span>℃</span>
                                                </div>
                                            
                                                <div class="XXRmain_16"><span id="water_pressure_bearing">承压 = 1.6MPa</span>
                                                
                                            </div>
                                        </div>   
                                        </td>
                                        <td><a href="javascript:void(0);" onclick="heat_exchanger_detail(0)">详情</a></td>
                                        <td>
                                            <input type="checkbox" id="water_board_check_btn" name="water_board_check"
                                                   value="water_board_check_btn">
                                        </td>
                                    </tr>' ?>
                        <?php
                    }
                    ?>

                    <!-- 不锈钢保温水箱 -->
                    <?php
                   if( $history_info['water_type'] == 1 || $history_info['water_type'] == 3){
                            $i = 1;
                            while($i <= $area_num_water){
                                echo '
                                <tr>
                                    <td id="hot_water_box_name">分区'?><?php echo $i ?> <?php echo '不锈钢保温水箱</td>
                                    <td> 1 台</td>
                                    <td> </td>
                                    <td >
                                        <div class="XXRmain_15">
                                            <div class="XXRmain_16"><span>公称容积</span>
                                                <input style="margin-top:8px" class="XXRmain_13" type="number" value="" id="water_hotwater_capacity" /><span > m³ </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td> </td>
                                    <td>
                                        <input type="checkbox" id="hot_water_box_check_btn" name="hot_water_box_check" value="hot_water_box_check_btn">
                                    </td>
                                </tr>
                                '?>
                                <?php
                                $i++;
                            }
                    }
                    else{
                        echo '
                        <tr>
                            <td id="hot_water_box_name">不锈钢保温水箱</td>
                            <td> 1 台</td>
                            <td> </td>
                            <td >
                                <div class="XXRmain_15">
                                    <div class="XXRmain_16"><span>公称容积</span>
                                        <input style="margin-top:8px" class="XXRmain_13" type="number" value="" id="water_hotwater_capacity" /><span > m³ </span>
                                    </div>
                                </div>
                            </td>
                            <td> </td>
                            <td>
                                <input type="checkbox" id="hot_water_box_check_btn" name="hot_water_box_check" value="hot_water_box_check_btn">
                            </td>
                        </tr>
                        ' ?>
                        <?php
                    }
                    ?>

                    <!-- 水泵控制柜 -->
                    <tr>
                        <td id="water_pump_control_name">水泵控制柜</td>
                        <td>1套</td>
                        <td></td>
                        <td > 电负荷<span id="water_pump_control_power" data-value="0"></span>kW </td>
                        <td></td>
                        <td><input type="checkbox" id="water_pump_control_check_btn" name="check_btn" value="water_pump_control_check_btn"></td>
                    </tr>

                    <!-- 钢制烟囱 -->
                    <tr>
                        <td id="water_chimney_name">钢制烟囱</td>
                        <td>1套</td>
                        <td></td>
                        <td >
                            <div class="XXRmain_12">
                                高度<input id="water_chimney_height" class="XXRmain_13" type="number" value="18"/><span style="margin-right: 16px;">m</span>
                                直径<input id="water_chimney_diameter" class="XXRmain_13" type="number" value="2000"/><span>mm</span>
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" id="water_chimney_check_btn" name="check_btn" value="water_chimney_check_btn"></td>
                    </tr>

                    <!-- 配电柜 -->
                    <tr>
                        <td id="water_power_box_name">配电柜</td>
                        <td> 1 台</td>
                        <td>&nbsp;</td>
                        <td >电负荷<span id="water_power_box_power" data-value="0"></span>kW</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" id="water_power_box_check_btn"  name="check_btn" value="water_power_box_check_btn"></td>
                    </tr>


                </table>
            </div>
            <?php
        }
        ?>
        <div class="XXRmain_17"><span>添加备注</span></div>
        <textarea class="XXRmain_18" id="remark"></textarea>
        <button class="XXRmain_19" id="subimt_fuji">确定</button>
    </div>
</div>
<script>

    $(function () {
        $("body").on("click", '.power_compute', function(){
            var box_power = 0;
            var pump_control = '';
            //燃烧机电功率
            var burner_power = $('#heating_burner_power').val();
            if(burner_power == undefined) burner_power = 0;
            if(burner_power != 0){
                box_power = parseInt(box_power) + parseFloat(burner_power)*100;
            }
            //循环泵
            var pipeline_power = $('input[name="pipeline_pump"]:checked ').parent().find('.motorpower').val();
            if(pipeline_power == undefined) pipeline_power = 0;
            if(pipeline_power != 0){
                box_power = parseInt(box_power) + 3*parseFloat(pipeline_power)*100;
                pump_control = '2×'+pipeline_power + 'kW';
            }
            //采暖循环泵
            var length = $(".heating_pump_data").length;
            for(i=0;i<length;i++){
                var heating_pump_power = $('input[name="heating_pump'+ i +'"]:checked ').parent().find('.motorpower').val();
                if(heating_pump_power == undefined) heating_pump_power = 0;
                if(heating_pump_power != 0){
                    box_power = parseInt(box_power) + 3*parseFloat(heating_pump_power)*100;
                    if(pump_control == ''){
                        pump_control = '2×'+heating_pump_power + 'kW';
                    }else{
                        pump_control = pump_control + ' + ' + '2×'+heating_pump_power + 'kW';
                    }
                }
            }

            //补水泵
            var length = $(".water_pump_data").length;
            for(i=0;i<length;i++){
                var water_pump_power = $('input[name="water_pump'+ i +'"]:checked ').parent().find('.motorpower').val();
                if(water_pump_power == undefined) water_pump_power = 0;
                if(water_pump_power != 0){
                    box_power = parseInt(box_power) + 3*parseFloat(water_pump_power)*100;
                    if(pump_control == ''){
                        pump_control = '2×'+water_pump_power + 'kW';
                    }else{
                        pump_control = pump_control + ' + ' + '2×'+water_pump_power + 'kW';
                    }
                }
            }
            box_power = box_power / 100;
            $('#heating_pump_control').text(pump_control);
            $('#heating_powe_box').text(box_power);
        });

        var application = '<?php echo $history_info['application'];?>';
        $('#subimt_fuji').click(function () {

            // ----------------------------- 采暖辅机 -------------------------------
            // --------- 单选，与分区数无关 --------
            // 采暖-燃气燃烧机
            var heat_burner_check = 0;
            // 采暖-全自动软水器
            var heat_hdys_check = 0;
            // 采暖-膨胀/软化水箱
            var heat_water_box_check = 0;
            // 采暖- 类型2 锅炉循环泵  /  类型3 一次侧循环泵
            var heat_pipeline_pump_check = 0;
            // 采暖-锅炉除污器
            var heat_dirt_separater_check = 0;
            // 采暖-钢制烟囱
            var heat_chimney_check = 0;
            // 采暖-配电柜
            var heat_powe_box_check = 0;
            // 采暖-分集水器
            var diversity_water_check = 0;

            // ----------------------------- 热水辅机 -------------------------------
            // --------- 单选，与分区数无关 --------
            // 热水-燃气燃烧机
            var water_burner_check = 0;
            // 热水-全自动软水器
            var water_hdys_check = 0;
            // 热水-软化水箱
            var water_box_check = 0;
            // 热水-水泵控制柜
            var water_pump_control_check = 0;
            // 热水-钢制烟囱
            var water_chimney_check = 0;
            // 热水-配电柜
            var water_powe_box_check = 0;
            // 锅炉循环泵
            var water_pipeline_pump_check = 0;
            // 热水-除污器
            var water_dirt_separater_check = 0;

            $('input[name="check_btn"]:checked').each(function(){
                switch ($(this).val()) {
                    // ----------------------------- 采暖辅机 单选，与分区数无关-------------------------------
                    // 采暖-燃气燃烧机
                    case "heat_burner_check_btn":
                        heat_burner_check = 1;
                        break;
                    // 采暖-全自动软水器
                    case "heat_hdys_check_btn":
                        heat_hdys_check = 1;
                        break;
                    // 采暖-膨胀/软化水箱
                    case "heat_water_box_check_btn":
                        heat_water_box_check = 1;
                        break;
                    // 采暖- 类型2 锅炉循环泵  /  类型3 一次侧循环泵
                    case "heat_pipeline_pump_check_btn":
                        heat_pipeline_pump_check = 1;
                        break;
                    // 采暖-锅炉除污器
                    case "heat_dirt_separater_check_btn":
                        heat_dirt_separater_check = 1;
                        break;
                    // 采暖-钢制烟囱
                    case "heat_chimney_check_btn":
                        heat_chimney_check = 1;
                        break;
                    // 采暖-分集水器
                    case "diversity_water_check_btn":
                        diversity_water_check = 1;
                        break;
                    // 采暖-配电柜
                    case "heat_power_box_check_btn":
                        heat_powe_box_check = 1;
                        break;

                    // ----------------------------- 热水辅机 单选，与分区数无关-------------------------------
                    // 热水-燃气燃烧机
                    case "water_burner_check_btn":
                        water_burner_check = 1;
                        break;
                    // 热水-全自动软水器
                    case "water_hdys_check_btn":
                        water_hdys_check = 1;
                        break;
                    // 热水-软化水箱
                    case "water_box_check_btn":
                        water_box_check = 1;
                        break;
                    // 热水-水泵控制柜
                    case "water_pump_control_check_btn":
                        water_pump_control_check = 1;
                        break;
                    // 热水-钢制烟囱
                    case "water_chimney_check_btn":
                        water_chimney_check = 1;
                        break;
                    // 热水-配电柜
                    case "water_power_box_check_btn":
                        water_powe_box_check = 1;
                        break;
                    // 热水-锅炉循环泵
                    case "water_pipeline_pump_check_btn":
                        water_pipeline_pump_check = 1;
                        break;
                    // 热水-除污器
                    case "water_dirt_separater_check_btn":
                        water_dirt_separater_check = 1;
                        break;
                }
            });

            // ----------------------------- 采暖辅机 -------------------------------

            // 采暖-燃气燃烧机
            var heat_burner_id = $('#heat_burner_version').val();
            var heat_burner_name = $('#heat_burner_name').text();
            var heat_burner_count = 1;
            if(heat_burner_check == 0 || heat_burner_id == undefined)  {
                heat_burner_id = 0;
                heat_burner_name = '';
                heat_burner_count = 0;
            }

            // 采暖-全自动软水器
            var heat_hdys_id= $('#heat_hyds_version').val();
            var heat_hdys_name= $('#heat_hyds_name').text();
            var heat_hdys_count= 1;
            if(heat_hdys_check == 0 || heat_hdys_id == undefined){
                heat_hdys_id = 0;
                heat_hdys_name = '';
                heat_hdys_count = 0;
            }

            // 采暖-膨胀/软化水箱
            var heat_water_box_id= $('#heat_water_box_version').val();
            var heat_water_box_name= $('#heat_water_box_name').text();
            var heat_water_box_count= 1;
            if(heat_water_box_check == 0 || heat_water_box_id == undefined){
                heat_water_box_id = 0;
                heat_water_box_name = '';
                heat_water_box_count = 0;
            }

            // 采暖- 类型2 锅炉循环泵  /  类型3 一次侧循环泵
            var heat_pipeline_pump_id =  $('#heat_pipeline_pump_version').val();
            var heat_pipeline_pump_name =  $('#heat_pipeline_pump_name').text();
            var heat_pipeline_pump_count =  2;
            if(heat_pipeline_pump_check == 0 || heat_pipeline_pump_id == undefined){
                heat_pipeline_pump_id = 0;
                heat_pipeline_pump_name = '';
                heat_pipeline_pump_count = 0;
            }

            // 采暖循环泵
            var length = $(".heat_pipeline_version").length;
            var heat_pipeline_id = "";
            var heat_pipeline_name = "";
            var heat_pipeline_count = "";
            var heat_pipeline_check = "";
            for(i=0;i<length;i++){
                var thisE = $(".heat_pipeline_version").eq(i);
                var thisid = thisE.parent().parent().find('#heat_pipeline_version option:selected').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="heat_pipeline_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                if(thisIfCheck == 0){
                    heat_pipeline_count = heat_pipeline_count + '||' + "0";
                }else {
                    heat_pipeline_count = heat_pipeline_count + '||' + "2";
                }
                heat_pipeline_id = heat_pump_id + '||' + thisid;
                heat_pipeline_name = heat_pump_name + '||' + thisE.parent().parent().find('#heat_pipeline_name').text();
                heat_pipeline_check = heat_pump_check + '||' + thisIfCheck;

            }

            // 采暖-系统补水泵
            var length1 = $(".heat_syswater_pump_version").length;
            var heat_syswater_pump_id = "";
            var heat_syswater_pump_name = "";
            var heat_syswater_pump_count = "";
            var heat_syswater_pump_check = "";
            for(i=0;i<length1;i++){
                var thisE = $(".heat_syswater_pump_version").eq(i);
                var thisid = thisE.parent().parent().find('#heat_syswater_pump_version option:selected').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="heat_water_pump_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                if(thisIfCheck == 0){
                    heat_syswater_pump_count = heat_syswater_pump_count + '||' + "0";
                }else {
                    heat_syswater_pump_count = heat_syswater_pump_count + '||' + "2";
                }
                heat_syswater_pump_id = heat_syswater_pump_id + '||' + thisid;
                heat_syswater_pump_name = heat_syswater_pump_name + '||' + thisE.parent().parent().find('#heat_syswater_pump_name').text();
                heat_syswater_pump_check = heat_syswater_pump_check + '||' + thisIfCheck;
            }

            // 采暖-锅炉除污器
            var heat_dirt_separater_id= $('#heat_dirt_separator_version').val();
            var heat_dirt_separater_name= $('#heat_dirt_separator_name').text();
            var heat_dirt_separater_count= 1;
            if(heat_dirt_separater_check == 0 || heat_dirt_separater_id == undefined){
                heat_dirt_separater_id = 0;
                heat_dirt_separater_name = '';
                heat_dirt_separater_count = 0;
            }

            // 采暖-分区除污器
            var length2 = $(".heat_dirt_separator_version_multi").length;
            var heat_dirt_separater_multi_id = "";
            var heat_dirt_separater_multi_name = "";
            var heat_dirt_separater_multi_count = "";
            var heat_dirt_separater_multi_check = "";
            for(i=0;i<length2;i++){
                var thisE = $(".heat_dirt_separator_version_multi").eq(i);
                var thisid = thisE.parent().parent().find('#heat_dirt_separator_version_multi option:selected').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="heat_dirt_separater_multi_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                if (thisIfCheck == 0){
                    heat_dirt_separater_multi_count = heat_dirt_separater_multi_count + '||' + "0";
                } else {
                    heat_dirt_separater_multi_count = heat_dirt_separater_multi_count + '||' + "1";
                }
                heat_dirt_separater_multi_id = heat_dirt_separater_multi_id + '||' + thisid;
                heat_dirt_separater_multi_name = heat_dirt_separater_multi_name + '||' + thisE.parent().parent().find('#heat_dirt_separator_multi_name').text();
                heat_dirt_separater_multi_check = heat_dirt_separater_multi_check + '||' + thisIfCheck;

            }

            // 采暖-采暖板换
            var length3 = $(".heat_board_check").length;
            var heat_board_value = "";
            var heat_board_name = "";
            var heat_board_count = "";
            var heat_board_check = "";
            for(i=0;i<length3;i++){
                var thisE = $(".heat_board_check").eq(i);
                var thisIfCheck = thisE.parent().parent().find('input[name="heat_board_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                if (thisIfCheck == 0){
                    heat_board_count = heat_board_count + '||' + "0";
                }else {
                    heat_board_count = heat_board_count + '||' + "1";
                }
                var board_value_temp = "";
                var heat_exchange_Q_name = thisE.parent().parent().find('#heat_exchange_Q_name').text();
                var heat_exchange_Q_value = thisE.parent().parent().find('#heat_exchange_Q').val();
                var heat_exchange_Q_unit = thisE.parent().parent().find('#heat_exchange_Q_unit').text();
                board_value_temp = board_value_temp + heat_exchange_Q_name + heat_exchange_Q_value + heat_exchange_Q_unit + "&nbsp;";

                var heat_once_sarwt_name = thisE.parent().parent().find('#heat_once_sarwt_name').text();
                var heat_once_sarwt_value = thisE.parent().parent().find('#heat_once_sarwt').val();
                var heat_once_sarwt_unit = thisE.parent().parent().find('#heat_once_sarwt_unit').text();
                board_value_temp = board_value_temp + heat_once_sarwt_name + heat_once_sarwt_value + heat_once_sarwt_unit + "&nbsp;";

                var heat_twice_sarwt_name = thisE.parent().parent().find('#heat_twice_sarwt_name').text();
                var heat_twice_sarwt_value = thisE.parent().parent().find('#heat_twice_sarwt').val();
                var heat_twice_sarwt_unit = thisE.parent().parent().find('#heat_twice_sarwt_unit').text();
                board_value_temp = board_value_temp + heat_twice_sarwt_name + heat_twice_sarwt_value + heat_twice_sarwt_unit + "&nbsp;";

                var heat_pressure_bearing = thisE.parent().parent().find('#heat_pressure_bearing').text();
                board_value_temp = board_value_temp + heat_pressure_bearing;

                heat_board_value = heat_board_value + '||' + board_value_temp;
                heat_board_name = heat_board_name + '||' + thisE.parent().parent().find('#heat_board_name').text();
                heat_board_check = heat_board_check + '||' + thisIfCheck;
            }


            // 采暖-水泵控制柜  // 采暖水泵控制柜(真空锅炉时为多分区)
            var len = $(".heat_pump_control_check").length;
            var heat_pump_control = "";
            var heat_pump_control_name = "";
            var heat_pump_control_check = "";
            for(i=0;i<len;i++){
                var thisE = $(".heat_pump_control_check").eq(i);
                var thisIfCheck = thisE.parent().parent().find('input[name="heat_pump_control_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                heat_pump_control = heat_pump_control  + "||" +  $('#heat_pump_control').data("value");
                heat_pump_control_name = heat_pump_control_name + "||" + thisE.parent().parent().find('#heat_pump_control_name').text();
                heat_pump_control_check = heat_pump_control_check + "||" + thisIfCheck;
            }

            // 采暖-钢制烟囱
            var chimney_height = $('#heat_chimney_height').val();
            if(heat_chimney_check == 0 || chimney_height == '' || chimney_height == undefined) chimney_height = 0;
            var chimney_diameter = $('#heat_chimney_diameter').val();
            if(heat_chimney_check == 0 || chimney_diameter == undefined) chimney_diameter = 0;

            // 采暖-分集水器
            var diversity_water_num = $('#diversity_water_num').val();
            if(diversity_water_check == 0 || diversity_water_num == '' || diversity_water_num == undefined)
                diversity_water_num = 0;
            var diversity_water_length = $('#diversity_water_length').val();
            if(diversity_water_check == 0 || diversity_water_length == '' || diversity_water_length == undefined)
                diversity_water_length = 0;
            var diversity_water_diameter = $('#diversity_water_diameter').val();
            if(diversity_water_check == 0 || diversity_water_diameter == '' || diversity_water_diameter == undefined)
                diversity_water_diameter = 0;

            // 采暖-配电柜
            var heating_powe_box = $('#heat_power_box_power').data("value");
            if(water_powe_box_check == 0  || heating_powe_box == undefined) heating_powe_box = 0;




            //----------------------------- 热水 -------------------------------

            // 热水-燃气燃烧机
            var water_burner_id= $('#water_burner_version').val();
            var water_burner_name= $('#water_burner_name').text();
            var water_burner_count= 1;
            if(water_burner_check == 0 || water_burner_id == undefined){
                water_burner_id = 0;
                water_burner_name = '';
                water_burner_count = 0;
            }

            // 热水-全自动软水器
            var water_hdys_id= $('#water_hyds_version').val();
            var water_hdys_name= $('#water_hdys_name').text();
            var water_hdys_count= 1;
            if(water_hdys_check == 0 || water_hdys_id == undefined){
                water_hdys_id = 0;
                water_hdys_name = '';
                water_hdys_count = 0;
            }

            // 热水-软化水箱
            var water_water_box_id = $('#water_box_version').val();
            var water_water_box_name = $('#water_box_name').text();
            var water_water_box_count = 1;
            if(water_box_check == 0 || water_water_box_id == undefined){
                water_water_box_id = 0;
                water_water_box_name = '';
                water_water_box_count = 0;
            }

            // 热水-锅炉循环泵
            var water_pipeline_pump_id =  $('#water_pipeline_version').val();
            var water_pipeline_pump_name =  $('#water_pipeline_name').text();
            var water_pipeline_pump_count =  2;
            if(water_pipeline_pump_check == 0 || water_pipeline_pump_id == undefined){
                water_pipeline_pump_id = 0;
                water_pipeline_pump_name = '';
                water_pipeline_pump_count = 0;
            }


            // 待沟通 单选
            // 热水-除污器
            var length4 = $(".water_dirt_separater_data").length;
            var water_dirt_separater_id = "";
            var water_dirt_separater_name = "";
            var water_dirt_separater_count = "";
            var water_dirt_separater_check = "";
            for(i=0;i<length4;i++){
                var thisE = $(".water_dirt_separater_data").eq(i);
                var thisIfCheck = thisE.parent().parent().find('input[name="water_dirt_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                water_dirt_separater_id = water_dirt_separater_id + '||' + thisE.find('.water_dirt_separater_id').val();
                water_dirt_separater_name = water_dirt_separater_name + '||' + thisE.find('.water_dirt_separater_name').val();
                water_dirt_separater_count = water_dirt_separater_count + '||' + thisE.find('.water_dirt_separater_count').val();
                water_dirt_separater_check = water_dirt_separater_check + '||' + thisIfCheck;
            }

            // 热水-板换
            var length5 = $(".water_board_check").length;
            var water_board_value = "";
            var water_board_name = "";
            var water_board_count = "";
            var water_board_check = "";
            for(i=0;i<length5;i++){
                var thisE = $(".water_board_check").eq(i);
                var thisIfCheck = thisE.parent().parent().find('input[name="water_board_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                if (thisIfCheck == 0){
                    water_board_count = water_board_count + '||' + "0";
                }else {
                    water_board_count = water_board_count + '||' + "1";
                }
                var water_board_value_temp = "";
                var water_exchange_Q_name = thisE.parent().parent().find('#water_exchange_Q_name').text();
                var water_exchange_Q_value = thisE.parent().parent().find('#water_exchange_Q').val();
                var water_exchange_Q_unit = thisE.parent().parent().find('#water_exchange_Q_unit').text();
                water_board_value_temp = water_board_value_temp + water_exchange_Q_name + water_exchange_Q_value + water_exchange_Q_unit + "&nbsp;";

                var water_once_sarwt_name = thisE.parent().parent().find('#water_once_sarwt_name').text();
                var water_once_sarwt_value = thisE.parent().parent().find('#water_once_sarwt').val();
                var water_once_sarwt_unit = thisE.parent().parent().find('#water_once_sarwt_unit').text();
                water_board_value_temp = water_board_value_temp + water_once_sarwt_name + water_once_sarwt_value + water_once_sarwt_unit + "&nbsp;";

                var water_twice_sarwt_name = thisE.parent().parent().find('#water_twice_sarwt_name').text();
                var water_twice_sarwt_value = thisE.parent().parent().find('#water_twice_sarwt').val();
                var water_twice_sarwt_unit = thisE.parent().parent().find('#water_twice_sarwt_unit').text();
                water_board_value_temp = water_board_value_temp + water_twice_sarwt_name + water_twice_sarwt_value + water_twice_sarwt_unit + "&nbsp;";

                var water_pressure_bearing = thisE.parent().parent().find('#water_pressure_bearing').text();
                water_board_value_temp = water_board_value_temp + water_pressure_bearing;

                water_board_value = water_board_value + '||' + water_board_value_temp;
                water_board_name = water_board_name + '||' + thisE.parent().parent().find('#water_board_name').text();
                water_board_check = water_board_check + '||' + thisIfCheck;
            }

            // 热水循环泵 待沟通 多分区
            var hotwater_pump_id =  $('input[name="hotwater_pump"]:checked ').val();
            var hotwater_pump_name =  $('#hotwater_pump_name').val();
            var hotwater_pump_count =  $('#hotwater_pump_count').val();
            if(hotwater_pump_id == undefined){
                hotwater_pump_id = 0;
                hotwater_pump_name = '';
                hotwater_pump_count = 0;
            }

            // 热水-不锈钢保温水箱 待沟通 多分区
            //var hot_water_box_id= $('#hot_water_box_id').val();
            var hot_water_box_id= $('input[name="hot_water_box_id"]:checked ').val();
            var hot_water_box_name= $('#hot_water_box_name').val();
            var hot_water_box_count= $('#hot_water_box_count').val();
            if(hot_water_box_id == undefined){
                hot_water_box_id = 0;
                hot_water_box_name = '';
                hot_water_box_count = 0;
            }

            // 热水-变频供水泵
            var length6 = $(".dynamic_water_pump_version").length;
            var hotwater_water_pump_id = "";
            var hotwater_water_pump_name = "";
            var hotwater_water_pump_count = "";
            var hotwater_water_pump_check = "";
            for(i=0;i<length6;i++){
                var thisE = $(".dynamic_water_pump_version").eq(i);
                var thisid = thisE.parent().parent().find('#dynamic_water_pump_version option:selected').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="dynamic_water_pump_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                if(thisIfCheck == 0){
                    hotwater_water_pump_count = hotwater_water_pump_count + '||' + "0";
                }else {
                    hotwater_water_pump_count = hotwater_water_pump_count + '||' + "2";
                }
                hotwater_water_pump_id    = hotwater_water_pump_id + '||' + thisid;
                hotwater_water_pump_name  = hotwater_water_pump_name + '||' + thisE.parent().parent().find('.dynamic_water_pump_name').text();
                hotwater_water_pump_check = hotwater_water_pump_check + '||' + thisIfCheck;
            }

            // 热水-钢制烟囱
            var chimney_height2 = $('#water_chimney_height').val();
            if(water_chimney_check == 0 || chimney_height2 == undefined) chimney_height2 = 0;
            var chimney_diameter2 = $('#water_chimney_diameter').val();
            if(water_chimney_check == 0 || chimney_diameter2 == undefined) chimney_diameter2 = 0;

            // 热水-水泵控制柜
            var water_pump_control = $('#water_pump_control_power').data("value");
            if(water_pump_control_check == 0 || water_pump_control == undefined) water_pump_control = 0;

            // 热水-配电柜
            var water_powe_box = $('#water_power_box').data("value");
            if(water_powe_box_check == 0 || water_powe_box == undefined) water_powe_box = 0;

            // 备注
            var remark = $('#remark').val();

            var index = layer.load(0, {shade: false});

            $.ajax({
                type        : 'POST',
                data        : {
                    history_id : <?php echo $history_id;?>,
                    heating_type : '<?php echo $heating_type;?>',
                    water_type : '<?php echo $water_type;?>',

                    heating_burner_id : heating_burner_id,
                    heating_burner_name : heating_burner_name,
                    heating_burner_count : heating_burner_count,

                    heating_hdys_id    : heating_hdys_id,
                    heating_hdys_name  : heating_hdys_name,
                    heating_hdys_count : heating_hdys_count,

                    heating_water_box_id    : heating_water_box_id,
                    heating_water_box_name  : heating_water_box_name,
                    heating_water_box_count : heating_water_box_count,

                    dirt_separater_id    : dirt_separater_id,
                    dirt_separater_name  : dirt_separater_name,
                    dirt_separater_count : dirt_separater_count,
                    dirt_separater_check:dirt_separater_check,

                    pipeline_pump_id    : pipeline_pump_id,
                    pipeline_pump_name  : pipeline_pump_name,
                    pipeline_pump_count : pipeline_pump_count,

                    heating_pump_id    : heating_pump_id,
                    heating_pump_name  : heating_pump_name,
                    heating_pump_count : heating_pump_count,
                    heating_pump_check:heating_pump_check,

                    water_pump_id    : water_pump_id,
                    water_pump_name  : water_pump_name,
                    water_pump_count : water_pump_count,
                    water_pump_check : water_pump_check,

                    board_count : board_count,
                    board_check : board_check,
                    board_value : board_value,
                    board_name : board_name,

                    chimney_height : chimney_height,
                    chimney_diameter : chimney_diameter,

                    heating_pump_control : heating_pump_control,
                    heating_pump_control_name : heating_pump_control_name,
                    heating_powe_box : heating_powe_box,

                    diversity_water_num : diversity_water_num,
                    diversity_water_length : diversity_water_length,
                    diversity_water_diameter : diversity_water_diameter,

                    water_burner_id : water_burner_id,
                    water_burner_name : water_burner_name,
                    water_burner_count : water_burner_count,

                    water_hdys_id    : water_hdys_id,
                    water_hdys_name  : water_hdys_name,
                    water_hdys_count : water_hdys_count,

                    water_water_box_id    : water_water_box_id,
                    water_water_box_name  : water_water_box_name,
                    water_water_box_count : water_water_box_count,

                    hot_water_box_id    : hot_water_box_id,
                    hot_water_box_name  : hot_water_box_name,
                    hot_water_box_count : hot_water_box_count,

                    water_board_count : water_board_count,
                    water_board_value : water_board_value,
                    water_board_name : water_board_name,
                    water_board_check:water_board_check,

                    water_dirt_separater_id    : water_dirt_separater_id,
                    water_dirt_separater_name  : water_dirt_separater_name,
                    water_dirt_separater_count : water_dirt_separater_count,
                    water_dirt_separater_check : water_dirt_separater_check,

                    water_pipeline_pump_id    : water_pipeline_pump_id,
                    water_pipeline_pump_name  : water_pipeline_pump_name,
                    water_pipeline_pump_count : water_pipeline_pump_count,

                    hotwater_pump_id    : hotwater_pump_id,
                    hotwater_pump_name  : hotwater_pump_name,
                    hotwater_pump_count : hotwater_pump_count,

                    hotwater_water_pump_id    : hotwater_water_pump_id,
                    hotwater_water_pump_name  : hotwater_water_pump_name,
                    hotwater_water_pump_count : hotwater_water_pump_count,
                    hotwater_water_pump_check : hotwater_water_pump_check,

                    chimney_height2 : chimney_height2,
                    chimney_diameter2 : chimney_diameter2,

                    water_pump_control : water_pump_control,
                    water_powe_box : water_powe_box,

                    remark : remark,
                    application : application
                },
                // burner_check:burner_check,
                // hdys_check:hdys_check,
                // water_box_check:water_box_check,
                // pipeline_pump_check:pipeline_pump_check,
                // chimney_check:chimney_check,
                // diversity_water_check:diversity_water_check,
                // heating_pump_control_check:heating_pump_control_check,
                // water_pump_control_check:water_pump_control_check,
                // powe_box_check:powe_box_check,
                // hot_water_box_check:hot_water_box_check,
                // hotwater_pump_check:hotwater_pump_check,
                dataType :    'json',
                url :         'selection_do.php?act=fuji_manual_selected',
                success :     function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            location.href = 'selection_plan_one.php?id=<?php echo $history_id;?>';
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });

    });



    function hdys_detail(thisid){
        var id = $('#heat_hyds_version').val();
        layer.open({
            type: 2,
            title: '全自动软水器详情',
            shadeClose: true,
            shade: 0.3,
            area: ['500px', '200px'],
            content: 'hdys_info.php?id='+id
        });
    }
    function water_box_detail(thisid){
        var id = $('#heat_water_box_version').val();
        layer.open({
            type: 2,
            title: '水箱详情',
            shadeClose: true,
            shade: 0.3,
            area: ['600px', '300px'],
            content: 'water_box_detail.php?id='+id
        });
    }
    function burner_detail(thisid){
        layer.open({
            type: 2,
            title: '燃烧器详情',
            shadeClose: true,
            shade: 0.3,
            area: ['600px', '300px'],
            content: 'burner_info.php?id='+thisid
        });
    }
    function pipeline_detail(thisid){
        var id = $('#heat_pipeline_version').val();
        layer.open({
            type: 2,
            title: '管道泵详情',
            shadeClose: true,
            shade: 0.3,
            area: ['800px', '350px'],
            content: 'pipeline_info.php?id='+id
        });
    }
    function syswater_pump_detail(thisid){
        var id = $('#heat_syswater_pump_version').val();
        layer.open({
            type: 2,
            title: '系统补水泵详情',
            shadeClose: true,
            shade: 0.3,
            area: ['800px', '350px'],
            content: 'syswater_pump_info.php?id='+id
        });
    }

    function dirt_separator_detail(type){
        var version_id = "";
        if(type == 0){
            version_id = "heat_dirt_separator_version";
        }
        if(type == 1){
            version_id = "water_dirt_separator_version";
        }
        var id = $("#" + version_id).val();
        layer.open({
            type: 2,
            title: '除污器详情',
            shadeClose: true,
            shade: 0.3,
            area: ['600px', '200px'],
            content: 'dirt_separator_detail.php?id='+id
        });
    }
    function heat_exchanger_detail(thisid){
        layer.open({
            type: 2,
            title: '换热器详情',
            shadeClose: true,
            shade: 0.3,
            area: ['600px', '300px'],
            content: 'heat_exchanger_detail.php?id='+thisid
        });
    }
</script>

<script type="text/javascript">

    // 燃气有问题，待修复
    // 获取采暖燃气燃烧器的型号
    $('#heat_burner_vender').change(function () {

        var listDom = $("#heat_burner_vender").find(".GLXXmain_2").find("#heat_burner_version");
        listDom.html("<option value='0'>暂无可用型号</option>");
        var heat_burner_vender = $("#heat_burner_vender").val();
        var is_lownitrogen = "<?php echo $history_info['is_lownitrogen'] ?>";

        $.ajax({
            type: 'POST',
            data: {
                heat_burner_vender: heat_burner_vender,
                is_lownitrogen:is_lownitrogen
            },
            dataType: 'json',
            url: 'selection_do.php?act=get_burner_vender_list',
            success: function (data) {
                var code = data.code;
                var msg = data.msg;
                var heat_burner_vender_list = data.data;
                switch (code) {
                    case 1:
                        var html = "";
                        for (var i = 0; i < heat_burner_vender_list.length; i++) {
                            var name = heat_burner_vender_list[i].burner_version;
                            var value = heat_burner_vender_list[i].burner_id;

                            html += "<option value='" + value + "'>" + name + "</option>";
                        }

                        listDom.html(html);
                        break;

                    default:
                        break;
                }
            }
        });
    });

    // 获取热水燃气燃烧器的型号
    $('#water_burner_vender').change(function () {


        var listDom = $("#water_burner_vender").find(".GLXXmain_2").find("#water_burner_version");
        listDom.html("<option value='0'>暂无可用型号</option>");
        var heat_burner_vender = $("#water_burner_vender").val();
        var is_lownitrogen = <?php echo $history_info['is_lownitrogen'] ?>;

        $.ajax({
            type: 'POST',
            data: {
                heat_burner_vender: heat_burner_vender,
                is_lownitrogen:is_lownitrogen
            },
            dataType: 'json',
            url: 'selection_do.php?act=get_burner_vender_list',
            success: function (data) {
                var code = data.code;
                var msg = data.msg;
                var heat_burner_vender_list = data.data;
                switch (code) {
                    case 1:
                        var html = "";
                        for (var i = 0; i < heat_burner_vender_list.length; i++) {
                            var name = heat_burner_vender_list[i].burner_version;
                            var value = heat_burner_vender_list[i].burner_id;

                            html += "<option value='" + value + "'>" + name + "</option>";
                        }

                        listDom.html(html);
                        break;

                    default:
                        break;
                }
            }
        });
    });


    // 采暖-锅炉循环泵/一次侧循环泵
    $(".heat_pipeline_pump_vender").change(function () {
        var pipeline_vender = $(this).val();
        var versionDom = $(this).parent().parent().find(".heat_pipeline_pump_version");
        getPipeVersionList(pipeline_vender,versionDom);
    });

    // 采暖循环泵
    $(".heat_pipeline_vender").change(function(){
        var pipeline_vender = $(this).val();
        var versionDom = $(this).parent().parent().find(".heat_pipeline_version");
        getPipeVersionList(pipeline_vender,versionDom);
    });

    // 热水循环泵
    $(".water_pipeline_vender").change(function(){
        var pipeline_vender = $(this).val();
        var versionDom = $(this).parent().parent().find(".water_pipeline_version");
        getPipeVersionList(pipeline_vender,versionDom);
    });

    // 热水循环泵多分区情况
    $(".hotwater_pump_vender").change(function(){
        var pipeline_vender = $(this).val();
        var versionDom = $(this).parent().parent().find(".hotwater_pump_version");
        getPipeVersionList(pipeline_vender,versionDom);
    });

    // 变频换水泵多分区情况
    $(".dynamic_water_pump_vender").change(function(){
        var pipeline_vender = $(this).val();
        var versionDom = $(this).parent().parent().find(".dynamic_water_pump_version");
        getPipeVersionList(pipeline_vender,versionDom);
    });

    // 管道泵多分区情况
    function getPipeVersionList(pipeline_vender,versionDom) {

        versionDom.html("<option value='0'>暂无可用型号</option>");

        $.ajax({
            type: 'POST',
            data: {
                pipeline_vender: pipeline_vender
            },
            dataType: 'json',
            url: 'selection_do.php?act=get_pipeline_version_list',
            success: function (data) {
                var code = data.code;
                var msg = data.msg;
                var heat_pipeline_vender_list = data.data;
                switch (code) {
                    case 1:
                        var html = "";
                        for (var i = 0; i < heat_pipeline_vender_list.length; i++) {
                            var name = heat_pipeline_vender_list[i].pipeline_version;
                            var value = heat_pipeline_vender_list[i].pipeline_id;

                            html += "<option value='" + value + "'>" + name + "</option>";
                        }

                        versionDom.html(html);
                        break;

                    default:
                        break;
                }
            }
        });
    }


    // 系统补水泵多分区情况
    $(".heat_syswater_pump_vender").change(function(){
        var $system_water_pump_vender = $(this).val();
        var versionDom = $(this).parent().parent().find(".heat_syswater_pump_version");
        getSysWaterPumpVersionList($system_water_pump_vender,versionDom);
    });

    // 补水泵多分区情况
    function getSysWaterPumpVersionList(system_water_pump_vender,versionDom) {

        versionDom.html("<option value='0'>暂无可用型号</option>");

        $.ajax({
            type: 'POST',
            data: {
                system_water_pump_vender: system_water_pump_vender
            },
            dataType: 'json',
            url: 'selection_do.php?act=get_syswater_pump_version_list',
            success: function (data) {
                var code = data.code;
                var msg = data.msg;
                var $system_water_pump_vender_list = data.data;
                switch (code) {
                    case 1:
                        var html = "";
                        for (var i = 0; i < $system_water_pump_vender_list.length; i++) {
                            var name = $system_water_pump_vender_list[i].pump_version;
                            var value = $system_water_pump_vender_list[i].pump_id;

                            html += "<option value='" + value + "'>" + name + "</option>";
                        }

                        versionDom.html(html);
                        break;

                    default:
                        break;
                }
            }
        });
    }

</script>
</body>
</html>