<?php
/**
 * 项目第五阶段 project_stage_five.php
 *
 * @version       v0.01
 * @create time   2018/06/29
 * @update time   2018/06/29
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = "five";
$TOP_FLAG = 'myproject';

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
$projectinfo = Project::getInfoById($id) ;
if(empty($projectinfo)){
    echo '非法操作！';
    die();
}
if(!empty($id)){
    $project_five = Project_five::getInfoByProjectId($id);

    if(empty($project_five)){
        $project_five = Project_five::Init();
    }
    if($projectinfo['user'] != $USERId){
        echo '没有权限操作！';
        die();
    }
}
$bonus_stage = explode('|', $projectinfo['bonus_stage']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-<?php echo $projectinfo['name'];?>五级</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>

    <script type="text/javascript">
        $(function(){
            laydate({
                elem: '#pre_build_time', //需显示日期的元素选择器
                event: 'click', //触发事件
                format: 'YYYY-MM-DD', //日期格式
                istime: false, //是否开启时间选择
                isclear: true, //是否显示清空
                istoday: true, //是否显示今天
                issure: true, //是否显示确认
                festival: true, //是否显示节日
                choose: function(dates){ //选择好日期的回调
                }
            });
            laydate({
                elem: '#pre_check_time', //需显示日期的元素选择器
                event: 'click', //触发事件
                format: 'YYYY-MM-DD', //日期格式
                istime: false, //是否开启时间选择
                isclear: true, //是否显示清空
                istoday: true, //是否显示今天
                issure: true, //是否显示确认
                festival: true, //是否显示节日
                choose: function(dates){ //选择好日期的回调
                }
            });
            $('#money').blur(function(){
                var money = $('#money').val();
//                var reg = /^(-?\d+)(\.\d+)?$/;
//                if (!(reg.test(money))) {
//                    layer.tips('请输入数字', '#money');
//                    return false;
//                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        money  : money,
                        id     : <?php echo $id;?>
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=getProjectreward',
                    success :     function(data){
                        switch(data.code){
                            case 1:
                                $('#first_reward').text(data.first_reward);
                                $('#second_reward').text(data.second_reward);
                                $('#third_reward').text(data.third_reward);
                                break;
                            case 2:
                                $('#first_reward').text(data.first_reward);
                                $('#second_reward').text(data.second_reward);
                                $('#third_reward').text(data.third_reward);
                                layer.alert("项目经理还未设置提成比例！", {icon: 5});
                                break;
                            default:
                                layer.alert(data.msg, {icon: 5});
                        }
                    }
                });
            });
            $('#project_five_save').click(function(){
                var after_solve = $('#after_solve').val();
                var money = $('#money').val();
                var pay_condition = $('#pay_condition').val();
                var cost_plan = $('#cost_plan').val();
                var pre_build_time = $('#pre_build_time').val();
                var pre_check_time = $('#pre_check_time').val();
                var project_contract_file = '';
                var project_contract_ac_file = '';
                var length = $(".fileinfo").length;
                for(i=0;i<length;i++){
                    var thisE = $(".fileinfo").eq(i);
                    var pcf = thisE.find('input[name="project_contract_file"]').val();
                    var pcaf = thisE.find('input[name="project_contract_ac_file"]').val();
                    if(pcf != '' && pcaf != ''){
                        project_contract_file += pcf + '|';
                        project_contract_ac_file += pcaf + '|';
                    }
                }
                if(money=="")
                {
                    layer.alert("请填写合同金额!",{icon:5});
                    return false;
                }

                if(pay_condition=="")
                {
                    layer.alert("请填写付款条件!",{icon:5});
                    return false;
                }



                if(pre_build_time=="")
                {
                    layer.alert("请填写拟开工日期!",{icon:5});
                    return false;
                }


                if(pre_check_time=="")
                {
                    layer.alert("请填写验收日期!",{icon:5});
                    return false;
                }



                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_five['id'];?>,
                        project_id : <?php echo $id;?>,
                        after_solve : after_solve,
                        money  : money,
                        pay_condition  : pay_condition,
                        cost_plan  : cost_plan,
                        pre_build_time : pre_build_time,
                        pre_check_time : pre_check_time,
                        project_contract_file:project_contract_file,
                        project_contract_ac_file:project_contract_ac_file
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_five_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

            $('#record_five').click(function(){
                location.href = 'project_five_record.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });


            //上传
            $('#fileinput1').on('change', '#file1', function(){
                var file = $("#file1").val();
                var pos=file.lastIndexOf("\\");
                //$('#project_contract_ac_file').val(file.substring(pos+1));
                $(".contractfiles").find('input[name="project_contract_ac_file"]:last').val(file.substring(pos+1));
                ajaxUpload(1);
                return false;
            });


            $("body").on("click",".fileinfo .text-input",function(){

                var currentFile = $(this).parent().find("input[name='project_contract_file']").val();
                var win = window.open(
                    'file_preview.php?' +
                    'filepath='+ currentFile +
                    '&username='+ '<?php echo $USERINFO['name'];?>',
                    '文件预览','height=500,width=611,scrollbars=yes,status=yes');
                //alert(currentFile);

            });

            $('.add_contract').click(function () {
                var htmladd="";
                htmladd +='<div class="fileinfo">';
                htmladd +=' <input name="project_contract_ac_file" type="text" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">';
                htmladd +='<div class="remove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="float: left;margin-bottom:0px;color: red;">-删除该文件</p></div>';
                htmladd +='<input name="project_contract_file" type="hidden" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">';
                htmladd +='</div>';

                $(".contractfiles").append(htmladd);
            });

            $("body").on("click", ".remove", function(){
                $(this).parent().remove();
            });
        });



        function ajaxUpload(value){
            var uploadUrl = 'file_upload.php';//处理文件
            var index = layer.load(0, {shade: false});
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            layer.msg('上传成功');
                            $(".contractfiles").find('input[name="project_contract_file"]:last').val(msg);
                            break;

                        default:
                            layer.alert(msg, {icon: 5, title:'信息'});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
    </script>

    <style>
        .middleDiv_five{
            height:520px;
            vertical-align: middle;
        }
        .middleDiv_five_check{
            margin-top: 0px;
            background: rgba(4,166,254,0.03);
            border: 1px solid #04A6FE;
        }
        .fileinput {
            float: left;
            position: relative;
            overflow: hidden;
            width: 140px;
            height: 40px;
            line-height: 40px;
            background: #04A6FE;
            box-shadow: 0 6px 24px 0 rgba(10,122,182,0.30);
            border-radius: 6px;
            font-family: PingFangSC-Regular;
            font-size: 20px;
            color: #FFFFFF;
            letter-spacing: 0.93px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            text-align: center;
        }

        .fileinput input {
            position: absolute;
            right: 0;
            top: 0;
            opacity: 0;
        }

        .text-input {
            height: 18px;
            width: 500px;
            border: 1px solid #cccccc;
            border-radius: 3px;
            float: left;
            background-color: #eee;
        }
    </style>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
            <?php include('project_top.inc.php');?>
    <div class="manageHRWJCont">
        <?php include('project_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">
                <div class="middleDiv_all">
                    <div class="middleDiv_one">
                        <span></span>
                        <div class="manageHRWJCont_middle_right">
                            <button id="record_five">修改记录</button>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="middleDiv_four middleDiv_four_cheack">
                        <p>善后情况</p>
                        <textarea name="after_solve" id="after_solve" cols="30" rows="10" placeholder="Lorem ipsum dolor sit amet, consectetur ..."><?php echo HTMLDecode($project_five['after_solve']); ?></textarea>
                    </div>
                    <div class="middleDiv_one middleDiv_one_one">
                        <div><p><img class="must_reactImg" src="images/must_react.png" alt="">合同金额</p></div>
                        <input type="number" placeholder="50000" id="money" value="<?php echo $project_five['money']; ?>"><span class="middleDiv_one_span">元</span>
                    </div>
                    <div class="middleDiv_one" style="overflow: hidden;width: 500px;" >
                        <div><p>上传合同文件</p></div>
                        <div class="fileinput" id="fileinput1">上传文件<input type="file" name="file" id="file1"></div>
                        <div class="contractfiles">
                            <?php
                            if($project_five['contract_file']){
                                $fileurlarr = explode('|', $project_five['contract_file']);
                                $filenamearr = explode('|', $project_five['contract_ac_file']);
                                for ($i= 0; $i < count($fileurlarr); $i++){
                                    echo '<div class="fileinfo">';
                                    echo '<input name="project_contract_ac_file" type="text" readonly="readonly" class="text-input" value="'.$filenamearr[$i].'" style="height: 35px;width: 400px;">';
                                    if($i != 0){
                                        echo '<div class="remove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="float: left;margin-bottom:0px;color: red;">-删除该文件</p></div>';
                                    }
                                    echo '<input name="project_contract_file" type="hidden" readonly="readonly" class="text-input" value="'.$fileurlarr[$i].'" style="height: 35px;width: 400px;">';
                                    echo '</div>';
                                }
                            }else{
                                echo '<div class="fileinfo">
                                        <input name="project_contract_ac_file" type="text" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">
                                        <input name="project_contract_file" type="hidden" readonly="readonly" class="text-input" value="" style="height: 35px;width: 400px;">
                                      </div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="middleDiv_four">
                        <p class="add_contract" style="color: #04A6FE;">+添加文件</p>
                    </div>

                    <div class="middleDiv_four">
                        <p><img class="must_reactImg" src="images/must_react.png" alt="">付款条件</p>
                        <textarea name="pay_condition" id="pay_condition" cols="30" rows="10" placeholder="Lorem ipsum dolor sit amet, consectetur......."><?php echo HTMLDecode($project_five['pay_condition']); ?></textarea>

                    </div>
                    <div class="middleDiv_seven">
                        <div class="top3">
                            <p>项目提成</p>
                        </div>
                        <div class="cont">
                            <?php if(in_array(1, $bonus_stage)){ ?><p>预付款到账后所得：<span id="first_reward"><?php echo number_format($project_five['first_reward'],2);?></span>元</p><?php } ?>
                            <?php if(in_array(2, $bonus_stage)){ ?><p>项目竣工验收后得：<span id="second_reward"><?php echo number_format($project_five['second_reward'],2);?></span>元</p><?php } ?>
                            <?php if(in_array(3, $bonus_stage)){ ?><p>质保金到账后所得：<span id="third_reward"><?php echo number_format($project_five['third_reward'],2);?></span>元</p><?php } ?>
                        </div>
                    </div>

                    <div class="middleDiv_four">
                        <p>用款计划</p>
                        <textarea name="cost_plan" id="cost_plan" cols="30" rows="10" placeholder="Lorem ipsum dolor sit amet, consectetur....."><?php echo HTMLDecode($project_five['cost_plan']); ?></textarea>

                    </div>
                    <div class="middleDiv_one middleDiv_one_one">
                        <div><p><img class="must_reactImg" src="images/must_react.png" alt="">拟开工日期</p></div>
                        <input id="pre_build_time" type="text" placeholder="2018-06-30" value="<?php echo getDateStrS($project_five['pre_build_time']); ?>">
                    </div>
                    <div class="middleDiv_one middleDiv_one_one">
                        <div><p><img class="must_reactImg" src="images/must_react.png" alt="">验收日期</p></div>
                        <input id="pre_check_time" type="text" placeholder="2018-06-30" value="<?php echo getDateStrS($project_five['pre_check_time']); ?>">
                    </div>

                    <div class="middleDiv_two">
                        <?php if($projectinfo['stop_flag'] != 1){ ?>
                            <button id="project_five_save">保存</button>
                        <?php } ?>
                    </div>

                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>