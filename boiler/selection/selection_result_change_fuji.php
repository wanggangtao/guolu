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
$plan_front_info = Selection_plan_front::getInfoByHistoryId($id);
$plan_front_id = null;
if (!empty($plan_front_info)) {
    $plan_front_id = $plan_front_info['id'];
}
$info = Selection_history::getInfoById($id);
$inCase = Selection_fuji::getInfoByHistory_Id($id);
$banhuan =$banhuan_remark=$fuji_remark=null;
$beng = "";
if(!empty($inCase)){
    foreach ($inCase as $item){

        if($item['name']=="板换" and $item['add_type']==1){
            $banhuan="checked";
            if($item['context'])
                $banhuan_remark = $item['context'];
        }
        if($item['name']=="锅炉循环泵" and $item['add_type']==1){
            $beng = "checked";
            if($item['context'])
            $fuji_remark = $item['context'];
        }
    }
}
if(empty($info)){
    echo '非法操作！';
    die();
}

/*if ($info['user'] != $USERId) {
    echo '没有权限操作！';
    die();
}*/


$guoluinfo = array();

$guolu_ids = explode(",",$info['guolu_id']);
$guolu_nums= explode(",",$info['guolu_num']);
$guolu_context = explode(",",$info['guolu_context']);
$page = 1;
$pageSize = 1;

foreach ($guolu_ids as $key=> $guolu_id)
{
    $guoluinfoItem = array();
    $guoluinfoItem["guolu_id"] = $guolu_id;
    $guoluinfoItem["guolu_num"] = $guolu_nums[$key];

    $guoluinfo[] = $guoluinfoItem;
}

$guolus = array();
foreach ($guolu_ids as $key=> $guolu_id)
{

    $guolu_Item = array();

    $guoluinfos = Guolu_attr::getInfoById($guolu_id?$guolu_id:0);

    $addtype = isset($_GET['addtype'])?safeCheck($_GET['addtype']):0;
    $sttime = isset($_GET['sttime'])?safeCheck($_GET['sttime']):0;
    $endtime = isset($_GET['endtime'])?safeCheck($_GET['endtime']):0;
    $countarr     = Case_pricelog::getPageList($page, $pageSize, 0, 1, $guoluinfos['proid'], $addtype, $sttime, $endtime);
    $count     = $countarr['ct'];
    $prices = Case_pricelog::getPageList($page, $pageSize, 1, 1, $guoluinfos['proid'], $addtype, $sttime, $endtime);


    $guolu_Item["guoluinfo"] = $guoluinfos;
    $guolu_Item["prices"] = $prices;
    $guolu_Item["countarr"] = $countarr;
    $guolu_Item["guolu_num"] = $guolu_nums[$key];
    if(!empty($guolu_context[$key])){
        $guolu_Item['context'] = $guolu_context[$key];
    }
    $guolus[] = $guolu_Item;

}

$dataArr = array(
    'guoluinfo' => $guoluinfo,
    'is_condensate' => $info['is_condensate'],
    'is_lownitrogen' => $info['is_lownitrogen'],
    'heating_type' => $info['hm_heating_type'],
    'board_power' => $info['board_power'],

);

//var_dump($dataArr);
$data = 'jsonstr='.json_encode($dataArr);
$curl     = $HTTP_PATH.'api/selection/select_change_fuji.php';
$rs       = json_decode(Curl::post($curl,$data),true);


$heating = array();

