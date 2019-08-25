<?php

/**
 * 选型
 *
 * @version       v0.01
 * @create time   2018/7/16
 * @update time   2018/7/19
 * @author        yjl
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */


require_once('../../init.php');
// ini_set('display_errors',1);            //错误信息
// ini_set('display_startup_errors',1);    //php启动错误信息
// error_reporting(-1);                    //打印出所有的 错误信息
//用途为采暖+热水要分为两个数组，因为建筑类型范围不同
error_reporting(0);
$json           = json_decode($_POST['jsonstr'],true);
//$json           = json_decode($jsonstr,true);
//$location       = safeCheck($json['guolu_location']);  //锅炉房位置，no use
//$height         = safeCheck($json['guolu_height']);    //锅炉房高度,no use
$is_condensate  = safeCheck($json['is_condensate']);   //锅炉是否冷凝，
$is_lownitrogen = safeCheck($json['is_lownitrogen']);  //锅炉是否低氮，
$guolu_use      = safeCheck($json['guolu_use']);       //锅炉用途，0 采暖， 1 热水， 2 采暖+热水
$guolu_count     = safeCheck($json['guolu_count']);     //锅炉数量
$application    = safeCheck($json['application']);     //锅炉应用形式
$area           = $json['area'];                       //分区数组
//$B              = isset($json['b']) ? safeCheck($json['b']) : 1 ; //每个分区的参数b，用于定时供水时计算热负荷，0 < b <= 1

global $html;
$html = '<h2>锅炉选型中间参数值：</h2><br/>'.
        '<p>锅炉类型：';
//判断锅炉类型
if( ($guolu_use == 0 and $application == 4) or ($guolu_use == 2 and $application == 4) ){
    $guolu_type = 13; //真空锅炉
    $html .= '真空锅炉';
}elseif( ($guolu_use == 0 and $application == 1) or ($guolu_use == 2 and $application == 1)
         or ($guolu_use == 1 and ($application == 2 or $application == 4) ) ){
    $guolu_type = 12; //承压锅炉
    $html .= '承压锅炉';
}else{
    $guolu_type = 11; //常压锅炉
    $html .= '常压锅炉';
}
$html .= '</p><br/>';


//获取符合条件的锅炉列表，目前假设获取全部符合要求的列表时其他不需要的参数设为0或空
$guolu_list = Guolu_attr::getList('','', 0, '', $guolu_type, $is_condensate, $is_lownitrogen);

///计算总负荷
$Q = 0;//总热负荷
$total_exchange_Q = array(//换热量
    'heating' => array(),
    'water'   => array(),
    'use_water' => array()
);
$heating_area = 0;   //总的采暖面积
if($guolu_use == 0){  //采暖
    //计算总的采暖热负荷
    for ($i=0; $i<count($area); $i++) {
        $html .= '<h3>分区'.($i+1).'</h3>';
        $this_Q = cal_heating_Q($area[$i]);
        $total_exchange_Q['heating'][$i] = $this_Q;//存储每个分区的换热量，板换计算需要使用
        $Q += $this_Q;
    }

}elseif($guolu_use == 1){  //热水
    //计算总的供水热负荷
    for ($i=0; $i<count($area); $i++){
        $html .= '<h3>分区'.($i+1).'</h3>';
        $this_Q = cal_water_Q($area[$i]);
//        file_put_contents('guolu_var_test', var_export($area[$i],true));
        $heating_area += $area[$i]['heating_area'];
//        $total_exchange_Q['water'][$i] = $this_Q * $area[$i]['heating_area']/3600;//
        $total_exchange_Q['water'][$i] = $this_Q ;//
//        $total_exchange_Q['water'][$i]= $this_Q;
        $html .= '<p>分区热负荷22Q:'.$this_Q .'</p>';
        $Q += $this_Q;
    }

}elseif($guolu_use == 2){  //采暖+热水
    //计算总的热负荷
    //先计算总采暖热负荷
    for ($i=0; $i<count($area[0]); $i++){
        $html .= '<h3>采暖-分区'.($i+1).'</h3><br/>';
        $this_Q = cal_heating_Q($area[0][$i]);
        $total_exchange_Q['heating'][$i] = $this_Q;///1000;//将单位换为kw
        $Q += $this_Q;
    }

    unset($this_Q);
    //再计算总供水热负荷
    for ($i=0; $i<count($area[1]); $i++){
        //print_r($area[$i]);
        $html .= '<h3>热水-分区'.($i+1).'</h3><br/>';
        $this_Q = cal_water_Q($area[1][$i]);
//        file_put_contents('guolu_var_test_1', var_export($area[1][$i],true));
        $total_exchange_Q['water'][$i] = $this_Q;//$area[1][$i]['heating_area']*?
        $Q += $this_Q;
        $html .= '<p>分区热负荷Q:'.$this_Q .'</p>';
    }

}
$Q = round($Q, 3);

