<?php

/**
 * 选型-辅机选择
 *
 * @version       v0.01
 * @create time   2018/7/25
 * @update time
 * @author        yjl
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */


require_once('../../init.php');
error_reporting(0);
// ini_set('display_errors',1);            //错误信息
 // ini_set('display_startup_errors',1);    //php启动错误信息
 // error_reporting(-1);                    //打印出所有的 错误信息

//测试用例
//$json = '[{
//	"guolu": {
//		"guolu_id": 3,
//		"guolu_hauled_weight": 700,
//		"guolu_iowater_interface": 40,
//		"guolu_hot_flow": 600,
//		"guolu_outwater_t": 90,
//		"guolu_inwater_t": 40,
//		"count": 2
//	},
//	"floor": {
//		"0": {
//			"0": 13,
//			"1": 14
//		},
//		"1": {
//			"0": 8,
//			"1": 9
//		}
//	},
//	"floor_height": {
//		"0": 7,
//		"1": 9
//	},
//	"is_condensate": 1,
//	"heating_area": 100,
//	"total_exchange_Q": {
//	   "heating" : 7000,
//	   "water"   : 0
//	  },
//	"heating_type": 1,
//	"location": 1,
//	"guolu_use": 1,
//	"application": 2
//}]';
//$json = '{"guolu":{"guolu_id":"19","guolu_ratedpower":"2800","guolu_iowater_interface":"150","guolu_hot_flow":"241","guolu_outwater_t":"85","guolu_inwater_t":"65","count":"2"},"floor":[["12","12"]],"floor_height":["3.00"],"total_exchange_Q":{"heating":[0.52],"water":[1522.2291319444],"use_water":120},"is_condensate":"15","is_lownitrogen":"17","location":"0","guolu_use":0,"heating_type":["0"],"application":"3","area":["0"]}';

$json            = json_decode($_POST['jsonstr'],true);//接收json数据
//$json            = json_decode($json, true);
//file_put_contents('fuji_var_in.html',var_export($json, true));
$guolu           = $json['guolu'];
$floor           = $json['floor'];                       //每个分区的楼层数组,$floor['0']为第一个分区的楼层数据，$floor['0']['0']为低楼层
$floor_height    = $json['floor_height'];                //楼层高度数组，$floor_height['0']为第一个分区的楼层高度
$floor_hw           = $json['floor_hw'];                       //每个分区的楼层数组,$floor['0']为第一个分区的楼层数据，$floor['0']['0']为低楼层
$floor_height_hw    = $json['floor_height_hw'];                //楼层高度数组，$floor_height['0']为第一个分区的楼层高度
$total_exchange_Q= $json['total_exchange_Q'];            //每个分区的换热量以及用水量
//$heating_area    = safeCheck($json['heating_area']);    //总的采暖面积,热水用
$heating_types   = $json['heating_type'];                //各个分区的采暖形式 0 暖气 1 地暖 2 风机盘管
$location        = safeCheck($json['location']);         //锅炉位置
$is_condensate   = safeCheck($json['is_condensate']);    //锅炉是否冷凝，0 不是 1 是
$guolu_use       = safeCheck($json['guolu_use']);        //锅炉用途，0 采暖， 1 热水， 2 采暖+热水
$application     = safeCheck($json['application']);      //锅炉应用形式
$area            = $json['area'];                       //分区数组


//先取出最高楼层及其高度，同时计算总的楼层高度，后面采暖用途用到
$max_floor = 0;
$max_height = 0;
$floors_height = 0;
$floors_height_area = array();

$j=0;//得到分区数
foreach($floor as $key => $value){
    if($value[1] > $max_floor){
        $max_floor = $value[1];
        $max_height = $floor_height[$key];
    }
    $floors_height += ($value[1] - $value[0] + 1) * $floor_height[$key];//高楼层-低楼层+1 乘以 楼层高度
    $floors_height_area[$j]=$floors_height;
    $j++;
}


$max_floor_hw = 0;
$max_height_hw = 0;
$floors_height_hw = 0;
$t=0;
$floors_height_area_hw = array();
foreach($floor_hw as $key => $value){
    if($value[1] > $max_floor_hw){
        $max_floor_hw = $value[1];
        $max_height_hw = $floor_height_hw[$key];
    }
    $floors_height_hw += ($value[1] - $value[0] + 1) * $floor_height_hw[$key];//高楼层-低楼层+1 乘以 楼层高度
    $floors_height_area_hw=$floors_height_hw;
        $t++;
}

$guolu_info = Guolu_attr::getInfoById($guolu['guolu_id']);

$html = '<h2>辅机选型中间参数值：</h2><br/>'.
    '<p>分区最高楼层：';
$html .= $max_floor.'</p>';
$html .= '<h3>输入的数据：</h3>';
$html .= '<p>锅炉id:'.$guolu['guolu_id'].'</p>';
$html .= '<p>锅炉额定功率:'.$guolu['guolu_ratedpower'].'</p>';
$html .= '<p>锅炉数量:'.$guolu['count'].'</p>';
$html .= '<p>锅炉接口管径:'.$guolu['guolu_iowater_interface'].'</p>';
$html .= '<p>锅炉热水流量:'.$guolu['guolu_hot_flow'].'</p>';
$html .= '<p>锅炉出水温度:'.$guolu['guolu_outwater_t'].'</p>';
$html .= '<p>锅炉进水温度:'.$guolu['guolu_inwater_t'].'</p>';
$html .= '<p>锅炉用途（0,采暖 1,热水）:'.$guolu_use.'</p>';
$html .= '<p>锅炉应用形式:'.$application.':'.$ARRAY_selection_application[$guolu_use]['type'][$application].'</p>';

//----选辅机start

$guolu_power = $guolu['guolu_ratedpower'] * $guolu['count'];//计算锅炉总功率

