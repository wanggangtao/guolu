<?php
/**
 * 选型方案一 selection_plan_1.php
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

$id = isset($_GET['id'])?safeCheck($_GET['id']):0;//获取项目的id，即历史id

//选型入口
//$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;


//$id =1510;
$info = Selection_history::getInfoById($id);
$guolu_vender_list=Dict::getListByParentid(1);//获取锅炉厂家列表
//print_r($info);

if(empty($info)){
    echo '非法操作!';
    die();
}

/*根据历史id从Selection_history表中获取锅炉的相关数据*/
//根据获得的锅炉的id从guolu_attr获取该锅炉所对应的proid作为objectid参数
//从prielog表中获取最高，最低、平均价格和添加的最新价格
//type=1表示从产品中心获取锅炉和辅机的报价，type=2表示从选型方案获取其他项报价



$guolu_ids = explode(",",$info['guolu_id']);
$guolu_nums = explode(",",$info['guolu_num']);
if($info['guolu_context']){
    $guolu_contexts=explode(",",$info['guolu_context']);
}else{
    $guolu_contexts="";
}



$page = 1;
$pageSize = 1;

$guolus = array();
foreach ($guolu_ids as $key=> $guolu_id)
{

    $guoluItem = array();

    $guoluinfo = Guolu_attr::getInfoById($guolu_id?$guolu_id:0);

    $addtype = isset($_GET['addtype'])?safeCheck($_GET['addtype']):0;
    $sttime = isset($_GET['sttime'])?safeCheck($_GET['sttime']):0;
    $endtime = isset($_GET['endtime'])?safeCheck($_GET['endtime']):0;
    $countarr     = Case_pricelog::getPageList($page, $pageSize, 0, 1, $guoluinfo['proid'], $addtype, $sttime, $endtime);
    $count     = $countarr['ct'];
    $prices = Case_pricelog::getPageList($page, $pageSize, 1, 1, $guoluinfo['proid'], $addtype, $sttime, $endtime);


    $guoluItem["guoluinfo"] = $guoluinfo;
    $guoluItem["prices"] = $prices;
    $guoluItem["countarr"] = $countarr;
    $guoluItem["guolu_num"] = $guolu_nums[$key];
    $guoluItem["guolu_context"] = !empty($guolu_contexts)?$guolu_contexts[$key]:'';

    $guolus[] = $guoluItem;

}


/*获取其他项表单的相关数据*/
$rows      = Case_tpl::getListByAttrid(13, 1, 8, $count = 0);
//从case_attr表中报价方案的attrid=13，利用attrid从case_tpl表中提取属性名称



//$attrid = 7;
////查询方案模板列表
//$rowsTpl = Case_tpl::getListByAttrid($attrid, null, null, $count = 0);



?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>选型方案</title>
    <link rel="stylesheet" href="css/scheme.css" />
    <link rel="stylesheet" href="css/Tc.css" />

    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/GLXX1.css" />
    <script type="text/javascript" src="js/nav.js" ></script>
    <script type="text/javascript" src="js/2.0.0/jquery.min.js" ></script>
    <script type="text/javascript" src="js/laydate.js" ></script>

    <script type="text/javascript" src="layer/layer.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>


    <link rel="stylesheet" href="css/GLXX2.css" />
    <script type="text/javascript" src="js/layer.js" ></script>

    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>

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