$html .= '<p>总热负荷Q:</p>'.$Q.' kw<br/>';
$html .= '<p>总热负荷Q:</p>'.$mq.' kw<br/>';
//计算出符合要求的方案

array_multisort(array_column($guolu_list, 'guolu_ratedpower'), $guolu_list);//将锅炉列表按功率从小到大进行排序

$plan = cal_plan($Q, $guolu_list, $guolu_count);
//array_multisort(array_column($plan, 'diff'), $plan);//将方案按差值从小到大进行排序


$plan['total_exchange_Q'] = $total_exchange_Q;
//$plan['heating_area']     = $heating_area;
$plan['html']     = $html;
$plan_str     = json_encode($plan);//将方案数组转换成json字符串
$key          = '32143413123';
$sign         = md5sign($plan_str, $key);
$plan['sigh'] = $sign;
$json_str     = json_encode($plan);//将添加签名后的方案数组转换成json字符串
//file_put_contents('guolu_var.html', iconv('utf-8','gb2312',$html));
echo($json_str);





/**
 *  求解可行的锅炉方案
 * @param $Q           锅炉总负荷
 * @param $guolu_list  锅炉列表，必须按功率大小从低到高排序
 * @return $plan       方案数组
 */
function cal_plan($Q, $guolu_list, $guolu_count){
    $plan = array( array() );//存储方案的数组
    $plan['total_Q'] = $Q;
    global $html;
    $x = 0;   //方案数组下标
    $num = count($guolu_list);
    if ($num < 1) { //数据库没有符合用户选中的锅炉
        return $plan;
    }
    $diff = $guolu_count * $guolu_list[ $num-1 ]['guolu_ratedpower'] - $Q ;//差值，锅炉总功率与$Q的差值

    if($diff < 0){//最大的总功率与$Q的差值都小于0，下面就不用算了，没有符合要求的锅炉
        return $plan;
    }
    for ($i=0; $i<count($guolu_list); $i++){
        $t_power = $guolu_count * $guolu_list[$i]['guolu_ratedpower'];//计算锅炉功率乘以用户给的锅炉数量
        $diff2 = $t_power - $Q;

        if($diff2 >= 0 && $diff2 <= $diff){//差值大于零且小于当前差值，存入方案数组
            $diff = $diff2;
            //$html .= '差值'.$x.':'.$diff.'<br/>';
            $plan_t['plan'][$x] = $guolu_list[$i];//锅炉数据
            $plan_t['plan'][$x]['count'] = $guolu_count;//锅炉数量
            $plan_t['plan'][$x]['diff']  = $diff;
            $x++;
        }
    }
    $x--;
    $html .= '最小差值diff：'.$plan_t['plan'][$x]['diff'].'<br/>';
    for($j=0; $j<count($plan_t['plan']); $j++){//只存入差值最小的方案

        if($plan_t['plan'][$x]['diff'] == $diff){
            $plan['plan'][$j] = $plan_t['plan'][$x];
            $x--;
        }
    }
    return $plan;
}




/**
 * 计算单个分区的采暖热负荷
 * @param $arr 该采暖分区数据
 * @return float|int 该分区采暖总负荷
 */