$fuji_res = array(
    'burner'         => array(),        //燃烧机
    'hdys'           => array(array()), //.全自动软水机
    'water_box'      => array(array()), //水箱
    'pipeline_pump'  => array(array()), //.锅炉循环泵
    'heating_pump'   => array(array()), //采暖循环泵
    'water_pump'     => array(array()), //补水泵
    'dirt_separater' => array(array()), //除污器
    'board'          => array(array()),  //板换
    'hot_water_box'  => array(array()),  //.不锈钢保温水箱 热水用途时需返回
//    'pump_control'   => $pump_control, //水泵控制柜
//    'power_box'      => $power_box,    //配电柜功率
);
if($guolu_use == 0 ){//采暖选择辅机
    if($application == 1){//承压热水锅炉+承压系统start
        //1.根据锅炉id获取燃烧机数据
        $html .= '<h3>计算出的数据（id后数据为空说明数据库中没有相应的设备）：</h3>';
        if($guolu_info['vender'] != 43){
            $burner = Burner_attr::getInfoByGuoluid($guolu['guolu_id']);
            $burner['count'] = 1;
            $html .= '<p>燃烧机id:'.$burner['id'].'</p>';
            $burner['name'] = '燃气燃烧机';
            $fuji_res['burner'] = $burner;
        }else{
            $html .= '<p>音诺伟森锅炉不需要燃烧器</p>';
        }

        //2.根据锅炉功率获取全自动软水机数据
        $outwater = round($guolu_power/700, 1); //自动软水机出水量计算结果，锅炉功率/700对应自动软水机出水量
        $hdys = Hdys_attr::getInfoByWeight($outwater);
        $html .= '<b>根据锅炉总功率:'.$guolu['guolu_ratedpower'].' * '. $guolu['count'].' = '.$guolu_power.'/700 获取全自动软水机</b><br/>';
        if(!empty($hdys)){
            foreach ($hdys as $h){
                $html .= '<p>全自动软水机id:'.$h['id'].'</p>';
                $html .= '<p>全自动软水机出水量:'.$h['outwater'].'</p><br/>';
            }
        }
        $hdys['count'] = 1;
        $hdys['outwater'] = $outwater;//自动软水机出水量计算结果
        $hdys['name'] = '全自动软水机';
        $fuji_res['hdys'] = $hdys;
        //3.根据锅炉电功率转换成的水箱公称容积获取水箱数据
        $capacity = $guolu_power/700;
        $water_box = Water_box_attr::getInfoByCapacity($capacity);
        $html .= '<b>根据功率转化成的公称容积:'.$guolu_power.'/700 获取软化水箱</b><br/>';
        if(!empty($water_box)){
            foreach ($water_box as $box){
                $html .= '<p>水箱id:'.$box['id'].'</p>';
                $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
            }
        }
        $water_box['count'] = 1;
        $water_box['name'] = '软化水箱';
        $water_box['capacity'] = $capacity;//公称容积计算结果
        $fuji_res['water_box'] = $water_box;
        //4.根据流量和扬程获取采暖循环泵数据
        $total_exchange_Q2 = $guolu_power; //额定供热量
        $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
        $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
        $lift1 = 32;
        $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
        $pumparr1 = $pump1;
        $pump1['count'] = 2;
        $html .= '<b>采暖循环泵:</b><br/><p>流量:'.$flow1.'</p>';
        $html .= '<p>扬程:'.$lift1.'</p>';
        $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
        if($pumparr1){
            foreach ($pumparr1 as $pump){
                if(isset($pump['id'])){
                    $html .= '<p>循环泵id:'.$pump['id'].'</p>';
                }
            }
        }
        $pump1['name'] = '采暖循环泵';
        $pump1['lift'] = $lift1;
        $pump1['flow'] = $flow1;
        $fuji_res['heating_pump'][] = $pump1;
        //5.计算直径和获取除污器数据
        if($guolu['count'] == 1){
            $DN = $guolu['guolu_iowater_interface'];
            if($max_floor >= 15){
                $flag = 0;
                foreach ($ARRAY_pipe_DN as $key => $value){
                    if($key < $DN) continue;
                    else{
                        if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                            $flag = 1;
                            continue;
                        }
                        $DN = $key;  //65
                        break;
                    }
                }
            }
        }else{
            $r = $ARRAY_pipe_DN[ $guolu['guolu_iowater_interface'] ][0]/2;//半径  mm
            $s = 3.14 * $r * $r * $guolu['count'];//总截面面积
            $DN = round(sqrt($s/3.14) * 2, 2 );//总管道直径,保留两位小数  mm
            //$DN遍历钢管公称直径数据，让$DN向上取最接近的直径
            $flag = 0;
            foreach ($ARRAY_pipe_DN as $key => $value){
                if($key < $DN) continue;
                else{
                    if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                        $flag = 1;
                        continue;
                    }
                    $DN = $key;  //65
                    break;
                }
            }
        }
        $dirt_separater = Dirt_separator_attr::getInfoByDN($DN);
        $dirt_separater['count'] = 1;
        $dirt_separater['DN'] = $DN; //DN计算结果
        $html .= '<b>除污器 DN:' . $DN . '</b><br/>';
        $html .= '<b>除污器 id:' . $dirt_separater['id'] . '</b><br/>';
        $dirt_separater['name'] = '除污器';
        $fuji_res['dirt_separater'][0] = $dirt_separater;


        //6.根据流量和扬程获取系统补水泵数据，如果需要的话
        $pump2 = array();
        $lift2 = 0;
        if($location == 0 or $location == 1){//锅炉位于地面或地下室需要补水泵
            $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
            //$lift2 = ($floors_height - $pump_location) * 1.2 +32;//
            $lift2 = ($floors_height - $pump_location) * 1.2 ;
            $flow2 = $flow1 * 0.05;    //
            $pump2 = Syswater_pump_attr::getInfoByFlowLift($flow2, $lift2);
            $pumparr2 = $pump2;
            $pump2['count'] = 2;
            $html .= '<br/><b>系统补水泵:</b><br/><p>流量:' . $flow2 . '</p>';
            $html .= '<p>扬程:' . $lift2 . '</p>';
            if($pumparr2){
                foreach ($pumparr2 as $pump){
                    if(isset($pump['id'])){
                        $html .= '<p>补水泵id:' . $pump['id'] . '</p>';
                    }
                }
            }

            $pump2['name'] = '系统补水泵';
            $pump2['lift'] = $lift2;
            $pump2['flow'] = $flow2;

            $fuji_res['water_pump'] = $pump2;
        }else{
            $html .= '系统位于楼顶或裙楼，不需要补水泵';
        }