</head>
<body class="body_2">

      <?php include('top.inc.php');?>

      <div class="manageHRWJCont_middle_middle">

          <div id="step" style="margin-top: 30px">
              <div class="step-wrap" align="center">
                  <div class="step-list">
                      <div class="step-num" id="selection_one">
                          <?php
                          if($info['type'] == 1) {//智能选型
                              ?>
                              <a href="selection.php?id=<?php echo $id ?>" </a>
                              <div class="num-bg">1</div>
                              <?php
                          } elseif($info['type'] == 3){//更换锅炉
                              ?>
                              <a href="selection_change_old.php?id=<?php echo $id?>" </a>
                              <div class="num-bg">1</div>
                              <?php
                          }
                          ?>
                      </div>
                      <span class="step-name">选型</span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="step-num " id="selection_two">
                          <?php
                          if($info['type'] == 1) {//智能选型
                              ?>
                              <div class="num-bg"><a href="selection_make_price.php?id=<?php echo $id ?>&&isUpdate=0" style="color: white">2</a>
                              </div>
                              <?php
                          } elseif($info['type'] == 3){//更换锅炉
                              ?>
                              <div class="num-bg"><a href="selection_make_price.php?id=<?php echo $id?>&&isUpdate=0" style="color: white">2</a>
                              </div>
                              <?php
                          }
                          ?>
                      </div>
                      <span class="step-name">报价</span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-list">
                      <div class="nums">3</div>
                      <span class="step-names">方案</span>
                  </div>
              </div>
          </div>

      </div>

      <!--方案一页面-->
      <div style="margin-top: 20px;">


             <div class="GLXX1_main1">
                 <p class="p2">请选择推荐价格的有效时间</p>
                 <input  id="sttime"  value="<?php echo $sttime?date('Y-m-d',$sttime):'';?>" />
                 <p class="p3">—</p>
                 <input  id="endtime"  value="<?php echo $endtime?date('Y-m-d',$endtime):'';?>" />
                 <input  id="search" type="button"  value="查询"/>
             </div><!--时间检索-->
            <script>

                laydate.render({
                    elem: '#sttime' //需显示日期的元素选择器
                    ,event: 'click' //触发事件
                    ,format: 'yyyy-MM-dd' //日期格式
                    ,istime: false //是否开启时间选择
                    ,isclear: true //是否显示清空
                    ,istoday: true //是否显示今天
                    ,issure: true //是否显示确认
                    ,festival: true //是否显示节日
                    ,choose: function (dates) { //选择好日期的回调
                    }
                });
                laydate.render({
                    elem: '#endtime' //需显示日期的元素选择器
                    ,event: 'click' //触发事件
                    ,format: 'yyyy-MM-dd' //日期格式
                    ,istime: false //是否开启时间选择
                    ,isclear: true //是否显示清空
                    ,istoday: true //是否显示今天
                    ,issure: true //是否显示确认
                    ,festival: true //是否显示节日
                    ,choose: function (dates) { //选择好日期的回调
                    }
                });



                $(function() {
                    //查找
                    $('#search').click(function () {
                        // var addtype = $('#addtype').val();
                        var sttime = 0;
                        var sttimestr = $('#sttime').val() + " 00:00:00";

                        if ($('#sttime').val() != '') {
                            sttime = Date.parse(new Date(sttimestr)) / 1000;
                        } else {
                            sttime = 0;
                        }
                        var endtime = 0;
                        var endtimestr = $('#endtime').val() + " 23:59:59";
                        if ($('#endtime').val() != '') {
                            endtime = Date.parse(new Date(endtimestr)) / 1000;
                        } else {
                            endtime = 0;
                        }
                         // alert($id);

                        location.href = "selection_plan_one.php?id=" + '<?php echo $id;?>'+ "&sttime=" + sttime + "&endtime=" + endtime;
                    });


                    /*如果选择价格框则自定义价格的文本框清空*/
                    // $(".price_input").click(function(){
                    //
                    //     $(this).parent().parent().find(".inputing").attr("checked",false);
                    //
                    // });


                    /*其他项表单的清除数据操作*/
                    $(".clearAll").click(function(){

                        $(this).parent().parent().find(".inputing").attr("checked",false);

                        $(this).parent().find(".price_input").val("");

                        /*如果选择价格框则自定义价格的文本框清空*/

                    });
                });
                    // 手动输入价格时清除选中
                    $(document).on('click','.price_input',function(){
                        $(this).parent().parent().find(".inputing").attr("checked",false);

                        // 锅炉表单
                        $(this).parent("td").find("#guolu_add_price").val("");
                        $(this).parent("td").parent("tr").find("#choose_guolu_price").val("");
                        // 采暖辅机表单
                        $(this).parent("td").find("#heat_add_price").val("");
                        $(this).parent("td").parent("tr").find("#choose_heat_price").val("");
                        // 热水辅机表单
                        $(this).parent("td").find("#water_add_price").val("");
                        $(this).parent("td").parent("tr").find("#choose_water_price").val("");

                        // 其他项表单
                        $(this).parent("td").find("#other_add_price").val("");
                        $(this).parent("td").parent("tr").find("#choose_other_price").val("");
                    });

                    $(document).on('click','.inputing',function(){
                        var val = $(this).val();

                        //锅炉表单
                        $(this).parent("td").parent("tr").find(".price_input").val("");
                        $(this).parent("td").parent("tr").find("#choose_guolu_price").val(val);
                        //采暖辅机表单
                        $(this).parent("td").parent("tr").find(".price_input").val("");
                        $(this).parent("td").parent("tr").find("#choose_heat_price").val(val);
                        //热水辅机表单
                        $(this).parent("td").parent("tr").find(".price_input").val("");
                        $(this).parent("td").parent("tr").find("#choose_water_price").val(val);
                        //其他项表单
                        $(this).parent("td").parent("tr").find(".price_input").val("");
                        $(this).parent("td").parent("tr").find("#choose_other_price").val(val);
                    });


            </script>
            <div class="GLXX1_main2" ><!--锅炉-->
                <p class="GL" style="width:666px">锅炉</p>
                <table >
                    <tr style="background-color: #E5F6FE;" >
                        <td>设备名称</td>
                        <td style="width: 242px;">厂家</td>
                        <td style="width: 242px;">规格型号</td>
                        <td style="width: 100px;">数量</td>
                        <td style="width: 100px;">备注</td>
                        <td>最高价(万元)</td>
                        <td>最低价(万元)</td>
                        <td>均价(万元)</td>
                        <td>上次(万元)</td>
                        <td style="width: 144px;">自定义(万元)</td>
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
                                <td class="center" id="guolu_vender" data-value="<?php echo $guoluinfo["vender"]; ?>"><?php echo $vendername ?></td>
                                <td class="center" id="guolu_version" data-value="<?php echo $version; ?>"><?php echo $version ?></td>
                                <td class="center" id="guolu_num" data-value="<?php echo $guolu_num; ?>"><?php echo $guolu_num; ?></td>
                                <td class="center" id="guolu_context" data-value="<?php echo $guolu["guolu_context"]; ?>"><?php echo $guolu["guolu_context"]; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["proid"]?>"   value="<?php echo $guolu_maxprice; ?>"  class="inputing" /><?php echo $guolu_maxprice; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["proid"]?>"   value="<?php echo $guolu_minprice; ?>" class="inputing" /><?php echo $guolu_minprice; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["proid"]?>"   value="<?php echo $guolu_avgprice; ?>" class="inputing" /><?php echo $guolu_avgprice; ?></td>
                                <td><input type="radio" name="price_guolu_<?php echo $guoluinfo["proid"]?>"   value="<?php echo $guolu_newprice; ?>" class="inputing"/><?php echo $guolu_newprice; ?></td>
                                <td class="defined" style="width:35px;">
                                    <input type="number" name="textprice" id="guolu_add_price" value="" class="price_input" style="max-width:55%;"  />
                                    <input type="hidden" name="choose_guolu_price" id="choose_guolu_price" value=""/>
                                    <input type="hidden" name="attrid" id="attrid" value="<?php echo $guoluinfo["id"]; ?>"/>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                    ?>

                </table>
                <div class="GLXXmain_16">
                    <span class="addgl" style="display: block;margin-left: 120px;margin-top: 10px" >添加锅炉报价项</span>
                </div>
            </div><!--锅炉-->
            <?php
            $heat_fujilist = Selection_fuji::getInfoByHistoryIdandAddtype($id, 0,1);
            //type=0表示采暖辅机
            //addtype=1表示在之间辅机辅机页面选择了辅机，页面就会出现被选中辅机的表单
            if($heat_fujilist){
            ?>
            <div class="GLXX1_main3" ><!--采暖辅机-->
                <p class="GL" style="width:666px;position:relative;margin-top:30px;">采暖辅机</p>
                <table style="position:relative;">
                    <tr style="background-color: #E5F6FE;">
                        <td style="width: 140px;">设备名称</td>
                        <td style="width: 214px;">规格型号</td>
                        <td style="width: 80px;">数量</td>
                        <td style="width: 100px;">备注</td>
                        <td>最高价(万元)</td>
                        <td>最低价(万元)</td>
                        <td>均价(万元)</td>
                        <td>上次(万元)</td>
                        <td>自定义(万元)</td>
                    </tr>
                    <?php
                    foreach ($heat_fujilist as $item){

                        if($item['data_type'] == 1){
                            if($item['value']) {

                                $modelInfo = products_model::getInfoById($item['modelid']);


                                if(!empty($modelInfo))
                                {
                                    $detailinfo = $modelInfo["attrname"]::getInfoById($item['value']);

                                    if ($detailinfo) {
//
//                                        if($item['modelid']==8)
//                                        {
//                                            $vendername = "现场制作";
//                                        }
//                                        else if($item['modelid']==9)
//                                        {
//                                            $vendername = "";
//                                        }
//                                        else
//                                        {
//                                            $vendernameinfo = Dict::getInfoById($detailinfo['vender']);
//                                            $vendername = $vendernameinfo['name'];
//                                        }
                                        //水泵要有计算参数
                                        if($item['modelid']==4 ){//循环泵
                                            if($item['name'] == "锅炉循环泵" || $item['name'] == "一次侧循环泵"){//锅炉循环泵
                                                $var=json_decode("{$item['param']}",true);
                                                $param="Q=".$var["pipeline_pump_flow"].","."H=".$var["pipeline_pump_lift"];
                                                $version=$detailinfo['version']."  ".$param;
                                            }else{//采暖循环泵
                                                $var=json_decode("{$item['param']}",true);
                                                $param="Q=".$var["heating_pump_flow"].","."H=".$var["heating_pump_lift"];
                                                $version=$detailinfo['version']."  ".$param;
                                            }
                                        }elseif($item['modelid']==5){//补水泵
                                            $var=json_decode("{$item['param']}",true);
                                            $param="Q=".$var["water_pump_flow"].","."H=".$var["water_pump_lift"];
                                            $version=$detailinfo['version']."  ".$param;
                                        }else{
                                            $version=$detailinfo['version'];
                                        }



                                        $fuji_price = Case_pricelog::getPageList(1, 10, 0, 1, $detailinfo['proid'], $addtype, $sttime, $endtime);
                                        $maxprice = $fuji_price['maxprice'] ? floatval($fuji_price['maxprice']) : 0;
                                        $minprice = $fuji_price['minprice'] ? floatval($fuji_price['minprice']) : 0;
                                        $avgprice = $fuji_price['avgprice'] ? round($fuji_price['avgprice'], 2) : 0;
                                        $fuji_prices = Case_pricelog::getPageList($page, $pageSize, 1, 1, $detailinfo['proid'], $addtype, $sttime, $endtime);
                                        $before_price = $fuji_prices ? floatval($fuji_prices[0]['price']) : 0;
                                        echo '
                                            <tr class="heat_fuji_sel">
                                                <td>' . $item['name'] . '</td>
                                                <td>' . $version . '</td>
                                                <td>' . $item['num'] . '</td>
                                               <td>' . $item['context'] . '</td>';
//                                        echo '<td>' . $vendername . '</td>';
                                        //这里前面用price1（带上了个1） 是防止和 下面的方案部分的id重复
                                        echo '<td><input type="radio" name="price_fuji_'.$item["id"].'" value="' . $maxprice . '" class="inputing"/>' . $maxprice . '</td>';
                                        echo '<td><input type="radio" name="price_fuji_'.$item["id"].'" value="' . $minprice . '" class="inputing"/>' . $minprice . '</td>';
                                        echo '<td><input type="radio" name="price_fuji_'.$item["id"].'" value="' . $avgprice . '" class="inputing"/>' . $avgprice . '</td>';
                                        echo '<td><input type="radio" name="price_fuji_'.$item["id"].'" value="' . $before_price . '" class="inputing"/>' . $before_price . '</td>';
                                        echo '<td class="defined">
    					                        <input type="number" name = "textprice" id="heat_add_price" value="" class="price_input"  style="max-width:55%;"/>
    					                    
    					                        <input type="hidden" name = "choose_heat_price" id="choose_heat_price" value=""  />
    					                        <input type="hidden" name = "hid" id="hid" value="' . $item['id'] . '"  />
    					                        <input type="hidden" name = "hmid" id="hmid" value="' .  $item['modelid']. '"  />
    					                       </td></tr>';
                                    }

                                }
                            }
                        }else{
                            $numdesc = "";
                            if($item['num']){
                                $numdesc = $item['num'];

                            }
                            $version="";
                            if ((substr_count($item['name'],"不锈钢保温水箱")) == 1){
                                $version="公称容积".$item['value']."m³";
                            }else{
                                $version=HTMLDecode($item['value']);
                            }
                            echo '
                                    <tr class="heat_fuji_text">
                                        <td>'.$item['name'].'</td>
                                        <td>'.$version.'</td>
                                        <td>'.$numdesc.'</td>
                                        <td>' . $item['context'] . '</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="defined"> 
                                             <input type="number" name = "textprice" id="heat_add_price" value="" class="price_input"  style="max-width:55%;"/>
                                            <input type="hidden" name = "hid" id="hid" value="'.$item['id'].'"/>
                                        </td>
                                    </tr>
                                ';
                        }

                    }
                    ?>
                    <!-------添加的自定义采暖辅机表单------->
                    <tr class="heat_addtr"  style="display: none">
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="heat_name"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="heat_version"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="number" value="" id="heat_num"/>
                        </td>
                        <td >
                            <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="heat_context"/>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="defined">
                            <input type="number" name="heat_price" id="heat_price" value="" class="price_input"  style="max-width:55%;" />
                            <span   style="color: red" class="mouheat" id="delete_heat" />删除</span>
                        </td>
                    </tr>
                </table>
                <div class="GLXXmain_16">
                    <span class="addhfj" style="display: block;margin-left: 120px;margin-top: 10px">添加采暖辅机报价项</span>
                </div>
            </div><!--采暖辅机-->
                <?php
                }
                ?>
                <?php

                $water_fujilist = Selection_fuji::getInfoByHistoryIdandAddtype($id, 1,1);
                //type=1表示热水辅机
                //addtype=1表示在之间辅机辅机页面选择了辅机，页面就会出现被选中辅机的表单
                if($water_fujilist){
                ?>
                <div class="GLXX1_main4" ><!--辅机-->
                    <p class="GL" style="width: 666px;position:relative;margin-top:30px;">热水辅机</p>
                    <table style="position:relative;">
                        <tr style="background-color: #E5F6FE;">
                            <td style="width: 140px;">设备名称</td>
                            <td style="width: 214px;">规格型号</td>
                            <td style="width: 80px;">数量</td>
                            <td style="width: 100px;">备注</td>
                            <td>最高价(万元)</td>
                            <td>最低价(万元)</td>
                            <td>均价(万元)</td>
                            <td>上次(万元)</td>
                            <td>自定义(万元)</td>
                        </tr>
                        <?php
                        foreach ($water_fujilist as $item){
                            if($item['data_type'] == 1){


                                if($item['value']) {

                                    $modelInfo = products_model::getInfoById($item['modelid']);

                                    if(!empty($modelInfo)) {
                                        $detailinfo = $modelInfo["attrname"]::getInfoById($item['value']);
                                        if ($detailinfo) {
//                                            if($item['modelid']==8)
//                                            {
//                                                $vendername = "现场制作";
//                                            }
//                                            else if($item['modelid']==9)
//                                            {
//                                                $vendername = "";
//                                            }
//                                            else
//                                            {
//                                                $vendernameinfo = Dict::getInfoById($detailinfo['vender']);
//                                                $vendername = $vendernameinfo['name'];
//                                            }
                                            //水泵要有计算参数
                                            if ($item['modelid'] == 4) {//循环泵
                                                if ($item['name'] == "锅炉循环泵") {//锅炉循环泵
                                                    $var = json_decode("{$item['param']}", true);
                                                    $param = "Q=" . $var["water_pipeline_pump_flow"] . "," . "H=" . $var["water_pipeline_pump_lift"];
                                                    $version = $detailinfo['version'] . "  " . $param;
                                                } else {//热水循环泵
                                                    $var = json_decode("{$item['param']}", true);
                                                    $param = "Q=" . $var["hotwater_pump_flow"] . "," . "H=" . $var["hotwater_pump_lift"];
                                                    $version = $detailinfo['version'] . "  " . $param;
                                                }
                                            } elseif ($item['modelid'] == 5) {//补水泵
                                                $var = json_decode("{$item['param']}", true);
                                                $param = "Q=" . $var["hotwater_water_pump_flow"] . "," . "H=" . $var["hotwater_water_pump_lift"];
                                                $version = $detailinfo['version'] . "  " . $param;
                                            } else {
                                                $version = $detailinfo['version'];
                                            }

                                            $fuji_price = Case_pricelog::getPageList(1, 10, 0, 1, $detailinfo['proid'], $addtype, $sttime, $endtime);
                                            $maxprice = $fuji_price['maxprice'] ? floatval($fuji_price['maxprice']) : 0;
                                            $minprice = $fuji_price['minprice'] ? floatval($fuji_price['minprice']) : 0;
                                            $avgprice = $fuji_price['avgprice'] ? round($fuji_price['avgprice'], 2) : 0;
                                            $fuji_prices = Case_pricelog::getPageList($page, $pageSize, 1, 1, $detailinfo['proid'], $addtype, $sttime, $endtime);
                                            $before_price = $fuji_prices ? floatval($fuji_prices[0]['price']) : 0;
                                            echo '
                                                <tr class="water_fuji_sel">
                                                    <td>' . $item['name'] . '</td>
                                                    <td>' . $version . '</td>
                                                    <td>' . $item['num'] . '</td>
                                                    <td>' . $item['context'] . '</td>';
                                            echo '<td><input type="radio" name="price_fuji_' . $item["id"] . '" value="' . $maxprice . '" class="inputing"/>' . $maxprice . '</td>';
                                            echo '<td><input type="radio" name="price_fuji_' . $item["id"] . '" value="' . $minprice . '" class="inputing"/>' . $minprice . '</td>';
                                            echo '<td><input type="radio" name="price_fuji_' . $item["id"] . '" value="' . $avgprice . '" class="inputing"/>' . $avgprice . '</td>';
                                            echo '<td><input type="radio" name="price_fuji_' . $item["id"] . '" value="' . $before_price . '" class="inputing"/>' . $before_price . '</td>';
                                            echo '<td class="defined">
    					                        <input type="number" name = "textprice" id="water_add_price" value="" class="price_input"  style="max-width:55%;" />
    					               
    					                        <input type="hidden" name = "choose_water_price" id="choose_water_price" value=""  />
    					                       <input type="hidden" name = "wid" id="wid" value="' . $item['id'] . '"  />
    					                       <input type="hidden" name = "wmid" id="wmid" value="' . $item['modelid'] . '"  />
    					                       </td></tr>';
                                        }
                                    }
                                }
                            }else{
                                $numdesc = "";
                                $version = "";
                                if($item['num']){
                                    $numdesc = $item['num'];
                                }
                                if ((substr_count($item['name'],"不锈钢保温水箱")) == 1){
                                    $version="公称容积".$item['value']."m³";
                                }else{
                                    $version=HTMLDecode($item['value']);
                                }
                                echo '
                                    <tr class="water_fuji_text">
                                        <td>'.$item['name'].'</td>
                                        <td>'.$version.'</td>
                                        <td>'.$numdesc.'</td>
                                        <td>' . $item['context'] . '</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="defined">
                                               <input type="number" name = "textprice" id="water_add_price" value="" class="price_input"  style="max-width:55%;"/>
                                       
                                            <input type="hidden" name = "wid" id="wid" value="'.$item['id'].'"/>
                                        </td>
                                    </tr>
                                    ';
                            }

                        }
                        ?>
                        <!-------添加的自定义热水辅机表单------->
                        <tr class="water_addtr"  style="display: none">
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="water_name"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="water_version"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="number" value="" id="water_num"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="water_context"/>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="defined">
                                <input type="number" name="water_price" id="water_price" value="" class="price_input"  style="max-width:55%;" />
                                <span   style="color: red" class="mouwater" id="delete_water" />删除</span>
                            </td>
                        </tr>
                    </table>
                    <div class="GLXXmain_16">
                        <span class="addwfj" style="display: block;margin-left: 120px;margin-top: 10px">添加热水辅机报价项</span>
                    </div>
                </div><!--辅机-->
                    <?php
                    }
                    ?>

                <div class="GLXX1_main4"><!--其他项-->
                    <p class="GL" style="width: 666px;position:relative;margin-top:30px;">请选择报价方案中其他项的价格，如果本次方案不需要某一项，可以不必选择或填写</p>
                    <table style="position:relative;" id="other_tab">
                        <tr style="background-color: #E5F6FE;">
                                <td style="width: 160px;">名称</td>
                                <td style="width: 324px;">规格型号</td>
                                <td style="width: 80px;">数量</td>
                                <td style="width: 100px;">备注</td>
                                <td>最高价(万元)</td>
                                <td>最低价(万元)</td>
                                <td>均价(万元)</td>
                                <td>上次(万元)</td>
                                <td style="width: 144px;">自定义(万元)</td>
                        </tr>
                        <!-------添加的自定义其他项表单------->
                        <tr class="other_addtr"  style="display: none">
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="other_name"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="other_version"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="number" value="" id="other_num"/>
                            </td>
                            <td >
                                <input style="margin:2px" class="GLXXmain_3" type="text" value="" id="other_context"/>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <input type="number" name="other_add_price" id="other_add_price" value="" class="price_input"  style="max-width:55%;" />
                                <span   style="color: red" class="mouother" id="delete_other" />删除</span>
                            </td>
                        </tr>
                        <?php
                        if(!empty($rows) ) {
                            foreach ($rows as $row) {
                                $tplcontentInfo =  Case_tplcontent::getInfoByAttridAndTplid(31,$row['id']);
                                $priceInfo = Case_pricelog::getPageList(1, 10, 0, 2, $row['id'], $addtype, $sttime, $endtime);
                                $newpriceInfo= Case_pricelog::getPageList($page, $pageSize, 1, 2, $row['id'], $addtype, $sttime, $endtime);

                                $other_maxprice=$priceInfo['maxprice']?floatval($priceInfo['maxprice']):0;
                                $other_minprice=$priceInfo['minprice']?floatval($priceInfo['minprice']):0;
                                $other_avgprice=$priceInfo['avgprice']?round($priceInfo['avgprice'],2):0;
                                $other_newprice=$newpriceInfo?floatval($newpriceInfo[0]['price']):0;
                                echo '               
                            <tr class="other">
                                 <td id="other_name" data-value="'?><?php echo $row['name'] ?><?php echo'">' . $row['name'] . '</td>
                                <td id="other_version" data-value="'?><?php echo !empty($tplcontentInfo)?$tplcontentInfo[0]["content"]:" " ?><?php echo '">' . HTMLDecode(!empty($tplcontentInfo)?$tplcontentInfo[0]["content"]:"") . '</td>
                                <td id="other_num" data-value="1">1</td>
                                <td><input type="text" name="other_context" id="other_context" value="" class="GLXXmain_3"  style="margin:2px"  /></td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_maxprice.'" class="inputing" />' . $other_maxprice. '</td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_minprice.'" class="inputing" />' . $other_minprice . '</td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_avgprice.'" class="inputing" />' . $other_avgprice . '</td>
                                <td><input type="radio" name="price_other_'.$row["id"].'" value="'.$other_newprice.'" class="inputing" />' . $other_newprice . '</td>
                                <td class="defined">
                                    
                                     <input type="number" name="other_add_price" id="other_add_price" value="" class="price_input"  style="max-width:55%;" />                               
                                     <a href="javascript:void(0)"  style="color: #04A6FE" class="clearAll"/>清除</a>
                                     <input type="hidden" name = "choose_other_price" id="choose_other_price" value=""  />
                                     <input type="hidden" name = "aid" id="aid" value="'.$row["id"].'"  />
        
                                </td>
                            </tr>';
                                    }
                        }
                        ?>

                    </table>
                    <div class="GLXXmain_16">
                        <span class="addother" style="display: block;margin-left: 120px;margin-top: 10px">添加其他报价项</span>
                    </div>
                </div><!--其他项-->

                <div class="footer" style="margin: 0 auto 99px auto;display: flex;justify-content: space-around;padding-top: 90px;">
                    <button class="GLXXmain_17" type="button"  id="btn_submit_ahead" value="上一步">
                          <?php
                          if($info['type'] == 1) {//智能选型
                              ?>
                              <a href="selection_result_fuji.php?id=<?php echo $id ?>" style="color: white">上一步</a>
                              <?php
                          } elseif($info['type'] == 3){//更换锅炉
                              if($info['heating_type'] == 3){
                                  ?>
                                  <a href="selection_result_change_fuji.php?id=<?php echo $id?>" style="color: white">上一步</a>
                                  <?php
                              }else{
                            ?>
                            <a href="selection_change_info.php?id=<?php echo $id?>" style="color: white">上一步</a>
                            <?php
                              }
                        }
                        ?>
                    </button>
                    <button class="GLXXmain_17" type="button"  id="btn_submit_next" value="下一步"  style="color: white">下一步</button>
                </div><!--下一步-->
      </div>

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
          #step .step-bg {
              width: 100%;
              height: 10px;
              border-radius: 5px;
              position: absolute;
              top: 10px;
              left: 0;
              background-color: lightgrey;
          }
          #step .step-progress {
              width: 66.66%;
              height: 10px;
              border-radius: 5px;
              position: absolute;
              top: 10px;
              left: 0;
              background-color: #04A6FE;
          }
          #step .step {
              display: inline-block;
          }
          #step .step-item {
              width: 33.33%;
              margin-bottom: 10px;
              display: inline-block;
              position: absolute;
              top: 0;
          }
          #step .step-item .step-item-title {
              font-size: 14px;
              text-align: center;
          }
          #step .step-item.active .step-item-num {
              background-color: #04A6FE;
          }
          #step .step .step-item .step-item-num {
              line-height: 30px;
              margin-left: 44%;
          }
          #step .step .step-item:nth-child(1) {
              left: 0;
          }
          #step .step .step-item:nth-child(2) {
              left: 33.33%;
          }
          #step .step .step-item:nth-child(3) {
              left: 66.66%;
          }
          #step .step .step-item-num {
              width: 30px;
              height: 30px;
              background-color: lightgrey;
              border-radius: 50%;
              text-align: center;
              padding: 3px;
          }

      </style>

      <script>

              // 添加一行锅炉(锅炉厂家和型号选择)参数
              $('.addgl').click(function () {
                  var vender_list = new Array();
                  vender_list = <?php echo json_encode($guolu_vender_list) ?>;
                  var guolu_vender_html = "";
                  for (var l = 0; l < vender_list.length; l++){
                      guolu_vender_html += "<option value='" + vender_list[l]['id'] + "'>" + vender_list[l]['name'] + "</option>";
                  }

                  var NHtml = '<tr  class="guolu">' +
                      '<td class="center">锅炉</td>'+
                      '<td class="center">'+
                      '<select type="text" class="GLXXmain_3 guolu_vender" id="guolu_vender" >'+
                      '<option value="0">请选择厂家</option>' + guolu_vender_html +
                      '</select>'+
                      '</td>'+
                      '<td class="center"> '+
                      '<select type="text" class="GLXXmain_3 guolu_version" id="guolu_version" >'+
                      '<option value="0">请选择锅炉型号</option>'+
                      '</select>'+
                      '</td>'+
                      '<td class="center">'+
                      '<input style="margin:2px" class="GLXXmain_3" type="number" value="" id="guolu_num"/>'+
                      '</td>'+
                      '<td class="center">'+
                      '<input style="margin:2px" class="GLXXmain_3" type="text" value="" id="guolu_context"/>'+
                      '</td>'+
                      '<td class="guolu_maxprice">'+
                      '<input type="radio" name="" value="0"  class="inputing" />0'+
                      '</td>'+
                      '<td class="guolu_minprice">'+
                      '<input type="radio" name="" value="0" class="inputing" />0'+
                      '</td>'+
                      '<td class="guolu_avgprice">'+
                      '<input type="radio" name="" value="0" class="inputing" />0'+
                      '</td>'+
                      '<td class="guolu_newprice">'+
                      '<input type="radio" name="" value="0" class="inputing" />0'+
                      '</td>'+
                      '<td class="defined guolu_addprice" style="width:35px;">'+
                      '<input type="number" name="textprice" id="guolu_add_price" value="" class="price_input" style="max-width:55%;"  />' +
                      '<span id="delete_guolu" class="mougl" style="color: red"> 删除 </span>'+
                      '</td>'+
                      '</tr>';

                  $(this).parent().parent().find('.guolu:nth-last-child(1)').after(NHtml);

              });

              // 删除一行锅炉参数
              $(document).on('click', '.mougl', function () {
                  var len = $(".mougl").length;
                  var len = parseFloat(len);
                  $(this).parent().parent().find('.guolu:nth-last-child(1)').remove();
                  if (len >= 1) {
                  $(this).parent().parent().remove();
                  }
              });
              // 添加一行采暖辅机参数
              $('.addhfj').click(function () {
                  var newht = $(this).parent().parent().find('.heat_addtr').html();
                  var NHtml = '<tr  class="heat_item">' + newht + '</tr>';
                  var heat_trlen=$(".heat_item").length;
                  if(heat_trlen == 0){
                      $(this).parent().parent().find('.heat_addtr:nth-last-child(1)').after(NHtml);
                  }else{
                      $(this).parent().parent().find('.heat_item:nth-last-child(1)').after(NHtml);

                  }
              });

              // 删除一行采暖辅机参数
              $(document).on('click', '.mouheat', function () {
                  var len = $(".mouheat").length;
                  var len = parseFloat(len);
                  $(this).parent().parent().find('.heat_item:nth-last-child(1)').remove();
                  if (len >= 1) {
                      $(this).parent().parent().remove();
                  }
              });
              // 添加一行热水辅机参数
              $('.addwfj').click(function () {
                  var newht = $(this).parent().parent().find('.water_addtr').html();
                  var NHtml = '<tr  class="water_item">' + newht + '</tr>';
                  var water_trlen=$(".water_item").length;
                  if(water_trlen == 0){
                      $(this).parent().parent().find('.water_addtr:nth-last-child(1)').after(NHtml);
                  }else{
                      $(this).parent().parent().find('.water_item:nth-last-child(1)').after(NHtml);

                  }
              });

              // 删除一行热水辅机参数
              $(document).on('click', '.mouwater', function () {
                  var len = $(".mouwater").length;
                  var len = parseFloat(len);
                  $(this).parent().parent().find('.water_item:nth-last-child(1)').remove();
                  if (len >= 1) {
                      $(this).parent().parent().remove();
                  }
              });
              // 添加一行其他项参数
              $('.addother').click(function () {
                  var newht = $(this).parent().parent().find('.other_addtr').html();
                  var NHtml = '<tr  class="other">' + newht + '</tr>';
                      $(this).parent().parent().find('.other:nth-last-child(1)').after(NHtml);

              });
              // 删除一行其他项参数
              $(document).on('click', '.mouother', function () {
                  var len = $(".mouother").length;
                  var len = parseFloat(len);
                  $(this).parent().parent().find('.other:nth-last-child(1)').remove();
                  if (len >= 1) {
                      $(this).parent().parent().remove();
                  }
              });



              $(function () {

              $('#btn_submit_next').on('click', function () {

                  //-------------------------------自定义设备报价------------------------------------
                  //采暖辅机参数

                  var heat_addArr=$(".heat_item");
                  var heat_len=heat_addArr.length;
                  var heat_add_dataArr=[];
                  var heat_addStr="";

                  if(heat_len > 0) {
                      for(var i=0;i < heat_len;i++){
                          var heat_currentDom = heat_addArr[i];
                          var heat_currentName = $(heat_currentDom).find("#heat_name").val();
                          var heat_currentVersion = $(heat_currentDom).find("#heat_version").val();
                          var heat_currentNum = $(heat_currentDom).find("#heat_num").val();
                          var heat_currentContext = $(heat_currentDom).find("#heat_context").val();
                          var heat_currentPrice = $(heat_currentDom).find("#heat_price").val();

                          if(heat_currentName !=  "" && heat_currentName !=  null ){
                              var heat_addName=heat_currentName;
                          }else{
                              layer.alert("请填写采暖辅机名称！", {icon: 5});
                              return false;
                          }
                          if(heat_currentVersion != "" && heat_currentVersion != null){
                              var heat_addVersion=heat_currentVersion;
                          }else{
                              layer.alert("请填写采暖辅机型号！", {icon: 5});
                              return false;
                          }
                          if(heat_currentNum != "" && parseFloat(heat_currentNum) > 0 ){
                              var heat_addNum=heat_currentNum;
                          }else{
                              layer.alert("请填写采暖辅机数量！", {icon: 5});
                              return false;
                          }
                          if(heat_currentPrice != ""){
                              if(heat_currentPrice >= 0){
                                  var heat_addPrice=heat_currentPrice;
                              }else{
                                  layer.alert("采暖辅机价格不能小于0！", {icon: 5});
                                  return false;
                              }
                          }else{
                              layer.alert("请填写采暖辅机价格！", {icon: 5});
                              return false;
                          }
                          var heat_add_currentVal=heat_addName+"||"+heat_addVersion+"||"+heat_addNum+
                              "||"+heat_currentContext+"||"+heat_addPrice;
                          heat_add_dataArr.push(heat_add_currentVal);
                      }

                      heat_addStr = heat_add_dataArr.join("#");
                  }
                  //热水辅机参数

                  var water_addArr=$(".water_item");
                  var water_len=water_addArr.length;
                  var water_add_dataArr=[];
                  var water_addStr="";

                  if(water_len > 0) {
                      for(var i=0;i < water_len;i++) {
                          var water_currentDom = water_addArr[i];
                          var water_currentName = $(water_currentDom ).find("#water_name").val();
                          var water_currentVersion = $(water_currentDom ).find("#water_version").val();
                          var water_currentNum = $(water_currentDom ).find("#water_num").val();
                          var water_currentContext = $(water_currentDom ).find("#water_context").val();
                          var water_currentPrice = $(water_currentDom ).find("#water_price").val();
                          if (water_currentName != "" && water_currentName != null) {
                              var water_addName = water_currentName;
                          } else {
                              layer.alert("请填写热水辅机名称！", {icon: 5});
                              return false;
                          }
                          if (water_currentVersion != "" && water_currentVersion != null) {
                              var water_addVersion = water_currentVersion;
                          } else {
                              layer.alert("请填写热水辅机型号！", {icon: 5});
                              return false;
                          }
                          if (water_currentNum != "" && parseFloat(water_currentNum) > 0) {
                              var water_addNum = water_currentNum;
                          } else {
                              layer.alert("请填写热水辅机数量！", {icon: 5});
                              return false;
                          }
                          if (water_currentPrice != ""){
                              if(water_currentPrice >= 0){
                                  var water_addPrice = water_currentPrice;
                              }else{
                                  layer.alert("热水辅机价格不能小于0！", {icon: 5});
                                  return false;
                              }
                          } else {
                              layer.alert("请填写热水辅机价格！", {icon: 5});
                              return false;
                          }

                          var water_add_currentVal = water_addName + "||" + water_addVersion + "||" + water_addNum + "||" + water_currentContext + "||" + water_addPrice;

                          water_add_dataArr.push(water_add_currentVal);
                      }
                      water_addStr = water_add_dataArr.join("#");
                  }

                  //------------------------------之前选择的设备报价-------------------------------------
                  /*对锅炉表单中获取选择的价格和自定义价格作比较最终输出一个*/
                  var guoluArr = $(".guolu");
                  var guolu_dataArr = [];
                  var guolu_origin_length = 0;
                  guolu_origin_length = <?php echo count($guolu_ids) ?>;
                  for (var i = 0; i < guoluArr.length; i++) {
                      var guolu_currentDom = guoluArr[i];
                      var guoluVal = $(guolu_currentDom).find("#guolu_add_price").val();//自定义价格
                      var guolu_chooseVal = $(guolu_currentDom).find("#choose_guolu_price").val();//选择的价格
                      var guolu_currentVal = guoluVal != "" ? guoluVal : guolu_chooseVal;//最后得到的数据

                      if (guolu_currentVal == "" )//提醒锅炉价格未选择
                      {
                          layer.alert("请选择锅炉价格！", {icon: 5});
                          return false;
                      }else if ( guolu_currentVal < 0)
                      {
                          layer.alert("锅炉价格不能小于0！", {icon: 5});
                          return false;
                      }
                      var guolu_attrId = $(guolu_currentDom).find("#attrid").val();//获取每一行的属性id


                      var guolu_currentNum = 0;
                      var guolu_currentVender=0;
                      var guolu_currentVersion = 0;
                      var guolu_currentContext = "";

                      if (i >= guolu_origin_length){
                          guolu_currentNum = $(guolu_currentDom).find("#guolu_num").val();// 获取每一行的锅炉数量
                          guolu_currentVender=$(guolu_currentDom).find("#guolu_vender").val();

                          guolu_currentVersion = $(guolu_currentDom).find("#guolu_version option:selected").text();// 获取每一行的锅炉型号

                          guolu_currentContext = $(guolu_currentDom).find("#guolu_context").val();// 获取每一行的锅炉备注


                      } else {
                          guolu_currentNum = $(guolu_currentDom).find("#guolu_num").data("value");// 获取每一行的锅炉数量
                          guolu_currentVender=$(guolu_currentDom).find("#guolu_vender").data("value");

                          guolu_currentVersion = $(guolu_currentDom).find("#guolu_version").data("value");// 获取每一行的锅炉型号

                          guolu_currentContext = $(guolu_currentDom).find("#guolu_context").data("value");// 获取每一行的锅炉备注
                      }
                      if ( guolu_currentVender == null || guolu_currentVender == 0){
                          layer.alert("请选择锅炉厂家！",{icon:5});
                          return false;
                      }

                      if (guolu_currentNum == null || guolu_currentNum < 1){
                          layer.alert("请填写锅炉数量！",{icon:5});
                          return false;
                      }


                      var guolu_currentStr =  guolu_currentVal+"||"+ guolu_attrId+"||"+guolu_currentNum+"||"+guolu_currentVersion+"||"+guolu_currentContext;
                      //将每一行的id和价格以及文本框中的价格拼接成字符串

                      guolu_dataArr.push(guolu_currentStr);
                  }
                  var guolu_dataStr = guolu_dataArr.join("#");

                  /*采暖辅机*/
                  //后台本来就有的辅机数据
                  var fuji_sel_Arr = $(".heat_fuji_sel");
                  var fuji_sel_dataArr = [];
                  if (fuji_sel_Arr.length > 0) {//页面出现辅机表单
                      for (var i = 0; i < fuji_sel_Arr.length; i++) {
                          var fuji_sel_currentDom = fuji_sel_Arr[i];
                          var fuji_sel_Val = $(fuji_sel_currentDom).find("#heat_add_price").val();//自定义价格

                          var fuji_sel_chooseVal = $(fuji_sel_currentDom).find("#choose_heat_price").val();//选择的价格
                          var fuji_sel_currentVal = fuji_sel_Val != "" ? fuji_sel_Val : fuji_sel_chooseVal;//最后得到的数据
                          // var fuji_sel_currentType = fuji_sel_Val != "" ? 2 : 1;//取出文本框中的数据的结果为2

                          if (fuji_sel_currentVal == "" ) {
                              layer.alert("请选择采暖辅机价格！", {icon: 5});
                              return false;
                          }else if ( fuji_sel_currentVal < 0) {
                              layer.alert("采暖辅机价格不能小于0！", {icon: 5});
                              return false;
                          }
                          var fuji_sel_currentId = $(fuji_sel_currentDom).find("#hid").val();//获取每一行的selection_fuji_id
                          var fuji_sel_currentMId = $(fuji_sel_currentDom).find("#hmid").val();//获取每一行的modelid

                          var fuji_sel_currentStr = fuji_sel_currentId + "-" + fuji_sel_currentVal + "-" + fuji_sel_currentMId;
                          //将每一行的id和价格以及文本框中的价格拼接成字符串

                          fuji_sel_dataArr.push(fuji_sel_currentStr);
                      }
                      var fuji_sel_dataStr = fuji_sel_dataArr.join("#");
                  } else {
                      fuji_sel_dataStr = "";
                  }
                  //后台数据库不存在后来添加的，只有文本框中的数据
                  var fuji_text_Arr = $(".heat_fuji_text");
                  var fuji_text_dataArr = [];
                  if (fuji_text_Arr.length > 0) {
                      for (var i = 0; i < fuji_text_Arr.length; i++) {
                          var fuji_text_currentDom = fuji_text_Arr[i];
                          var fuji_text_Val = $(fuji_text_currentDom).find("#heat_add_price").val();//自定义价格

                          if (fuji_text_Val == "" ) {
                              layer.alert("请添加采暖辅机价格！", {icon: 5});
                              return false;
                          }else if (fuji_text_Val < 0) {
                              layer.alert("采暖辅机价格不能小于0！", {icon: 5});
                              return false;
                          }
                          var fuji_text_currentId = $(fuji_text_currentDom).find("#hid").val();//获取每一行的selection_fuji_id

                          var fuji_text_currentStr = fuji_text_currentId + "-" + fuji_text_Val;
                          //将每一行的id和价格以及文本框中的价格拼接成字符串

                          fuji_text_dataArr.push(fuji_text_currentStr);
                      }
                      var fuji_text_dataStr = fuji_text_dataArr.join("#");
                  } else {
                      fuji_text_dataStr = "";
                  }

                  /*热水辅机*/
                  //后台本来就有的辅机
                  var water_sel_Arr = $(".water_fuji_sel");
                  var water_sel_dataArr = [];
                  if (water_sel_Arr.length > 0) {
                      for (var i = 0; i < water_sel_Arr.length; i++) {
                          var water_sel_currentDom = water_sel_Arr[i];
                          var water_sel_Val = $(water_sel_currentDom).find("#water_add_price").val();//自定义价格

                          var water_sel_chooseVal = $(water_sel_currentDom).find("#choose_water_price").val();//选择的价格
                          var water_sel_currentVal = water_sel_Val != "" ? water_sel_Val : water_sel_chooseVal;//最后得到的数据
                          // var water_sel_currentType = water_sel_Val != "" ? 2 : 1;//取出文本框中的数据的结果为2

                          if (water_sel_currentVal == "" ) {
                              layer.alert("请选择热水辅机价格！", {icon: 5});
                              return false;
                          }else if ( water_sel_currentVal < 0) {
                              layer.alert("热水辅机价格不能小于0！", {icon: 5});
                              return false;
                          }
                          var water_sel_currentId = $(water_sel_currentDom).find("#wid").val();//获取每一行的selection_fuji_id
                          var water_sel_currentMId = $(water_sel_currentDom).find("#wmid").val();//获取每一行的modelid

                          var water_sel_currentStr = water_sel_currentId + "-" + water_sel_currentVal + "-" + water_sel_currentMId;
                          //将每一行的id和价格以及文本框中的价格拼接成字符串

                          water_sel_dataArr.push(water_sel_currentStr);
                      }
                      var water_sel_dataStr = water_sel_dataArr.join("#");
                  } else {
                      water_sel_dataStr = "";
                  }

                  //后台数据库不存在后来添加的，只有文本框中的数据
                  var water_text_Arr = $(".water_fuji_text");
                  var water_text_dataArr = [];
                  if (water_text_Arr.length > 0) {
                      for (var i = 0; i < water_text_Arr.length; i++) {
                          var water_text_currentDom = water_text_Arr[i];
                          var water_text_Val = $(water_text_currentDom).find("#water_add_price").val();//自定义价格

                          if (water_text_Val == "" ) {
                              layer.alert("请添加热水辅机价格！", {icon: 5});
                              return false;
                          }else if ( water_text_Val < 0) {
                              layer.alert("热水辅机价格不能小于0！", {icon: 5});
                              return false;
                          }
                          var water_text_currentId = $(water_text_currentDom).find("#wid").val();//获取每一行的selection_fuji_id

                          var water_text_currentStr = water_text_currentId + "-" + water_text_Val;
                          //将每一行的id和价格以及文本框中的价格拼接成字符串

                          water_text_dataArr.push(water_text_currentStr);
                      }
                      var water_text_dataStr = water_text_dataArr.join("#");
                  } else {
                      water_text_dataStr = "";
                  }


                  /***对其他项表单中获取选择的价格和自定义价格作比较最终输出一个*/
                  var otherArr = $(".other");
                  var dataArr = [];
                  var other_dataStr = "";
                  var other_origin_length = 0;
                  other_origin_length = <?php echo  count($rows) ?>;
                  var other_new_dataArr = [];
                  var other_new_dataStr = "";
                  if (otherArr.length > 0) {
                      for (var i = 0; i < otherArr.length; i++) {
                          var other_currentDom = otherArr[i];
                          var otherVal = $(other_currentDom).find("#other_add_price").val();//自定义价格
                          var chooseVal = $(other_currentDom).find("#choose_other_price").val();//选择的价格
                          var currentVal = otherVal != "" ? otherVal : chooseVal;//最后得到的数据


                          if (i < other_origin_length) {
                              var other_currentName = $(other_currentDom).find("#other_name").data("value");
                              var other_currentNum = $(other_currentDom).find("#other_num").data("value");
                              var other_currentVersion = $(other_currentDom).find("#other_version").data("value");
                              var other_currentContext = $(other_currentDom).find("#other_context").val();


                              if (other_currentVersion == undefined) {
                                  other_currentVersion = "";
                              }
                              if (currentVal == "" || currentVal == undefined) continue;
                              if(currentVal != "" && currentVal < 0){
                                  layer.alert("其他项价格不能小于0！", {icon: 5});
                                  return false;
                              }

                              var currentId = $(other_currentDom).find("#aid").val();//获取每一行的id

                              var currentStr = other_currentName + "-" + other_currentVersion + "-" + other_currentNum + "-" +
                                  other_currentContext + "-" + currentVal + "-" + currentId;
                              //将每一行的id和价格以及文本框中的价格拼接成字符串

                              dataArr.push(currentStr);
                          } else {
                              var other_new_currentName = $(other_currentDom).find("#other_name").val();
                              var other_new_currentNum = $(other_currentDom).find("#other_num").val();
                              var other_new_currentVersion = $(other_currentDom).find("#other_version").val();
                              var other_new_currentContext = $(other_currentDom).find("#other_context").val();
                              var other_new_currentPrice=$(other_currentDom).find("#other_add_price").val();

                              if (other_new_currentName != "" && other_new_currentName != null) {
                                  var other_new_Name=other_new_currentName;
                              }else{
                                  layer.alert("请添加其他项名称！", {icon: 5});
                                  return false;
                              }
                              if (other_new_currentNum != "" && parseFloat(other_new_currentNum)> 0) {
                                  var other_new_Num=other_new_currentNum;
                              }else{
                                  layer.alert("请添加其他项数量！", {icon: 5});
                                  return false;
                              }
                              if (other_new_currentVersion != "" && other_new_currentVersion != null) {
                                 var other_new_Version=other_new_currentVersion;
                              }else{
                                  layer.alert("请添加其他项型号！", {icon: 5});
                                  return false;
                              }
                              if (other_new_currentPrice != "" && other_new_currentPrice >= 0) {
                                  var other_new_Price=other_new_currentPrice;
                              }else if(other_new_currentPrice < 0){
                                  layer.alert("其他项价格不能小于0！", {icon: 5});
                                  return false;
                              }else{
                                  layer.alert("请添加其他项价格！", {icon: 5});
                                  return false;
                              }

                              var currentNewStr = other_new_Name + "-" + other_new_Version + "-" + other_new_Num + "-" +
                                  other_new_currentContext + "-" + other_new_Price;

                              other_new_dataArr.push(currentNewStr);


                          }
                      }
                      other_dataStr = dataArr.join("#");
                      other_new_dataStr = other_new_dataArr.join("#");
                  }

                  //添加选型方案
                  $.ajax({
                      type        : 'POST',
                      data        : {

                          heat_addStr:heat_addStr,
                          water_addStr:water_addStr,

                          guolu_dataStr : guolu_dataStr,
                          fuji_sel_dataStr:fuji_sel_dataStr,
                          fuji_text_dataStr:fuji_text_dataStr,
                          water_sel_dataStr:water_sel_dataStr,
                          water_text_dataStr:water_text_dataStr,
                          other_dataStr  :other_dataStr,
                          other_new_dataStr : other_new_dataStr,
                          id  : <?php echo $id;?>
                },
                dataType :    'json',
                url :         'selection_plan_one_do.php?act=addprice',
                success :     function(data){
                    // alert(data);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            // layer.alert(msg, {icon: 6,shade: false}, function(index){
                            // parent.location.reload();
                            window.location.href = 'selection_plan_two.php?id=<?php echo $id;?>';
                            // });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
            });
        });

              // 获取指定商家的锅炉列表
              $(document).on('change','.guolu_vender',function(){
                  var vender = $(this).val();
                  var versionDom = $(this).parent().parent().find(".guolu_version");
                  versionDom.html("<option value='0'>请选择锅炉型号</option>");

                  var maxPriceDom = $(this).parent().parent().find(".guolu_maxprice");
                  var minPriceDom = $(this).parent().parent().find(".guolu_minprice");
                  var avgPriceDom = $(this).parent().parent().find(".guolu_avgprice");
                  var newPriceDom = $(this).parent().parent().find(".guolu_newprice");
                  var addPriceDom = $(this).parent().parent().find(".guolu_addprice");


                  if(vender != 0){
                      var select_version_length=0;
                      select_version_length = <?php echo count($guolu_ids) ?>;

                      var guoluArr=$(".guolu");
                      var guolu_exist_ids_arr = [];  // 存储已有锅炉型号
                      for(var i=0;i<guoluArr.length;i++){
                          var guolu_currentDom = guoluArr[i];
                          var venderId = 0;
                          if(i >= select_version_length){
                              //存在手动添加锅炉厂家
                              venderId=$(guolu_currentDom).find("#guolu_vender option:selected").val();//获取厂家id
                          }else{//只获取之前选择的锅炉厂家id
                              venderId=$(guolu_currentDom).find("#guolu_vender").data("value");//获取厂家id
                          }
                          if(venderId == vender){
                              // 将同一个厂家的锅炉型号收集起来
                              var guoluId = $(guolu_currentDom).find("#attrid").val();// 获取每一行的锅炉id
                              guolu_exist_ids_arr.push(guoluId);
                          }
                      }

                  $.ajax({
                      type: 'POST',
                      data: {
                          vender: vender
                      },
                      dataType: 'json',
                      url: 'selection_plan_one_do.php?act=get_guolu_list_vender',
                      success: function (data) {
                          var code = data.code;
                          var msg = data.msg;
                          var guolu_list = data.data;//获取的锅炉型号列表
                          switch (code) {
                              case 1:
                                  // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数
                                  var html = "";

                                  var show_guolu_list = [];
                                  for (var j = 0; j < guolu_list.length; j++) {
                                      var isExist = false;
                                      for(var k=0;k < guolu_exist_ids_arr.length;k++){
                                          if (guolu_list[j]['guolu_id'] == guolu_exist_ids_arr[k]) {
                                              isExist = true;
                                          }
                                      }

                                      if (!isExist) {
                                          show_guolu_list.push(guolu_list[j]);
                                      }
                                  }
                                  for (var i = 0; i < show_guolu_list.length; i++) {
                                      var guolu_version = show_guolu_list[i].guolu_version;
                                      var value = show_guolu_list[i].guolu_id;
                                      html += "<option value='" + value + "'>" + guolu_version + "</option>";
                                  }
                                  versionDom.html(html);
                                  if (show_guolu_list != null && show_guolu_list.length >= 1) {
                                      getGuoluPrice(show_guolu_list[0].guolu_id, maxPriceDom, minPriceDom, avgPriceDom, newPriceDom, addPriceDom);
                                  }else {
                                      versionDom.html("<option value='0'>请选择锅炉型号</option>");
                                      getGuoluPrice(0, maxPriceDom, minPriceDom, avgPriceDom, newPriceDom, addPriceDom);
                                  }
                                  break;

                              default:
                                  break;
                          }
                      }
                  });
              }else {
                  getGuoluPrice(0,maxPriceDom,minPriceDom,avgPriceDom,newPriceDom,addPriceDom);
              }
        });

          // 锅炉id发生变化时获取其历史价格
          $(document).on('change','.guolu_version',function(){
              var guolu_id = $(this).val();
              var maxPriceDom = $(this).parent().parent().find(".guolu_maxprice");
              var minPriceDom = $(this).parent().parent().find(".guolu_minprice");
              var avgPriceDom = $(this).parent().parent().find(".guolu_avgprice");
              var newPriceDom = $(this).parent().parent().find(".guolu_newprice");
              var addPriceDom = $(this).parent().parent().find(".guolu_addprice");
              getGuoluPrice(guolu_id,maxPriceDom,minPriceDom,avgPriceDom,newPriceDom,addPriceDom);
          });

          // 获取指定型号锅炉的历史价格
          function getGuoluPrice(my_guolu_id,mMaxPriceDom,mMinPriceDom,mAvgPriceDom,mNewPriceDom,mAddPriceDom) {

              var guolu_id = my_guolu_id;

              var maxPriceDom = mMaxPriceDom;
              var minPriceDom = mMinPriceDom;
              var avgPriceDom = mAvgPriceDom;
              var newPriceDom = mNewPriceDom;
              var addPriceDom = mAddPriceDom;

              if (guolu_id != 0 && guolu_id != undefined) {
              $.ajax({
                  type: 'POST',
                  data: {
                      guolu_id: guolu_id
                  },
                  dataType: 'json',
                  url: 'selection_plan_one_do.php?act=get_guolu_price',
                  success: function (data) {
                      var code = data.code;
                      var msg = data.msg;
                      var guoluItem = data.data;
                      switch (code) {
                          case 1:
                              // 更新价格
                              var maxPrice = (guoluItem["countarr"]['maxprice'] == null)?0:parseFloat(guoluItem["countarr"]['maxprice']);
                              var maxPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["proid"]
                                  + "\" value = \"" + maxPrice + "\" class=\"inputing\"/>" + maxPrice ;
                              maxPriceDom.html(maxPriceDomHtml);

                              var minPrice = (guoluItem["countarr"]['minprice'] == null)?0:parseFloat(guoluItem["countarr"]['minprice']);
                              var minPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["proid"]
                                  + "\" value = \"" + minPrice + "\" class=\"inputing\"/>" + minPrice ;
                              minPriceDom.html(minPriceDomHtml);

                              var avgPrice = (guoluItem["countarr"]['avgprice'] == null)?0:parseFloat(guoluItem["countarr"]['avgprice']);
                              var get_avgPrice=Math.floor(avgPrice * 100)/100;//均价取小数点后2位
                              var avgPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["proid"]
                                  + "\" value = \"" + get_avgPrice + "\" class=\"inputing\"/>" + get_avgPrice ;
                              avgPriceDom.html(avgPriceDomHtml);

                              var newPrice = (guoluItem["prices"][0] == null)?0:parseFloat(guoluItem["prices"][0]['price']);
                              var newPriceDomHtml = "<input type=\"radio\" name=\"price_guolu_" + guoluItem["guoluinfo"]["proid"]
                                  + "\" value = \"" + newPrice + "\" class=\"inputing\"/>" + newPrice ;
                              newPriceDom.html(newPriceDomHtml);

                              var gid = (guoluItem["guoluinfo"]['id'] == null)?0:parseInt(guoluItem["guoluinfo"]['id']);
                              // var proid = (guoluItem["guoluinfo"]['proid'] == null)?0:parseInt(guoluItem["guoluinfo"]['proid']);
                              var addInputOneHtml = "<input type=\"number\" name=\"guolu_add_price\" value =\"\" " +
                                  "id=\"guolu_add_price\" style=\"max-width:55%;\" class=\"price_input\"/>" ;
                              var addInputTwoHtml = "<input type=\"hidden\" name=\"choose_guolu_price\" id=\"choose_guolu_price\" value =\"\" />" ;
                              // var addInputThreeHtml = "<input type=\"hidden\" name=\"textprice\" value =\"" + proid + "\" id=\"attrid\" />" ;
                              var addInputFourHtml = "<input type=\"hidden\" name=\"textprice\" value =\"" + gid + "\" id=\"attrid\" />" ;
                              var addInputFiveHtml = "<span id=\"delete_guolu\" class=\"mougl\" style=\"color: red\"> 删除 </span>";
                              addPriceDom.html(addInputOneHtml + addInputTwoHtml  + addInputFourHtml + addInputFiveHtml);

                              break;

                          default:
                              alert(msg);
                              break;
                      }
                  }
              });
          }else{
                  var maxPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  maxPriceDom.html(maxPriceDomHtml);

                  var minPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  minPriceDom.html(minPriceDomHtml);

                  var avgPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  avgPriceDom.html(avgPriceDomHtml);

                  var newPriceDomHtml = "<input type=\"radio\" name=\"\" value = \"" + 0 + "\" class=\"inputing\"/>" + 0;
                  newPriceDom.html(newPriceDomHtml);
              }
          }

    </script>
    <!--弹窗3-->

</body>
</html>