function cal_heating_Q($arr){
    global $ARRAY_project_qf;
    global $total_exchange_Q;
    global $html;
    $Q = 0;
    //判断采暖指标选择
    if($arr['heating_time'] == 0) {  //采暖形式-24小时
        $qf = $ARRAY_project_qf[$arr['project_type']][0] + 5;  //下限+5
    }else{  //间断运行
        $qf = $ARRAY_project_qf[$arr['project_type']][1];
    }
    $Q = $qf * $arr['heating_area']/1000;
    $html .= '<p>分区qf:</p>'.$qf.'<br/>';
    $html .= '<p>分区热负荷Q:</p>'.$Q.'kw<br/>';
    //$total_exchange_Q['heating'][] = $qf * $arr['heating_area'];//存储每个分区的换热量，板换计算需要使用
    return $Q;
}



/**
 * 计算单个分区的供水热负荷
 * @param $arr 该供水分区数据
 * @return float|int 该分区供水总负荷
 */
function cal_water_Q($arr){
    global $ARRAY_project_qr;
    global $ARRAY_water_kh;
    global $ARRAY_appliance_qh;
    global $total_exchange_Q;
    global $html;

    $Q = 0; //热负荷
    $C = 4.187; //水的比热v
    //先判断供水方式
    $t = 50;//水的温度差
    $html .= '<p>水的比热容C:'.$C.'</p>';
    $html .= '<p>水的温度差tr-tl:'.$t.'</p>';
    if($arr['water_type'] == 31){  //24小时
        $html .= '<p>24小时供水:</p>';
        //计算每天总的热水用水定额m*qr和获取用水时间T
        //先计算宾馆和医院，这俩比较特殊
        $hotel_num    =  18; //宾馆编号
        $hospital_num =  19; //医院编号
        if($arr['project_type'] == $hotel_num) {  //宾馆
            $mq = $arr['total_bed'] * $ARRAY_project_qr[$hotel_num][101][0] +
                $arr['daily_woker'] * $ARRAY_project_qr[$hotel_num][102][0];
            $T  = $ARRAY_project_qr[$hotel_num][102][2];
            $user_num = $arr['total_bed'] + $arr['daily_woker'];

//            if($user_num > 100){   //使用者大于100 取最小值
//                $Kh = $ARRAY_water_kh[ $arr['project_type'] ][0];
//            }else{  //其余用插值法取值
//                $Kh_max = $ARRAY_water_kh[ $arr['project_type'] ][1];
//                $Kh_min = $ARRAY_water_kh[ $arr['project_type'] ][0];
//                $Kh = $Kh_max - ( ($Kh_max - $Kh_min)/100 * $user_num );
//            }
            $Kh = $ARRAY_water_kh[ $arr['project_type'] ][1];//目前需求，Kh永远取最大值

        }elseif ($arr['project_type'] == $hospital_num){  //医院
            $cond = $arr['appliance'][105];//医院内包含有的条件，如设有独立卫生间等

            $mq = $arr['total_bed'] * $ARRAY_project_qr[$hospital_num][$cond][0] ;//床位
            $mq1 = $arr['daily_woker'] * $ARRAY_project_qr[$hospital_num][3][0];//人数

            $T  = $ARRAY_project_qr[$hospital_num][$cond][2];
            $T_1  = $ARRAY_project_qr[$hospital_num][3][2];
            $user_num = $arr['total_bed'] + $arr['daily_worker'];
//            if($user_num > 100){   //使用者大于100 取最小值
//                $Kh = $ARRAY_water_kh[ $arr['project_type'] ][0];
//            }else{  //其余用插值法取值
//                $Kh_max = $ARRAY_water_kh[ $arr['project_type'] ][1];
//                $Kh_min = $ARRAY_water_kh[ $arr['project_type'] ][0];
//                $Kh = $Kh_max - ( ($Kh_max - $Kh_min)/100 * $user_num );
//            }
            $Kh = $ARRAY_water_kh[ $arr['project_type'] ][1];//目前需求，Kh永远取最大值
        }
        elseif( count($ARRAY_project_qr[ $arr['project_type'] ], 1) == 3) {  //无条件选项的建筑

            $mq = $arr['user_num'] * $ARRAY_project_qr[ $arr['project_type'] ][0];
            $T  = $ARRAY_project_qr[ $arr['project_type'] ][2];
//            if($arr['user_num']  > 100){   //使用者大于100 取最小值
//                $Kh = $ARRAY_water_kh[ $arr['project_type'] ][0];
//            }else{  //其余用插值法取值
//                $Kh_max = $ARRAY_water_kh[ $arr['project_type'] ][1];
//                $Kh_min = $ARRAY_water_kh[ $arr['project_typcde'] ][0];
//                $Kh = $Kh_max - ( ($Kh_max - $Kh_min)/100 * $arr['user_num']);
//            }
            $Kh = $ARRAY_water_kh[ $arr['project_type'] ][1];//目前需求，Kh永远取最大值
        }else{  //有条件选项的建筑
            $html .= '<p>1小时变化系数Kh:</p>';
            $mq = $arr['user_num'] * $ARRAY_project_qr[ $arr['project_type']]
                [ $arr['project_cond'] ][0];
            $T  = $ARRAY_project_qr[ $arr['project_type'] ]
            [ $arr['project_cond']  ][2];

            if($arr['project_type']==16){
                $mq = $arr['user_num'] * $ARRAY_project_qr[$arr['project_type'] ]
                    [ $arr['appliance'][91] ][0];
                $T  = $ARRAY_project_qr[$arr['project_type'] ]
                [ $arr['appliance'][91] ][2];
            }
            $html .= '<p>m * qr :'.$ARRAY_project_qr[$arr['project_type'] ]
                [ $arr['appliance'][91] ][0].'</p>';
            $html .= '<p>m * qr :'.$arr['user_num'].'</p>';

//            if($arr['user_num']  > 100){   //使用者大于100 Kh取最小值
//                $Kh = $ARRAY_water_kh[ $arr['project_type'] ][0];
//            }else{  //其余用差值法取值
//                $Kh_max = $ARRAY_water_kh[ $arr['project_type'] ][1];
//                $Kh_min = $ARRAY_water_kh[ $arr['project_type'] ][0];
//                $Kh = $Kh_max - ( ($Kh_max - $Kh_min)/100 * $arr['user_num']);
//            }
            $Kh = $ARRAY_water_kh[ $arr['project_type'] ][1];//目前需求，Kh永远取最大值
        }
        $html .= '<p>小时变化系数Kh:'.$Kh.'</p>';
        $html .= '<p>m * qr :'.$mq.'</p>';
        $total_exchange_Q['use_water'][] = $mq;//存下用水量
        $html .= '<p>m * qr :'.$mq.'</p>';
        $html .= '<p>每日用水时长:'.$T.'</p>';
        if($arr['project_type'] == $hospital_num){
            $html .= '<p>每日用水时长:'.$T_1.'</p>';
            $Q = (($Kh * $mq * $C * $t) / $T)+($Kh * $mq1 * $C * $t) / $T_1;
        }else{
            $Q = ($Kh * $mq * $C * $t) / $T;
        }


    }elseif($arr['water_type'] == 32){//定时供应
        $html .= '<p>定时供水2:</p><br/>';
        $b = $arr['b']; //卫生器具的同时使用百分数
        //先计算qh * no，所有器具每小时用水量的总和
        $qhno = 0;
        foreach ($arr['appliance'] as $key => $value){
            $qhno += $ARRAY_appliance_qh[$arr['project_type']][$key] * $value;//器具用水乘以数量
        }
        $html .= '<p>所有器具每小时用水量的总和 qh * no:'.$qhno.'</p>';
        $html .= '<p>卫生器具同时使用百分数:'.($b*100).' %</p>';
        $Q = $qhno * $t * $b * $C;
        $total_exchange_Q['use_water'][] = $qhno;//存下用水量
    }
    return round($Q/3600, 4);
}

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