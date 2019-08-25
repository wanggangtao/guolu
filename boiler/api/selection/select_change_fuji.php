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


try{
    $json            = json_decode($_POST['jsonstr'],true);//接收json数据
//    file_put_contents('fuji_var_in_change.html',var_export($json, true));
    $guoluinfo          = $json['guoluinfo'];

    if(!isset($json['heating_type']))
    {
        throw new MyException("缺少采暖形式",101);
    }


    if(!isset($json['is_condensate']))
    {
        $is_condensate   = 0; //锅炉是否冷凝，0 不是 1 是
    }
    else
    {
        $is_condensate   = $json['is_condensate']; //锅炉是否冷凝，0 不是 1 是
    }


    if(!isset($json['board_power']))
    {
        $board_power = 0;
    }
    else
    {
        $board_power   = $json['board_power'];    //原有板换功率
    }


    $heating_type   = $json['heating_type'];   //采暖形式 0 暖气 1 地暖 2 风机盘管




    $fuji_res = array(
        'pipeline_pump'  => array(array()), //.锅炉循环泵
        'board'          => array(array()),  //板换
    );



    $guolu_power = 0;

    $outwater_t = 0;
    $inwater_t = 0;
    foreach ($guoluinfo as $guolu)
    {
        $currentInfo = Guolu_attr::getInfoById($guolu['guolu_id']);
        $guolu_power += $currentInfo['ratedpower'] * $guolu['guolu_num'];//计算锅炉总功率

        if(empty($outwater_t))
        {
            $outwater_t = $currentInfo["outwater_t"];
            $inwater_t = $currentInfo["inwater_t"];
        }

    }

    $pump = array();

    $tem_int = $outwater_t - $inwater_t;//供水温度差
    $flow = round($guolu_power/700 * 600/$tem_int, 2);
    $lift = 20; //扬程 暂时使用默认值，($floors_height - $pump_location) * 1.2 +32;//



    $pumpInfo = array();
    $pump = Pipeline_attr::getInfoByFlowLift($flow, $lift);
    $pumpInfo['count'] = 2;
    $pumpInfo['name'] = '锅炉循环泵';
    $pumpInfo['flow'] = $flow;
    $pumpInfo['lift'] = $lift;
    $pumpInfo['pump'] = $pump;

    $fuji_res['pipeline_pump'] = $pumpInfo;






//计算板换数据
    if($is_condensate == 1)//判断是否是冷凝锅炉，以冷凝为主
        $once_sarwt = '80/60';//一次侧供回水温度=锅炉供回水水温, supply and return water temperature
    else
        $once_sarwt = $outwater_t.'/'.$inwater_t;

    $twice_sarwt_arr = $ARRAY_system_tem[$heating_type];//二次侧供回水温度=采暖系统温度
    $twice_sarwt = $twice_sarwt_arr[1]."/".$twice_sarwt_arr[0];

    $pressure_bearing = 1.6;//承压 MPa  2.896


    $exchange_Q  = $guolu_power - $board_power;//该分区的换热量

    $board = array(
        //'heating_sys_t' =>$heating_sys_t,
        'once_sarwt'  => $once_sarwt,
        'twice_sarwt' => $twice_sarwt,
        'exchange_Q'  => $exchange_Q, //换热量
        'pressure_bearing' => $pressure_bearing,//承压
        'count' => 1,
        'name'  => '板换'
    );


    $fuji_res['board'] = $board;


//打包数据，便于返回

//    file_put_contents('fuji_out_var.html', iconv('utf-8','gb2312',$html));

//计算签名

    echo action_msg($fuji_res,1);



}catch (MyException $e){


    echo $e->jsonMsg();
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