if($rs["code"] ==1)
{
    $heating = $rs["msg"];
}


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
            $(".detail").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '锅炉详情',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1200px', '600px'],
                    content: 'guolu_info.php?id='+thisid
                });
            });
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
    <style type="text/css">
    .XXRmain_11{
        width: 400px;
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
<body class="body_2">
<?php include('top.inc.php');?>
<!--<div class="guolumain">-->
<!--    <div class="guolumain_1">当前位置：锅炉选型 ><span>选型结果</span></div><div class="clear"></div>-->
<!--</div>-->
<div class="manageHRWJCont_middle_middle" align="center">

    <div id="step" style="margin-top: 30px">
        <div class="step-wrap">
            <div class="step-list">
                <div class="step-num">
                    <a href="selection_change_old.php?id=<?php echo $id;?>"><div class="num-bg">1</div></a>
                </div>
                <span class="step-name">选型</span>
            </div>
            <div class="step-line"></div>
            <?php if($info['status']==5){
                ?>
                <div class="step-list">
                    <div class="step-num">
                        <a href="selection_plan_one.php?id=<?php echo $id;?>"><div class="num-bg">2</div></a>
                    </div>
                    <span class="step-name">报价</span>
                </div>
                <div class="step-line"></div>
                <div class="step-list">
                    <div class="step-num">
                        <a href="selection_plan_two.php?id=<?php echo $id;?><?php echo'&front_plan_id='?><?php echo $plan_front_id ?>"><div class="num-bg">3</div></a>
                    </div>
                    <span class="step-name">方案</span>
                </div>
            <?}else{?>
                <div class="step-list">
                    <div class="nums">2</div>
                    <span class="step-names">报价</span>
                </div>
                <div class="step-lines"></div>
                <div class="step-list">
                    <div class="nums">3</div>
                    <span class="step-names">方案</span>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<div class="XXresult">
    <div id="next" class="XXRmain">
        <div class="GLXX1_main2" id="box"><!--锅炉-->
            <table class="XXRmain_7">
                <div class="XXRmain_1">锅炉</div>
                <tr class="GLDetils9_fir">
                    <td width="20%">锅炉名称</td>
                    <td width="10%">数量</td>
                    <td width="10%">厂家</td>
                    <td width="10%">规格型号</td>
                    <td width="10%">查看详情</td>
                    <td width="20%">备注</td>
                </tr>
                <?php



                if(!empty($guolus))
                {

                    foreach ($guolus as $guolu)
                    {

                        $vendername = "";

                        $guoluinfo = $guolu["guoluinfo"];

                        $countarr = $guolu["countarr"];
                        $prices = $guolu["prices"];
                        $guolu_num = $guolu["guolu_num"];

                        if ($guoluinfo) {//获取厂家的名称
                            $venderinfo = Dict::getInfoById($guoluinfo['vender']);
                            $vendername = $venderinfo['name'];
                        }

                        $version = $guoluinfo ? $guoluinfo['version'] : '';


                        $guolu_maxprice = $countarr['maxprice'] ? floatval($countarr['maxprice']) : 0;
                        $guolu_minprice = $countarr['minprice'] ? floatval($countarr['minprice']) : 0;
                        $guolu_avgprice = $countarr['avgprice'] ? round($countarr['avgprice'],2) : 0;
                        $guolu_newprice = $prices?floatval($prices[0]['price']) : 0;
                        ?>
                        <tr class="guolu">
                            <td class="center">锅炉</td>
                            <td class="center"><?php echo $guolu_num; ?>台</td>
                            <td class="center"><?php echo $vendername; ?></td>
                            <td class="center"><?php echo $version ?></td>
                            <td>
                                <a href="javascript:void(0)" class="detail">查看详情</a>
                                <input type="hidden" id="aid" value="<?php echo $guoluinfo['id']?>"/></td>
                            <td><input type="text" id="guolu_context" name="text" value="<?php if(!empty($guolu['context'])) echo $guolu['context']?>"></td>
                        </tr>


                        <?php


                    }
                }
                ?>
            </table>


        </div><!--锅炉-->
            <div id="heatingDiv" style="display: <?php if($info['application'] == 0 || $info['application'] == 2 ) echo 'block'; else echo 'none'; ?>">
                <div class="XXRmain_1">采暖辅机</div>
                <table class="XXRmain_7">
                    <tr class="GLDetils9_fir">
                        <td>设备名称</td>
                        <td>数量</td>
                        <td>计算参数</td>
                        <td >厂家</td>
                        <td >规格型号</td>
                        <td>查看详情</td>
                        <td>备注</td>
                        <td >是否添加到方案里</td>
                    </tr>


                    <!--锅炉循环泵-->
                    <?php
                    if(!empty($heating)) {
                        if ($heating["pipeline_pump"]) {

                            $pipeline_pump = $heating["pipeline_pump"]["pump"];

                            ?>
                            <tr>
                                <td>
                                    <?php echo $heating['pipeline_pump']['name']; ?>
                                    <p style="line-height: 5px;">
                                        <input type="hidden" id="pipeline_pump_count"
                                               value="<?php echo $heating['pipeline_pump']['count']; ?>">
                                        <input type="hidden" id="pipeline_pump_name"
                                               value="<?php echo $heating['pipeline_pump']['name']; ?>">
                                        <input type="hidden" class="pipeline_pump_flow"
                                               value="<?php echo $heating['pipeline_pump']['flow']; ?>">
                                        <input type="hidden" class="pipeline_pump_lift"
                                               value="<?php echo $heating['pipeline_pump']['lift']; ?>">
                                        <a href="javascript:void(0);" class="pipeline_pump_edit"
                                           data-radioname="pipeline_pump" style="color: #04A6FE;">修改扬程</a></p>
                                </td>
                                <td><?php echo $heating['pipeline_pump']['count'] ? $heating['pipeline_pump']['count'] : 2; ?>
                                    台
                                </td>
                                <td>流量=<?php echo $heating['pipeline_pump']['flow']; ?>m³/h </br>
                                    扬程=<?php echo $heating['pipeline_pump']['lift']; ?>m
                                </td>
                                <td colspan="3">
                                    <?php
                                    if ($pipeline_pump) {

                                        foreach ($pipeline_pump as $pipelineinfo) {
                                            $thispv = Dict::getInfoById($pipelineinfo['vender']);
                                            echo '
                                            <div class="XXRmain_8">
                                                <input type="radio" class="XXRmain_9 power_compute" name="pipeline_pump" value="' . $pipelineinfo['id'] . '" '.$beng.'/>
                                                <input type="hidden" class="motorpower" value="' . $pipelineinfo['motorpower'] . '">
                                                <div class="XXRmain_10">' . $thispv['name'] . '</div>
                                                <div class="XXRmain_11">' . $pipelineinfo['version'] . '</div>
                                                <a href="javascript:void(0);" onclick="pipeline_detail(' . $pipelineinfo['id'] . ')">详情</a>
                                            </div>
                                            ';
                                        }
                                    } else {
                                        echo "没有找到合适的循环泵";
                                    }
                                    ?>

                                </td>
                                <td><input type="text" id="fuji_context" size="4" name="text_1" value="<?php echo $fuji_remark;?>"></td>
                                <td><input type="checkbox" id="pipeline_pump_check_btn" name="check_btn"
                                           value="pipeline_pump_check_btn" <?php echo $beng;?>></td>
                            </tr>
                            <?php
                        }


                        //采暖板换数据
                        if ($heating['board']) {
                            $board = $heating['board'];
                            echo '
                                    <tr>
                                        <td>' . $board['name'] . '</td>
                                        <td>' . $board['count'] . '台</td>
                                        <td>&nbsp;</td>
                                        <td colspan="3">
                                            <div class="XXRmain_8">
                                                <div class="XXRmain_10">&nbsp;</div>
                                                <div class="XXRmain_11 board_data" style="line-height: 50px;">
                                                    <input type="hidden" class="board_name" value="' . $board['name'] . '">
                                                    <input type="hidden" class="board_count" value="' . $board['count'] . '">
                                                    <span class="board_value fenqu_board">一次侧供回水温度' . $board['once_sarwt'] . '
                                                        &nbsp;二次侧供回水温度' . $board['twice_sarwt'] . '
                                                        &nbsp;换热量' . $board['exchange_Q'] . 'kw
                                                        &nbsp;承压' . $board['pressure_bearing'] . 'MPa</span>
                                                </div>
                                            </div>
                                            
                                        </td>
                                         <td><input type="text" id="banhuan_context" size="4" name="text_2" value="'.$banhuan_remark.'"></td>
                                        <td><input type="checkbox" id="board_data_check_btn" name="board_data_check" value="board_data_check_btn" '.$banhuan.'></td>
                                    </tr>';

                        }
                    }

                    ?>

                </table>
            </div>


<!--        <div class="XXRmain_17"><span>添加备注</span></div>-->
<!--        <textarea class="XXRmain_18" id="remark">--><?php //if(!empty($info['remark'])) echo $info['remark']?><!--</textarea>-->
        <div class="btns" style="margin: 0 auto;display: flex;justify-content: space-around;padding-top: 90px;margin-bottom: 90px">
            <button id="prior_fuji" class="GLXXmain_4" >上一步</button>
            <button id="subimt_fuji" class="GLXXmain_4">下一步</button>
        </div>
    </div>
</div>
<script>
    $(function () {

        $('#prior_fuji').click(function(){

            var id=<?php echo  $id;?>;
            location.href="selection_change_old.php?id="+id;

        });

        //采暖循环泵修改扬程
        $('.pipeline_pump_edit').click(function () {
            var guolutype = '<?php echo $info['heating_type']; ?>';
            var obj = $(this);
            var inputname = obj.attr("data-radioname");
            var fenqu = obj.parent().attr("data-fenqu");
            if(fenqu == undefined) fenqu = '';
            inputname = inputname + fenqu;
            var pipeline_pump_flow = obj.parent().find('.pipeline_pump_flow').val();
            var pipeline_pump_lift = obj.parent().find('.pipeline_pump_lift').val();
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
                                    boardtext = boardtext + '承压' + pressure;
                                    $('.fenqu_board'+ fenqu).html(boardtext);
                                }
                                break;
                        }
                    }
                });
            });
        });

        $('#subimt_fuji').click(function () {



            var pipeline_pump_check = 0;

            if($("#pipeline_pump_check_btn").is(':checked'))
            {
                pipeline_pump_check = 1;
            }



            //锅炉循环泵
            var pipeline_pump_id =  $('input[name="pipeline_pump"]:checked ').val();
            var pipeline_pump_name =  $('#pipeline_pump_name').val();
            var pipeline_pump_count =  $('#pipeline_pump_count').val();
            if(!pipeline_pump_id){
                pipeline_pump_id = 0;
                pipeline_pump_name = '';
                pipeline_pump_count = 0;
            }
            var obj = $(this);
            var pipeline_pump_flow = obj.parent().find('.pipeline_pump_flow').val();
            var pipeline_pump_lift = obj.parent().find('.pipeline_pump_lift').val();


            //板换
            var length = $(".board_data").length;
            var board_value = "";
            var board_name = "";
            var board_count = "";
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
            }
            var fuji_remark = $('#fuji_context').val();
            var banhuan_remark = $('#banhuan_context').val();
            var index = layer.load(0, {shade: false});
            var context =[];
            $("input[name='text']").each(function(){
                context.push($(this).val());
            })

            $.ajax({
                type        : 'POST',
                data        : {
                    id : <?php echo $id;?>,
                    pipeline_pump_id    : pipeline_pump_id,
                    pipeline_pump_name  : pipeline_pump_name,
                    pipeline_pump_count : pipeline_pump_count,
                    pipeline_pump_flow : pipeline_pump_flow,
                    pipeline_pump_lift : pipeline_pump_lift,
                    board_count : board_count,
                    board_check:board_check,
                    board_value : board_value,
                    board_name : board_name,
                    fuji_remark : fuji_remark,
                    banhuan_remark :banhuan_remark,
                    pipeline_pump_check:pipeline_pump_check,
                    context : context
                },
                dataType :    'json',
                url :         'selection_do.php?act=fuji_change_selected',
                success :     function(data){
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

</script>
</body>
</html>