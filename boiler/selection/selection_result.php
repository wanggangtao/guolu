<?php
/**
 * 选型 selection.php
 *
 * @version       v0.01
 * @create time   2018/07/25
 * @update time   2018/07/25
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');
error_reporting(0);  

$TOP_FLAG = "selection";

$id = isset($_GET['id'])?safeCheck($_GET['id']):0;
$plan_id = isset($_GET['plan_id'])?safeCheck($_GET['plan_id']):0;
$info = Selection_history::getInfoById($id);

//选型入口
//$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;

if(empty($info)){
    echo '非法操作！';
    die();
}

$areaArr = array();
//采暖
if($info['application'] == 0) {
    $heatinglist = Selection_heating_attr::getInfoByHistoryId($id);
    if($heatinglist){
        foreach ($heatinglist as $thisheat){
            $heatArr = array();
            $heatArr['project_type'] = $thisheat['build_type'];
            $heatArr['pump_head'] = $thisheat['floor_height'] * ($thisheat['floor_high'] - $thisheat['floor_low'] + 1);
            $heatArr['heating_area'] = $thisheat['area'];
            $heatArr['heating_type'] = $thisheat['type'];
            $heatArr['heating_time'] = $thisheat['usetime_type'];
            $areaArr[] = $heatArr;
        }
    }
}
//热水
elseif($info['application'] == 1){
    $hotwaterParamlist = Selection_hotwater_attr::getParamByHistoryId($id);
    if($hotwaterParamlist){
        foreach ($hotwaterParamlist as $thisparam){
            $hotattrlist = Selection_hotwater_attr::getInfoByHistoryId($id, $thisparam['hotwater_param_id']);

            $hotArr = array();
            $hotArr['water_type'] = $hotattrlist[0]['use_type'];
            $hotArr['project_type'] = $hotattrlist[0]['build_type'];
            $hotArr['heating_area'] = $hotattrlist[0]['heating_area'];

            $hotArr['project_cond'] = 0;
            $hotArr['user_num'] = 0;
            $hotArr['total_bed'] = 0;
            $hotArr['daily_woker'] = 0;
            if($hotattrlist){
                foreach ($hotattrlist as $hotwater){
                    //$applianceArr = array();
                    //$applianceArr[$hotwater['buildattr_id']] = $hotwater['attr_num'];
                    $hotArr['appliance'][ $hotwater['buildattr_id'] ] = $hotwater['attr_num'];
                    if(in_array($hotwater['buildattr_id'], array(86,96,112))){
                        $hotArr['project_cond'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(91))){
                        $hotArr['project_cond'] = $hotwater['buildattr_id'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(85,89,90,91,95,110,111,74))){
                        $hotArr['user_num'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(101,103))){
                        $hotArr['total_bed'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(102,104))){
                        $hotArr['daily_woker'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(91))){
                        $hotArr['appliance'][ $hotwater['buildattr_id'] ] = 93;
                    }
                }
            }
            $hotArr['b'] = $hotattrlist[0]['same_use'];
            $areaArr[] = $hotArr;
        }
    }
}
//采暖和热水
elseif($info['application'] == 2){
    //采暖
    $heatinglist = Selection_heating_attr::getInfoByHistoryId($id);
    if($heatinglist){
        foreach ($heatinglist as $thisheat){
            $heatArr = array();
            $heatArr['project_type'] = $thisheat['build_type'];
            $heatArr['pump_head'] = $thisheat['floor_height'] * ($thisheat['floor_high'] - $thisheat['floor_low'] + 1);
            $heatArr['heating_area'] = $thisheat['area'];
            $heatArr['heating_type'] = $thisheat['type'];
            $heatArr['heating_time'] = $thisheat['usetime_type'];
            $areaArr['0'][] = $heatArr;
        }
    }
    //热水
    $hotwaterParamlist = Selection_hotwater_attr::getParamByHistoryId($id);
    if($hotwaterParamlist){
        foreach ($hotwaterParamlist as $thisparam){
            $hotattrlist = Selection_hotwater_attr::getInfoByHistoryId($id, $thisparam['hotwater_param_id']);
//            print_r($hotattrlist);
            $hotArr = array();
            $hotArr['water_type'] = $hotattrlist[0]['use_type'];
            $hotArr['heating_area'] = $hotattrlist[0]['heating_area'];
            $hotArr['project_type'] = $hotattrlist[0]['build_type'];
            $hotArr['project_cond'] = 0;
            $hotArr['user_num'] = 0;
            $hotArr['total_bed'] = 0;
            $hotArr['daily_woker'] = 0;
            if($hotattrlist){
                foreach ($hotattrlist as $hotwater){
                   // $applianceArr = array();
                   // $applianceArr[$hotwater['buildattr_id']] = $hotwater['attr_num'];
                    $hotArr['appliance'][$hotwater['buildattr_id']] = $hotwater['attr_num'];
                    if($hotwater['buildattr_id'] == 96 && $hotwater['attr_num'] == 100){
                        $hotArr['project_cond'] = 1;
                    }
                    if(in_array($hotwater['buildattr_id'], array(91))){
                        $hotArr['appliance'][ $hotwater['buildattr_id'] ] = 93;
                    }
                    if(in_array($hotwater['buildattr_id'], array(85,89,90,91,95,110,111))){
                        $hotArr['user_num'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(101,103))){
                        $hotArr['total_bed'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(102,104))){
                        $hotArr['daily_woker'] = $hotwater['attr_num'];
                    }
                    if(in_array($hotwater['buildattr_id'], array(86,96,112))){
                        $hotArr['project_cond'] = $hotwater['attr_num'];
                    }
//                    if(in_array($hotwater['buildattr_id'], array(91))){
//                        $hotArr['appliance'][ $hotwater['buildattr_id'] ] = 93;
//                    }

                }
            }
            $hotArr['b'] = $hotattrlist[0]['same_use'];
            $areaArr['1'][] = $hotArr;
        }
    }
}
$dataArr = array(
        'guolu_location' => $info['guolu_position'],
        'guolu_height' => $info['guolu_height'],
        'guolu_count' => $info['guolu_num'],
        'is_condensate' => $info['is_condensate'],
        'is_lownitrogen' => $info['is_lownitrogen'],
        'guolu_use' => $info['application'],
        'application' => !empty($info['heating_type']) ? $info['heating_type'] : $info['water_type'],
        'area' => $areaArr
);
$data = 'jsonstr='.json_encode($dataArr);
$curl     = $HTTP_PATH.'api/selection/select_guolu.php';
$rs       = json_decode(Curl::post($curl,$data),true);
$str_x='';
if($rs['total_exchange_Q']['use_water']){
    foreach ($rs['total_exchange_Q']['use_water'] as $x){

            $str_x=$str_x.'||'.$x;
    }
}


//var_dump($rs);
//print_r($dataArr);
//print_r($dataArr);
// print_r($rs);
//print_r($data);
//file_put_contents('guolu-var.html', $res['html']);
$var_url = $HTTP_PATH.'api/selection/guolu_var.html';
/* $guolu_vender_search = array();
$guolu_count_search = array();
if($rs['plan']){
    foreach ($rs['plan'] as $thisplan){
        if(!in_array($thisplan['count'], $guolu_count_search)){
            $guolu_count_search[] = $thisplan['count'];
        }
        if(!array_key_exists($thisplan['guolu_vender'], $guolu_vender_search)){
            $thisvender = Dict::getInfoById($thisplan['guolu_vender']);
            $guolu_vender_search[$thisplan['guolu_vender']] = $thisvender['name'];
        }
    }
} */
$guolu_vender_list = Dict::getListByParentid(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉选型</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <style>
        .inputing{
            background-image:url('images/未选中按钮.png');
            -webkit-appearance: none;
            background-size:16px 16px;
            display: inline-block;
            width: 16px;
            height: 16px;
            background-repeat:no-repeat;
            position: relative;
            top: 2px;
            left: -5px;
            outline: none;
        }
        .inputing:checked{
            background:url('images/选中按钮.png');
            background-repeat:no-repeat;
        }

        .checkbox{
            background-image:url('images/未选中按钮.png');
            -webkit-appearance: none;
            background-size:16px 16px;
            display: inline-block;
            width: 16px;
            height: 16px;
            background-repeat:no-repeat;
            position: relative;
            outline: none;
        }
        .checkbox:checked{
            background:url('images/选中按钮.png');
            background-repeat:no-repeat;
        }
    </style>
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

</head>
<body class="body_1">
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
                        if($history_info['status']==Selection_history::HISTORY_Plan ){?>
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

<!--    <div class="guolumain">-->
<!--        <div class="guolumain_1">当前位置：锅炉选型 ><span>选型锅炉结果</span></div><div class="clear"></div>-->
<!--    </div>-->
    <div class="XXresult">
        <div id="prev" class="XXRmain" >
            <div class="XXRmain_1">锅炉</div>
            <div class="XXRmain_2"><span>根据输入的选型参数，计算得出热负荷为：<?php echo $rs['total_Q']; ?>kw </span></div>

            <div class="XXRmain_3">
                <div class="XXRmain_3_1">
                    <div class="XXRmain_3_2">选择厂家</div>
                    <select type="text" class="XXRmain_3_4" id="guolu_vender"  style="width: 344px">

                        <option value="0">全部</option>
                        <?php
                        if($guolu_vender_list){
                            foreach ($guolu_vender_list  as $thisvender){
                                echo '<option value="'.$thisvender['id'].'">'.$thisvender['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="XXRmain_3_1" style="margin-left: 80px;display: none">
                    <div class="XXRmain_3_2">锅炉数量</div>
                    <select type="text" class="XXRmain_3_4" id="guolu_count" style="width: 344px">
                        <option value="0">全部</option>
                        <?php
                        if($guolu_count_search){
                            foreach ($guolu_count_search as $thiscount){
                                echo '<option value="'.$thiscount.'">'.$thiscount.'台</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <button class="XXRmain_3_5" id="guolu_search">确定</button>
            </div>
            <div class="XXRmain_4" id="guolu_plan">
            </div>
            <div id="buttoning1" style="margin: 0 auto 99px auto;display: flex;justify-content: space-around;padding-top: 90px;">
                <button id="GLprior" class="GLXXmain_4 newStyle">上一步</button>
                <button id="GLnext" class="GLXXmain_4 newStyle">下一步</button>
            </div>

        </div>
    </div>
    <script>
        $(function () {

            var plan = <?php echo json_encode($rs['plan']); ?>;   //锅炉结果数组
            var http_path = '<?php echo $HTTP_PATH; ?>';
            $('#GLnext').click(function () {
                var guolu_id = $('input[name="guolu_sure"]:checked ').val();

                if(guolu_id == '' || guolu_id == undefined || guolu_id=="#"){
                    layer.msg('未选择方案');
                    return false;
                }


                var guolu_num = $('input[name="guolu_sure"]:checked ').data('value');

                var guolu_attr=<?php echo $rs['total_Q']; ?>;
                var str_x='<?php echo $str_x;?>';


                var index = layer.load(0, {shade: false});
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        guolu_id : guolu_id,
                        guolu_num : guolu_num,
                        guolu_attr:guolu_attr,
                        total_exchange_q : '<?php echo serialize($rs['total_exchange_Q']);?>',
                        str_x:str_x
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=guolu_selected',
                    success :     function(data){
//                        alert(data);
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                $('#prev').css('display','none');
                                location.href="selection_result_fuji.php?id=<?php echo $id;?>";
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
            $('#GLprior').click(function () {
                var id = <?php echo $id?>;

                location.href="selection.php?id="+id;

            });
            jQuery.extend({
                getData:function (plan, count, vender) {
                    $('#guolu_plan').html('');

                    if(plan)
                    {
                        for(var i = 0;i<plan.length;i++){

                            <?php $i=0;?>;
                            var result = plan[i];
                            var showflag = 0;
                            if(count != 0 && count != result['count']){
                                showflag = 1;
                            }
                            if(vender != 0 && vender != result['guolu_vender']){
                                showflag = 1;
                            }
                            if(showflag == 0){
                                var str='';
                                str += '<div class="XXRmain_5">';
                                str += '    ' +
                                    '<input type="radio" class="XXRmain_5_1" name="guolu_sure" data-value="' + result['count'] + '" value="' + result['guolu_id'] + '" >';
                                str += '	<div class="XXRmain_5_2" id="plan">方案' + (i + 1) + '</div>';
                                str += '	<div class="XXRmain_5_3">' + result['guolu_version'] + '</div>';
                                str += '	<div class="XXRmain_5_4">' + result['count'] + '台</div>';
                                str += '	<div class="XXRmain_5_5"><a href="' + http_path + 'product/guolu_details.php?id=' + result['guolu_id'] + '" target="_blank">查看详情 >></a></div>';
                                str += '</div>';
                                $('#guolu_plan').append(str);
                            }
                            <?php $i++;?>;
                        }
                    }

                }
            });
            $.getData(plan, 0, 0);   //初始状态
            $('#guolu_search').click(function () {
                var count = $('#guolu_count').val();
                var vender = $('#guolu_vender').val();
                $.getData(plan, count, vender);
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
    </script>

</body>
</html>