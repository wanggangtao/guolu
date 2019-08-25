<?php
/**
 *  手动选型 selection_manual.php
 *
 * @version       v0.01
 * @create time   2018/12/05
 * @update time   2018/12/05
 * @author        ozqowen
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉选型</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <style>
        .GLXXmain_3{
            float:left
        }

    </style>
</head>
<body class="body_2">
    <?php include('top.inc.php');?>

    <div class="manageHRWJCont_middle_left" style="margin-top: 30px">
        <ul>
            <a href="selection.php"><li>智能选型</li></a>
            <a href="selection_manual.php"><li class="manage_liCheck">手动选型</li></a>
            <a href="selection_change.php"><li>更换锅炉</li></a>

        </ul>
    </div>
    <div class="manageHRWJCont_middle_middle">
    <div class="GLXXmain">
        <div class="GLXXmain_1">客户名称</div>
        <div class="GLXXmain_2">
            <input type="text" class="GLXXmain_3" id="customer" ><button class="GLXXmain_4" id="resetting">重置</button>
        </div>
        <div class="GLXXmain_1">锅炉是否冷凝</div>
        <div class="GLXXmain_2" id="is_condensate">
            <input type="radio" class="GLXXmain_5" name="is_condensate" value="15" checked><span class="GLXXmain_6">冷凝</span>
            <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_condensate" value="16"><span class="GLXXmain_6">不冷凝</span>
        </div>
        <div class="GLXXmain_1">锅炉是否低氮</div>
        <div class="GLXXmain_2" id="is_lownitrogen">
            <input type="radio" class="GLXXmain_5" name="is_lownitrogen" value="17" checked><span class="GLXXmain_6">低氮30mg</span>
            <!--<input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="18"><span class="GLXXmain_6">低氮80mg</span>-->
            <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="19"><span class="GLXXmain_6">不低氮</span>
        </div>

        <div class="GLXXmain_1">锅炉厂家</div>
        <div class="GLXXmain_2">
            <select id="vender" class="GLXXmain_3" style="width: 344px" type="text">
                <option value="0">全部</option>
                <?php
                $list = Dict::getListByParentid(1);
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                    }
                ?>
            </select>
        </div>

        <div class="GLXXmain_1">锅炉用途</div>
        <div class="GLXXmain_2">
            <div data-id="nuanqi" data-value="0" class="GLXXmain_7 GLXXmain_check">采暖</div>
            <div data-id="water" data-value="1" class="GLXXmain_7">热水</div>
            <div data-id="WandN" data-value="2" class="GLXXmain_7">采暖和热水</div>
        </div>

        <!-- 采暖 -->
        <div id="nuanqi" class="GLXXmain_8" style="display: block">

            <div id="heating_type_all" style="display: block">
                <div class="GLXXmain_1">采暖锅炉形式</div>
                <div class="GLXXmain_11" id="heating_type">
                    <?php
                    foreach ($ARRAY_selection_application['0']['type'] as $key => $val){
                        echo '<div  class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';

                    }
                    ?>
                    <!--清除浮动-->
                    <div class="clear"></div>
                </div>
            </div>

            <div id="area_dom_nuan_qi" style="display: block">
                <div class="GLXXmain_1">分区数</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3" id="area_num_nuan_qi">
                </div>
            </div>

            <div id="guolu_type" >
                <div class="insertion">
                    <div  class="GLXXmain_1">锅炉型号</div>
                    <div class="GLXXmain_2">
                        <select type="text" class="GLXXmain_3" id="guolu_type_list" style="width: 344px">
                        </select>
                        <div style="padding-top: 3px">
                            <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                            <input type="number" class="GLXXmain_12" id="guolu_num">
                            <div class="GLXXmain_14" style="color: #686868;">台</div>
                        </div>
                    </div>
                </div>
                <div class="GLXXmain_16">
                    <span class="addgl" style="display: block" > + 添加锅炉</span>
                    <span class="mougl" style="display: none" > - 删除锅炉</span>
                </div>
            </div>
            <button class="GLXXmain_17"  >下一步</button>
        </div>

        <!-- 热水 -->
        <div id="water" class="GLXXmain_8" style="display: none">

            <div id="water_type_all" style="display: block">
                <div class="GLXXmain_1">热水锅炉形式</div>
                <div class="GLXXmain_11" id="water_type">
                    <?php
                    foreach ($ARRAY_selection_application['1']['type'] as $key => $val){
                        echo '<div  class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';
                    }
                    ?>
                    <!--清除浮动-->
                    <div class="clear"></div>
                </div>
            </div>
            <div id="area_dom_water" style="display: block">
                <div class="GLXXmain_1">分区数</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3" id="area_num_water">
                </div>
            </div>

            <div id="guolu_type" >
                <div class="insertion">
                    <div  class="GLXXmain_1">锅炉型号</div>
                    <div class="GLXXmain_2">
                        <select type="text" class="GLXXmain_3" id="guolu_type_list" style="width: 344px">
                        </select>
                        <div style="padding-top: 3px">
                            <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                            <input type="number" class="GLXXmain_12" id="guolu_num">
                            <div class="GLXXmain_14" style="color: #686868;">台</div>
                        </div>
                    </div>
                </div>
                <div class="GLXXmain_16">
                    <span class="addgl" style="display: block" > + 添加锅炉</span>
                    <span class="mougl" style="display: none" > - 删除锅炉</span>
                </div>
            </div>
            <button class="GLXXmain_17"  >下一步</button>
        </div>

        <!-- 采暖和热水 -->
        <div id="WandN" class="GLXXmain_8" style="display: none">

            <div id="heating_type_all" style="display: block">
                <div class="GLXXmain_1">采暖锅炉形式</div>
                <div class="GLXXmain_11" id="heating_type">
                    <?php
                    foreach ($ARRAY_selection_application['0']['type'] as $key => $val){
                        echo '<div  class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';

                    }
                    ?>
                    <!--清除浮动-->
                    <div class="clear"></div>
                </div>
            </div>

            <div id="area_dom_nuan_qi" style="display: block">
                <div class="GLXXmain_1">分区数</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3" id="area_num_nuan_qi">
                </div>
            </div>

            <div id="water_type_all" style="display: block">
                <div class="GLXXmain_1">热水锅炉形式</div>
                <div class="GLXXmain_11" id="water_type">
                    <?php
                    foreach ($ARRAY_selection_application['1']['type'] as $key => $val){
                        echo '<div class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';
                    }
                    ?>
                    <!--清除浮动-->
                    <div class="clear"></div>
                </div>
            </div>
            <div id="area_dom_water" style="display: block">
                <div class="GLXXmain_1">分区数</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3" id="area_num_water">
                </div>
            </div>

            <div id="guolu_type" >
                <div class="insertion">
                    <div  class="GLXXmain_1">锅炉型号</div>
                    <div class="GLXXmain_2">
                        <select type="text" class="GLXXmain_3" id="guolu_type_list" style="width: 344px">
                        </select>
                        <div style="padding-top: 3px">
                            <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                            <input type="number" class="GLXXmain_12" id="guolu_num">
                            <div class="GLXXmain_14" style="color: #686868;">台</div>
                        </div>
                    </div>
                </div>
                <div class="GLXXmain_16">
                    <span class="addgl" style="display: block" > + 添加锅炉</span>
                    <span class="mougl" style="display: none" > - 删除锅炉</span>
                </div>
            </div>
            <button class="GLXXmain_17"  >下一步</button>
        </div>

    </div>

    </div>

    <script>
        $(function () {


            $('.indexMtwo_1').hover(function () {
                $(this).find('.mouseset').slideDown('fast');
                var name = $(this).find('.indexMtwo_1_2').text();
                $(this).find('.mouseset').find('span').text(name);
            }, function () {
                $(this).find('.mouseset').slideUp(100);
            });

            $('#resetting').click(function () {
                location.href = 'selection_manual.php';
            });

            // 添加锅炉
            $('.addgl').click(function () {

                var newht = $(this).parent().parent().find('.insertion').html();
                var NHtml = '<div  class="insertion">' + newht + '</div>';
                $(this).parent().parent().find('.insertion:nth-last-child(2)').after(NHtml);
                $(this).parent().find('.mougl').css('display', 'block');
            });

            // 删除锅炉
            $('.mougl').click(function () {
                var len = $(this).parent().parent().find(".insertion").length;
                var len = parseFloat(len);
                $(this).parent().parent().find('.insertion:nth-last-child(2)').remove();
                len--;
                if (len <= 1) {
                    $(this).css('display', 'none');
                }

            });

            // 选型
            $('.GLXXmain_17').click(function () {

                var customer = $('#customer').val();

                var is_condensate = $('input[name="is_condensate"]:checked ').val();
                var is_lownitrogen = $('input[name="is_lownitrogen"]:checked ').val();

                //输入项检查
                if (customer == '') {
                    layer.msg('客户名称不能为空');
                    return false;
                }

                if (is_condensate == '' || is_condensate == undefined) {
                    layer.msg('请选择锅炉是否冷凝');
                    return false;
                }
                if (is_lownitrogen == '' || is_lownitrogen == undefined) {
                    layer.msg('请选择锅炉是否低氮');
                    return false;
                }

                var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');// 锅炉用途，对应数据库application字段
                var domId = $('.GLXXmain_7.GLXXmain_check').data('id');


                if (guolu_use == undefined) {
                    layer.msg('请选择锅炉用途');
                    return false;
                }

                var heating_type = 0;
                var water_type = 0;
                var area_num_nuan_qi = 0;
                var area_num_water = 0;

                if(guolu_use==0)
                {
                    heating_type = $("#" + domId).find('#heating_type').find('.GLXXmain_check').data('value');
                    area_num_nuan_qi = $("#"+domId).find("#area_num_nuan_qi").val();
                }

                if(guolu_use==1)
                {
                    water_type = $("#" + domId).find('#water_type').find('.GLXXmain_check').data('value');
                    area_num_water = $("#"+domId).find("#area_num_water").val();
                }

                if(guolu_use==2)
                {
                    heating_type = $("#" + domId).find('#heating_type').find('.GLXXmain_check').data('value');
                    area_num_nuan_qi = $("#"+domId).find("#area_num_nuan_qi").val();
                    water_type = $("#" + domId).find('#water_type').find('.GLXXmain_check').data('value');
                    area_num_water = $("#"+domId).find("#area_num_water").val();
                }

                var guoluArr = new Array();
                var guoluNumArr = new Array();

                $("#" + domId).find(".insertion").each(function(){

                    var currentNum = $(this).find("#guolu_num").val();


                    if(currentNum!=""&&parseFloat(currentNum)>0)
                    {
                        guoluArr.push($(this).find("#guolu_type_list").val());
                        guoluNumArr.push(currentNum);
                    }
                });


                var guoluStr = guoluArr.join(",");
                var guoluNumStr = guoluNumArr.join(",");


                var index = layer.load(0, {shade: false});
                $.ajax({
                    type: 'POST',
                    data: {
                        customer: customer,
                        is_condensate: is_condensate,
                        is_lownitrogen: is_lownitrogen,
                        application: guolu_use,
                        heating_type: heating_type,
                        area_num_nuan_qi: area_num_nuan_qi,
                        water_type: water_type,
                        area_num_water: area_num_water,
                        guoluStr: guoluStr,
                        guoluNumStr: guoluNumStr,
                    },
                    dataType: 'json',
                    url: 'selection_do.php?act=select_guolu_manual',
                    success: function (data) {
                        layer.close(index);
                        var code = data.code;
                        var msg = data.msg;
                        var historyid = data.historyid;
                        switch (code) {
                            case 1:
                                 location.href = 'selection_fuji_manual.php?id=' + historyid;
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });


            $('#is_condensate').change(function () {
                getGuoluList();
            });

            $('#is_lownitrogen').change(function () {
                getGuoluList();
            });

            $('#vender').change(function () {
                getGuoluList();
            })
        });

    </script>

    <script type="text/javascript">


        $(".GLXXmain_7").click(function(){
            $(".GLXXmain_7").removeClass("GLXXmain_check");
            $(this).addClass("GLXXmain_check");
            var domId = $(this).data('id');
            $(".GLXXmain_8").hide();
            $("#"+domId).show();
            getGuoluList();

        });

        $(".GLXXmain_10").click(function(){
            $(this).siblings().removeClass("GLXXmain_check");
            $(this).addClass("GLXXmain_check");
            domChange();
            getGuoluList();
        });

    </script>

    <script type="text/javascript">

        function domChange()
        {
            var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');
            var domId = $('.GLXXmain_7.GLXXmain_check').data('id');

            if(guolu_use==0)
            {
                $("#"+domId).find("#heating_type_all").show();
                $("#"+domId).find("#area_dom_nuan_qi").show();
                $("#"+domId).find("#water_type_all").hide();
                $("#"+domId).find("#area_dom_water").hide();
            }

            if(guolu_use==1)
            {
                $("#"+domId).find("#heating_type_all").hide();
                $("#"+domId).find("#area_dom_nuan_qi").hide();
                $("#"+domId).find("#water_type_all").show();
                $("#"+domId).find("#area_dom_water").show();
            }

            if(guolu_use==2)
            {
                $("#"+domId).find("#heating_type_all").show();
                $("#"+domId).find("#area_dom_nuan_qi").show();
                $("#"+domId).find("#water_type_all").show();
                $("#"+domId).find("#area_dom_water").show();
            }

        }


        // 获取符合条件的锅炉列表
        function getGuoluList() {
            var is_condensate = $("input[name='is_condensate']:checked").val();
            var is_lownitrogen = $("input[name='is_lownitrogen']:checked").val();

            var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');

            var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
            var application = $("#" + domId).find('.GLXXmain_check').data('value');


            var listDom = $("#" + domId).find(".GLXXmain_2").find("#guolu_type_list");
            listDom.html("<option value='0'>暂无可用锅炉</option>");


            if (application == undefined) return false;
            if (guolu_use == undefined) return false;

            var vender = $("#vender").val();

            //获取符合条件的锅炉列表，目前假设获取全部符合要求的列表时其他不需要的参数设为0或空
            $.ajax({
                type: 'POST',
                data: {
                    is_condensate: is_condensate,
                    is_lownitrogen: is_lownitrogen,
                    application: application,
                    guolu_use: guolu_use,
                    vender: vender
                },
                dataType: 'json',
                url: 'selection_do.php?act=get_guolu_list',
                success: function (data) {
                    var code = data.code;
                    var msg = data.msg;
                    var guolu_list = data.data;
                    switch (code) {
                        case 1:
                            // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数

                            var html = "";
                            for (var i = 0; i < guolu_list.length; i++) {
                                var name = guolu_list[i].guolu_version;
                                var value = guolu_list[i].guolu_id;
                                var new_opt = new Option(name, value);

                                html += "<option value='" + value + "'>" + name + "</option>";
                            }

                            listDom.html(html);
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