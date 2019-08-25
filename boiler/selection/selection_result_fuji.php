<?php
/**
 * 选型结果-辅机 selection_result_fuji.php
 *
 * @version       v0.01
 * @create time   2018/08/10
 * @update time   2018/08/10
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";

$id = isset($_GET['id'])?safeCheck($_GET['id']):0;
$number = isset($_GET['number'])?safeCheck($_GET['number']):0;

//选型入口
//$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;

$info = Selection_history::getInfoById($id);
//var_dump($info["total_exchange_q"]);
if(empty($info)){
    echo '非法操作！';
    die();
}

/*if ($info['user'] != $USERId) {
    echo '没有权限操作！';
    die();
}*/
$guoluinfo = Guolu_attr::getInfoById($info['guolu_id']);

$guoluarr = array(
    'guolu_id' => $info['guolu_id'],
    'guolu_ratedpower' => $guoluinfo['ratedpower'],
    'guolu_iowater_interface' => $guoluinfo['iowater_interface'],
    'guolu_hot_flow' => $guoluinfo['hot_flow'],
    'guolu_outwater_t' => $guoluinfo['outwater_t'],
    'guolu_inwater_t' => $guoluinfo['inwater_t'],
    'count' => $info['guolu_num']
);
$floor  = array();
$floor_height =  array();
$heating_type_arr = array();
$area_heating_arr = array();
$area_hotwater_arr = array();
$highflag = 0;
if($info['application'] == 0 || $info['application'] == 2) {
    $heatinglist = Selection_heating_attr::getInfoByHistoryId($id);
    if($heatinglist){
        foreach ($heatinglist as $thisheat){
            $floorArr = array();
            $floorArr['0'] = $thisheat['floor_low'];
            $floorArr['1'] = $thisheat['floor_high'];
            $floor[] = $floorArr;
            $floor_height[] = $thisheat['floor_height'];
            if($thisheat['floor_high'] > 15 && ($info['heating_type'] == 3 || $info['heating_type'] == 4)){
                $highflag = 1;
            }
            $heating_type_arr[] = $thisheat['type'];
            $area_heating_arr[] = $thisheat['type'];
        }
    }
}

if($info['application'] == 1 || $info['application'] == 2) {
    $hotwaterlist = Selection_hotwater_attr::getParamByHistoryId($id);
    if($hotwaterlist){
        foreach ($hotwaterlist as $thisparam){
            $area_hotwater_arr[] = $thisparam['hotwater_param_id'];
        }
        if($area_hotwater_arr){
            foreach ($area_hotwater_arr as $area_hotwater_arr_value){
                $attr_s=Selection_hotwater_attr::getInfoByHistoryId($id,$area_hotwater_arr_value);
                $floorArr = array();
                $floorArr['0'] = $attr_s[0]['floor_low'];
                $floorArr['1'] = $attr_s[0]['floor_high'];
                $floor_hw[] = $floorArr;
                $floor_height_hw[] = $attr_s[0]['floor_height'];
//                print_r($attr_s);
            }
        }
    }
}

$dataArr = array(
    'guolu' => $guoluarr,
//    'floor' => $floor,
//    'floor_height' => $floor_height,
    'total_exchange_Q' => unserialize($info['total_exchange_q']),
    'is_condensate' => $info['is_condensate'],
    'is_lownitrogen' => $info['is_lownitrogen'],
    'location' => $info['guolu_position'],
    'guolu_use' => $info['application'],
    'heating_type' => $heating_type_arr
);
if($info['application'] == 0 || $info['application'] == 2){
    $dataArr['floor'] = $floor;
    $dataArr['floor_height'] = $floor_height;
    $dataArr['application'] = $info['heating_type'];
    $dataArr['guolu_use'] = 0;
    $dataArr['area'] = $area_heating_arr;
}else{
    $dataArr['floor_hw'] = $floor_hw;
    $dataArr['floor_height_hw'] = $floor_height_hw;
    $dataArr['application'] = $info['water_type'];
    $dataArr['area'] = $area_hotwater_arr;
    $dataArr['guolu_use'] = 1;
}
//print_r($floor);
//print_r($floor_height);
$data = 'jsonstr='.json_encode($dataArr);
$curl     = $HTTP_PATH.'api/selection/select_fuji.php';
$rs       = json_decode(Curl::post($curl,$data),true);

//echo Curl::post($curl,$data);
//print_r($floor_hw);
$var_url  = $HTTP_PATH.'api/selection/fuji_out_var.html';
if($info['application'] == 2){
    $dataArr2 = array(
        'guolu' => $guoluarr,
        'floor' => $floor,
        'floor_height' => $floor_height,
        'floor_hw' => $floor_hw,
        'floor_height_hw' => $floor_height_hw,
        'total_exchange_Q' => unserialize($info['total_exchange_q']),
        'is_condensate' => $info['is_condensate'],
        'is_lownitrogen' => $info['is_lownitrogen'],
        'heating_type' => array(),
        'area' => $area_hotwater_arr,
        'location' => $info['guolu_position'],
        'guolu_use' => 1,
        'application' => $info['water_type']
    );
    //var_dump($dataArr2);
    $data2 = 'jsonstr='.json_encode($dataArr2);
    $rs2 = json_decode(Curl::post($curl,$data2),true);
    //echo Curl::post($curl,$data2);
}
$heating = "";
$hotwater = "";
$heating_type = 0;
$hotwater_type = 0;
if($info['application'] == 0){
    $heating = $rs;
    $heating_type = 1;
}elseif ($info['application'] == 1){
    $hotwater = $rs;
    $hotwater_type = 1;
}elseif ($info['application'] == 2){
    $heating = $rs;
    $hotwater = $rs2;

    $heating_type = 1;
    $hotwater_type = 1;
}
file_put_contents("heating",var_export($heating,true));


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉选型</title>
    <link rel="stylesheet" href="css/main.css?v=8">
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
    <style>
        .GLXXmain_3{
            float:left
        }

    </style>

    <style>
        #step .step-wrap {
            width: 100%;
            position: relative;
        }
        #step .step-wrap .step-list{
            display: inline-block;
            width: 64px;
            text-align: center;
        }
        #step .step-wrap .step-list .step-num{
            display: inline-block;
            position: relative;
            width: 48px;
            height: 48px;
            background: rgba(4,166,254,0.2);
            border-radius: 50%;
        }
        #step .step-wrap .step-list .nums{
            margin: auto;
            width: 32px;
            height: 32px;
            background: #FFC80A;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            color: #fff;
            line-height: 32px;
        }
        #step .step-wrap .step-list .step-num .num-bg{
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 32px;
            height: 32px;
            background: #04A6FE;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            color: #fff;
            line-height: 32px;
        }
        #step .step-wrap .step-list .step-name{
            font-size: 16px;
            color: #04A6FE;
        }
        #step .step-wrap .step-list .step-names{
            font-size: 16px;
            color: #293144;
            margin-top: 8px;
            display: block;
        }
        #step .step-wrap .step-line{
            display: inline-block;
            width: 290px;
            height: 2px;
            background: #04A6FE;
            margin: 0 -20px 42px -20px;
        }
        #step .step-wrap .step-lines{
            display: inline-block;
            width: 290px;
            height: 2px;
            background: #FFC80A;
            margin: 0 -20px 42px -20px;
        }

    </style>
    <style type="text/css">
    .XXRmain_11{
        width: 400px;
    }
    </style>
</head>
<body class="body_2">
<?php include('top.inc.php');?>
<div class="manageHRWJCont_middle_middle" align="center">

    <div id="step" style="margin-top: 30px">
        <div class="step-wrap">
            <div class="step-list">
                <div class="step-num">
                    <a href="selection.php?id=<?php echo $id;?>"><div class="num-bg">1</div></a>
                </div>
                <span class="step-name">选型</span>
            </div>
            <div class="step-line"></div>
            <div class="step-list">
                <?php if($id){
                    $history_info=Selection_history::getInfoById($id);
                    if($history_info['status']==Selection_history::HISTORY_Plan){?>
                        <a href="selection_plan_one.php?id=<?php echo $id;?>">  <div class="nums">2</div></a>
                        <?php
                    }else {
                        ?>
                        <div class="nums">2</div>
                        <?php
                    }
                }?>
                <span class="step-names">报价</span>
            </div>
            <div class="step-lines"></div>
            <div class="step-list">
                <?php if($id){
                    $history_info=Selection_history::getInfoById($id);
                    if($history_info['status']==Selection_history::HISTORY_Plan){?>
                        <a href="selection_plan_two.php?id=<?php echo $id;?>">  <div class="nums">3</div></a>
                        <?php
                    }else {
                        ?>
                        <div class="nums">3</div>
                        <?php
                    }
                }?>
                <span class="step-names">方案</span>
            </div>
        </div>
    </div>