//        //水泵控制柜功率
//        $pump2_power = isset($pump2['motorpower'])?$pump2['motorpower']:0;
//        $pump_control['power'] = 2 * $pump1[0]['motorpower'] + 2 * $pump2_power;
//        $pump_control['count'] = 1;
//        $html .= '<b>水泵控制柜功率:'.$pump_control['power'] .'</b><br/>';
//        //配电柜
//        $power_box = $burner['power'] + 2 * $pump_control['power'];
//        $html .= '<b>配电柜功率:'.$power_box .'</b><br/>';

    //承压热水锅炉+承压系统end

    }elseif($application == 2){  //常压锅炉+常压系统start
        //1.根据锅炉id获取燃烧机数据
        $html .= '<h3>计算出的数据（id后数据为空说明数据库中没有相应的设备）：</h3>';
        if($guolu_info['vender'] != 43){
            $burner = Burner_attr::getInfoByGuoluid($guolu['guolu_id']);
            $burner['count'] = 1;
            $html .= '<p>燃烧机id:'.$burner['id'].'</p>';
            $burner['name'] = '燃气燃烧机';
            $fuji_res['burner'] = $burner;
        }else{
            $html .= '<p>音诺伟森锅炉不需要燃烧器</p>';
        }
        //2.根据锅炉功率获取全自动软水机数据
        $outwater = round($guolu_power/700, 1); //自动软水机出水量计算结果，锅炉功率/700对应自动软水机出水量
        $hdys = Hdys_attr::getInfoByWeight($outwater);//锅炉功率/700对应自动软水机出水量
        $html .= '<b>根据锅炉总功率:'.$guolu['guolu_ratedpower'].' * '. $guolu['count'].' = '.$guolu_power.'/700 获取全自动软水机</b><br/>';
        if(!empty($hdys)){
            foreach ($hdys as $h){
                $html .= '<p>全自动软水机id:'.$h['id'].'</p>';
                $html .= '<p>全自动软水机出水量:'.$h['outwater'].'</p><br/>';
            }
        }
        $hdys['count'] = 1;
        $hdys['outwater'] = $outwater;
        $hdys['name'] = '全自动软水机';
        $fuji_res['hdys'] = $hdys;

        //3.根据锅炉电功率转换成的水箱公称容积获取膨胀水箱数据 ---- 待定

        //先计算膨胀水箱的公称容积
        if($guolu_power >= 120 && $guolu_power <= 2800 ){
            $capacity = 0.5;
        }elseif($guolu_power >= 3500 && $guolu_power <= 7000){
            $capacity = 1;
        }elseif($guolu_power > 7000 && $guolu_power <= 10500){
            $capacity = 2;
        }elseif($guolu_power > 10500 && $guolu_power <= 14000){
            $capacity = 3;
        }

        $water_box = Water_box_attr::getInfoByCapacity($capacity);
        $html .= '<b>根据锅炉功率对应的公称容积:'.$capacity.' 获取膨胀水箱</b><br/>';
        if(!empty($water_box)){
            foreach ($water_box as $box){
                $html .= '<p>水箱id:'.$box['id'].'</p>';
                $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
            }
        }
        $water_box['count'] = 1;
        $water_box['name'] = '膨胀水箱';
        $water_box['capacity'] = $capacity;
        $fuji_res['water_box'] = $water_box;

//        //4.根据流量和扬程获取锅炉循环泵数据
//        $total_exchange_Q2 = $guolu_power; //锅炉热功率
//        $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
//        $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
//        $lift1 = 32;
//        $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
//        $pumppr = $pump1;
//        $pump1['count'] = 2;
//        $pump1['name'] = '锅炉循环泵';
//        $pump1['lift'] = $lift1;
//        $pump1['flow'] = $flow1;
//        $fuji_res['pipeline_pump'] = $pump1;
//        $html .= '<b>锅炉循环泵:</b><br/><p>流量:'.$flow1.'</p>';
//        $html .= '<p>扬程:'.$lift1.'</p>';
//        $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
//        if($pumppr){
//            foreach ($pumppr as $pumpinfo){
//                if(isset($pumpinfo['id'])){
//                    $html .= '<p>循环泵id:'.$pumpinfo['id'].'</p>';
//                }
//            }
//        }

        //4.根据流量和扬程获取锅炉循环泵数据，如果需要的话
        $pump1 = array();
        $lift1 = 0;
        if($location == 0 or $location == 1){//锅炉位于地面或地下室需要补水泵
            $total_exchange_Q2 = $guolu_power; //锅炉热功率
            $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
            $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
            $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
            //update -
            $lift1 = ($floors_height + $pump_location) * 1.2 +32;//

            $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
            $pumppr = $pump1;
            $pump1['count'] = 2;
            $pump1['name'] = '锅炉循环泵';
            $pump1['lift'] = $lift1;
            $pump1['flow'] = $flow1;
            $fuji_res['pipeline_pump'] = $pump1;
            $html .= '<b>锅炉循环泵:</b><br/><p>流量:'.$flow1.'</p>';
            $html .= '<p>扬程:'.$floors_height. ' - '.$pump_location.' *1.2+32 = '.$lift1.'</p>';
            $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
            if($pumppr){
                foreach ($pumppr as $pumpinfo){
                    if(isset($pumpinfo['id'])){
                        $html .= '<p>循环泵id:'.$pumpinfo['id'].'</p>';
                    }
                }
            }
        }else{
            $html .= '系统位于楼顶或裙楼，不需要锅炉循环泵';
        }

        //5.计算直径和获取除污器数据
        if($guolu['count'] == 1){
            $DN = $guolu['guolu_iowater_interface'];
            if($max_floor >= 15){
                $flag = 0;
                foreach ($ARRAY_pipe_DN as $key => $value){
                    if($key < $DN) continue;
                    else{
                        if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                            $flag = 1;
                            continue;
                        }
                        $DN = $key;  //65
                        break;
                    }
                }
            }
        }else{
            $r = $ARRAY_pipe_DN[ $guolu['guolu_iowater_interface'] ][0]/2;//半径  mm
            $s = 3.14 * $r * $r * $guolu['count'];//总截面面积
            $DN = round(sqrt($s/3.14) * 2, 2 );//总管道直径,保留两位小数  mm
            //$DN遍历钢管公称直径数据，让$DN向上取最接近的直径
            $flag = 0;
            foreach ($ARRAY_pipe_DN as $key => $value){
                if($key < $DN) continue;
                else{
                    if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                        $flag = 1;
                        continue;
                    }
                    $DN = $key;  //65
                    break;
                }
            }
        }
        $dirt_separater = Dirt_separator_attr::getInfoByDN($DN);
        $dirt_separater['count'] = 1;
        $dirt_separater['DN'] = $DN;
        $dirt_separater['name'] = '除污器';
        $fuji_res['dirt_separater'][0] = $dirt_separater;

        $html .= '<b>除污器 DN:' . $DN . '</b><br/>';
        $html .= '<b>除污器 id:' . $dirt_separater['id'] . '</b><br/>';


    //常压锅炉+常压系统end

    }elseif($application == 3){//常压锅炉+板换+承压系统start
        //1.根据锅炉id获取燃烧机数据

        $html .= '<h3>计算出的数据（id后数据为空说明数据库中没有相应的设备）：</h3>';
        if($guolu_info['vender'] != 43){
            $burner = Burner_attr::getInfoByGuoluid($guolu['guolu_id']);
            $burner['count'] = 1;
            $html .= '<p>燃烧机id:'.$burner['id'].'</p>';
            $burner['name'] = '燃气燃烧机';
            $fuji_res['burner'] = $burner;
        }else{
            $html .= '<p>音诺伟森锅炉不需要燃烧器</p>';
        }
        //2.根据锅炉功率获取全自动软水机数据
        $outwater = round($guolu_power/700, 1); //自动软水机出水量计算结果，锅炉功率/700对应自动软水机出水量
        $hdys = Hdys_attr::getInfoByWeight($outwater);//锅炉功率/700对应自动软水机出水量
        $html .= '<b>根据锅炉总功率:'.$guolu['guolu_ratedpower'].' * '. $guolu['count'].' = '.$guolu_power.'/700 获取全自动软水机</b><br/>';
        if(!empty($hdys)){
            foreach ($hdys as $h){
                $html .= '<p>全自动软水机id:'.$h['id'].'</p>';
                $html .= '<p>全自动软水机出水量:'.$h['outwater'].'</p><br/>';
            }
        }
        $hdys['count'] = 1;
        $hdys['outwater'] = $outwater;
        $hdys['name'] = '全自动软水机';
        $fuji_res['hdys'] = $hdys;

        //3.根据锅炉电功率转换成的水箱公称容积获取膨胀水箱数据 ---- 待定

        //先计算膨胀水箱的公称容积
        if($guolu_power >= 120 && $guolu_power <= 2800 ){
            $capacity = 0.5;
        }elseif($guolu_power >= 3500 && $guolu_power <= 7000){
            $capacity = 1;
        }elseif($guolu_power > 7000 && $guolu_power <= 10500){
            $capacity = 2;
        }elseif($guolu_power > 10500 && $guolu_power <= 14000){
            $capacity = 3;
        }

        $water_box = Water_box_attr::getInfoByCapacity($capacity);
        $html .= '<b>根据锅炉功率对应的公称容积:'.$capacity.' 获取膨胀水箱</b><br/>';
        if(!empty($water_box)){
            foreach ($water_box as $box){
                $html .= '<p>水箱id:'.$box['id'].'</p>';
                $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
            }
        }
        $water_box['count'] = 1;
        $water_box['name'] = '膨胀水箱';
        $water_box['capacity'] = $capacity;
        $fuji_res['water_box'] = $water_box;

        //4.根据流量和扬程获取一次侧循环泵数据
        $total_exchange_Q2 = $guolu_power; //锅炉热功率
        //$tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
        $tem_int = 90-70;//供水温度差 锅炉循环泵固定90/70；其它水泵，暖气为80/60，地暖和风机盘管为60/50

        $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
        $lift1 = 20;
        $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
        $pumppr1 = $pump1;
        $pump1['count'] = 2;
        $pump1['name'] = '一次侧循环泵';
        $pump1['lift'] = $lift1;
        $pump1['flow'] = $flow1;
        $fuji_res['pipeline_pump'] = $pump1;
        $html .= '<b>一次侧循环泵:</b><br/><p>流量:'.$flow1.'</p>';
        $html .= '<p>扬程:'.$lift1.'</p>';
        $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
        if($pumppr1){
            foreach ($pumppr1 as $pump){
                if(isset($pump['id'])){
                    $html .= '<p>循环泵id:'.$pump['id'].'</p>';
                }
            }
        }

        //5.计算直径和获取除污器数据
        if($guolu['count'] == 1){
            $DN = $guolu['guolu_iowater_interface'];
            if($max_floor >= 15){
                $flag = 0;
                foreach ($ARRAY_pipe_DN as $key => $value){
                    if($key < $DN) continue;
                    else{
                        if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                            $flag = 1;
                            continue;
                        }
                        $DN = $key;  //65
                        break;
                    }
                }
            }
        }else{
            $r = $ARRAY_pipe_DN[ $guolu['guolu_iowater_interface'] ][0]/2;//半径  mm
            $s = 3.14 * $r * $r * $guolu['count'];//总截面面积
            $DN = round(sqrt($s/3.14) * 2, 2 );//总管道直径,保留两位小数  mm
            //$DN遍历钢管公称直径数据，让$DN向上取最接近的直径
            $flag = 0;
            foreach ($ARRAY_pipe_DN as $key => $value){
                if($key < $DN) continue;
                else{
                    if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                        $flag = 1;
                        continue;
                    }
                    $DN = $key;  //65
                    break;
                }
            }
        }
        $dirt_separater = Dirt_separator_attr::getInfoByDN($DN);
        $dirt_separater['count'] = 1;
        $dirt_separater['DN'] = $DN;
        $dirt_separater['name'] = '锅炉除污器';
        $fuji_res['dirt_separater'][0] = $dirt_separater;

        $html .= '<b>锅炉除污器 DN:' . $DN . '</b><br/>';
        $html .= '<b>锅炉除污器 id:' . $dirt_separater['id'] . '</b><br/>';


        for($i=0; $i<count($area); $i++){
            $total_lift = 0; //总扬程，用于计算板换承压
            $html .= '<br/><b>分区' . ($i + 1) . ':</b>';
            if($heating_types[$i] == 0 ){//采暖形式--暖气
                $tem_int = 20;
            }else{//供水温度差 锅炉循环泵固定90/70；其它水泵，暖气为80/60，地暖和风机盘管为60/50
                $tem_int = 10;
            }
            $flow_h = round($total_exchange_Q['heating'][$i]/700 * 600/$tem_int, 2);
            $lift_h = 32;
            $pump_h = Pipeline_attr::getInfoByFlowLift($flow_h, $lift_h);
            if(!empty($pump1)){
                $pump_h['name'] = '分区'.($i+1).'采暖循环泵';
                $pump_h['count'] = 2;
                $pump_h['lift'] = $lift_h;
                $pump_h['flow'] = $flow_h;
                $fuji_res['heating_pump'][$i] = $pump_h;

                $num = 0;
                foreach ($pump_h as $pump){
                    if(isset($pump['id'])){
                        if($num == 0){//能匹配到循环泵，则累加1次该扬程
                            $total_lift += $lift_h;
                        }
                        $num ++;
                        $html .= '<p>循环泵id:'.$pump['id'].'</p>';
                    }


                }
            }
            $html .= '<b>采暖循环泵:</b><br/><p>流量:'.$flow_h.'</p>';
            $html .= '<p>扬程:'.$lift_h.'</p>';
            $html .= '<p>额定供热量:'.$total_exchange_Q['heating'][$i].'kw</p>';

            //6.根据流量和扬程获取系统补水泵数据，如果需要的话
            $pump2 = array();
            $lift2 = 0;
            $flow2 = 0;
            if($location == 0 or $location == 1){//锅炉位于地面或地下室需要补水泵
                $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
                $lift2 = ($floors_height_area[$i] + $pump_location) * 1.2;//
                $flow2 = $flow_h * 0.05;    //
                $pump2 = Syswater_pump_attr::getInfoByFlowLift($flow2, $lift2);
                $pump2['name'] = '分区'.($i+1).'补水泵';
                if(!empty($pump2)){

                    $html .= '<br/><b>系统补水泵:</b><br/><p>流量:' . $flow2 . '</p>';
                    $html .= '<p>扬程:' . $lift2 . '</p>';
                    foreach ($pump2 as $pump){
                        $num = 0;
                        if(isset($pump['id'])){
                            if($num == 0){//能匹配到补水泵，则累加1次该扬程
                                $total_lift += $lift2;
                            }
                            $num++;
                            $html .= '<p>补水泵id:'.$pump['id'].'</p>';
                        }
                    }
                }
                $pump2['count'] = 2;
                $pump2['lift'] = $lift2;
                $pump2['flow'] = $flow2;
            }else{
                $html .= '系统位于楼顶或裙楼，不需要补水泵';
            }

            $fuji_res['water_pump'][$i] = $pump2;
            //计算板换数据
            $heating_sys_t = $ARRAY_system_tem[ $heating_types[$i] ][0].'/'.$ARRAY_system_tem[$heating_types[$i] ][1];//确定采暖系统温度
            if($is_condensate == 1)//判断是否是冷凝锅炉，以冷凝为主
                $once_sarwt = '80/60';//一次侧供回水温度=锅炉供回水水温, supply and return water temperature
            else
                $once_sarwt = $guolu['guolu_outwater_t'].'/'.$guolu['guolu_inwater_t'];
            $twice_sarwt = $heating_sys_t;//二次侧供回水温度=采暖系统温度

//            $pressure_bearing = $total_lift/100;//承压 MPa
            $pressure_bearing = 1.6;//承压 MPa

            $exchange_Q = $total_exchange_Q['heating'][$i];

            $board[$i] = array(
                'heating_sys_t' =>$heating_sys_t,
                'once_sarwt'  => $once_sarwt,
                'twice_sarwt' => $twice_sarwt,
                'exchange_Q'  => $exchange_Q, //换热量
                'pressure_bearing' => $pressure_bearing,//承压
                'count' => 1,
                'name'  => '分区'.($i+1).'板换'
            );
            $html .= '<b>板换:</b>';
            $html .= '<p>采暖形式:' . $heating_types[$i] . '</p>';
            $html .= '<p>采暖系统温度:' . $board[$i]['heating_sys_t'] . '</p>';
            $html .= '<p>换热量:' . $board[$i]['exchange_Q'] . '</p>';
            $html .= '<p>承压:' . $board[$i]['pressure_bearing'] . '</p>';
            $html .= '<p>一次侧供回水温度:' . $board[$i]['once_sarwt'] . '</p>';
            $html .= '<p>二次侧供回水温度:' . $board[$i]['twice_sarwt'] . '</p>';

            $html .= '<b>锅炉除污器 DN:' . $DN . '</b><br/>';
            $html .= '<b>锅炉除污器 id:' . $dirt_separater['id'] . '</b><br/>';
            $dirt_separater['name'] = '分区'.($i+1).'除污器';
            $fuji_res['dirt_separater'][$i+1] = $dirt_separater;

        }
        $fuji_res['board'] = $board;
        //常压锅炉+板换+承压系统end

    }elseif($application == 4){//真空锅炉+承压系统start
        //1.根据锅炉id获取燃烧机数据
        $html .= '<h3>计算出的数据（id后数据为空说明数据库中没有相应的设备）：</h3>';
        if($guolu_info['vender'] != 43){
            $burner = Burner_attr::getInfoByGuoluid($guolu['guolu_id']);
            $burner['count'] = 1;
            $html .= '<p>燃烧机id:'.$burner['id'].'</p>';
            $burner['name'] = '燃气燃烧机';
            $fuji_res['burner'] = $burner;
        }else{
            $html .= '<p>音诺伟森锅炉不需要燃烧器</p>';
        }
        //2.根据锅炉功率获取全自动软水机数据
        $outwater = round($guolu_power/700, 1); //自动软水机出水量计算结果，锅炉功率/700对应自动软水机出水量
        $hdys = Hdys_attr::getInfoByWeight($outwater);//锅炉功率/700对应自动软水机出水量
        $html .= '<b>根据锅炉总功率:'.$guolu['guolu_ratedpower'].' * '. $guolu['count'].' = '.$guolu_power.'/700 获取全自动软水机</b><br/>';
        if(!empty($hdys)){
            foreach ($hdys as $h){
                $html .= '<p>全自动软水机id:'.$h['id'].'</p>';
                $html .= '<p>全自动软水机出水量:'.$h['outwater'].'</p><br/>';
            }
        }
        $hdys['count'] = 1;
        $hdys['outwater'] = $outwater;
        $hdys['name'] = '全自动软水机';
        $fuji_res['hdys'] = $hdys;

        //3.根据锅炉电功率转换成的水箱公称容积获取软化水箱数据
        $capacity = $guolu_power/700;
        $water_box = Water_box_attr::getInfoByCapacity($capacity);
        $html .= '<b>根据功率转化成的公称容积:'.$guolu_power.'/700 获取软化水箱</b><br/>';
        if(!empty($water_box)){
            foreach ($water_box as $box){
                $html .= '<p>水箱id:'.$box['id'].'</p>';
                $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
            }
        }
        $water_box['count'] = 1;
        $water_box['name'] = '软化水箱';
        $water_box['capacity'] = $capacity;
        $fuji_res['water_box'] = $water_box;

        //4.根据流量和扬程获取采暖循环泵数据
        $total_exchange_Q2 = $guolu_power; //锅炉热功率
        $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
        $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
        $lift1 = 32;
        $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
        $pump1['count'] = 2;

        //5.计算直径和获取除污器数据
        if($guolu['count'] == 1){
            $DN = $guolu['guolu_iowater_interface'];
            if($max_floor >= 15){
                $flag = 0;
                foreach ($ARRAY_pipe_DN as $key => $value){
                    if($key < $DN) continue;
                    else{
                        if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                            $flag = 1;
                            continue;
                        }
                        $DN = $key;  //65
                        break;
                    }
                }
            }
        }else{
            $r = $ARRAY_pipe_DN[ $guolu['guolu_iowater_interface'] ][0]/2;//半径  mm
            $s = 3.14 * $r * $r * $guolu['count'];//总截面面积
            $DN = round(sqrt($s/3.14) * 2, 2 );//总管道直径,保留两位小数  mm
            //$DN遍历钢管公称直径数据，让$DN向上取最接近的直径
            $flag = 0;
            foreach ($ARRAY_pipe_DN as $key => $value){
                if($key < $DN) continue;
                else{
                    if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                        $flag = 1;
                        continue;
                    }
                    $DN = $key;  //65
                    break;
                }
            }
        }
        $dirt_separater = Dirt_separator_attr::getInfoByDN($DN);
        $dirt_separater['count'] = 1;
        $dirt_separater['DN'] = $DN;
        $dirt_separater['name'] = '锅炉除污器';
        $fuji_res['dirt_separater'][0] = $dirt_separater;
        $html .= '<b>锅炉除污器 DN:' . $DN . '</b><br/>';
        $html .= '<b>锅炉除污器 id:' . $dirt_separater['id'] . '</b><br/>';

        for($i=0; $i<count($area); $i++){
            $html .= '<br/><b>分区' . ($i + 1) . ':</b>';
            $flow_h = round($total_exchange_Q['heating'][$i]/700 * 600/$tem_int, 2);
            $pump_h = Pipeline_attr::getInfoByFlowLift($flow_h, $lift1);
            if(!empty($pump1)){
                $pump_h['name'] = '分区'.($i+1).'采暖循环泵';
                $pump_h['count'] = 2;
                $pump_h['lift'] = $lift1;
                $pump_h['flow'] = $flow_h;
                $fuji_res['heating_pump'][$i] = $pump_h;

                $num = 0;
                foreach ($pump_h as $pump){
                    if(isset($pump['id'])){
                        if($num == 0){//能匹配到循环泵，则累加1次该扬程
                            $total_lift += $lift1;
                        }
                        $num ++;
                        $html .= '<p>循环泵id:'.$pump['id'].'</p>';
                    }


                }
            }
            $html .= '<b>采暖循环泵:</b><br/><p>流量:'.$flow_h.'</p>';
            $html .= '<p>扬程:'.$lift1.'</p>';
            $html .= '<p>额定供热量:'.$total_exchange_Q['heating'][$i].'kw</p>';

            //6.根据流量和扬程获取系统补水泵数据，如果需要的话
            $pump2 = array();
            $lift2 = 0;
            if($location == 0 or $location == 1){//锅炉位于地面或地下室需要补水泵
                $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
                $lift2 = ($floors_height_area[$i] + $pump_location) * 1.2;//
                $flow2 = $flow_h * 0.05;    //
                $pump2 = Syswater_pump_attr::getInfoByFlowLift($flow2, $lift2);
            }else{
                $html .= '系统位于楼顶或裙楼，不需要补水泵';
            }
            $pump2['name'] = '分区'.($i+1).'补水泵';
            if(!empty($pump2)){

                $pump2['count'] = 2;
                $pump2['lift'] = $lift2;
                $pump2['flow'] = $flow2;
                $fuji_res['water_pump'][$i] = $pump2;
                $html .= '<br/><b>系统补水泵:</b><br/><p>流量:' . $flow2 . '</p>';
                $html .= '<p>扬程:' . $lift2 . '</p>';
                foreach ($pump2 as $pump){
                    $num = 0;
                    if(isset($pump['id'])){
                        if($num == 0){//能匹配到补水泵，则累加1次该扬程
                            $total_lift += $lift2;
                        }
                        $num++;
                        $html .= '<p>补水泵id:'.$pump['id'].'</p>';
                    }
                }
            }


        }
        //真空锅炉+承压系统end
    }
}elseif($guolu_use == 1 ){//热水选择辅机
    if($application == 1 or $application == 2){//直供系统-（常压/承压）热水锅炉+板换start
        //1.根据锅炉id获取燃烧机数据
        $html .= '<h3>计算出的数据（id后数据为空说明数据库中没有相应的设备）：</h3>';
        if($guolu_info['vender'] != 43){
            $burner = Burner_attr::getInfoByGuoluid($guolu['guolu_id']);
            $burner['count'] = 1;
            $html .= '<p>燃烧机id:'.$burner['id'].'</p>';
            $burner['name'] = '燃气燃烧机';
            $fuji_res['burner'] = $burner;
        }else{
            $html .= '<p>音诺伟森锅炉不需要燃烧器</p>';
        }

        //2.根据锅炉功率获取全自动软水机数据
        $outwater = round($guolu_power/700, 1); //自动软水机出水量计算结果，锅炉功率/700对应自动软水机出水量
        $hdys = Hdys_attr::getInfoByWeight($outwater);//锅炉功率/700对应自动软水机出水量
        $html .= '<b>根据锅炉总功率:'.$guolu['guolu_ratedpower'].' * '. $guolu['count'].' = '.$guolu_power.'/700 获取全自动软水机</b><br/>';
        if(!empty($hdys)){
            foreach ($hdys as $h){
                $html .= '<p>全自动软水机id:'.$h['id'].'</p>';
                $html .= '<p>全自动软水机出水量:'.$h['outwater'].'</p><br/>';
            }
        }
        $hdys['count'] = 1;
        $hdys['outwater'] = $outwater;
        $hdys['name'] = '全自动软水机';
        $fuji_res['hdys'] = $hdys;

        //3.根据锅炉电功率转换成的水箱公称容积获取软化水箱数据
        $capacity = $guolu_power/700;
        $water_box = Water_box_attr::getInfoByCapacity($capacity);
        $html .= '<b>根据功率转化成的公称容积:'.$guolu_power.'/700 获取软化水箱</b><br/>';
        if(!empty($water_box)){
            foreach ($water_box as $box){
                $html .= '<p>水箱id:'.$box['id'].'</p>';
                $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
            }
        }
        $water_box['count'] = 1;
        $water_box['name'] = '软化水箱';
        $water_box['capacity'] = $capacity;
        $fuji_res['water_box'] = $water_box;

//        //4.根据流量和扬程获取锅炉循环泵数据
//        $total_exchange_Q2 = $guolu_power; //锅炉热功率
//        $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
//        $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
//        $lift1 = 32;
//        $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
//        $pumparr = $pump1;
//        $pump1['count'] = 2;
//        $pump1['name'] = '锅炉循环泵';
//        $pump1['lift'] = $lift1;
//        $pump1['flow'] = $flow1;
//        $fuji_res['pipeline_pump'] = $pump1;
//        $html .= '<b>锅炉循环泵:</b><br/><p>流量:'.$flow1.'</p>';
//        $html .= '<p>扬程:'.$lift1.'</p>';
//        $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
//        foreach ($pumparr as $pump){
//            if(isset($pump['id'])){
//                $html .= '<p>循环泵id:'.$pump['id'].'</p>';
//            }
//        }
        //4.根据流量和扬程获取锅炉循环泵数据，如果需要的话
        $pump1 = array();
        $lift1 = 0;
        if($location == 0 or $location == 1){//锅炉位于地面或地下室需要补水泵
            $total_exchange_Q2 = $guolu_power; //锅炉热功率
            $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
            $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
            $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
            $lift1 = 20; //扬程，暂时使用默认值   //($floors_height - $pump_location) * 1.2 +32;//
            $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
            $pumppr = $pump1;
            $pump1['count'] = 2;
            $pump1['name'] = '锅炉循环泵';
            $pump1['lift'] = $lift1;
            $pump1['flow'] = $flow1;
            $fuji_res['pipeline_pump'] = $pump1;
            $html .= '<b>锅炉循环泵:</b><br/><p>流量:'.$flow1.'</p>';
            $html .= '<p>扬程:'.$lift1.'</p>';
            $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
            if($pumppr){
                foreach ($pumppr as $pumpinfo){
                    if(isset($pumpinfo['id'])){
                        $html .= '<p>循环泵id:'.$pumpinfo['id'].'</p>';
                    }
                }
            }
        }else{
            $html .= '系统位于楼顶或裙楼，不需要锅炉循环泵';
        }

        //5.计算直径和获取除污器数据
        if($guolu['count'] == 1){
            $DN = $guolu['guolu_iowater_interface'];
//            if($max_floor >= 15){
//                $flag = 0;
//                foreach ($ARRAY_pipe_DN as $key => $value){
//                    if($key < $DN) continue;
//                    else{
//                        if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
//                            $flag = 1;
//                            continue;
//                        }
//                        $DN = $key;  //65
//                        break;
//                    }
//                }
//            }
        }else{
            $r = $ARRAY_pipe_DN[ $guolu['guolu_iowater_interface'] ][0]/2;//半径  mm
            $s = 3.14 * $r * $r * $guolu['count'];//总截面面积
            $DN = round(sqrt($s/3.14) * 2, 2 );//总管道直径,保留两位小数  mm
            //$DN遍历钢管公称直径数据，让$DN向上取最接近的直径
            $flag = 0;
            foreach ($ARRAY_pipe_DN as $key => $value){
                if($key < $DN) continue;
                else{
                    if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                        $flag = 1;
                        continue;
                    }
                    $DN = $key;  //65
                    break;
                }
            }
        }
        $dirt_separater = Dirt_separator_attr::getInfoByDN($DN);
        $dirt_separater['count'] = 1;
        $dirt_separater['DN'] = $DN;
        $dirt_separater['name'] = '锅炉除污器';
        $fuji_res['dirt_separater'][0] = $dirt_separater;
        $html .= '<b>锅炉除污器 DN:' . $DN . '</b><br/>';
        $html .= '<b>锅炉除污器 id:' . $dirt_separater['id'] . '</b><br/>';
        $board = array();
        for($i=0; $i<count($area); $i++){
            $html .= '<br/><b>分区' . ($i + 1) . ':</b>';
            //计算分区的不锈钢保温水箱的数据
            $capacity = $total_exchange_Q['use_water'][$i] * 0.5*0.001;//公称容积，*0.001是为了将单位升转换成立方米
            $water_box2 = Water_box_attr::getInfoByCapacity( $capacity );
            $html .= '<b>根据公称容积: '.$capacity.' = '.$total_exchange_Q['use_water'].'*0.5*0.001 获取不锈钢保温水箱</b><br/>';
            if(!empty($water_box2)){
                foreach ($water_box2 as $box){
                    $html .= '<p>不锈钢保温水箱id:'.$box['id'].'</p>';
                    $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
                }
            }else{
                $html .= '<p>没有找到合适的不锈钢保温水箱'.'</p>';
            }
            $water_box2['count'][$i] = 1;
            $water_box2['capacity'][$i] = $capacity;
            $water_box2['use_water'][$i] = $total_exchange_Q['use_water'][$i];//分区用水情况
            $water_box2['coincidence_factor'][$i] = 0.5;//同时使用系数
            $water_box2['name'][$i] = '分区'.($i+1).'不锈钢保温水箱';
            $fuji_res['hot_water_box'][$i] = $water_box2;

            //计算板换数据
            if($is_condensate == 1)//判断是否是冷凝锅炉，以冷凝为主
                $once_sarwt = '80/60';//一次侧供回水温度=锅炉供回水水温, supply and return water temperature
            else
                $once_sarwt = $guolu['guolu_outwater_t'].'/'.$guolu['guolu_inwater_t'];

//            $exchange_Q  = round($total_exchange_Q['water'][$i]/3600, 2);//该分区的换热量
            $exchange_Q  = $total_exchange_Q['water'][$i];//该分区的换热量
            $twice_sarwt = '60/10';
            $pressure_bearing = 1.6;//MPa

            $board[$i] = array(
                //'heating_sys_t' =>$heating_sys_t,
                'once_sarwt'  => $once_sarwt,
                'twice_sarwt' => $twice_sarwt,
                'exchange_Q'  => $exchange_Q, //换热量
                'pressure_bearing' => $pressure_bearing,//承压
                'count' => 1,
                'name'  => '分区'.($i+1).'热水板换'
            );
            $html .= '<b>板换:</b>';
            $html .= '<p>采暖形式:' . $heating_types[$i] . '</p>';
            //$html .= '<p>采暖系统温度:' . $board[$i]['heating_sys_t'] . '</p>';
            $html .= '<p>换热量:' . $board[$i]['exchange_Q'] . '</p>';
            $html .= '<p>承压:' . $board[$i]['pressure_bearing'] . '</p>';
            $html .= '<p>一次侧供回水温度:' . $board[$i]['once_sarwt'] . '</p>';
            $html .= '<p>二次侧供回水温度:' . $board[$i]['twice_sarwt'] . '</p>';

        }
        $fuji_res['board'] = $board;
        //直供系统-（常压/承压）热水锅炉+板换end
    }elseif($application == 3 or $application == 4){//循环系统--（常压/承压）热水锅炉+板换+保温水箱start
        //1.根据锅炉id获取燃烧机数据
        $html .= '<h3>计算出的数据（id后数据为空说明数据库中没有相应的设备）：</h3>';
        if($guolu_info['vender'] != 43){
            $burner = Burner_attr::getInfoByGuoluid($guolu['guolu_id']);
            $burner['count'] = 1;
            $html .= '<p>燃烧机id:'.$burner['id'].'</p>';
            $burner['name'] = '燃气燃烧机';
            $fuji_res['burner'] = $burner;
        }else{
            $html .= '<p>音诺伟森锅炉不需要燃烧器</p>';
        }
        //2.根据锅炉功率获取全自动软水机数据
        $outwater = round($guolu_power/700, 1); //自动软水机出水量计算结果，锅炉功率/700对应自动软水机出水量
        $hdys = Hdys_attr::getInfoByWeight($outwater);//锅炉功率/700对应自动软水机出水量
        $html .= '<b>根据锅炉总功率:'.$guolu['guolu_ratedpower'].' * '. $guolu['count'].' = '.$guolu_power.'/700 获取全自动软水机</b><br/>';
        if(!empty($hdys)){
            foreach ($hdys as $h){
                $html .= '<p>全自动软水机id:'.$h['id'].'</p>';
                $html .= '<p>全自动软水机出水量:'.$h['outwater'].'</p><br/>';
            }
        }
        $hdys['count'] = 1;
        $hdys['outwater'] = $outwater;
        $hdys['name'] = '全自动软水机';
        $fuji_res['hdys'] = $hdys;

        //3.根据锅炉电功率转换成的水箱公称容积获取软化水箱数据
        $capacity = $guolu_power/700;
        $water_box = Water_box_attr::getInfoByCapacity($capacity);
        $html .= '<b>根据功率转化成的公称容积:'.$guolu_power.'/700 获取软化水箱</b><br/>';
        if(!empty($water_box)){
            foreach ($water_box as $box){
                $html .= '<p>水箱id:'.$box['id'].'</p>';
                $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
            }
        }
        $water_box['count'] = 1;
        $water_box['name'] = '软化水箱';
        $water_box['capacity'] = $capacity;
        $fuji_res['water_box'] = $water_box;



//        //4.根据流量和扬程获取锅炉循环泵数据
//        $total_exchange_Q2 = $guolu_power; //锅炉热功率
//        $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
//        $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
//        $lift1 = 32;
//        $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
//        $pumparr = $pump1;
//        $pump1['count'] = 2;
//        $pump1['name'] = '锅炉循环泵';
//        $pump1['lift'] = $lift1;
//        $pump1['flow'] = $flow1;
//        $fuji_res['pipeline_pump'] = $pump1;
//
//        $html .= '<b>锅炉循环泵:</b><br/><p>流量:'.$flow1.'</p>';
//        $html .= '<p>扬程:'.$lift1.'</p>';
//        $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
//        if($pumparr){
//            foreach ($pumparr as $pump){
//                if(isset($pump['id'])){
//                    $html .= '<p>循环泵id:'.$pump['id'].'</p>';
//                }
//            }
//        }

        //4.根据流量和扬程获取锅炉循环泵数据，如果需要的话
        $pump1 = array();
        $lift1 = 0;
        if($location == 0 or $location == 1){//锅炉位于地面或地下室需要补水泵
            $total_exchange_Q2 = $guolu_power; //锅炉热功率
            $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
            $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
            $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X

            $lift1 = 20; //扬程 暂时使用默认值，($floors_height - $pump_location) * high +32;//
            $pump1 = Pipeline_attr::getInfoByFlowLift($flow1, $lift1);
            $pumppr = $pump1;
            $pump1['count'] = 2;
            $pump1['name'] = '锅炉循环泵';
            $pump1['lift'] = $lift1;
            $pump1['flow'] = $flow1;
            $fuji_res['pipeline_pump'] = $pump1;
            $html .= '<b>锅炉循环泵:</b><br/><p>流量:'.$flow1.'</p>';
            $html .= '<p>扬程:'.$lift1.'</p>';
            $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
            if($pumppr){
                foreach ($pumppr as $pumpinfo){
                    if(isset($pumpinfo['id'])){
                        $html .= '<p>循环泵id:'.$pumpinfo['id'].'</p>';
                    }
                }
            }
        }else{
            $html .= '系统位于楼顶或裙楼，不需要锅炉循环泵';
        }

        //5.计算直径和获取除污器数据
        if($guolu['count'] == 1){
            $DN = $guolu['guolu_iowater_interface'];

        }else{
            $r = $ARRAY_pipe_DN[ $guolu['guolu_iowater_interface'] ][0]/2;//半径  mm
            $s = 3.14 * $r * $r * $guolu['count'];//总截面面积
            $DN = round(sqrt($s/3.14) * 2, 2 );//总管道直径,保留两位小数  mm
            //$DN遍历钢管公称直径数据，让$DN向上取最接近的直径
            $flag = 0;
            foreach ($ARRAY_pipe_DN as $key => $value){
                if($key < $DN) continue;
                else{
                    if($flag == 0 and count($area)>1){ //当多分区时，实现DN再大一号
                        $flag = 1;
                        continue;
                    }
                    $DN = $key;  //65
                    break;
                }
            }
        }
        $dirt_separater = Dirt_separator_attr::getInfoByDN($DN);
        $dirt_separater['count'] = 1;
        $dirt_separater['DN'] = $DN;
        $dirt_separater['name'] = '锅炉除污器';
        $fuji_res['dirt_separater'][0] = $dirt_separater;
        $html .= '<b>锅炉除污器 DN:' . $DN . '</b><br/>';
        $html .= '<b>锅炉除污器 id:' . $dirt_separater['id'] . '</b><br/>';

        for($i=0; $i<count($area); $i++){
            $html .= '<br/><b>分区' . ($i + 1) . ':</b>';

            //计算分区的不锈钢保温水箱的数据
            $capacity = $total_exchange_Q['use_water'][$i] * 0.5*0.001;//公称容积，*0.001是为了将单位升转换成立方米
            $water_box2 = Water_box_attr::getInfoByCapacity( $capacity );
            $html .= '<b>根据公称容积: '.$capacity.' = '.$total_exchange_Q['use_water'].'*0.5*0.001 获取不锈钢保温水箱</b><br/>';
            if(!empty($water_box2)){
                foreach ($water_box2 as $box){
                    $html .= '<p>不锈钢保温水箱id:'.$box['id'].'</p>';
                    $html .= '<p>水箱公称容积:'.$box['nominal_capacity'].'</p><br/>';
                }
            }else{
                $html .= '<p>没有找到合适的不锈钢保温水箱'.'</p>';
            }
            $water_box2['count'][$i] = 1;
            $water_box2['capacity'][$i] = $capacity;
            $water_box2['use_water'][$i] = $total_exchange_Q['use_water'][$i];//分区用水情况
            $water_box2['coincidence_factor'][$i] = 0.5;//同时使用系数
            $water_box2['name'][$i] = '分区'.($i+1).'不锈钢保温水箱';
            $fuji_res['hot_water_box'][$i] = $water_box2;


            //根据流量和扬程获取热水循环泵数据，如果需要的话
            $pump2 = array();
            if($location == 0 or $location == 1){//锅炉位于地面或地下室需要泵
                $flow2 = $flow1;
                $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
                $lift2 = 15; //扬程 暂时使用默认值，($floors_height - $pump_location) * 1.2 +32;//
                $pump2 = Pipeline_attr::getInfoByFlowLift($flow2, $lift2);
                $pumppr = $pump1;
                $pump2['count'] = 2;
                $pump2['name'] = '热水循环泵';
                $pump2['lift'] = $lift2;
                $pump2['flow'] = $flow2;
                $fuji_res['heating_pump'][$i] = $pump2;
                $html .= '<b>热水循环泵:</b><br/><p>流量:'.$flow2.'</p>';
                $html .= '<p>扬程:'.$lift2.'</p>';
                $html .= '<p>额定供热量:'.$total_exchange_Q2.'kw</p>';
                if($pumppr){
                    foreach ($pumppr as $pumpinfo){
                        if(isset($pumpinfo['id'])){
                            $html .= '<p>循环泵id:'.$pumpinfo['id'].'</p>';
                        }
                    }
                }
            }else{
                $html .= '系统位于楼顶或裙楼，不需要热水循环泵';
            }


            //计算板换数据
            //$heating_sys_t = $ARRAY_system_tem[ $heating_types[$i] ][0].'/'.$ARRAY_system_tem[$heating_types[$i] ][1];//确定采暖系统温度
            if($is_condensate == 1)//判断是否是冷凝锅炉，以冷凝为主
                $once_sarwt = '80/60';//一次侧供回水温度=锅炉供回水水温, supply and return water temperature
            else
                $once_sarwt = $guolu['guolu_outwater_t'].'/'.$guolu['guolu_inwater_t'];
            $twice_sarwt = '60/10';//二次侧供回水温度=采暖系统温度


            $pressure_bearing = 1.6;//承压 MPa  2.896


//            $exchange_Q  =  round($total_exchange_Q['water'][$i]/3600, 2);//该分区的换热量

            $board[$i] = array(
                //'heating_sys_t' =>$heating_sys_t,
                'once_sarwt'  => $once_sarwt,
                'twice_sarwt' => $twice_sarwt,
                'exchange_Q'  => $total_exchange_Q['water'][$i], //换热量
                'pressure_bearing' => $pressure_bearing,//承压
                'count' => 1,
                'name'  => '分区'.($i+1).'热水板换'
            );
            $html .= '<b>板换:</b>';
            //$html .= '<p>采暖形式:' . $heating_types[$i] . '</p>';
            //$html .= '<p>采暖系统温度:' . $board[$i]['heating_sys_t'] . '</p>';
            $html .= '<p>换热量:' . $board[$i]['exchange_Q'] . '</p>';
            $html .= '<p>承压:' . $board[$i]['pressure_bearing'] . '</p>';
            $html .= '<p>一次侧供回水温度:' . $board[$i]['once_sarwt'] . '</p>';
            $html .= '<p>二次侧供回水温度:' . $board[$i]['twice_sarwt'] . '</p>';

            $total_exchange_Q2 = $guolu_power; //锅炉热功率
            $tem_int = $guolu['guolu_outwater_t'] - $guolu['guolu_inwater_t'];//供水温度差
            $flow1 = round($total_exchange_Q2/700 * 600/$tem_int, 2);
            $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X

            $flow3 = $flow1;
            $html .= '<p>二次侧供回水温度:' . $floors_height_hw . '</p>';
            $pump_location = $location ?  4*$location : 0 ; //地下室X层为4X
            $lift3 =   ($floors_height_area_hw[$i] + $pump_location) * 1.2 +32;


            $pump3 = Syswater_pump_attr::getInfoByFlowLift($flow3, $lift3);
            $pumparr3 = $pump3;
            $pump3['count'] = 2;
            $pump3['name'] = '分区'.($i+1).'变频供水泵';
            $pump3['lift'] = $lift3;
            $pump3['flow'] = $flow3;
            $fuji_res['water_pump'][$i] = $pump3;
            $html .= '<b>变频供水泵:</b><br/><p>流量:'.$flow3.'</p>';
            $html .= '<p>扬程:'.$lift3.'</p>';

            if(!empty($pumparr3)){
                foreach ($pumparr3 as $pump){
                    if(isset($pump['id'])){
                        $html .= '<p>变频供水泵id:'.$pump['id'].'</p>';
                    }
                }
            }

        }
        $fuji_res['board'] = $board;

    //循环系统--（常压/承压）热水锅炉+板换+保温水箱end
    }
}

//打包数据，便于返回

//file_put_contents('fuji_out_var.html', iconv('utf-8','gb2312',$html));

//计算签名
$fuji_str     = json_encode($fuji_res);
$key          = '32143413123';
$sigh         = md5sign($fuji_str, $key);
$fuji_res['html'] = $html;
$fuji_res['sigh'] = $sigh;
$json_str     = json_encode($fuji_res);//将添加签名后的辅机数组转换成json字符串
echo($json_str);




































/**
 * 计算数据的md5签名
 * @param $str  数据字符串
 * @param $key  私钥
 * @return string 签名
 */
function md5sign($str, $key){
    $str .= $key;
    return md5($str);
}


?>