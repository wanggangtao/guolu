<?php
/**
 * 项目第一阶段 project_stage_one.php
 *
 * @version       v0.01
 * @create time   2018/06/28
 * @update time   2018/06/28
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = "one";
$TOP_FLAG = 'myproject';

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$projectinfo = Project::Init();
$project_one = Project_one::Init();
if(!empty($id)) {
    $projectinfo = Project::getInfoById($id);
    $project_one = Project_one::getInfoByProjectId($id);
    if (empty($projectinfo)) {
        echo '非法操作！';
        die();
    } else {
        if ($projectinfo['user'] != $USERId) {
            echo '没有权限操作！';
            die();
        }
    }
}
$piccount = 0;
$picarr = array();
if($project_one['project_partya_pic']) {
    $picarr = explode("|", $project_one['project_partya_pic']);
    $piccount = count($picarr);
}
$burnerlist = Project_burner_type::getInfoByPoId($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-<?php echo $projectinfo['name'];?>一级</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script src="laydate/laydate.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script src="http://api.map.baidu.com/api?v=2.0&ak=G0uudN8VvLK1QOSTP4i79CmsGWbjOD7R"></script>
    <script src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>

    <script type="text/javascript">
        $(function(){
            laydate({
                elem: '#project_pre_buildtime', //需显示日期的元素选择器
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
            var burnernum = <?php echo !empty($burnerlist)?count($burnerlist):1;?>;
            $('#add_burner_type').click(function(){
                burnernum += 1;
                var htmladd="";
                htmladd +='  <div class="middleDiv_one">';
                htmladd +='     <div> <img src="images/must_react.png" alt=""> <p>类型'+burnernum+'</p></div>';
                htmladd +='     <div> <input type="text" placeholder="锅炉功率" class="guolu_tonnage" value=""><span>KW</span></div>';
                htmladd +='     <div><input type="text" placeholder="锅炉数量"  class="guolu_num" value=""><span>台</span></div>';
                htmladd +='     <div class="remove" style="width: 324px;margin-top:10px;margin-bottom:0px;cursor: pointer;"><p style="padding-left: 150px;margin-bottom:0px;color: red;">-删除该类型</p></div>';
                htmladd +='  </div>';
                $("#type3_content").append(htmladd);
            });
            $("body").on("click", ".remove", function(){
                burnernum -= 1;
                $(this).parent().remove();
            });
            var picnum = <?php echo count($picarr)?count($picarr):1;?>;
            // $('#add_partya_pic').click(function(){
            //     picnum += 1;
            //     var htmladd="";
            //     htmladd +='  <div class="middleDiv_one" style="overflow: hidden;width: 500px;">';
            //     htmladd +='     <div><img src="images/must_react.png" alt=""><p>甲方组织架构</p></div>';
            //     htmladd +='     <div class="fileinput" id="fileinput'+picnum+'"  onchange="return ajaxUpload('+picnum+');">选择图片<input type="file" name="file" id="file'+picnum+'"></div>';
            //     htmladd +='     <input id="project_cid_file'+picnum+'" type="hidden" class="parta_pic" value="" >';
            //     htmladd +='  </div>';
            //     htmladd +='  <div class="managePicture_cont" style="display: none">';
            //     htmladd +='  <img class="openbigimg" id="picimg'+picnum+'" src="" alt="">';
            //     htmladd +='  </div>';
            //     $("#add_partya_pic").parent().before(htmladd);
            //     if(picnum >= 3){
            //         $("#add_partya_pic").parent().hide();
            //     }
            // });
            $('#fileinput').on('change', '#file', function(){
                var num = $('.managePicture_cont').find('.picture_detail').length;
                if (num>=3)
                {
                    layer.msg('超过3张禁止上传');
                }
                else
                {
                    ajaxUpload();
                    return false;
                }

            });


            $('#project_one_sbumit').click(function(){
                var project_name = $('#project_name').val();
                var project_detail = $('#project_detail').val();
                var project_lat = $('#project_lat').val();
                var project_long = $('#project_long').val();
                var project_type = $('#project_type').val();
                var project_partya_desc = $('#project_partya_desc').val();
                var project_partya_address = $('#project_partya_address').val();
                var project_partya = $('#project_partya').val();
                var project_linkman = $('#project_linkman').val();
                var project_linktel = $('#project_linktel').val();
                // var project_history = $('#project_history').val();
                var project_history_attr1 = $('#project_history_attr1').val();
                var project_history_attr2 = $('#project_history_attr2').val();
                var project_linkposition = $('#project_linkposition').val();
                var project_brand = $('#project_brand').val();
                var project_xinghao = $('#project_xinghao').val();
                var project_build_type = $('#project_build_type').val();
                var project_isnew = $('input[name="project_isnew"]:checked ').val();
                var project_pre_buildtime = $('#project_pre_buildtime').val();
                var project_competitive_brand = $('#project_competitive_brand').val();
                var project_competitive_desc = $('#project_competitive_desc').val();
                var project_desc = $('#project_desc').val();
                var project_boiler_num = $('#project_boiler_num').val();
                var project_boiler_tonnage = $('#project_boiler_tonnage').val();
                var project_wallboiler_num = $('#project_wallboiler_num').val();
                var all_guolu_tonnage = all_guolu_num = '';
                var project_partya_pic = "";
                $('.parta_pic').each(function () {
                    project_partya_pic += $(this).val() + '|';
                });
                if(project_name == ''){
                    layer.msg('项目名称不能为空');
                    return false;
                }
                if(project_detail == ''){
                    layer.msg('项目地址不能为空');
                    return false;
                }
                if(project_type == ''){
                    layer.msg('项目类型不能为空');
                    return false;
                }
                // if(project_partya == ''){
                //     layer.msg('甲方单位不能为空');
                //     return false;
                // }
                // if(project_partya_address == ''){
                //     layer.msg('甲方地址不能为空');
                //     return false;
                // }
                // if(project_partya_desc == ''){
                //     layer.msg('甲方简介不能为空');
                //     return false;
                // }
                /*var checkpic = project_partya_pic.replace('|','').replace('|','').replace('|','');
                if(checkpic == ''){
                    layer.msg('甲方组织架构图不能为空');
                    return false;
                }*/
                if(project_linkman == ''){
                    layer.msg('联系人不能为空');
                    return false;
                }
                if(project_linktel == ''){
                    layer.msg('联系人电话不能为空');
                    return false;
                }
                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                // if (!(reg.test(project_linktel))) {
                //     layer.msg('联系人电话:请输入正确的11位手机号码！');
                //     return false;
                // }
                if(project_linkposition == ''){
                    layer.msg('联系人职位不能为空');
                    return false;
                }
                project_boiler_num = 0;
                project_boiler_tonnage = 0;
                /*if(project_type == 1){
                    if(project_boiler_num == ''){
                        layer.msg('采暖总面积不能为空');
                        return false;
                    }
                    if(project_boiler_tonnage == ''){
                        layer.msg('锅炉总吨位不能为空');
                        return false;
                    }
                    project_wallboiler_num = 0;
                }else */
                if(project_type == 2){
                    if(project_wallboiler_num == ''){
                        layer.msg('壁挂炉总数量位不能为空');
                        return false;
                    }
                    project_boiler_num = 0;
                    project_boiler_tonnage = 0;
                }else{
                    project_wallboiler_num = 0;
                    var length = $("#type3_content").children(".middleDiv_one").length;
                    var regnum = /(^[1-9]\d*$)/;
                    for(i=0;i<length;i++){
                        var thisE = $("#type3_content").children(".middleDiv_one").eq(i);
                        var guolu_tonnage = thisE.find('.guolu_tonnage').val();
                        var guolu_num = thisE.find('.guolu_num').val();
                        if(guolu_tonnage == '' || guolu_num == ''){
                            layer.msg('类型'+(i+1)+'所有选项均不能为空');
                            return false;
                        }
                        if (!(regnum.test(guolu_tonnage))) {
                            layer.msg('类型'+(i+1)+'锅炉功率应为正整数');
                            return false;
                        }
                        if (!(regnum.test(guolu_num))) {
                            layer.msg('类型'+(i+1)+'锅炉数量应为正整数');
                            return false;
                        }
                        all_guolu_tonnage = guolu_tonnage+'||'+all_guolu_tonnage;
                        all_guolu_num = guolu_num+'||'+all_guolu_num;
                    }
                }
                if(project_history_attr1 == ''||project_history_attr2 == ''){
                    layer.msg('原锅炉参数不能为空');
                    return false;
                }
                $(this).unbind('click');
                var index = layer.load(0, {shade: false});
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_one['id'];?>,
                        project_id : <?php echo $project_one['project_id'];?>,
                        project_name : project_name,
                        project_detail  : project_detail,
                        project_lat  : project_lat,
                        project_long  : project_long,
                        project_type  : project_type,
                        project_partya  : project_partya,
                        project_partya_address  : project_partya_address,
                        project_partya_desc  : project_partya_desc,
                        project_partya_pic  : project_partya_pic,
                        project_linkman  : project_linkman,
                        project_linktel : project_linktel,
                        // project_history  : project_history,
                        project_history_attr1 : project_history_attr1,
                        project_history_attr2 : project_history_attr2,
                        project_linkposition  : project_linkposition,
                        project_brand  : project_brand,
                        project_xinghao  : project_xinghao,
                        project_build_type  : project_build_type,
                        project_isnew  : project_isnew,
                        project_pre_buildtime  : project_pre_buildtime,
                        project_competitive_brand  : project_competitive_brand,
                        project_competitive_desc  : project_competitive_desc,
                        project_desc  : project_desc,
                        project_boiler_tonnage : project_boiler_tonnage,
                        project_boiler_num  : project_boiler_num,
                        project_wallboiler_num : project_wallboiler_num,
                        all_guolu_tonnage : all_guolu_tonnage,
                        all_guolu_num : all_guolu_num
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_one_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        var projectid = data.projectid;
                        switch(code){
                            case 1:
                                /*layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    location.href = 'project_stage_one.php?id=' + projectid;
                                });*/
                                $.ajax({
                                    type        : 'POST',
                                    data        : {
                                        id : <?php echo $project_one['id'];?>,
                                        project_id : projectid
                                    },
                                    dataType :    'json',
                                    url :         'project_do.php?act=project_one_submit',
                                    success :     function(data){
                                        layer.close(index);
                                        var code = data.code;
                                        var msg  = data.msg;
                                        var projectid = data.projectid;
                                        switch(code){
                                            case 1:
                                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                                    location.href = 'project_stage_one.php?tag=1&id=' + projectid;
                                                });
                                                break;
                                            default:
                                                layer.alert(msg, {icon: 5});
                                        }
                                    }
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

            $('#project_one_save').click(function(){
                var project_name = $('#project_name').val();
                var project_detail = $('#project_detail').val();
                var project_lat = $('#project_lat').val();
                var project_long = $('#project_long').val();
                var project_type = $('#project_type').val();
                var project_partya_desc = $('#project_partya_desc').val();
                var project_partya_address = $('#project_partya_address').val();
                var project_partya = $('#project_partya').val();
                var project_linkman = $('#project_linkman').val();
                var project_linktel = $('#project_linktel').val();
                // var project_history = $('#project_history').val();
                var project_history_attr1 = $('#project_history_attr1').val();
                var project_history_attr2 = $('#project_history_attr2').val();
                var project_linkposition = $('#project_linkposition').val();
                var project_brand = $('#project_brand').val();
                var project_xinghao = $('#project_xinghao').val();
                var project_build_type = $('#project_build_type').val();
                var project_isnew = $('input[name="project_isnew"]:checked ').val();
                var project_pre_buildtime = $('#project_pre_buildtime').val();
                var project_competitive_brand = $('#project_competitive_brand').val();
                var project_competitive_desc = $('#project_competitive_desc').val();
                var project_desc = $('#project_desc').val();
                var project_boiler_num = $('#project_boiler_num').val();
                var project_boiler_tonnage = $('#project_boiler_tonnage').val();
                var project_wallboiler_num = $('#project_wallboiler_num').val();

                var length = $("#type3_content").children(".middleDiv_one").length;
                var all_guolu_tonnage = all_guolu_num = '';
                var regnum = /(^[1-9]\d*$)/;
                for(i=0;i<length;i++){
                    var thisE = $("#type3_content").children(".middleDiv_one").eq(i);
                    var guolu_tonnage = thisE.find('.guolu_tonnage').val();
                    var guolu_num = thisE.find('.guolu_num').val();
                    if (guolu_tonnage != '' && !(regnum.test(guolu_tonnage))) {
                        layer.msg('类型'+(i+1)+'锅炉功率应为正整数');
                        return false;
                    }
                    if (guolu_num != '' && !(regnum.test(guolu_num))) {
                        layer.msg('类型'+(i+1)+'锅炉数量应为正整数');
                        return false;
                    }
                    all_guolu_tonnage = guolu_tonnage+'||'+all_guolu_tonnage;
                    all_guolu_num = guolu_num+'||'+all_guolu_num;
                }
                var project_partya_pic = "";
                $('.parta_pic').each(function () {
                    project_partya_pic += $(this).val() + '|';
                });

                if(project_name == ''){
                    layer.msg('项目名称不能为空');
                    return false;
                }

                if(project_history_attr1 == ''||project_history_attr2 == ''){
                    layer.msg('原锅炉参数不能为空');
                    return false;
                }
                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                // if (project_linktel !='' && !(reg.test(project_linktel))) {
                //     layer.msg('联系人电话:请输入正确的11位手机号码！');
                //     return false;
                // }
                $(this).unbind('click');
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $project_one['id'];?>,
                        project_id : <?php echo $project_one['project_id'];?>,
                        project_name : project_name,
                        project_detail  : project_detail,
                        project_lat  : project_lat,
                        project_long  : project_long,
                        project_type  : project_type,
                        project_partya  : project_partya,
                        project_partya_address  : project_partya_address,
                        project_partya_desc  : project_partya_desc,
                        project_partya_pic  : project_partya_pic,
                        project_linkman  : project_linkman,
                        project_linktel : project_linktel,
                        // project_history  : project_history,
                        project_history_attr1 : project_history_attr1,
                        project_history_attr2 : project_history_attr2,
                        project_linkposition  : project_linkposition,
                        project_brand  : project_brand,
                        project_xinghao  : project_xinghao,
                        project_build_type  : project_build_type,
                        project_isnew  : project_isnew,
                        project_pre_buildtime  : project_pre_buildtime,
                        project_competitive_brand  : project_competitive_brand,
                        project_competitive_desc  : project_competitive_desc,
                        project_desc  : project_desc,
                        project_boiler_tonnage : project_boiler_tonnage,
                        project_boiler_num  : project_boiler_num,
                        project_wallboiler_num : project_wallboiler_num,
                        all_guolu_tonnage : all_guolu_tonnage,
                        all_guolu_num : all_guolu_num
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=project_one_save',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        var projectid = data.projectid;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    location.href = 'project_stage_one.php?tag=1&id=' + projectid;
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });

            $('#openmap').click(function(){

                var content = $('#project_detail').val();
                layer.open({
                    type: 2,
                    title: '选择地址',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['800px', '600px'],
                    content: "address_search.php?content="+content
                });
            });
            $('#project_type').change(function(){
                var val =  $('#project_type').val();

                initType3();
                if( val == 2){
                    $('#type1').hide();
                    $('#type2').show();
                    $('#type3').hide();

                }else{
                    $('#type1').hide();
                    $('#type2').hide();
                    $('#type3').show();
                }
                /*if( val == 1){
                    $('#type1').show();
                    $('#type2').hide();
                    $('#type3').hide();
                }else if( val == 2){
                    $('#type1').hide();
                    $('#type2').show();
                    $('#type3').hide();
                }else if( val == 3){
                    $('#type1').hide();
                    $('#type2').hide();
                    $('#type3').show();
                }*/
            });


            function initType3()
            {

                var initHtml = '<div class="middleDiv_one">\n' +
                    '                            <div> <img src="images/must_react.png" alt=""> <p>类型1</p></div>\n' +
                    '                            <div> <input type="number" placeholder="锅炉功率" class="guolu_tonnage" value=""><span>KW</span></div>\n' +
                    '                            <div><input type="number" placeholder="锅炉数量"  class="guolu_num" value=""><span>台</span></div>\n' +
                    '                        </div>';
                $('#type3_content').html(initHtml);


            }


            $('#record_one').click(function(){
                location.href = 'project_one_record.php?id='+'<?php echo $id;?>'+'&tag='+'<?php echo $tag;?>';
            });
            $('#project_name').blur(function(){
                var project_name = $('#project_name').val();
                $.ajax({
                    type        : 'POST',
                    data        : {
                        project_name  : project_name,
                        id     : <?php echo $id;?>
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=checkProjectname',
                    success :     function(data){
                        switch(data.code){
                            case 1:
                                break;
                            default:
                                layer.alert(data.msg, {icon: 5});
                        }
                    }
                });
            });

            $('#project_detail').blur(function(){
                var project_detail = $('#project_detail').val();
                $.ajax({
                    type        : 'POST',
                    data        : {
                        project_detail  : project_detail,
                        id     : <?php echo $id;?>
                    },
                    dataType :    'json',
                    url :         'project_do.php?act=checkProjectaddress',
                    success :     function(data){
                        switch(data.code){
                            case 1:
                                break;
                            default:
                                layer.alert(data.msg, {icon: 5});
                        }
                    }
                });
            });

            //$('#project_partya').blur(function(){
            //    var project_partya = $('#project_partya').val();
            //    $.ajax({
            //        type        : 'POST',
            //        data        : {
            //            project_partya  : project_partya,
            //            id     : <?php echo $id;?>
            //        },
            //        dataType :    'json',
            //        url :         'project_do.php?act=checkProjectpartya',
            //        success :     function(data){
            //            switch(data.code){
            //                case 1:
            //                    break;
            //                default:
            //                    layer.alert(data.msg, {icon: 5});
            //            }
            //        }
            //    });
            //});

            //$('#project_partya_address').blur(function(){
            //    var partya_address = $('#project_partya_address').val();
            //    $.ajax({
            //        type        : 'POST',
            //        data        : {
            //            partya_address  : partya_address,
            //            id     : <?php echo $id;?>
            //        },
            //        dataType :    'json',
            //        url :         'project_do.php?act=checkPartyaaddress',
            //        success :     function(data){
            //            switch(data.code){
            //                case 1:
            //                    break;
            //                default:
            //                    layer.alert(data.msg, {icon: 5});
            //            }
            //        }
            //    });
            //});
            
            //$('#project_linkman').blur(function(){
            //    var project_linkman = $('#project_linkman').val();
            //    $.ajax({
            //        type        : 'POST',
            //        data        : {
            //            project_linkman  : project_linkman,
            //            id     : <?php //echo $id;?>
            //        },
            //        dataType :    'json',
            //        url :         'project_do.php?act=checkProjectlinkman',
            //        success :     function(data){
            //            switch(data.code){
            //                case 1:
            //                    break;
            //                default:
            //                    layer.alert(data.msg, {icon: 5});
            //            }
            //        }
            //    });
            //});
            //$('#project_linktel').blur(function(){
            //    var project_linktel = $('#project_linktel').val();
            //    var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                // if (!(reg.test(project_linktel))) {
                //     layer.alert('请输入正确的11位手机号码！', {icon: 5});
                //     return false;
                // }
            //    $.ajax({
            //        type        : 'POST',
            //       data        : {
            //            project_linktel  : project_linktel,
            //            id     : <?php echo $id;?>
            //        },
            //        dataType :    'json',
            //        url :         'project_do.php?act=checkProjectlinktel',
            //        success :     function(data){
            //            switch(data.code){
            //                case 1:
            //                    break;
            //                default:
            //                    layer.alert(data.msg, {icon: 5});
            //            }
            //        }
            //    });
            //});

            $("body").on('click','.openbigimg',function(){
                var obj = $(this).attr("src");
                $('#bigimgcontent').attr("src",obj);
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area: '800px',
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true,
                    content: $('#bigimgdiv')
                });
            });
        });
        function ajaxUpload(){
            var uploadUrl = 'pic_upload.php';//处理文件
            var index = layer.load(0, {shade: false});
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file',
                dataType      : 'json',
                success       : function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            layer.msg('上传成功');
                            switch(code){
                                case 1:
                                    var thisPic = '';
                                    thisPic +='<div class="picture_detail">';
                                    thisPic +='<img class="openbigimg" src="<?php echo $HTTP_PATH;?>'+msg+'" alt="" >';
                                    thisPic +='<p><img class="picture_deletBtn" src="images/picture_delet.png" alt=""></p>';
                                    thisPic +='<input id="project_cid_file" type="hidden" class="parta_pic" value="'+msg+'" >';
                                    thisPic +='</div>';
                                    $('.managePicture_cont').append(thisPic)
                                    break;
                            }
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
        .fileinput {
            float: left;
            position: relative;
            overflow: hidden;
            width: 110px;
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
    </style>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
<!-- -->
<!--    <div class="guolumain">-->
<!--        <a href="/">-->
<!--            <div class="guolumain_1">当前位置：项目管理 >-->
<!--                <span>我的项目</span></div>-->
<!--            <div class="clear"></div>-->
        <?php include('project_top.inc.php');?>
<!--    </div>-->
    <div class="manageHRWJCont">
        <?php include('project_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">
                <div class="middleDiv_one">
                    <div><img src="images/must_react.png" alt=""><p>项目名称</p></div>
                    <input type="text" placeholder="项目名称" id="project_name" value="<?php echo $project_one['project_name'] ?>">
                    <div class="manageHRWJCont_middle_right">
                        <?php if($projectinfo['status'] != 2 && $projectinfo['level'] <= 1 && $projectinfo['stop_flag'] != 1){ ?>
                        <button class="submit" id="project_one_sbumit">提交</button>
                        <?php } ?>
                        <button id="record_one">修改记录</button>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="middleDiv_one  middleDiv_one_Two">
                    <div><img src="images/must_react.png" alt=""><p>项目地址 </p></div>
                    <div>
                        <button class="middleDiv_one_btn2" id="openmap">请选择地址</button>
                        <input type="text" placeholder="项目地址" id="project_detail" value="<?php echo $project_one['project_detail']; ?>">
                    </div>
                </div>
                <div class="middleDiv_one middleDiv_one_Two">
                    <div class="middleDiv_one_div"><p>纬度</p><input type="text" placeholder="纬度" id="project_lat" value="<?php echo $project_one['project_lat']; ?>"></div>
                </div>
                <div class="middleDiv_one">
                    <div class="middleDiv_one_div"><p>经度</p> <input type="text" placeholder="经度" id="project_long" value="<?php echo $project_one['project_long']; ?>"></div>

                </div>
                 <div class="middleDiv_one middleDiv_one_select">
                 <div> <img src="images/must_react.png" alt=""> <p>项目类型</p></div>
                     <div>
                         <select id="project_type">
                             <?php
                             $typlist = Project_type::getAllList();
                             foreach ($typlist as $thisinfo){
                                 $selectstr = "";
                                 if($project_one['project_type'] == $thisinfo['id'])
                                     $selectstr = "selected";
                                 echo '<option value ="'.$thisinfo['id'].'"'.$selectstr.'>'.$thisinfo['name'].'</option>';
                             }
                             ?>
                         </select>
                     </div>
                </div>
                <div class="middleDiv_one">
                    <div><img src="images/must_react.png" alt=""><p>联系人</p></div>
                    <div><input type="text" placeholder="联系人"  id="project_linkman" value="<?php echo $project_one['project_linkman']; ?>"></div>
                </div>
                <div class="middleDiv_one">
                    <div> <img src="images/must_react.png" alt=""> <p>电话</p></div>
                    <div><input type="number" placeholder="联系人电话"  id="project_linktel" value="<?php echo $project_one['project_linktel']; ?>"></div>
                </div>
                <div class="middleDiv_one">
                    <div> <img src="images/must_react.png" alt=""> <p>职位</p></div>
                    <div> <input type="text" placeholder="联系人职位"  id="project_linkposition" value="<?php echo $project_one['project_linkposition']; ?>"></div>
                </div>
                <div id="type3" style="display: <?php if($project_one['project_type'] != 2) echo 'block'; else echo 'none';?>">
                    <div class="middleDiv_one">
                        <div><p>原锅炉型号</p></div>
                        <div> <input type="text" id="project_xinghao" value="<?php echo $project_one['project_xinghao']; ?>"></div>
                    </div>
                    <div class="middleDiv_one">
                        <div> <img src="images/must_react.png" alt=""> <p>原锅炉</p></div>
                        <div> <input type="number" placeholder="锅炉功率" id="project_history_attr1"  value="<?php echo substr($project_one['project_history_attr'],0,strrpos($project_one['project_history_attr'],"|")); ?>"><span>KW</span></div>
                        <div><input type="number" placeholder="锅炉数量"  id="project_history_attr2"  value="<?php echo substr($project_one['project_history_attr'],strripos($project_one['project_history_attr'],"|")+1); ?>"><span>台</span></div>
                    </div>
                    <div id="type3_content">
                        <?php
                        if($burnerlist){
                            $i = 0;
                            foreach ($burnerlist as $thisburner){
                                $i++;
                        ?>
                            <div class="middleDiv_one">
                                <div> <img src="images/must_react.png" alt=""> <p>新换锅炉<?php echo $i;?></p></div>
                                <div> <input type="number" placeholder="锅炉功率" class="guolu_tonnage" value="<?php echo $thisburner['guolu_tonnage'] ?>"><span>KW</span></div>
                                <div><input type="number" placeholder="锅炉数量"  class="guolu_num" value="<?php echo $thisburner['guolu_num'] ?>"><span>台</span></div>
                            </div>
                        <?php
                            }
                        }else{
                        ?>
                            <div class="middleDiv_one">
                                <div> <img src="images/must_react.png" alt=""> <p>新换锅炉</p></div>
                                <div> <input type="number" placeholder="锅炉功率" class="guolu_tonnage" value=""><span>KW</span></div>
                                <div><input type="number" placeholder="锅炉数量"  class="guolu_num" value=""><span>台</span></div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="middleDiv_button">
                        <button id="add_burner_type">+添加类型</button>
                    </div>
                </div>
                <div id="type1" style="display: none<?php //if($project_one['project_type'] == 1 || empty($project_one['project_type'])) echo 'block'; else echo 'none';?>">
                    <div class="middleDiv_one">
                        <div> <img src="images/must_react.png" alt=""> <p>采暖总面积</p></div>
                        <div> <input type="text" placeholder="采暖总面积"  id="project_boiler_num" value="<?php echo $project_one['project_boiler_num']; ?>"></div>
                    </div>
                    <div class="middleDiv_one">
                        <div> <img src="images/must_react.png" alt=""> <p>锅炉总吨位</p></div>
                        <div> <input type="text" placeholder="锅炉总吨位"  id="project_boiler_tonnage" value="<?php echo $project_one['project_boiler_tonnage']; ?>"></div>
                    </div>
                </div>
                <div id="type2" style="display: <?php if($project_one['project_type'] == 2) echo 'block'; else echo 'none';?>">
                    <div class="middleDiv_one">
                        <div><p>原锅炉型号</p></div>
                        <div> <input type="text" id="project_xinghao" value="<?php echo $project_one['project_xinghao']; ?>"></div>
                    </div>
                    <div class="middleDiv_one">
                        <div> <img src="images/must_react.png" alt=""> <p>壁挂炉总数量</p></div>
                        <div> <input type="number" placeholder="壁挂炉总数量"  id="project_wallboiler_num" value="<?php echo $project_one['project_wallboiler_num']; ?>"><span>台</span></div>
                    </div>
                </div>
                <div class="middleDiv_one">
                    <div><p>甲方单位</p></div>
                    <div><input type="text" placeholder="甲方单位"  id="project_partya" value="<?php echo $project_one['project_partya']; ?>"></div>
                </div>
                <div class="middleDiv_one">
                    <div><p>甲方地址</p></div>
                    <div><input type="text" placeholder="甲方地址"  id="project_partya_address" value="<?php echo $project_one['project_partya_address']; ?>"></div>
                </div>
                <div class="middleDiv_one">
                    <p>甲方简介</p>
                    <div><textarea cols="30" rows="10" placeholder="甲方简介"  id="project_partya_desc" /><?php echo HTMLDecode($project_one['project_partya_desc']); ?></textarea></div>
                </div>
                <div class="middleDiv_one" style="overflow: hidden;width: 500px;">
                    <div><p>甲方组织架构 <span class="note_span">注：请上传jpg/png,最多3张</span></p></div>
                    <div class="fileinput" id="fileinput">选择图片<input type="file" name="file" id="file"></div>
                </div>
                <div class="managePicture_cont">
                    <?php
                    if($picarr){
                        $j=0;
                        foreach ($picarr as $pic){
                            $j++;
                            ?>

                            <div class="picture_detail">
                                <img class="openbigimg" src="<?php echo $HTTP_PATH.$pic;?>" alt="" >
                                <p><img class="picture_deletBtn" src="images/picture_delet.png" alt=""></p>
                                <input id="project_cid_file" type="hidden" class="parta_pic" value="<?php echo $pic;?>" >
                            </div>
                            <?php
                        }

                    }
                    ?>

                </div>
                <div class="clear"></div>

                <div class="middleDiv_one">
                    <p>拟用锅炉品牌</p>
                    <div><input type="text" placeholder="康佳"  id="project_brand" value="<?php echo $project_one['project_brand'] ?>"></div>
                </div>
<!--                <div class="middleDiv_one">-->
<!--                    <p>锅炉型号</p>-->
<!--                    <div><input type="text" placeholder="EXCLE-39g"  id="project_xinghao" value="--><?php //echo $project_one['project_xinghao'] ?><!--"></div>-->
<!--                </div>-->
                <div class="middleDiv_one middleDiv_one_select">
                    <p>建筑类型</p>
                    <div>
                        <select id="project_build_type">
                            <?php
                            foreach ($ARRAY_project_build_type as $key => $val){
                                $selectstr = "";
                                if($project_one['project_build_type'] == $key)
                                    $selectstr = "selected";
                                echo '<option value ="'.$key.'"'.$selectstr.'>'.$val.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="middleDiv_one">
                    <p>新建锅炉房</p>
                    <div class="middleDiv_one_radio">
                        <label><input name="project_isnew" type="radio" value="1" <?php if($project_one['project_isnew'] == 1) echo "checked"; ?>/>是</label>
                        <label><input name="project_isnew" type="radio" value="0" <?php if($project_one['project_isnew'] == 0) echo "checked"; ?>/>否 </label> </div>

                </div>
                <div class="middleDiv_one">
                    <p>项目拟开工日期</p>
                    <div><input type="text" placeholder="项目拟开工日期"  id="project_pre_buildtime" value="<?php echo $project_one['project_pre_buildtime']?getDateStrS($project_one['project_pre_buildtime']):''; ?>" readonly></div>
                </div>
                <div class="middleDiv_one">
                    <p>竞争品牌</p>
                    <div><input type="text" placeholder="竞争品牌名称"  id="project_competitive_brand" value="<?php echo $project_one['project_competitive_brand']; ?>"></div>
                </div>
                <div class="middleDiv_one">
                    <p>竞品情况</p>
                    <div><textarea cols="30" rows="10" placeholder="竞品情况"  id="project_competitive_desc" /><?php echo HTMLDecode($project_one['project_competitive_desc']); ?></textarea></div>
                </div>
                <div class="middleDiv_one">
                    <p>备注</p>
                    <div><textarea cols="30" rows="10" placeholder="备注"  id="project_desc" /><?php echo HTMLDecode($project_one['project_desc']); ?></textarea></div>
                </div>
                <div class="middleDiv_two">
                    <?php if($projectinfo['status'] != 2 && $projectinfo['stop_flag'] != 1){ ?>
                        <button id="project_one_save">保存</button>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div id="bigimgdiv" style="display: none"><img id="bigimgcontent" src="" width="800px" height="600px"></div>
<!--    <div id="mapdiv" style="display: none">-->
<!--        <div style="margin-bottom:2px;overflow:visible;">-->
<!--            <input id="searchplace" style="margin-left:10px;width: 324px;height: 34px;border-radius: 6px;margin-top: 4px;background: #FFFFFF;border: 1px solid #DDE3E8;" class="easyui-textbox-simple" placeholder="输入搜索关键字" />-->
<!--            <a id="s_p_search_btn" href="javascript:void(0);" class="easyui-linkbutton" style="width: 80px;" iconcls="icon-search">搜索</a>-->
<!--        </div>-->
<!--        <div id="searchlist" style="width: 350px; height: 460px; margin-right:10px; float:left;position: absolute;top: 40px;z-index: 999;"></div>-->
<!--        <div id="allmap" style="height:600px;width:800px;display: block;"></div>-->
<!--    </div>-->

    <script type="text/javascript">
        // 百度地图API功能
//        var map = new BMap.Map("allmap",{enableMapClick: false});
//        var point = new BMap.Point(108.70, 34.43);
//        map.centerAndZoom(point,12);
//        map.enableScrollWheelZoom(true);
//        var geoc = new BMap.Geocoder();
//        map.setCurrentCity("西安");
//
//        map.addEventListener("click", function(e){
//            //通过点击百度地图，可以获取到对应的point, 由point的lng、lat属性就可以获取对应的经度纬度
//            var pt = e.point;
//            geoc.getLocation(pt, function(rs){
//                //addressComponents对象可以获取到详细的地址信息
//                var addComp = rs.addressComponents;
//                var site = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
//                //将对应的HTML元素设置值
//                $("#project_detail").val(site);
//                $("#project_long").val(pt.lng);
//                $("#project_lat").val(pt.lat);
//            });
//        });
//
//        marker.addEventListener("click",attribute);
//        }
//        createSearch();
//        createAutocomlete();
//        $("#s_p_search_btn").click(function () {
//            searchPlace($("#searchplace").val());
//        });
//        function createSearch() {
//            var map = window.map;
//            var local = new BMap.LocalSearch(map,
//                {
//                    renderOptions: { map: map, panel: "searchlist" }
//                });
//            window.local = local;
//        }
//        //搜索
//        function searchPlace(value) {
//            window.local.search(value);
//        }
//        function createAutocomlete() {
//            var map = window.map;
//            var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
//                {
//                    "input": "searchplace",
//                    "location": map
//                });
//            ac.addEventListener("onconfirm", function (e) {    //鼠标点击下拉列表后的事件
//                var _value = e.item.value;
//                var addr =_value.business+ _value.province + _value.city + _value.district + _value.street + _value.streetNumber ;
//                searchPlace(addr);
//            });
//        }
        $(document).on("mouseover mouseout",'.picture_detail',function(event){
            if(event.type == "mouseover"){
                $(this).find('p').css('display','block');
                $('.managePicture_cont .picture_deletBtn').click(function(){
                    $(this).parent().parent().remove();
                })
            }else if(event.type == "mouseout"){
                $(this).find('p').css('display','none');
            }
        })


    </script>
</body>
</html>