</div>
<!--<div class="guolumain">-->
<!--    <div class="guolumain_1">当前位置：锅炉选型 ><span>选型结果</span></div><div class="clear"></div>-->
<!--</div>-->
<div class="XXresult">
    <div id="next" class="XXRmain">
        <?php
        if($guoluinfo){
            ?>
            <div id="guoluDiv" style="display: block">
                <div class="XXRmain_1">锅炉</div>
                <table class="XXRmain_7">
                    <tr class="GLDetils9_fir">
                        <td width="20%">锅炉名称</td>
                        <td width="10%">数量</td>
                        <td width="10%">计算参数</td>
                        <td width="20%">厂家</td>
                        <td width="20%">规格型号</td>
                        <td width="10%">查看详情</td>
                        <td width="20%">备注</td>
                    </tr>
                        <tr>
                            <td>锅炉</td>
                            <td><?php echo $info['guolu_num'] ? $info['guolu_num'] : 1; ?>台</td>
                            <td>热负荷=<?php echo $info['guolu_attr']?>kw</td>
                            <td><?php echo  Dict::getInfoById($guoluinfo['vender'])['name'] ?></td>
                            <td><?php echo $guoluinfo['version']  ?></td>
                            <td><a href="#" id="info">查看详情 </a></td>
                            <td><input type="text" id="guolu_context" value="<?php  echo $info['guolu_context']?>"></td>
                        </tr>
                </table>
            </div>
            <?php
            }
        if($heating){

            ?>
            <div id="heatingDiv" style="display: <?php if($info['application'] == 0 || $info['application'] == 2 ) echo 'block'; else echo 'none'; ?>">
                <div class="XXRmain_1">采暖辅机</div>
                <table class="XXRmain_7">
                    <tr class="GLDetils9_fir">
                        <td width="20%">设备名称</td>
                        <td width="10%">数量</td>
                        <td width="10%">计算参数</td>
                        <td width="10%">厂家</td>
                        <td width="20%">规格型号</td>
                        <td width="10%">查看详情</td>
                        <td width="5%">备注</td>
                        <td width="20%">是否添加到方案里</td>
                    </tr>
                    <!--燃烧器-->
                    <?php
                    if($guoluinfo['vender'] != 43) {
                        $burner_attr=Selection_fuji::getInfoByName($id,"燃气燃烧器",0);
                        ?>
                        <tr>
                            <td>燃气燃烧器</td>
                            <td><?php echo $heating['burner'] ? $heating['burner']['count'] : 1; ?>台</td>
                            <td>&nbsp;</td>
                            <td colspan="2">
                                <div class="XXRmain_8">
                                    <div class="XXRmain_10">
                                        <input type="hidden" id="heating_burner_id" value="<?php echo $heating['burner'] ? $heating['burner']['id'] : 0; ?>">
                                        <input type="hidden" id="heating_burner_name" value="<?php echo $heating['burner'] ? $heating['burner']['name'] : ''; ?>">
                                        <input type="hidden" id="heating_burner_count" value="<?php echo $heating['burner'] ? $heating['burner']['count'] : 0; ?>">
                                        <input type="hidden" id="heating_burner_power" value="<?php echo $heating['burner'] ? $heating['burner']['power'] : 0; ?>">
                                        <?php if ($heating['burner']) {
                                            $burnervendername = Dict::getInfoById($heating['burner']['vender']);
                                            echo $burnervendername['name'];
                                        } else {
                                            echo "没有找到合适的燃气燃烧器";
                                        } ?>
                                    </div>
                                    <div class="XXRmain_11"><?php echo $heating['burner'] ? $heating['burner']['version'] : ''; ?></div>
                                </div>
                            </td>
                            <td><a href="javascript:void(0);" onclick="burner_detail(<?php echo $heating['burner'] ? $heating['burner']['id'] : 0; ?>)">详情</a></td>
                            <td><input type="text" id="burner_context" size="5" value="<?php echo $burner_attr ? $burner_attr[0]['context'] : ''; ?>"></td>
                            <td><input type="checkbox" id="burner_check_btn" name="check_btn" value="burner_check_btn" <?php if($burner_attr and $burner_attr[0]['add_type']) echo "checked";?>></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <!--全自动软水机-->
                    <?php 
                    $hdys_attr=Selection_fuji::getInfoByName($id,"全自动软水机",0);
                    $hdys_id=0;
                    if($hdys_attr){
                        $hdys_id=$hdys_attr[0]['value'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $heating['hdys'] ? $heating['hdys']['name'] : ''; ?></td>
                        <td><?php echo $heating['hdys']?$heating['hdys']['count']:1; ?>台</td>
                        <td>额定出水量=<?php echo $heating['hdys'] ? $heating['hdys']['outwater'] : ''; ?>m³/L</td>
                        <td colspan="3">
                            <input type="hidden" id="heating_hdys_name" value="<?php echo $heating['hdys'] ? $heating['hdys']['name'] : ''; ?>">
                            <input type="hidden" id="heating_hdys_count" value="<?php echo $heating['hdys'] ? $heating['hdys']['count'] : 0; ?>">
                            <input type="hidden" id="heating_hdys_parameter" value="<?php echo $heating['hdys'] ? $heating['hdys']['outwater'] : ''; ?>">
                            <?php
                            $hdysnum = count($heating['hdys']) - 3;
                            if($hdysnum > 0){
                                $i = 0;
                                foreach ($heating['hdys'] as $hdysinfo){
                                    if($i == $hdysnum) break;
                                    $hdysvendername = Dict::getInfoById($hdysinfo['vender']);
                                    echo '
                                    <div class="XXRmain_8">
                                        <input type="radio" class="XXRmain_9" name="heating_hdys" value="'.$hdysinfo['id'].'" ';if($hdys_id==$hdysinfo['id']) echo 'checked'; echo '>
                                        <div class="XXRmain_10">'.$hdysvendername['name'].'</div>
                                        <div class="XXRmain_11">'.$hdysinfo['version'].'</div>
                                        <a href="javascript:void(0);" onclick="hdys_detail('.$hdysinfo['id'].')">详情</a>
                                    </div>
                                    ';
                                    $i++;
                                }
                            }else{
                                echo "没有找到合适的全自动软水机";
                            }
                            ?>
                        </td>
                        <td><input type="text" id="hdys_context" size="5" value="<?php echo $hdys_attr ? $hdys_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="hdys_check_btn" name="check_btn" value="hdys_check_btn" <?php if($hdys_attr and $hdys_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <!--膨胀水箱-->
                    <?php
                    $water_box_attr=Selection_fuji::getInfoByName($id,"膨胀水箱",0);
                    $water_box_id=0;
                    if($water_box_attr){
                        $water_box_id=$water_box_attr[0]['value'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $heating['water_box'] ? $heating['water_box']['name'] : ''; ?></td>
                        <td><?php echo $heating['water_box']?$heating['water_box']['count']:1; ?>台</td>
                        <td>公称容积=<?php echo $heating['water_box'] ? $heating['water_box']['capacity'] : ''; ?>m³</td>
                        <td colspan="3">
                            <input type="hidden" id="heating_water_box_name" value="<?php echo $heating['water_box'] ? $heating['water_box']['name'] : ''; ?>">
                            <input type="hidden" id="heating_water_box_count" value="<?php echo $heating['water_box'] ? $heating['water_box']['count'] : 0; ?>">
                            <input type="hidden" id="heating_water_box_parameter" value="<?php echo $heating['water_box'] ? $heating['water_box']['capacity'] : ''; ?>">
                            <?php
                            $waterboxnum = count($heating['water_box']) - 3;
                            if($waterboxnum > 0){
                                $i = 0;
                                foreach ($heating['water_box'] as $wboxinfo){
                                    if($i == $waterboxnum) break;
                                    echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9" name="heating_water_box" value="'.$wboxinfo['id'].'" ';if($water_box_id==$wboxinfo['id']) echo 'checked'; echo '>
                                                <div class="XXRmain_10">现场制作</div>
                                                <div class="XXRmain_11">'.$wboxinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="water_box_detail('.$wboxinfo['id'].')">详情</a>
                                            </div>
                                            ';
                                    $i++;
                                }
                            }else{
                                echo "没有找到合适的水箱";
                            }
                            ?>
                        </td>
                        <td><input type="text" id="water_box_context" size="5" value="<?php echo $water_box_attr ? $water_box_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="water_box_check_btn"  name="check_btn" value="water_box_check_btn" <?php if($water_box_attr and $water_box_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <!--锅炉循环泵-->
                    <?php
                    if($info['heating_type'] != 4){
                        if(!empty($heating['pipeline_pump']['name'])){
                        $pipeline_pump_attr=Selection_fuji::getInfoByName($id,"锅炉循环泵",0);

                        $pipelinenum = count($heating['pipeline_pump']) - 4;

                        if($info['heating_type'] == 1){
                            $showname = '采暖循环泵';
                            $pipeline_pump_attr=Selection_fuji::getInfoByName($id,"采暖循环泵",0);
                        }elseif($info['heating_type'] == 2){
                            $showname = '锅炉循环泵';
                            $pipeline_pump_attr=Selection_fuji::getInfoByName($id,"锅炉循环泵",0);
                        }elseif($info['heating_type'] == 3){
                            $showname = '一次侧循环泵';
                            $pipeline_pump_attr=Selection_fuji::getInfoByName($id,"一次侧循环泵",0);
                        }
                        ?>
                        <tr>
                            <td>
                                <?php echo $heating['pipeline_pump']['name'];?>
                                <p style="line-height: 5px;">
                                    <input type="hidden" id="pipeline_pump_count" value="<?php echo $heating['pipeline_pump']['count'];?>">
                                    <input type="hidden" id="pipeline_pump_name" value="<?php echo $heating['pipeline_pump']['name'];?>">
                                    <input type="hidden" id="pipeline_pump_flow" value="<?php echo $heating['pipeline_pump']['flow'];?>">
                                    <input type="hidden" id="pipeline_pump_lift" value="<?php echo $heating['pipeline_pump']['lift'];?>">
                                    <input type="hidden" class="pipeline_pump_flow" value="<?php echo $heating['pipeline_pump']['flow'];?>">
                                    <input type="hidden" class="pipeline_pump_lift" value="<?php echo $heating['pipeline_pump']['lift'];?>">
                                    <a href="javascript:void(0);" class="pipeline_pump_edit" data-radioname="pipeline_pump" style="color: #04A6FE;">修改扬程</a></p>
                            </td>
                            <td><?php echo $heating['pipeline_pump']['count']?$heating['pipeline_pump']['count']:2; ?>台</td>
                            <td>流量=<?php echo $heating['pipeline_pump']['flow'];?>m³/h </br> 扬程=<?php echo $heating['pipeline_pump']['lift'];?>m</td>
                            <td colspan="3">
                                <?php
                                if($pipeline_pump_attr){

                                    $pipelineinfo=Pipeline_attr::getInfoById($pipeline_pump_attr[0]['value']);
                                    $thispv = Dict::getInfoById($pipelineinfo['vender']);

                                    echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 power_compute" name="pipeline_pump" value="'.$pipeline_pump_attr[0]['value'].'"';echo "checked";echo'>
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipeline_pump_attr[0]['value'].')">详情</a>
                                            </div>
                                            ';
                                }else{
                                if($pipelinenum > 0){
                                    $i = 0;
                                    //$pump_control_power += $heating['pipeline_pump'][0]['motorpower'] * 2;
                                    foreach ($heating['pipeline_pump'] as $pipelineinfo){
                                        if($i == $pipelinenum) break;
                                        $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                        echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 power_compute" name="pipeline_pump" value="'.$pipelineinfo['id'].'">
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                            </div>
                                            ';
                                        $i++;
                                    }
                                }else{
                                    echo "没有找到合适的循环泵";
                                }}
                                ?>

                            </td>
                            <td><input type="text" id="pipeline_pump_context" size="5" value="<?php echo $pipeline_pump_attr ? $pipeline_pump_attr[0]['context'] : ''; ?>"></td>
                            <td><input type="checkbox" id="pipeline_pump_check_btn"  name="check_btn" value="pipeline_pump_check_btn" <?php if($pipeline_pump_attr and $pipeline_pump_attr[0]['add_type']) echo "checked";?>></td>
                        </tr>
                        <?php
                    }
                    }
                    //采暖循环泵数据
                    if($heating['heating_pump'][0]){
                        $j = 0;
                        foreach ($heating['heating_pump'] as $heating_pump){
                            $heating_pump_attr=Selection_fuji::getInfoByName($id,"分区".($j+1)."采暖循环泵",0);

                            $heating_pump_data="";
                            $heating_pump_add_type=0;
                            if($heating_pump_attr){
                                $heating_pump_data=$heating_pump_attr[0]['context'];
                                $heating_pump_add_type=$heating_pump_attr[0]['add_type'];
                            }
                            $pumpnum = count($heating_pump) - 4;
                            echo '
                                <tr>
                                    <td>
                                        '.$heating_pump['name'].'
                                        <p  class="heating_pump_data" id="fenqu_pipeline'.$j.'" data-fenqu="'.$j.'" data-pumpnum="'.$pumpnum.'">
                                            <input type="hidden" class="heating_pump_count" value="'.$heating_pump['count'].'">
                                            <input type="hidden" class="heating_pump_name" value="'.$heating_pump['name'].'">
                                            <input type="hidden" class="heating_pump_flow" value="'.$heating_pump['flow'].'">
                                            <input type="hidden" class="heating_pump_lift" value="'.$heating_pump['lift'].'">
                                            <a href="javascript:void(0);" class="pipeline_pump_edit" data-radioname="heating_pump" style="color: #04A6FE;">修改扬程</a>
                                        </p>
                                    </td>
                                    <td>'.$heating_pump['count'].'台</td>
                                    <td>流量='.$heating_pump['flow'].'m³/h </br> 扬程='.$heating_pump['lift'].'m</td>
                                    <td colspan="3">
                            ';
                            if($heating_pump_attr){
                                $pipelineinfo=Pipeline_attr::getInfoById($heating_pump_attr[0]['value']);
                                $thispv['name']='';
                                if($pipelineinfo){
                                    $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 power_compute" name="heating_pump'.$j.'" value="'.$pipelineinfo['id'].'" ';echo "checked";echo'>
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                $i++;
                            }else{
                                    if($pumpnum > 0){
                                        $i = 0;
                                        foreach ($heating_pump as $pipelineinfo){
                                            if($i == $pumpnum) break;
                                            $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                            echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 power_compute" name="heating_pump'.$j.'" value="'.$pipelineinfo['id'].'">
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                            $i++;
                                        }
                                    }else{
                                        echo "没有找到合适的采暖循环泵";
                                    }
                                }}else{
                                if($pumpnum > 0){
                                    $i = 0;
                                    foreach ($heating_pump as $pipelineinfo){
                                        if($i == $pumpnum) break;
                                        $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                        echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 power_compute" name="heating_pump'.$j.'" value="'.$pipelineinfo['id'].'">
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                        $i++;
                                    }
                                }else{
                                    echo "没有找到合适的采暖循环泵";
                                }
                            }

                            echo '</td>
                                <td><input type="text" id="heating_pump_context'.($j).'" size="5" value="'.$heating_pump_data.'"></td>
                                 <td><input type="checkbox"  id="heating_pump_check_btn" name="heating_pump_check" value="heating_pump_check_btn"';  if($heating_pump_add_type) echo 'checked'; echo'></td>
                            </tr>';
                            $j ++ ;
                        }
                    }
                    //补水泵数据
                    if($heating['water_pump'][0]){
                        $j = 0;

                        foreach ($heating['water_pump'] as $water_pump){
                            $water_pump_attr=Selection_fuji::getInfoByName($id,"分区".($j+1)."补水泵",0);
                            $water_pump_data="";
                            $water_pump_add_type=0;

                            if($water_pump_attr){
                                $water_pump_data=$water_pump_attr[0]['context'];
                                $water_pump_add_type=$water_pump_attr[0]['add_type'];
                            }

                            $pumpnum = count($water_pump) - 4;
                            echo '
                                <tr>
                                    <td>
                                        '.$water_pump['name'].'
                                        <p style="line-height: 5px;"  class="water_pump_data" id="fenqu_syspump'.$j.'" data-fenqu="'.$j.'" data-pumpnum="'.$pumpnum.'">
                                            <input type="hidden" class="water_pump_count" value="'.$water_pump['count'].'">
                                            <input type="hidden" class="water_pump_name" value="'.$water_pump['name'].'">
                                            <input type="hidden" class="water_pump_flow" value="'.$water_pump['flow'].'">
                                            <input type="hidden" class="water_pump_lift" value="'.$water_pump['lift'].'">
                                            <a href="javascript:void(0);" class="water_pump_edit" data-radioname="water_pump" style="color: #04A6FE;">修改扬程</a>
                                        </p>
                                    </td>
                                    <td>'.$water_pump['count'].'台</td>
                                    <td>流量='.$water_pump['flow'].'m³/h </br> 扬程='.$water_pump['lift'].'m</td>
                                    <td colspan="3">
                            ';
                           if($water_pump_attr){
                               $pipelineinfo=Pipeline_attr::getInfoById($water_pump_attr[0]['value']);
                               $thispv['name']='';
                               if($pipelineinfo){
                                   $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                   echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 power_compute" name="water_pump'.$j.'" value="'.$pipelineinfo['id'].'"';echo "checked";echo'>
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                   $i++;
                               }
                            else{ if($pumpnum > 0){
                                   $i = 0;
                                   foreach ($water_pump as $pipelineinfo){
                                       if($i == $pumpnum) break;
                                       $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                       echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 power_compute" name="water_pump'.$j.'" value="'.$pipelineinfo['id'].'">
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                       $i++;
                                   }
                               }else{
                                   echo "没有找到合适的补水泵";
                               }
                               }
                           }else{
                               if($pumpnum > 0){
                                   $i = 0;
                                   foreach ($water_pump as $pipelineinfo){
                                       if($i == $pumpnum) break;
                                       $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                       echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 power_compute" name="water_pump'.$j.'" value="'.$pipelineinfo['id'].'">
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                       $i++;
                                   }
                               }else{
                                   echo "没有找到合适的补水泵";
                               }
                           }
                            echo '</td>
                                  <td><input type="text" id="water_pump_context'.$j.'" size="5" value="'.$water_pump_data.'"></td>
                                 <td><input type="checkbox" id="water_pump_check_btn" name="water_pump_check" value="water_pump_check_btn" ';  if($water_pump_add_type)  echo 'checked'; echo'></td>
                            </tr>';
                            $j ++;
                        }
                    }
                    //采暖板换数据
                    if($heating['board']){
                        $a = 0;
                        foreach ($heating['board'] as $board){
                            $board_attr=Selection_fuji::getInfoByName($id,"分区".($a+1)."板换",0);
                            $board_data="";
                            $board_add_type=0;
                            if($board_attr){
                                $board_data=$board_attr[0]['context'];
                                $board_add_type=$board_attr[0]['add_type'];
                            }
                            if($board){
                                echo '
                                    <tr>
                                        <td>'.$board['name'].'</td>
                                        <td>'.$board['count'].'台</td>
                                        <td>&nbsp;</td>
                                        <td colspan="3">
                                            <div class="XXRmain_8">
                                                <div class="XXRmain_10">&nbsp;</div>
                                                <div class="XXRmain_11 board_data" style="line-height: 50px;">
                                                    <input type="hidden" class="board_name" value="'.$board['name'].'">
                                                    <input type="hidden" class="board_count" value="'.$board['count'].'">
                                                    <span class="board_value fenqu_board'.$a.'">
                                                        一次侧供回水温度'.$board['once_sarwt'].'
                                                        &nbsp;二次侧供回水温度'.$board['twice_sarwt'].'<br>
                                                        换热量'.$board['exchange_Q'].'kw
                                                        承压1.6MPa</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><input type="text" id="board_data_context'.($a).'" size="5" value="'.$board_data.'"></td>
                                        <td><input type="checkbox" id="board_data_check_btn" name="board_data_check" value="board_data_check_btn" ';  if($board_add_type)  echo 'checked'; echo'></td>
                                    </tr>';
                                $a ++;
                            }
                        }

                    }
                    //除污器数据
                    if($heating['dirt_separater']){
                        $j=0;
                        foreach ($heating['dirt_separater'] as $dirt_separater){
                            $dirt_separater_attr=Selection_fuji::getInfoByName($id,$dirt_separater['name'],0);

                            $dirt_separater_data="";
                            $dirt_separater_add_type=0;
                            if($dirt_separater_attr){
                                $dirt_separater_data=$dirt_separater_attr[0]['context'];
                                $dirt_separater_add_type=$dirt_separater_attr[0]['add_type'];
                            }
                            if($dirt_separater){
                                if(empty($dirt_separater['id']))
                                    $dirt_separater['id']=0;
                                if(empty($dirt_separater['diameter']))
                                    $dirt_separater['diameter']=0;
                                echo '
                                    <tr>
                                        <td>'.$dirt_separater['name'].'
                                                <div class="dirt_separater_data">
                                                    <input type="hidden" class="dirt_separater_id" value="'.$dirt_separater['id'].'">
                                                    <input type="hidden" class="dirt_separater_name" value="'.$dirt_separater['name'].'">
                                                    <input type="hidden" class="dirt_separater_count" value="'.$dirt_separater['count'].'">
                                                     <input type="hidden" id="dirt_separater_parameter" class="dirt_separater_parameter" value="'.$dirt_separater['DN'].'">
                                                </div>
                                        </td>
                                        <td>'.$dirt_separater['count'].'台</td>
                                        <td>DN'.$dirt_separater['DN'].'</td>
                                        <td colspan="3">
                                            <div class="XXRmain_8">
                                                <div class="XXRmain_10">'.($dirt_separater['id']?"&nbsp;":"没有找到合适的除污器").'</div>
                                                <div class="XXRmain_11">DN'.$dirt_separater['diameter'].'&nbsp;PN1.6</div>
                                                <a href="javascript:void(0);" onclick="dirt_separator_detail('.$dirt_separater['id'].')">详情</a>
                                            </div>
                                        </td>
                                         <td><input type="text" id="dirt_separater_context'.($j).'" size="5" value="'.$dirt_separater_data.'"></td>
                                        <td><input type="checkbox" id="dirt_separater_check_btn" name="dirt_separater_check" value="dirt_separater_check_btn" ';  if($dirt_separater_add_type) echo 'checked'; echo'></td>
                                    </tr>
                                ';
                            }
                            $j++;
                        }

                    }
                    //if($info['heating_type'] != 4){
                    ?>
                    <!--采暖水泵控制柜-->
                    <?php
                    $pump_control_attr=Selection_fuji::getInfoByName($id,"水泵控制柜",0);
                    ?>
                    <tr>
                        <td>水泵控制柜</td>
                        <td>1台</td>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <div class="XXRmain_8">
                                <div class="XXRmain_10">&nbsp;</div>
                                <?php if($pump_control_attr){?>
                                <div class="XXRmain_11"><span id="heating_pump_control"><?php echo $pump_control_attr[0]['value'];?></span></div>
                                <?php }else{?>
                                <div class="XXRmain_11"><span id="heating_pump_control"><?php echo !empty($pump_control_power)?$pump_control_power:0;?></span></div>
                                <?php }?>
                            </div>
                        </td>
                        <td><input type="text" id="heating_pump_control_context" size="5" value="<?php echo $pump_control_attr ? $pump_control_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="heating_pump_control_check_btn" name="check_btn" value="heating_pump_control_check_btn"  <?php if($pump_control_attr and $pump_control_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    $chimney_attr=Selection_fuji::getInfoByName($id,"钢制烟囱",0);

                    if($chimney_attr){
                        $chimney_value=explode(" ",$chimney_attr[0]['value']);
                        $chimney_height=mb_substr($chimney_value[0],2,100,"UTF-8");
                        $chimney_height=substr($chimney_height,0,-1);
                        $chimney_diameter=mb_substr($chimney_value[1],2,100,"UTF-8");
                        $chimney_diameter=substr($chimney_diameter,0,-2);
                    }

                    ?>
                    <tr>
                        <td>钢制烟囱</td>
                        <td><?php echo $info['guolu_num'] ? $info['guolu_num'] : 1; ?>台</td>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <div class="XXRmain_12">
                                <?php if($chimney_attr){?>
                                    高度<input id="chimney_height" class="XXRmain_13" type="number" value="<?php echo $chimney_height;?>"/><span style="margin-right: 16px;">m</span>直径<input id="chimney_diameter" class="XXRmain_13" type="number" value="<?php echo $chimney_diameter;?>"/><span>mm</span>
                                <?php }else{?>
                                高度<input id="chimney_height" class="XXRmain_13" type="number" value="18"/><span style="margin-right: 16px;">m</span>直径<input id="chimney_diameter" class="XXRmain_13" type="number" value="<?php echo $guoluinfo['flue_interface'];?>"/><span>mm</span>
                                <?php }?>
                            </div>
                        </td>
                        <td><input type="text" id="chimney_context" size="5" value="<?php echo $chimney_attr ? $chimney_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="chimney_check_btn" name="check_btn" value="chimney_check_btn" <?php if($chimney_attr and $chimney_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    $diameter_attr=Selection_fuji::getInfoByName($id,"分集水器",0);
                    if($diameter_attr){
                        $diameter_value=explode(" ",$diameter_attr[0]['value']);
                        $diameter_num=mb_substr($diameter_value[0],4,100,"UTF-8");
                        $diameter_diameter=mb_substr($diameter_value[1],2,100,"UTF-8");
                        $diameter_diameter=substr($diameter_diameter,0,-2);
                        $diameter_length=mb_substr($diameter_value[2],2,100,"UTF-8");
                        $diameter_length=substr($diameter_length,0,-1);
                    }


                    ?>
                    <tr>
                        <td>分集水器</td>
                        <td>1台</td>
                        <td>&nbsp;</td>
                        <?php if($diameter_attr){?>
                        <td colspan="3">
                            <div class="XXRmain_15">
                                    <div class="XXRmain_16">接口数量<input class="XXRmain_13" type="number"  id="diversity_water_num"  oninput="num(this.value)" value="<?php echo $diameter_num;?>"/></div>
                                    <div id="diameter" class="XXRmain_16" style="display: block"><span>直径</span><input style="margin-top:8px;display: block;" class="XXRmain_13" type="number"  id="diversity_water_length" value="<?php echo $diameter_length;?>"/><span >mm</span></div>
                                    <div id="len" class="XXRmain_16" style="display: block"><span>长度</span><input style="margin-top:8px;display: block;" class="XXRmain_13" type="number"  id="diversity_water_diameter"  value="<?php echo $diameter_diameter;?>"/><span >m</span></div>
                            </div>
                        </td>
                        <?php }else{?>
                        <td colspan="3">
                            <div class="XXRmain_15">

                                    <div class="XXRmain_16">接口数量<input class="XXRmain_13" type="number" value="0" id="diversity_water_num" oninput="num(this.value)" /></div>
                                    <div id="diameter" class="XXRmain_16"><span>直径</span><input style="margin-top:8px" class="XXRmain_13" type="number" value="" id="diversity_water_length" /><span >mm</span></div>
                                    <div id="len" class="XXRmain_16"><span>长度</span><input style="margin-top:8px" class="XXRmain_13" type="number" value="" id="diversity_water_diameter" /><span >m</span></div>

                            </div>
                        </td>
                        <?php }?>
                        <td><input type="text" id="diversity_water_context" size="5" value="<?php echo $diameter_attr ? $diameter_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="diversity_water_check_btn" name="check_btn" value="diversity_water_check_btn" <?php if($diameter_attr and $diameter_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    $powe_box_attr=Selection_fuji::getInfoByName($id,"配电柜",0);
                    $powe_box_value=0;
                    if($powe_box_attr){
                        $kw_value= mb_substr($powe_box_attr[0]['value'],3,100,"UTF-8");
                        $powe_box_value= substr($kw_value,0,-2);
                    }

                   
                    ?>
                    <tr>
                        <td>配电柜</td>
                        <td>1台</td>
                        <td>&nbsp;</td>
                        <td colspan="3">
                                <div class="XXRmain_8">
                                    <div class="XXRmain_10">&nbsp;</div>
                                    <div class="XXRmain_11">电负荷<span id="heating_powe_box"><?php echo $powe_box_value?></span>kW</div>
                                </div>

                        </td>
                        <td><input type="text" id="powe_box_context" size="5" value="<?php echo $powe_box_attr ? $powe_box_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="powe_box_check_btn"  name="check_btn" value="powe_box_check_btn" <?php if($powe_box_attr and $powe_box_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                </table>
            </div>
            <?php
        }
        if($hotwater){
            ?>
            <div style="display: <?php if($info['application'] == 1 || $info['application'] == 2 ) echo 'block'; else echo 'none'; ?>">
                <div class="XXRmain_1">热水辅机</div>

                <table class="XXRmain_7" border="0">
                    <tr class="GLDetils9_fir">
                        <td width="20%">设备名称</td>
                        <td width="10%">数量</td>
                        <td width="10%">计算参数</td>
                        <td width="10%">厂家</td>
                        <td width="20%">规格型号</td>
                        <td width="10%">查看详情</td>
                        <td width="5%">备注</td>
                        <td width="20%">是否添加到方案里</td>
                    </tr>
                    <?php
                    if($guoluinfo['vender'] != 43) {
                        $burner_attr=Selection_fuji::getInfoByName($id,"燃气燃烧器",1);
                        ?>
                        <tr>
                            <td>燃气燃烧器</td>
                            <td><?php echo $hotwater['burner'] ? $hotwater['burner']['count'] : 1; ?>台</td>
                            <td>&nbsp;</td>
                            <td colspan="2">
                                <div class="XXRmain_8">
                                    <div class="XXRmain_10">
                                        <input type="hidden" id="water_burner_id" value="<?php echo $hotwater['burner'] ? $hotwater['burner']['id'] : 0; ?>">
                                        <input type="hidden" id="water_burner_name" value="<?php echo $hotwater['burner'] ? $hotwater['burner']['name'] : ''; ?>">
                                        <input type="hidden" id="water_burner_count" value="<?php echo $hotwater['burner'] ? $hotwater['burner']['count'] : 0; ?>">
                                        <input type="hidden" id="water_burner_power" value="<?php echo $hotwater['burner'] ? $hotwater['burner']['power'] : 0; ?>">
                                        <?php if ($hotwater['burner']) {
                                            $burnervendername = Dict::getInfoById($hotwater['burner']['vender']);
                                            echo $burnervendername['name'];
                                        } else {
                                            echo "没有找到合适的燃气燃烧器";
                                        } ?>
                                    </div>
                                    <div class="XXRmain_11"><?php echo $hotwater['burner'] ? $hotwater['burner']['version'] : ''; ?></div>
                                </div>
                            </td>
                            <td><a href="javascript:void(0);" onclick="burner_detail(<?php echo $heating['burner'] ? $heating['burner']['id'] : 0; ?>)">详情</a></td>
                            <td><input type="text" id="hw_burner_context" size="5"  value="<?php echo $burner_attr ? $burner_attr[0]['context'] : ''; ?>"></td>
                            <td><input type="checkbox" id="burner_check_btn"  name="check_btn" value="burner_check_btn"  <?php if($burner_attr and $burner_attr[0]['add_type']) echo "checked";?>/></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <?php
                    $hdys_attr=Selection_fuji::getInfoByName($id,"全自动软水机",1);
                    $hdys_id=0;
                    if($hdys_attr){
                        $hdys_id=$hdys_attr[0]['value'];
                    }
                    ?>
                    <tr>
                        <td>全自动软水器</td>
                        <td><?php echo $hotwater['hdys']?$hotwater['hdys']['count']:1; ?>台</td>
                        <td>额定出水量=<?php echo $hotwater['hdys'] ? $hotwater['hdys']['outwater'] : ''; ?>m³/L</td>
                        <td colspan="3">
                            <input type="hidden" id="water_hdys_name" value="<?php echo $hotwater['hdys'] ? $hotwater['hdys']['name'] : ''; ?>">
                            <input type="hidden" id="water_hdys_count" value="<?php echo $hotwater['hdys'] ? $hotwater['hdys']['count'] : 0; ?>">
                            <input type="hidden" id="water_hdys_parameter" value="<?php echo $hotwater['hdys'] ? $hotwater['hdys']['outwater'] : ''; ?>">
                            <?php
                            $hdysnum = count($hotwater['hdys']) - 3;
                            if($hdysnum > 0){
                                $i = 0;
                                foreach ($hotwater['hdys'] as $hdysinfo){
                                    if($i == $hdysnum) break;
                                    $hdysvendername = Dict::getInfoById($hdysinfo['vender']);
                                    echo '
                                    <div class="XXRmain_8">
                                        <input type="radio" class="XXRmain_9" name="water_hdys" value="'.$hdysinfo['id'].'" ';if($hdys_id==$hdysinfo['id']) echo 'checked'; echo '>
                                        <div class="XXRmain_10">'.$hdysvendername['name'].'</div>
                                        <div class="XXRmain_11">'.$hdysinfo['version'].'</div>
                                        <a href="javascript:void(0);" onclick="hdys_detail('.$hdysinfo['id'].')">详情</a>
                                    </div>
                                    ';
                                    $i++;
                                }
                            }else{
                                echo "没有找到合适的全自动软水机";
                            }
                            ?>
                        </td>
                        <td><input type="text" id="hw_hdys_context" size="5" value="<?php echo $hdys_attr ? $hdys_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="hdys_check_btn_hw" name="check_btn" value="hdys_check_btn_hw" <?php if($hdys_attr and $hdys_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <!--软化水箱-->
                    <?php
                    $water_box_attr=Selection_fuji::getInfoByName($id,"软化水箱",1);
                    $water_box_id=0;
                    if($water_box_attr){
                        $water_box_id=$water_box_attr[0]['value'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $hotwater['water_box'] ? $hotwater['water_box']['name'] : ''; ?></td>
                        <td><?php echo $hotwater['water_box']?$hotwater['water_box']['count']:1; ?>台</td>
                        <td>公称容积=<?php echo $hotwater['water_box'] ? $hotwater['water_box']['capacity'] : ''; ?>m³</td>
                        <td colspan="3">
                            <input type="hidden" id="water_water_box_name" value="<?php echo $hotwater['water_box'] ? $hotwater['water_box']['name'] : ''; ?>">
                            <input type="hidden" id="water_water_box_count" value="<?php echo $hotwater['water_box'] ? $hotwater['water_box']['count'] : 0; ?>">
                            <input type="hidden" id="water_water_box_parameter" value="<?php echo $hotwater['water_box'] ? $hotwater['water_box']['capacity'] : ''; ?>">
                            <?php
                            $waterboxnumw = count($hotwater['water_box']) - 3;
                            if($waterboxnumw > 0){
                                $i = 0;
                                //$pump_control_power += $heating['pipeline_pump'][0]['motorpower'] * 2;
                                foreach ($hotwater['water_box'] as $wboxinfo){
                                    if($i == $waterboxnumw) break;
                                    //$thispv = Dict::getInfoById($pipelineinfo['vender']);
                                    echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9" name="water_water_box" value="'.$wboxinfo['id'].'" ';if($water_box_id==$wboxinfo['id']) echo 'checked'; echo '>
                                                <div class="XXRmain_10">现场制作</div>
                                                <div class="XXRmain_11">'.$wboxinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="water_box_detail('.$wboxinfo['id'].')">详情</a>
                                            </div>
                                            ';
                                    $i++;
                                }
                            }else{
                                echo "没有找到合适的软化水箱";
                            }
                            ?>
                        </td>
                        <td><input type="text" id="hw_water_box_context" size="5"  value="<?php echo $water_box_attr ? $water_box_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="water_box_check_btn_hw"   name="check_btn" value="water_box_check_btn_hw" <?php if($water_box_attr and $water_box_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    //update 1.17
                    if(!empty($hotwater['pipeline_pump']['name'])){
                    $hpipelinenum = count($hotwater['pipeline_pump']) - 4;
                    $pipeline_pump_attr=Selection_fuji::getInfoByName($id,"锅炉循环泵",1);
                    ?>
                    <!--循环泵-->
                    <tr>
                        <td>
                            <?php echo $hotwater['pipeline_pump']['name'];?>
                            <p style="line-height: 5px;">
                                <input type="hidden" id="water_pipeline_pump_count" value="<?php echo $hotwater['pipeline_pump']['count'];?>">
                                <input type="hidden" id="water_pipeline_pump_name" value="<?php echo $hotwater['pipeline_pump']['name'];?>">
                                <input type="hidden" id="water_pipeline_pump_flow" value="<?php echo $hotwater['pipeline_pump']['flow'];?>">
                                <input type="hidden" id="water_pipeline_pump_lift" value="<?php echo $hotwater['pipeline_pump']['lift'];?>">
                                <input type="hidden" class="hotwater_pump_flow" value="<?php echo $hotwater['pipeline_pump']['flow'];?>">
                                <input type="hidden" class="hotwater_pump_lift" value="<?php echo $hotwater['pipeline_pump']['lift'];?>">
                                <a href="javascript:void(0);" class="hot_pipeline_pump_edit"  data-radioname="water_pipeline_pump" style="color: #04A6FE;">修改扬程</a></p>
                        </td>
                        <td><?php echo $hotwater['pipeline_pump']['count']?$hotwater['pipeline_pump']['count']:2; ?>台</td>
                        <td>流量=<?php echo $hotwater['pipeline_pump']['flow'];?>m³/h </br> 扬程=<?php echo $hotwater['pipeline_pump']['lift'];?>m</td>
                        <td colspan="3">
                            <?php
                            if($pipeline_pump_attr){
                                $pipelineinfo=Pipeline_attr::getInfoById($pipeline_pump_attr[0]['value']);
                                if($pipelineinfo){
                                    $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                    echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 power_compute" name="water_pipeline_pump" value="'.$pipeline_pump_attr[0]['value'].'"';echo "checked";echo'>
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipeline_pump_attr[0]['value'].')">详情</a>
                                            </div>
                                            ';
                                }
                                else{
                                    if($hpipelinenum > 0){
                                        $i = 0;
                                        //$pump_control_power += $heating['pipeline_pump'][0]['motorpower'] * 2;
                                        foreach ($hotwater['pipeline_pump'] as $pipelineinfo){
                                            if($i == $hpipelinenum) break;
                                            $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                            echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 water_power_compute" name="water_pipeline_pump" value="'.$pipelineinfo['id'].'">
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                            </div>
                                            ';
                                            $i++;
                                        }
                                    }else{
                                        echo "没有找到合适的循环泵";
                                    }
                                }

                            }else{
                            if($hpipelinenum > 0){
                                $i = 0;
                                //$pump_control_power += $heating['pipeline_pump'][0]['motorpower'] * 2;
                                foreach ($hotwater['pipeline_pump'] as $pipelineinfo){
                                    if($i == $hpipelinenum) break;
                                    $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                    echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 water_power_compute" name="water_pipeline_pump" value="'.$pipelineinfo['id'].'">
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                            </div>
                                            ';
                                    $i++;
                                }
                            }else{
                                echo "没有找到合适的循环泵";
                            }}
                            ?>
                        </td>
                        <td><input type="text" id="hw_pipeline_pump_context" size="5"  value="<?php echo $pipeline_pump_attr ? $pipeline_pump_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="pipeline_pump_check_btn"  name="check_btn" value="pipeline_pump_check_btn" <?php if($pipeline_pump_attr and $pipeline_pump_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php }?>
                    <?php
                    //除污器数据
                    if($hotwater['dirt_separater']){
                        $dirt_separater_attr=Selection_fuji::getInfoByName($id,"锅炉除污器",1);

                        $dirt_separater_data="";
                        $dirt_separater_add_type=0;
                        if($dirt_separater_attr){
                            $dirt_separater_data=$dirt_separater_attr[0]['context'];
                            $dirt_separater_add_type=$dirt_separater_attr[0]['add_type'];
                        }

                        foreach ($hotwater['dirt_separater'] as $dirt_separater){
                            if($dirt_separater){

                                if(empty($dirt_separater['id']))
                                    $dirt_separater['id']=0;
                                if(empty($dirt_separater['diameter']))
                                    $dirt_separater['diameter']=0;
//                                echo '<div class="water_dirt_separater_data">';
//                                echo '<input type="hidden" class="water_dirt_separater_id" value="'.$dirt_separater['id'].'">';
//                                echo '<input type="hidden" class="water_dirt_separater_name" value="'.$dirt_separater['name'].'">';
//                                echo '<input type="hidden" class="water_dirt_separater_count" value="'.$dirt_separater['count'].'">';
//                                echo '</div>';
                                echo '
                                    <tr>
                                        <td>'.$dirt_separater['name'].'
                                              <div class="water_dirt_separater_data">  
                                                  <input type="hidden" class="water_dirt_separater_id" value="'.$dirt_separater['id'].'">
                                                  <input type="hidden" class="water_dirt_separater_name" value="'.$dirt_separater['name'].'">
                                                  <input type="hidden" class="water_dirt_separater_count" value="'.$dirt_separater['count'].'">
                                                   <input type="hidden"  id="water_dirt_separater_parameter" class="water_dirt_separater_parameter" value="'.$dirt_separater['DN'].'">
                                              </div>
                                        </td>
                                        <td>'.$dirt_separater['count'].'台</td>
                                        <td>DN'.$dirt_separater['DN'].'</td>
                                        <td colspan="3">
                                            <div class="XXRmain_8">
                                                <div class="XXRmain_10">'.($dirt_separater['id']?"&nbsp;":"没有找到合适的除污器").'</div>
                                                <div class="XXRmain_11">DN'.$dirt_separater['diameter'].'&nbsp;PN1.6</div>
                                                <a href="javascript:void(0);" onclick="dirt_separator_detail('.$dirt_separater['id'].')">详情</a>
                                            </div>
                                        </td>
                                        <td><input type="text" id="hw_dirt_separater_context" size="5" value="'.$dirt_separater_data.'"></td>
                                        <td><input type="checkbox" id="dirt_separater_check_btn"   name="water_dirt_check" value="dirt_separater_check_btn"';if($dirt_separater_add_type) echo "checked";echo '></td>
                                    </tr>
                                ';
                            }
                        }
                    }
                    //板换数据
                    if($hotwater['board']){
                        $j=0;
                        foreach ($hotwater['board'] as $board){
                            $board_attr=Selection_fuji::getInfoByName($id,"分区".($j+1)."热水板换",1);
                            $board_data=" ";
                            $board_add_type=0;
                            if($board_attr){
                                $board_data=$board_attr[0]['context'];
                                $board_add_type=$board_attr[0]['add_type'];
                            }
                            if($board)
                                echo '
                                    <tr>
                                        <td>'.$board['name'].'</td>
                                        <td>'.$board['count'].'台</td>
                                        <td>&nbsp;</td>
                                        <td colspan="3">
                                            <div class="XXRmain_8">
                                                <div class="XXRmain_10">&nbsp;</div>
                                                <div class="XXRmain_11 water_board_data" style="line-height: 50px;">
                                                    <input type="hidden" class="water_board_name" value="'.$board['name'].'">
                                                    <input type="hidden" class="water_board_count" value="'.$board['count'].'">
                                                    <span class="water_board_value">
                                                     一次侧供回水温度'.$board['once_sarwt'].'
                                                        &nbsp;二次侧供回水温度'.$board['twice_sarwt'].'<br>
                                                        换热量'.$board['exchange_Q'].'kw
                                                        承压1.6MPa</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><input type="text" id="hw_water_board_context'.$j.'" size="5" value="'.$board_data.'"></td>
                                        <td><input type="checkbox" id="water_board_check_btn" name="water_board_check" value="water_board_check_btn"';if($board_add_type) echo 'checked'; echo'></td>
                                    </tr>';
                                        $j++;
                        }

                    }
                    ?>
                    <!--热水水泵控制柜-->
                    <?php
                    $pump_control_attr=Selection_fuji::getInfoByName($id,"水泵控制柜",1);
                    ?>
                    <tr>
                        <td>水泵控制柜</td>
                        <td>1台</td>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <div class="XXRmain_8">
                                <div class="XXRmain_10">&nbsp;</div>
                                <?php if($pump_control_attr){?>
                                    <div class="XXRmain_11"><span id="water_pump_control"><?php echo $pump_control_attr[0]['value'];?></span></div>
                                <?php }else{?>
                                    <div class="XXRmain_11"><span id="water_pump_control"><?php echo !empty($pump_control_power)?$pump_control_power:0;?></span></div>
                                <?php }?>
                            </div>
                        </td>
                        <td><input type="text" id="hw_water_pump_control_context" size="5" value="<?php echo $pump_control_attr ? $pump_control_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="water_pump_control_check_btn" name="check_btn" value="water_pump_control_check_btn" <?php if($pump_control_attr and $pump_control_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    $chimney_attr=Selection_fuji::getInfoByName($id,"钢制烟囱",1);
                    if($chimney_attr){
                        $chimney_value=explode(" ",$chimney_attr[0]['value']);

                        $chimney_height=mb_substr($chimney_value[0],2,100,"UTF-8");
                        $chimney_height=substr($chimney_height,0,-1);
                        $chimney_diameter=mb_substr($chimney_value[1],2,100,"UTF-8");
                        $chimney_diameter=substr($chimney_diameter,0,-2);
                    }
                    ?>

                    <tr>
                        <td>钢制烟囱</td>
                        <td><?php echo $info['guolu_num'] ? $info['guolu_num'] : 1; ?>台</td>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <div class="XXRmain_12">
                                <?php if($chimney_attr){?>
                                    高度<input id="chimney_height2" class="XXRmain_13" type="number" value="<?php echo $chimney_height;?>"/><span style="margin-right: 16px;">m</span>直径<input id="chimney_diameter2" class="XXRmain_13" type="number" value="<?php echo $chimney_diameter;?>"/><span>mm</span>
                                <?php }else{?>
                                    高度<input id="chimney_height2" class="XXRmain_13" type="number" value="18"/><span style="margin-right: 16px;">m</span>直径<input id="chimney_diameter2" class="XXRmain_13" type="number" value="<?php echo $guoluinfo['flue_interface'];?>"/><span>mm</span>
                                <?php }?>
                            </div>
                        </td>
                        <td><input type="text" id="hw_chimney_context" size="5" value="<?php echo $chimney_attr ? $chimney_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="hw_chimney_check_btn"  name="check_btn" value="hw_chimney_check_btn"  <?php if($chimney_attr and $chimney_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    $powe_box_attr=Selection_fuji::getInfoByName($id,"配电柜",1);
                    $powe_box_value=0;
                    if($powe_box_attr){
                        $kw_value= mb_substr($powe_box_attr[0]['value'],3,100,"UTF-8");
                        $powe_box_value= substr($kw_value,0,-2);
                    }
                    ?>
                    <tr>
                        <td>配电柜</td>
                        <td>1台</td>
                        <td>&nbsp;</td>
                        <td colspan="3">
                                <div class="XXRmain_8">
                                    <div class="XXRmain_10">&nbsp;</div>
                                    <div class="XXRmain_11">电负荷<span id="water_powe_box"><?php echo $powe_box_value?></span>kW</div>
                                </div>
                        </td>
                        <td><input type="text" id="hw_powe_box_context" size="5" value="<?php echo $powe_box_attr ? $powe_box_attr[0]['context'] : ''; ?>"></td>
                        <td><input type="checkbox" id="hw_powe_box_check_btn"   name="check_btn" value="hw_powe_box_check_btn" <?php if($powe_box_attr and $powe_box_attr[0]['add_type']) echo "checked";?>></td>
                    </tr>
                    <?php
                    if($info['water_type'] ){

                        $hotwbnum = count($hotwater['hot_water_box']);
                        $j=0;
                        foreach ($hotwater['hot_water_box'] as $hot_water_box){
                        $hot_water_box_attr=Selection_fuji::getInfoByName($id,"分区".($j+1)."不锈钢保温水箱",1);
                        $hot_water_box_value=0;
                        if($hot_water_box){
                            $hot_water_box_value=$hot_water_box['capacity'][$j];
                        }
                        if($hot_water_box_attr){
                            $hot_water_box_value= $hot_water_box_attr[0]['value'];

                        }
//                        print_r($hot_water_box['use_water']);
                        ?>
                        <!--保温热水箱-->
                        <tr>
	                        <td><?php echo $hot_water_box ? $hot_water_box['name'][$j] : ''; ?>
                                <p style="line-height: 30px;" class="hot_water_box_data">
                                    <input type="hidden" id="use_water" class="number" value="<?php echo $j; ?>">
                                    <input type="hidden" id="use_water" class="use_water" value="<?php echo $hot_water_box['use_water'][$j]; ?>">
                                	<input type="hidden" id="hot_water_box_name<?php echo $j;?>" name="hot_water_box_name" value="<?php echo $hot_water_box ? $hot_water_box['name'][$j] : ''; ?>">
                                	<input type="hidden" id="hot_water_box_count<?php echo $j;?>" value="<?php echo $hot_water_box ? $hot_water_box['count'][$j] : 0; ?>">
                                    <input type="hidden" id="hot_water_box_capacity<?php echo $j;?>" value="<?php echo $hot_water_box['capacity'][$j];?>">
                                    <input type="hidden" id="hot_water_box_parameter<?php echo $j;?>" value="<?php echo $hot_water_box ? $hot_water_box['capacity'][$j] : ''; ?>">
                                    <a href="javascript:void(0);" class="hot_water_box_capacity_edit" data-radioname="hotwater_capacity" style="color: #04A6FE;">修改同时使用系数</a></p>
	                        </td>
                            <td><?php echo $hot_water_box?$hot_water_box['count'][$j]:1; ?>台</td>
                            <td>公称容积=<?php echo $hot_water_box ? $hot_water_box['capacity'][$j] : ''; ?>m³</td>
                            <td colspan="3">
                                <div class="XXRmain_8">
                                    <div class="XXRmain_10">&nbsp;</div>
                                    <div class="XXRmain_11">公称容积<span id="hw_hot_water_box_value<?php echo $j;?>"><?php echo $hot_water_box_value;?></span>m³</div>
                                </div>
                            </td>
                            <td><input type="text" id="hw_hot_water_box_context<?php echo $j;?>" size="5" value="<?php echo $hot_water_box_attr ? $hot_water_box_attr[0]['context'] : ''; ?>"></td>
	                        <td><input type="checkbox" id="hot_water_box_check_btn<?php echo $j;?>" name="check_btn" value="hot_water_box_check_btn" <?php if($hot_water_box_attr and $hot_water_box_attr[0]['add_type']) echo "checked";?>></td>
	                    </tr>
                    <?php $j++;}}?>
                        <?php
                        if($info['water_type'] == 3 || $info['water_type'] == 4 ){
                        //热水循环泵数据
                        
                        if(!empty($hotwater['heating_pump']['name'])){
                        	$j = 0;
                        	foreach ($hotwater['heating_pump'] as $heating_pumplist){
                                $heating_pump_attr=Selection_fuji::getInfoByName($id,"热水循环泵".($j+1),1);
                                $heating_pump_data="";
                                $heating_pump_add_type=0;
                                if($heating_pump_attr){
                                    $heating_pump_data=$heating_pump_attr[0]['context'];
                                    $heating_pump_add_type=$heating_pump_attr[0]['add_type'];
                                }
                        ?>
                        <tr>
                            <td>
                                <?php echo $heating_pumplist['name'].($j+1);?>
                                <p style="line-height: 5px;" class="hotwater_hotwater_pump_data">
                                    <input type="hidden" id="use_water" class="number" value="<?php echo $j; ?>">
                                    <input type="hidden" class="hotwater_pump_count" value="<?php echo $heating_pumplist['count'];?>">
                                    <input type="hidden" class="hotwater_pump_name" value="<?php echo $heating_pumplist['name'].($j+1);?>">
                                    <input type="hidden" id="hotwater_pump_flow" class="hotwater_pump_flow" value="<?php echo $heating_pumplist['flow'];?>">
                                    <input type="hidden" id="hotwater_pump_lift" class="hotwater_pump_lift" value="<?php echo $heating_pumplist['lift'];?>">

                                    <a href="javascript:void(0);" class="hot_pipeline_pump_edit" data-radioname="hotwater_pump<?php echo $j;?>" style="color: #04A6FE;">修改扬程</a>
                                </p>
                            </td>
                            <td><?php echo $heating_pumplist['count']?$heating_pumplist['count']:2; ?>台</td>
                            <td>流量=<?php echo $heating_pumplist['flow'];?>m³/h </br> 扬程=<?php echo $heating_pumplist['lift'];?>m</td>
                            <td colspan="3">
                                <?php
                                $hotwaterpumpnum = count($heating_pumplist) - 4;
                                if($heating_pump_attr){
                                    $pipelineinfo=Pipeline_attr::getInfoById($heating_pump_attr[0]['value']);
                                    $thispv['name']='';
                                    if($pipelineinfo){
                                        $thispv = Dict::getInfoById($pipelineinfo['vender']);

                                    echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 water_power_compute" name="hotwater_pump'.$j.'" value="'.$pipelineinfo['id'].'"';echo "checked"; echo'>
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                                <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo['id'].')">详情</a>
                                            </div>
                                            ';
                                }else{
                                        if($hotwaterpumpnum > 0){
                                            $i = 0;
                                            //$pump_control_power += $heating['pipeline_pump'][0]['motorpower'] * 2;
                                            foreach ($hotwater['heating_pump'] as $pipelineinfo){
                                                if($i == $hotwaterpumpnum) break;
                                                $thispv = Dict::getInfoById($pipelineinfo[$i]['vender']);
                                                echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 water_power_compute" name="hotwater_pump'.$j.'" value="'.$pipelineinfo[$i]['id'].'">
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo[$i]['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <!--<div class="XXRmain_11">Q='.$pipelineinfo[$i]['flow_min'].'m³/h，H='.$pipelineinfo[$i]['lift_min'].'m，P='.$pipelineinfo[$i]['motorpower'].'kW</div>-->
                                                <div class="XXRmain_11">'.$pipelineinfo[$i]['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo[$i]['id'].')">详情</a>
                                            </div>
                                            ';
                                                $i++;
                                            }
                                        }else{
                                            echo "没有找到合适的循环泵";
                                        }
                                }}else{
                                    if($hotwaterpumpnum > 0){
                                        $i = 0;
                                        //$pump_control_power += $heating['pipeline_pump'][0]['motorpower'] * 2;

                                        foreach ($hotwater['heating_pump'] as $pipelineinfo){
                                            if($i == $hotwaterpumpnum) break;


                                            $thispv = Dict::getInfoById($pipelineinfo[$i]['vender']);


                                            echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 water_power_compute" name="hotwater_pump'.$j.'" value="'.$pipelineinfo[$i]['id'].'">
                                                <input type="hidden" class="motorpower" value="'.$pipelineinfo[$i]['motorpower'].'">
                                                <div class="XXRmain_10">'.$thispv['name'].'</div>
                                                <!--<div class="XXRmain_11">Q='.$pipelineinfo[$i]['flow_min'].'m³/h，H='.$pipelineinfo[$i]['lift_min'].'m，P='.$pipelineinfo[$i]['motorpower'].'kW</div>-->
                                                <div class="XXRmain_11">'.$pipelineinfo[$i]['version'].'</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail('.$pipelineinfo[$i]['id'].')">详情</a>
                                            </div>
                                            ';
                                            $i++;
                                        }
                                    }else{
                                        echo "没有找到合适的循环泵";
                                    }}
                                ?>
                            </td>
                            <td><input type="text" id="hw_hotwater_pump_context<?php echo $j?>" size="5" value="<?php echo $heating_pump_data;?>"></td>
                            <td><input type="checkbox" id="hotwater_pump_check_btn" name="hotwater_pump_check" value="hotwater_pump_check_btn" <?php if($heating_pump_add_type) echo "checked";?>></td>
                        </tr>
                        <?php
                        	$j ++;
                        	}
                        }
                        //变频供水泵数据
                        //var_dump($hotwater['water_pump']);
                        if($hotwater['water_pump'][0]){
                            $j = 0;
                            foreach ($hotwater['water_pump'] as $hotwater_water_pump){
                                $pumpnum = count($hotwater_water_pump) - 4;
                                $water_pump_attr=Selection_fuji::getInfoByName($id,"分区".($j+1)."变频供水泵",1);
                                $water_pump_data="";
                                $water_pump_add_type=0;

                                if($water_pump_attr){
                                    $water_pump_data=$water_pump_attr[0]['context'];
                                    $water_pump_add_type=$water_pump_attr[0]['add_type'];
                                }


                                echo '
                                <tr>
                                    <td>
                                        '.$hotwater_water_pump['name'].'
                                        <p style="line-height: 5px;"  class="hotwater_water_pump_data">
                                            <input type="hidden" class="hotwater_water_pump_count" value="'.$hotwater_water_pump['count'].'">
                                            <input type="hidden" class="hotwater_water_pump_name" value="'.$hotwater_water_pump['name'].'">
                                            <input type="hidden" class="hotwater_water_pump_flow" value="'.$hotwater_water_pump['flow'].'">
                                            <input type="hidden" class="hotwater_water_pump_lift" value="'.$hotwater_water_pump['lift'].'">
                                            <a href="javascript:void(0);" class="hotwater_pump_edit" data-radioname="hotwater_water_pump'.$j.'" style="color: #04A6FE;">修改扬程</a>
                                        </p>
                                    </td>
                                    <td>'.$hotwater_water_pump['count'].'台</td>
                                    <td>流量='.$hotwater_water_pump['flow'].'m³/h </br> 扬程='.$hotwater_water_pump['lift'].'m</td>
                                    <td colspan="3">
                            ';

                                if($water_pump_attr){
                                    $pipelineinfo=Syswater_pump_attr::getInfoById($water_pump_attr[0]['value']);
                                    if($pipelineinfo){

                                    $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                    echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 water_power_compute" name="hotwater_water_pump'.$j.'" value="'.$pipelineinfo['id'].'"'; echo "checked"; echo '>
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>';
                                    }else{
                                        if($pumpnum > 0){
                                            $i = 0;
                                            foreach ($hotwater_water_pump as $pipelineinfo){
                                                if($i == $pumpnum) break;
                                                $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                                echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 water_power_compute" name="hotwater_water_pump'.$j.'" value="'.$pipelineinfo['id'].'">
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';
                                                $i++;
                                            }
                                        }else{
                                            echo "没有找到合适的补水泵";
                                        }

                                    }}else{
                                if($pumpnum > 0){
                                    $i = 0;
                                    foreach ($hotwater_water_pump as $pipelineinfo){


                                        if($i == $pumpnum) break;
                                        $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                        echo '
                                        <div class="XXRmain_8">
                                            <input type="radio" class="XXRmain_9 water_power_compute" name="hotwater_water_pump'.$j.'" value="'.$pipelineinfo['id'].'">
                                            <input type="hidden" class="motorpower" value="'.$pipelineinfo['motorpower'].'">
                                            <div class="XXRmain_10">'.$thispv['name'].'</div>
                                            <!--<div class="XXRmain_11">Q='.$pipelineinfo['flow_min'].'m³/h，H='.$pipelineinfo['lift_min'].'m，P='.$pipelineinfo['motorpower'].'kW</div>-->
                                            <div class="XXRmain_11">'.$pipelineinfo['version'].'</div>
                                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipelineinfo['id'].')">详情</a>
                                        </div>
                                        ';

                                        $i++;
                                    }
                                }else{
                                    echo "没有找到合适的补水泵";
                                }}
                                echo '</td>
                                             <td><input type="text" id="hw_dynamic_water_pump_context'.($j).'" size="5" value="'.$water_pump_data.'"></td>
                                            <td><input type="checkbox" id="dynamic_water_pump_check_btn" name="hotwater_water_pump_check" value="dynamic_water_pump_check_btn"';if($water_pump_add_type) echo 'checked'; echo'></td>
                                        </tr>';
                                $j ++;
                            }
                        }
                    }
                    ?>
                </table>
            </div>
            <?php
        }
        ?>
<!--        <div class="XXRmain_17"><span>添加备注</span></div>-->
<!--        <textarea class="XXRmain_18" id="remark"></textarea>-->

        <div id="buttoning1" style="margin: 0 auto 99px auto;display: flex;justify-content: space-around;padding-top: 90px;">
            <button id="GLprior" class="GLXXmain_4">上一步</button>
            <button id="subimt_fuji" class="GLXXmain_4">下一步</button>
        </div>
    </div>
</div>
<script>
    $(function () {
        //详情
        $("#info").click(function(){

            var thisid = <?php echo $info['guolu_id'];?>;

            layer.open({
                type: 2,
                title: '锅炉详情',
                shadeClose: true,
                shade: 0.3,
                area: ['1200px', '600px'],
                content: 'guolu_info.php?id='+thisid
            });
        });
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
        //采暖循环泵修改扬程
        $('.pipeline_pump_edit').click(function () {
            var guolutype = '<?php echo $info['heating_type']; ?>';
            var obj = $(this);
            var inputname = obj.attr("data-radioname");
            var fenqu = obj.parent().attr("data-fenqu");
            if(fenqu == undefined) fenqu = '';
            inputname = inputname + fenqu;
            var pipeline_pump_flow = obj.parent().find('.heating_pump_flow').val();
            var pipeline_pump_lift = obj.parent().find('.heating_pump_lift').val();
            layer.prompt({title: '修改参数', formType: 0, offsett: 'l',value:pipeline_pump_lift}, function(pass, index){
                layer.close(index);
                obj.parent().find('.pipeline_pump_lift').val(pass);
                $.ajax({
                    type        : 'POST',
                    data        : {
                        pump_flow : pipeline_pump_flow,
                        pump_lift : pass,
                        inputname : inputname
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=get_new_pipeline',
                    success :     function(data){
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                obj.parent().parent().parent().find('td').eq(3).html('');
                                obj.parent().parent().parent().find('td').eq(3).html(msg);
                                //计算承压
                                if(guolutype == 3 && inputname !='pipeline_pump'){
                                    var pressure = 0;
                                    //var fenqu = obj.parent().attr("data-fenqu");
                                    var pumpnum = $('#fenqu_syspump'+ fenqu).attr("data-pumpnum");
                                    var syspump = 0;
                                    if(pumpnum > 0){
                                        syspump = $('#fenqu_syspump'+ fenqu).find('.water_pump_lift').val();
                                    }
                                    var pressure = parseFloat(pass) + parseFloat(syspump);
                                    pressure = parseFloat(pressure) / 100;
                                    var boardtext= $('.fenqu_board'+ fenqu).html();
                                    var end = boardtext.indexOf('承压');
                                    boardtext = boardtext.substr(0, end);
                                    boardtext = boardtext + '承压1.6MPa' ;
                                    $('.fenqu_board'+ fenqu).html(boardtext);
                                }
                                break;
                        }
                    }
                });
            });
        });
        //热水循环泵修改扬程
        $('.hot_pipeline_pump_edit').click(function () {
            var obj = $(this);
            var inputname = obj.attr("data-radioname");

            var number = obj.parent().find('.number').val();
            var pipeline_pump_flow = obj.parent().find('.hotwater_pump_flow').val();
            var pipeline_pump_lift = obj.parent().find('.hotwater_pump_lift').val();

            layer.prompt({title: '修改参数', formType: 0, offsett: 'l',value:pipeline_pump_lift}, function(pass, index){
                layer.close(index);
                obj.parent().find('.pipeline_pump_lift').val(pass);
                $.ajax({
                    type        : 'POST',
                    data        : {
                        pump_flow : pipeline_pump_flow,
                        pump_lift : pass,
                        inputname : inputname
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=get_new_hot_pipeline',
                    success :     function(data){

                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;

                        switch(code){
                            case 1:
                                obj.parent().parent().parent().find('td').eq(3).html('');
                                obj.parent().parent().parent().find('td').eq(3).html(msg);
                                break;
                        }
                    }
                });
            });
        });

        //热水保温水箱修改公称容积
        $('.hot_water_box_capacity_edit').click(function () {
            var obj = $(this);
            var inputname =$('input[name="hot_water_box_name"]').val();
            var hot_water_box_capacity = obj.parent().find('.use_water').val();
            var number = obj.parent().find('.number').val();
            layer.prompt({title: '修改参数', formType: 0, offsett: 'l',value:0.5}, function(pass, index){
                layer.close(index);
                obj.parent().find('.hot_water_box_capacity').val(pass);
                $.ajax({
                    type        : 'POST',
                    data        : {
                        box_capacity : pass,
                        hot_water_box_capacity:hot_water_box_capacity,
                        number:number,
                        id :  <?php echo $id;?>
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=edit_water_box_value',
                    success :     function(data){
//                        alert(data);
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;

                        switch(code){
                            case 1:
                                obj.parent().parent().parent().find('td').eq(3).html('');
                                obj.parent().parent().parent().find('td').eq(3).html(msg);
                                break;
                        }
                    }
                });
            });
        });
        $("body").on("click", '.water_power_compute', function(){
            var box_power = 0;
            var pump_control = '';
            //燃烧机电功率
            var burner_power = $('#water_burner_power').val();
            if(burner_power == undefined) burner_power = 0;
            if(burner_power != 0){
                box_power = parseInt(box_power) + parseFloat(burner_power)*100;
            }
            //循环泵
            var pipeline_power = $('input[name="water_pipeline_pump"]:checked ').parent().find('.motorpower').val();
            if(pipeline_power == undefined) pipeline_power = 0;
            if(pipeline_power != 0){
                box_power = parseInt(box_power) + 3*parseFloat(pipeline_power)*100;
                pump_control = '2×'+pipeline_power + 'kW';
            }
            //热水循环泵
            var length2 = $(".hotwater_hotwater_pump_data").length;
            for(i=0;i<length2;i++){
                var hotwater_power = $('input[name="hotwater_pump'+ i +'"]:checked ').parent().find('.motorpower').val();
                if(hotwater_power == undefined) hotwater_power = 0;
                if(hotwater_power != 0){
                    box_power = parseInt(box_power) + 3*parseFloat(hotwater_power)*100;
                    if(pump_control == ''){
                        pump_control = '2×'+hotwater_power + 'kW';
                    }else{
                        pump_control = pump_control + ' + ' + '2×'+hotwater_power + 'kW';
                    }
                }
            }
            //补水泵
            var length = $(".hotwater_water_pump_data").length;
            for(i=0;i<length;i++){
                var water_pump_power = $('input[name="hotwater_water_pump'+ i +'"]:checked ').parent().find('.motorpower').val();
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
            $('#water_pump_control').text(pump_control);
            $('#water_powe_box').text(box_power);
        });
        //采暖修改扬程补水泵
        $('.water_pump_edit').click(function () {
            var guolutype = '<?php echo $info['heating_type']; ?>';
            var obj = $(this);
            var inputname = obj.attr("data-radioname");
            var fenqu = obj.parent().attr("data-fenqu");
            if(fenqu == undefined) fenqu = '';
            inputname = inputname + fenqu;
            var water_pump_flow = obj.parent().find('.water_pump_flow').val();
            var water_pump_lift = obj.parent().find('.water_pump_lift').val();
            layer.prompt({title: '修改参数', formType: 0, offsett: 'l',value:water_pump_lift}, function(pass, index){
                layer.close(index);
                obj.parent().find('.water_pump_lift').val(pass);
                $.ajax({
                    type        : 'POST',
                    data        : {
                        water_flow : water_pump_flow,
                        water_lift : pass,
                        inputname : inputname
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=get_new_waterpump',
                    success :     function(data){
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                obj.parent().parent().parent().find('td').eq(3).html('');
                                obj.parent().parent().parent().find('td').eq(3).html(msg);
                                //计算承压
                                if(guolutype == 3){
                                    var pressure = 0;
                                    var fenqu = obj.parent().attr("data-fenqu");
                                    var pumpnum = $('#fenqu_pipeline'+ fenqu).attr("data-pumpnum");
                                    var syspump = 0;
                                    if(pumpnum > 0){
                                        syspump = $('#fenqu_pipeline'+ fenqu).find('.pipeline_pump_lift').val();
                                    }
                                    var syspump1 = pass;
                                    if(msg == '没有找到合适的补水泵') syspump1 = 0;
                                    var pressure = parseFloat(syspump1) * 100 + parseFloat(syspump) * 100;
                                    pressure = parseFloat(pressure) / 10000;
                                    var boardtext= $('.fenqu_board'+ fenqu).html();
                                    var end = boardtext.indexOf('承压');
                                    boardtext = boardtext.substr(0, end);
                                    boardtext = boardtext + '承压1.6MPa' ;
                                    $('.fenqu_board'+ fenqu).html(boardtext);
                                }
                                break;
                        }
                    }
                });
            });
        });
        //热水修改扬程补水泵
        $('.hotwater_pump_edit').click(function () {
            var obj = $(this);
            var inputname = obj.attr("data-radioname");


            var water_pump_flow = obj.parent().find('.hotwater_water_pump_flow').val();
            var water_pump_lift = obj.parent().find('.hotwater_water_pump_lift').val();
            layer.prompt({title: '修改参数', formType: 0, offsett: 'l',value:water_pump_lift}, function(pass, index){
                layer.close(index);
                obj.parent().find('.water_pump_lift').val(pass);
                $.ajax({
                    type        : 'POST',
                    data        : {
                        water_flow : water_pump_flow,
                        water_lift : pass,
                        inputname : inputname
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=get_new_hot_waterpump',
                    success :     function(data){
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                obj.parent().parent().parent().find('td').eq(3).html('');
                                obj.parent().parent().parent().find('td').eq(3).html(msg);
                                break;
                        }
                    }
                });
            });
        });
        $('#GLprior').click(function () {


            location.href="selection_result.php?id=<?php echo $id;?>";
        });

        var application = '<?php echo $info['application'];?>';
        $('#subimt_fuji').click(function () {

            //燃烧机
            var burner_check = 0;
            //软水器
            var hdys_check = 0;
            //膨胀水箱
            var water_box_check = 0;
            //膨胀水箱
            var water_box_check_hw = 0;
            //锅炉循环泵
            var pipeline_pump_check = 0;
            //采暖循环泵
            //var heating_pump_check = 0;
            //补水泵
            //var water_pump_check = 0;
            //除污器
            //var dirt_separater_check = 0;
            //板换
            //var board_data_check = 0;
            //钢制烟囱
            var chimney_check = 0;
            //分集水器
            var diversity_water_check = 0;
            //采暖水泵控制柜
            var heating_pump_control_check = 0;
            //热水水泵控制柜
            var water_pump_control_check = 0;
            //配电柜
            var powe_box_check = 0;
            //保温热水箱
            var hot_water_box_check = 0;
            //热水循环泵
            var hotwater_pump_check = 0;
            //热水板换
            //var water_board_check = 0;
            var hw_chimney_check = 0;
            var hw_powe_box_check = 0;

            var hw_hdys_check= 0;
            $('input[name="check_btn"]:checked').each(function(){
                switch ($(this).val()) {
                    //燃烧机
                    case "burner_check_btn":
                        burner_check = 1;
                        break;
                    //软水器
                    case "hdys_check_btn":
                        hdys_check = 1;
                        break;
                    //水箱
                    case "water_box_check_btn":
                        water_box_check = 1;
                        break;
                    //水箱
                    case "water_box_check_btn_hw":
                        water_box_check_hw = 1;
                        break;
                        //热水
                    case "hdys_check_btn_hw":
                        hw_hdys_check = 1;
                        break;


                    //锅炉循环泵
                    case "pipeline_pump_check_btn":
                        pipeline_pump_check = 1;
                        break;
                    //采暖循环泵
                    // case "heating_pump_check_btn":
                    //     heating_pump_check = 1;
                    //     break;
                    //补水泵
                    // case "water_pump_check_btn":
                    //     water_pump_check = 1;
                    //     break;
                    //除污器
                    // case "dirt_separater_check_btn":
                    //     dirt_separater_check = 1;
                    //     break;
                    //采暖板换
                    // case "board_data_check_btn":
                    //     board_data_check = 1;
                    //     break;
                    //热水板换
                    // case "water_board_check_btn":
                    //     water_board_check = 1;
                    //     break;
                    //钢制烟囱
                    case "chimney_check_btn":
                        chimney_check = 1;
                        break;
                    //分集水器
                    case "diversity_water_check_btn":
                        diversity_water_check = 1;
                        break;
                    //采暖水泵控制柜
                    case "heating_pump_control_check_btn":
                        heating_pump_control_check = 1;
                        break;
                    //热水水泵控制柜
                    case "water_pump_control_check_btn":
                        water_pump_control_check = 1;
                        break;
                    //配电柜
                    case "powe_box_check_btn":
                        powe_box_check = 1;
                        break;
                    //保温热水箱
                    case "hot_water_box_check_btn":
                        hot_water_box_check = 1;
                        break;
                    //热水循环泵
                    case "hotwater_pump_check_btn":
                        hotwater_pump_check = 1;
                        break;
                    case "hw_powe_box_check_btn":
                        hw_powe_box_check = 1;
                        break;
                    case "hw_chimney_check_btn":
                        hw_chimney_check = 1;
                        break;

                }
            });



            //燃烧机
            var heating_burner_id= $('#heating_burner_id').val();
            var heating_burner_name= $('#heating_burner_name').val();
            var heating_burner_count= $('#heating_burner_count').val();
            var heating_burner_context= $('#burner_context').val();
            if(heating_burner_id == undefined){
                heating_burner_id = 0;
                heating_burner_name = '';
                heating_burner_count = 0;
                heating_burner_context = '';
            }


            //软水器
            var heating_hdys_id= $('input[name="heating_hdys"]:checked ').val();
            var heating_hdys_name= $('#heating_hdys_name').val();
            var heating_hdys_count= $('#heating_hdys_count').val();
            var heating_hdys_contxet= $('#hdys_context').val();

            //软水器的计算参数
            var heating_hdys_parameter=$('#heating_hdys_parameter').val();
            if(heating_hdys_id == undefined){
                heating_hdys_id = 0;
                heating_hdys_name = '';
                heating_hdys_count = 0;
                heating_hdys_contxet='';
                //软水器的计算参数
                heating_hdys_parameter = '';
            }


            //水箱
            var heating_water_box_id= $('input[name="heating_water_box"]:checked ').val();
            var heating_water_box_name= $('#heating_water_box_name').val();
            var heating_water_box_count= $('#heating_water_box_count').val();
            var heating_water_box_context= $('#water_box_context').val();
            //水箱的计算参数
            var heating_water_box_parameter= $('#heating_water_box_parameter').val();
            if(heating_water_box_id == undefined){
                heating_water_box_id = 0;
                heating_water_box_name = '';
                heating_water_box_count = 0;
                heating_water_box_context='';
                //水箱的计算参数
                heating_water_box_parameter = '';
            }



            //锅炉循环泵
            var pipeline_pump_id =  $('input[name="pipeline_pump"]:checked ').val();
            var pipeline_pump_name =  $('#pipeline_pump_name').val();
            var pipeline_pump_count =  $('#pipeline_pump_count').val();
            var pipeline_pump_context =  $('#pipeline_pump_context').val();

            //锅炉循环泵的计算参数
            var pipeline_pump_flow =  $('#pipeline_pump_flow').val();
            var pipeline_pump_lift =  $('#pipeline_pump_lift').val();
            var pipeline_pump_parameter = pipeline_pump_flow+"-"+pipeline_pump_lift;
            if(pipeline_pump_id == undefined){
                pipeline_pump_id = 0;
                pipeline_pump_name = '';
                pipeline_pump_count = 0;
                pipeline_pump_context='';
                //锅炉循环泵的计算参数
                pipeline_pump_parameter = '';
            }


            //采暖循环泵
            var length = $(".heating_pump_data").length;
            var heating_pump_id = "";
            var heating_pump_name = "";
            var heating_pump_count = "";

            var heating_pump_context = "";



            //采暖循环泵的计算参数
            var heating_pump_parameter = "";

            var heating_pump_check = "";
            for(i=0;i<length;i++){
                var thisE = $(".heating_pump_data").eq(i);
                var thisid = thisE.parent().parent().find('input[name="heating_pump'+ i +'"]:checked ').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="heating_pump_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                heating_pump_id = heating_pump_id + '||' + thisid;
                heating_pump_name = heating_pump_name + '||' + thisE.find('.heating_pump_name').val();
                heating_pump_count = heating_pump_count + '||' + thisE.find('.heating_pump_count').val();

                heating_pump_check = heating_pump_check + '||' + thisIfCheck;

                //采暖循环泵的计算参数
                var heating_pump_param = thisE.find('.heating_pump_flow ').val()+'-'+thisE.find('.heating_pump_lift ').val();
                heating_pump_parameter = heating_pump_parameter + '||' + heating_pump_param;

                var heating_pump_context_value = $("#heating_pump_context"+i).val();
                heating_pump_context=heating_pump_context + ' ||' +heating_pump_context_value;
            }

            //补水泵
            var length = $(".water_pump_data").length;
            var water_pump_id = "";
            var water_pump_name = "";
            var water_pump_count = "";
            var water_pump_context = "";

            //补水泵的计算参数
            var water_pump_parameter = "";

            var water_pump_check = "";
            for(i=0;i<length;i++){
                var thisE = $(".water_pump_data").eq(i);
                var thisid = thisE.parent().parent().find('input[name="water_pump'+ i +'"]:checked ').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="water_pump_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                water_pump_id = water_pump_id + '||' + thisid;
                water_pump_name = water_pump_name + '||' + thisE.find('.water_pump_name').val();
                water_pump_count = water_pump_count + '||' + thisE.find('.water_pump_count').val();

                water_pump_check = water_pump_check + '||' + thisIfCheck;

                //补水泵的计算参数
                var water_pump_param = thisE.find('.water_pump_flow').val()+'-' + thisE.find('.water_pump_lift').val();
                water_pump_parameter = water_pump_parameter+ '||'+water_pump_param;

                var dynamic_water_pump_context_value=$('#water_pump_context'+i).val()
                water_pump_context= water_pump_context +' ||'+dynamic_water_pump_context_value;
            }





            //除污器
            var length = $(".dirt_separater_data").length;
            var dirt_separater_id = "";
            var dirt_separater_name = "";
            var dirt_separater_count = "";
            var dirt_separater_context = "";

            //除污器的计算参数
            var dirt_separater_parameter = $("#dirt_separater_parameter").val();
            if(dirt_separater_context== 'undefined')
                dirt_separater_context='';
            if(dirt_separater_parameter== 'undefined')
                dirt_separater_parameter='';
            var dirt_separater_check = "";
            for(i=0;i<length;i++){
                var thisE = $(".dirt_separater_data").eq(i);
                var thisid = thisE.find('.dirt_separater_id').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="dirt_separater_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                dirt_separater_id = dirt_separater_id + '||' + thisid;
                dirt_separater_name = dirt_separater_name + '||' + thisE.find('.dirt_separater_name').val();
                dirt_separater_count = dirt_separater_count + '||' + thisE.find('.dirt_separater_count').val();
                dirt_separater_check = dirt_separater_check + '||' + thisIfCheck;

                var dirt_separater_context_value=$("#dirt_separater_context"+i).val();

                dirt_separater_context=dirt_separater_context + ' ||' +dirt_separater_context_value;
            }



            //板换
            var length = $(".board_data").length;
            var board_value = "";
            var board_name = "";
            var board_count = "";
            var board_context = "";


            var board_check = "";
            for(i=0;i<length;i++){
                var thisE = $(".board_data").eq(i);
                var thisIfCheck = thisE.parent().parent().parent().find('input[name="board_data_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                board_value = board_value + '||' + thisE.find('.board_value').html();
                board_name = board_name + '||' + thisE.find('.board_name').val();
                board_count = board_count + '||' + thisE.find('.board_count').val();
                board_check = board_check + '||' + thisIfCheck;
                var board_context_value=$("#board_data_context"+i).val();
                board_context=board_context+ ' ||' +board_context_value;
            }


            //钢制烟囱
            var chimney_height = $('#chimney_height').val();
            if(chimney_height == '' || chimney_height == undefined) chimney_height = 0;
            var chimney_diameter = $('#chimney_diameter').val();
            if(chimney_diameter == undefined) chimney_diameter = 0;
            var chimney_context=$('#chimney_context').val();
            if(chimney_diameter == undefined) chimney_context ='';


            //分集水器
            var diversity_water_num = $('#diversity_water_num').val();
            if(diversity_water_num == '' || diversity_water_num == undefined) diversity_water_num = 0;
            var diversity_water_length = $('#diversity_water_length').val();
            if(diversity_water_length == '' || diversity_water_length == undefined) diversity_water_length = 0;
            var diversity_water_diameter = $('#diversity_water_diameter').val();
            if(diversity_water_diameter == '' || diversity_water_diameter == undefined) diversity_water_diameter = 0;
            var diversity_water_context = $('#diversity_water_context').val();
            if(diversity_water_diameter == '' || diversity_water_diameter == undefined) diversity_water_context ='';






            //水泵控制柜
            var heating_pump_control = $('#heating_pump_control').html();
            if(heating_pump_control == undefined) heating_pump_control = "";
            var heating_pump_control_context = $('#heating_pump_control_context').val();





            //配电柜
            var heating_powe_box = $('#heating_powe_box').html();
            if(heating_powe_box == undefined) heating_powe_box = 0;
            var powe_box_context = $('#powe_box_context').val();

            if(heating_powe_box == undefined) powe_box_context = '';


            //-----------------------------热水-------------------------------
            //燃烧机
            var water_burner_id= $('#water_burner_id').val();
            var water_burner_name= $('#water_burner_name').val();
            var water_burner_count= $('#water_burner_count').val();
            var hw_burner_context= $('#hw_burner_context').val();
            if(water_burner_id == undefined){
                water_burner_id = 0;
                water_burner_name = '';
                water_burner_count = 0;
                hw_burner_context="";
            }

            //软水器
            var water_hdys_id= $('input[name="water_hdys"]:checked ').val();
            var water_hdys_name= $('#water_hdys_name').val();
            var water_hdys_count= $('#water_hdys_count').val();
            var hw_hdys_context= $('#hw_hdys_context').val();

            //软水器的计算参数
            var water_hdys_parameter= $('#water_hdys_parameter').val();

            if(water_hdys_id == undefined){
                water_hdys_id = 0;
                water_hdys_name = '';
                water_hdys_count = 0;
                hw_hdys_context="";
                //软水器的计算参数
                water_hdys_parameter= '';
            }
            //水箱
            var water_water_box_id= $('input[name="water_water_box"]:checked ').val();
            var water_water_box_name= $('#water_water_box_name').val();
            var water_water_box_count= $('#water_water_box_count').val();
            var hw_water_box_context= $('#hw_water_box_context').val();
            //水箱的计算参数
            var water_water_box_parameter= $('#water_water_box_parameter').val();
            if(water_water_box_id == undefined){
                water_water_box_id = 0;
                water_water_box_name = '';
                water_water_box_count = 0;
                hw_water_box_context="";
                //水箱的计算参数
                water_water_box_parameter = '';
            }

            //锅炉循环泵
            var water_pipeline_pump_id =  $('input[name="water_pipeline_pump"]:checked ').val();
            var water_pipeline_pump_name =  $('#water_pipeline_pump_name').val();
            var water_pipeline_pump_count =  $('#water_pipeline_pump_count').val();
            var hw_pipeline_pump_context =  $('#hw_pipeline_pump_context').val();

            //锅炉循环泵的计算参数
            var water_pipeline_pump_flow =  $('#water_pipeline_pump_flow').val();
            var water_pipeline_pump_lift =  $('#water_pipeline_pump_lift').val();
            var water_pipeline_pump_parameter = water_pipeline_pump_flow+"-"+water_pipeline_pump_lift;

            if(water_pipeline_pump_id == undefined){
                water_pipeline_pump_id = 0;
                water_pipeline_pump_name = '';
                water_pipeline_pump_count = 0;
                hw_pipeline_pump_context="";
                //锅炉循环泵的计算参数
                water_pipeline_pump_parameter = '';
            }
            //除污器
            var length = $(".water_dirt_separater_data").length;
            var water_dirt_separater_id = "";
            var water_dirt_separater_name = "";
            var water_dirt_separater_count = "";
            var water_dirt_separater_check = "";
            var hw_dirt_separater_context = $('#hw_dirt_separater_context').val();
            //除污器的计算参数
            var water_dirt_separater_parameter = $("#water_dirt_separater_parameter").val();

            if(dirt_separater_parameter== 'undefined')
                dirt_separater_parameter='';
            //除污器的计算参数

            for(i=0;i<length;i++){
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
                //除污器的计算参数


            }

            //板换
            var length = $(".water_board_data").length;
            var water_board_value = "";
            var water_board_name = "";
            var water_board_count = "";
            var water_board_check = "";
            var hw_water_board_context = '';
            for(i=0;i<length;i++){
                var thisE = $(".water_board_data").eq(i);
                var thisIfCheck = thisE.parent().parent().parent().find('input[name="water_board_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                water_board_value = water_board_value + '||' + thisE.find('.water_board_value').html();
                water_board_name = water_board_name + '||' + thisE.find('.water_board_name').val();
                water_board_count = water_board_count + '||' + thisE.find('.water_board_count').val();
                water_board_check = water_board_check + '||' + thisIfCheck;
                var hw_water_board_context_value=$('#hw_water_board_context'+i).val();

                hw_water_board_context=hw_water_board_context + ' ||' + hw_water_board_context_value;



            }

            //热水循环泵
            var length2 = $(".hotwater_hotwater_pump_data").length;
            var hotwater_pump_id = "";
            var hotwater_pump_name = "";
            var hotwater_pump_count = "";
            var hotwater_pump_check = "";
            var hw_hotwater_pump_context = "";

            //热水循环泵的计算参数
            var hotwater_pump_parameter = "";
            for(i=0;i<length2;i++){
                var thisE = $(".hotwater_hotwater_pump_data").eq(i);
                var thisid = thisE.parent().parent().find('input[name="hotwater_pump'+ i +'"]:checked ').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="hotwater_pump_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                hotwater_pump_id    = hotwater_pump_id + '||' + thisid;
                hotwater_pump_name  = hotwater_pump_name + '||' + thisE.find('.hotwater_pump_name').val();
                hotwater_pump_count = hotwater_pump_count + '||' + thisE.find('.hotwater_pump_count').val();
                hotwater_pump_check = hotwater_pump_check + '||' + thisIfCheck;
                //热水循环泵的计算参数
                var hotwater_pump_param = thisE.find('.hotwater_pump_flow').val()+'-' + thisE.find('.hotwater_pump_lift').val();
                hotwater_pump_parameter = hotwater_pump_parameter+'||'+hotwater_pump_param;

                var hw_hotwater_pump_context_value=$('#hw_hotwater_pump_context'+i).val();;
                hw_hotwater_pump_context=hw_hotwater_pump_context+' ||'+hw_hotwater_pump_context_value;
            }


            //保温热水箱
            //var hot_water_box_id= $('#hot_water_box_id').val();
//            var hot_water_box_id= $('#hot_water_box_parameter').val();
            var hot_water_box_id= "";
            var hot_water_box_name= "";
            var hot_water_box_count= "";
            var hw_hot_water_box_context= "";
            var hot_water_box_parameter= "";
            var hot_water_box_check = "";
            var nums=0;
            <?php if(isset($hotwbnum)){?>
            nums=<?php echo $hotwbnum; ?>;
            <?php }?>
            for(i=0;i<nums;i++){
                var  hw_hot_water_box_id= $('#hw_hot_water_box_value'+i).html();
                hot_water_box_id=hot_water_box_id+'||'+hw_hot_water_box_id;
                var  hw_hot_water_box_name= $('#hot_water_box_name'+i).val();
                hot_water_box_name=hot_water_box_name+'||'+hw_hot_water_box_name;
                var  hw_hot_water_box_count= $('#hot_water_box_count'+i).val();
                hot_water_box_count=hot_water_box_count+'||'+hw_hot_water_box_count;
                var  hot_water_box_context= $('#hw_hot_water_box_context'+i).val();
                hw_hot_water_box_context=hw_hot_water_box_context+' ||'+hot_water_box_context;
                var  hw_hot_water_box_parameter= $('#hot_water_box_parameter'+i).val();
                hot_water_box_parameter=hot_water_box_parameter+'||'+hw_hot_water_box_parameter;
                var  hw_hot_water_box_check=0 ;
                if($('#hot_water_box_check_btn'+i).is(':checked')){
                    var  hw_hot_water_box_check=1 ;
                }

                hot_water_box_check=hot_water_box_check+'||'+hw_hot_water_box_check;

            }

            //补水泵   变频供水泵
            var length = $(".hotwater_water_pump_data").length;
            var hotwater_water_pump_id = "";

            var hotwater_water_pump_name = "";
            var hotwater_water_pump_count = "";
            var hotwater_water_pump_check = "";
            var hw_dynamic_water_pump_context= "";

            //补水泵的计算参数
            var hotwater_water_pump_parameter = "";
            for(i=0;i<length;i++){
                var thisE = $(".hotwater_water_pump_data").eq(i);
                var thisid = thisE.parent().parent().find('input[name="hotwater_water_pump'+ i +'"]:checked ').val();
                if(thisid == undefined)  thisid = 0;
                var thisIfCheck = thisE.parent().parent().find('input[name="hotwater_water_pump_check"]:checked ').val();
                if(thisIfCheck == undefined) {
                    thisIfCheck = 0;
                }else{
                    thisIfCheck = 1;
                }
                hotwater_water_pump_id    = hotwater_water_pump_id + '||' + thisid;
                hotwater_water_pump_name  = hotwater_water_pump_name + '||' + thisE.find('.hotwater_water_pump_name').val();
                hotwater_water_pump_count = hotwater_water_pump_count + '||' + thisE.find('.hotwater_water_pump_count').val();
                hotwater_water_pump_check = hotwater_water_pump_check + '||' + thisIfCheck;
                //补水泵的计算参数
                var hotwater_water_pump_param = thisE.find('.hotwater_water_pump_flow').val()+'-'+ thisE.find('.hotwater_water_pump_lift').val();
                hotwater_water_pump_parameter = hotwater_water_pump_parameter+'||'+hotwater_water_pump_param;

                var dynamic_water_pump_context_value=$('#hw_dynamic_water_pump_context'+i).val()
                hw_dynamic_water_pump_context=hw_dynamic_water_pump_context+' ||'+dynamic_water_pump_context_value;
            }





            var chimney_height2 = $('#chimney_height2').val();
            if(chimney_height2 == undefined) chimney_height2 = 0;
            var chimney_diameter2 = $('#chimney_diameter2').val();
            if(chimney_diameter2 == undefined) chimney_diameter2 = 0;
            var hw_chimney_context = $('#hw_chimney_context').val();
            if(chimney_diameter2 == undefined) chimney_diameter2 = 0;

            var water_pump_control = $('#water_pump_control').html();
            if(water_pump_control == undefined) water_pump_control = "";
            var hw_water_pump_control_context= $('#hw_water_pump_control_context').val();

            var water_powe_box = $('#water_powe_box').html();
            if(water_powe_box == undefined) water_powe_box = 0;
            var hw_powe_box_context = $('#hw_powe_box_context').val();
            if(water_powe_box == undefined) water_powe_box = 0;

            var remark = $('#guolu_context').val();
            var index = layer.load(0, {shade: false});

            $.ajax({
                type        : 'POST',
                data        : {
                    id : <?php echo $id;?>,
                    heating : '<?php echo $heating_type;?>',
                    hotwater : '<?php echo $hotwater_type;?>',
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
                    water_pump_check:water_pump_check,
                    board_count : board_count,
                    board_check:board_check,
                    board_value : board_value,
                    board_name : board_name,
                    chimney_height : chimney_height,
                    chimney_diameter : chimney_diameter,
                    heating_pump_control : heating_pump_control,
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
                    water_dirt_separater_check:water_dirt_separater_check,
                    water_pipeline_pump_id    : water_pipeline_pump_id,
                    water_pipeline_pump_name  : water_pipeline_pump_name,
                    water_pipeline_pump_count : water_pipeline_pump_count,
                    hotwater_pump_id    : hotwater_pump_id,
                    hotwater_pump_name  : hotwater_pump_name,
                    hotwater_pump_count : hotwater_pump_count,
                    hotwater_water_pump_id    : hotwater_water_pump_id,
                    hotwater_water_pump_name  : hotwater_water_pump_name,
                    hotwater_water_pump_count : hotwater_water_pump_count,
                    hotwater_water_pump_check:hotwater_water_pump_check,
                    chimney_height2 : chimney_height2,
                    chimney_diameter2 : chimney_diameter2,
                    water_pump_control : water_pump_control,
                    water_powe_box : water_powe_box,
                    water_box_check_hw:water_box_check_hw,
                    remark : remark,
                    application : application,
                    burner_check:burner_check,
                    hdys_check:hdys_check,
                    water_box_check:water_box_check,
                    pipeline_pump_check:pipeline_pump_check,
                    chimney_check:chimney_check,
                    diversity_water_check:diversity_water_check,
                    heating_pump_control_check:heating_pump_control_check,
                    water_pump_control_check:water_pump_control_check,
                    powe_box_check:powe_box_check,
                    hot_water_box_check:hot_water_box_check,
                    hotwater_pump_check:hotwater_pump_check,
                    //取暖备注
                    heating_burner_context:heating_burner_context,
                    heating_hdys_contxet:heating_hdys_contxet,
                    heating_water_box_context:heating_water_box_context,
                    pipeline_pump_context:pipeline_pump_context,
                    heating_pump_context:heating_pump_context,
                    water_pump_context:water_pump_context,
                    dirt_separater_context:dirt_separater_context,
                    board_context:board_context,
                    chimney_context:chimney_context,
                    diversity_water_context:diversity_water_context,
                    heating_pump_control_context:heating_pump_control_context,
                    powe_box_context:powe_box_context,
                    //热水备注
                    hw_burner_context:hw_burner_context,
                    hw_hdys_context:hw_hdys_context,
                    hw_water_box_context:hw_water_box_context,
                    hw_pipeline_pump_context:hw_pipeline_pump_context,
                    hw_dirt_separater_context:hw_dirt_separater_context,
                    hw_water_board_context:hw_water_board_context,
                    hw_water_pump_control_context:hw_water_pump_control_context,
                    hw_chimney_context:hw_chimney_context,
                    hw_powe_box_context:hw_powe_box_context,
                    hw_hot_water_box_context:hw_hot_water_box_context,
                    hw_hotwater_pump_context:hw_hotwater_pump_context,
                    hw_dynamic_water_pump_context:hw_dynamic_water_pump_context,
                    hw_chimney_check:hw_chimney_check,
                    hw_powe_box_check:hw_powe_box_check,
                    hw_hdys_check:hw_hdys_check,

                    //----------------计算参数-------------
                    //采暖
                    heating_hdys_parameter : heating_hdys_parameter,
                    heating_water_box_parameter : heating_water_box_parameter,
                    dirt_separater_parameter : dirt_separater_parameter,
                    pipeline_pump_parameter : pipeline_pump_parameter,
                    heating_pump_parameter : heating_pump_parameter,
                    water_pump_parameter : water_pump_parameter,
                    //热水
                    water_hdys_parameter : water_hdys_parameter,
                    water_water_box_parameter : water_water_box_parameter,
                    hot_water_box_parameter : hot_water_box_parameter,
                    water_dirt_separater_parameter : water_dirt_separater_parameter,
                    water_pipeline_pump_parameter : water_pipeline_pump_parameter,
                    hotwater_pump_parameter : hotwater_pump_parameter,
                    hotwater_water_pump_parameter : hotwater_water_pump_parameter


                },
                dataType :    'json',
                url :         'selection_do.php?act=fuji_selected',
                success :     function(data){
//                    alert(data);

                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            ///layer.alert(msg, {icon: 6,shade: false}, function(index){
                            location.href = 'selection_plan_one.php?id=<?php echo $id;?>';
                            // });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });
    })
    function num(e) {

        if(e<=0)
        {
            $('#diameter').css('display','none');
            $('#len').css('display','none');
        }
        else
        {
            $('#diameter').css('display','block');
            $('#len').css('display','block');
        }

    }
    function num2(e) {
        if(e<=0)
        {
            $('#diameter2').css('display','none');
            $('#len2').css('display','none');
        }
        else
        {
            $('#diameter2').css('display','block');
            $('#len2').css('display','block');
        }
    }

    function hdys_detail(thisid){
        layer.open({
            type: 2,
            title: '全自动软水器详情',
            shadeClose: true,
            shade: 0.3,
            area: ['500px', '200px'],
            content: 'hdys_info.php?id='+thisid
        });
    }
    function water_box_detail(thisid){
        layer.open({
            type: 2,
            title: '水箱详情',
            shadeClose: true,
            shade: 0.3,
            area: ['600px', '300px'],
            content: 'water_box_detail.php?id='+thisid
        });
    }
    function dirt_separator_detail(thisid){
        layer.open({
            type: 2,
            title: '除污器详情',
            shadeClose: true,
            shade: 0.3,
            area: ['600px', '200px'],
            content: 'dirt_separator_detail.php?id='+thisid
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
        layer.open({
            type: 2,
            title: '管道泵详情',
            shadeClose: true,
            shade: 0.3,
            area: ['800px', '350px'],
            content: 'pipeline_info.php?id='+thisid
        });
    }
    function syswater_pump_detail(thisid){
        layer.open({
            type: 2,
            title: '系统补水泵详情',
            shadeClose: true,
            shade: 0.3,
            area: ['800px', '350px'],
            content: 'syswater_pump_info.php?id='+thisid
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
</body>
</html>