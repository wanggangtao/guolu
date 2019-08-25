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

$plan_front_info = Selection_plan_front::getInfoByHistoryId($id);
$plan_front_id = null;
if (!empty($plan_front_info)) {
    $plan_front_id = $plan_front_info['id'];
}
$info = Selection_history::getInfoById($id);

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
$guolu_context = explode(",",$info['guolu_context']);
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
    if(!empty($guolu_context[$key])){
        $guoluItem['context'] = $guolu_context[$key];
    }
    $guolus[] = $guoluItem;

}





/*获取其他项表单的相关数据*/
$rows      = Case_tpl::getListByAttrid(13, 1, 8, $count = 0);
//从case_attr表中报价方案的attrid=13，利用attrid从case_tpl表中提取属性名称



$attrid = 7;
//查询方案模板列表
$rowsTpl = Case_tpl::getListByAttrid($attrid, null, null, $count = 0);



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
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/layer.js" ></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
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
    <script type="text/javascript">
        $(function() {
            //编辑器初始化
            $('#prior_fuji').click(function(){

                var id=<?php echo  $id;?>;
                var project_id = <?php echo $info['project_id'];?>;
                location.href="selection_change_old.php?id="+id+"&project_id="+project_id;

            });

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

            $('#subimt_fuji').click(function(){
                var context =[];
                $("input[name='text']").each(function(){
                    context.push($(this).val());
                })

                var id=<?php echo  $id;?>;
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id  : id,
                        context :context

                    },
                    dataType:     'json',
                    url :         'selection_do.php?act=edit_guolu_context',

                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                 location.href="selection_plan_one.php?id="+id;
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error:     function()
                    {
                        layer.alert("操作失败", {icon: 5});
                    }
                });

            });
        })

        //自定义模板
        function userDefined() {
            var oEditor = CKEDITOR.instances.content;
            oEditor.setData("");
        }
        //跳转至  选型页面
        function toSelection() {
            document.getElementById("confirm2").style.backgroundColor="#04A6FE";
            document.getElementById("confirm2").style.color="white";
            document.getElementById("cancel2").style.backgroundColor="white";
            document.getElementById("cancel2").style.color="#04A6FE";
            window.location.href='selection.php';
        }
        //跳转至  方安池页面
        function toPlanPool() {
            document.getElementById("confirm2").style.backgroundColor="white";
            document.getElementById("confirm2").style.color="#04A6FE";
            document.getElementById("cancel2").style.backgroundColor="#04A6FE";
            document.getElementById("cancel2").style.color="white";
            window.location.href='selection_plan_pool.php';
        }





    </script>
</head>
<body class="body_2">

<?php include('top.inc.php');?>
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

    <div class="GLXX1_main2" id="box"><!--锅炉-->
        <p class="GL" style="width:666px">锅炉</p>
        <table>
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
<div>
<br><br><br>
    <div class="btn" style="margin: 0 auto;display: flex;justify-content: space-around;padding-top: 90px;">
        <button id="prior_fuji" class="GLXXmain_4" >上一步</button>
            <button id="subimt_fuji" class="GLXXmain_4">下一步</button>
    </div>
</div>





</body>
</html>
