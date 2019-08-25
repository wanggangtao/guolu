<?php
/**
 * 添加锅炉  guolu_add.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Guolu_attr::getInfoById($id);
if(empty($info))
    die();

$pInfo = Products::getInfoById($info['proid']);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/lunbo.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="js/dragMove.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){
            var ckeditor = CKEDITOR.replace('desc',{
                toolbar : 'Common',
                forcePasteAsPlainText : 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl : 'ckeditor_upload.php?type=img',
                filebrowserFlashUploadUrl : 'ckeditor_upload.php?type=flash',
                filebrowserUploadUrl : 'ckeditor_upload.php?type=file'
            });



            $('#btn_sumit').click(function(){

                var version = $('#version').val();
                var vender = $('#vender').val();
                var type = $('#type').val();
                var is_condensate = $('#is_condensate').val();
                var is_lownitrogen = $('#is_lownitrogen').val();
                var ratedpower = $('#ratedpower').val();
                var inwater_t = $('#inwater_t').val();
                var outwater_t = $('#outwater_t').val();
                var pressure = $('#pressure').val();
                var fueltype = $('#fueltype').val();
                var gas_consumption = $('#gas_consumption').val();
                var fuel_consumption = $('#fuel_consumption').val();
                var flue_caliber = $('#flue_caliber').val();
                var hauled_weight = $('#hauled_weight').val();
                var hot_flow = $('#hot_flow').val();
                var interface_diameter = $('#interface_diameter').val();
                var pressure_drop = $('#pressure_drop').val();
                var length = $('#length').val();
                var width = $('#width').val();
                var height = $('#height').val();
                var smoke_height = $('#smoke_height').val();
                var water = $('#water').val();
                var price = $('#price').val();
                var img = $('#img1').val();
                var desc = ckeditor.getData();

                var min_flow = $('#min_flow').val();
                var max_flow = $('#max_flow').val();
                var heatout_60 = $('#heatout_60').val();
                var heatout_30 = $('#heatout_30').val();
                var heatout_range = $('#heatout_range').val();
                var heateffi_80 = $('#heateffi_80').val();
                var heateffi_50 = $('#heateffi_50').val();
                var heateffi_30 = $('#heateffi_30').val();
                var syswater_pre = $('#syswater_pre').val();
                var heat_capacity = $('#heat_capacity').val();
                var fluegas_80 = $('#fluegas_80').val();
                var fluegas_50 = $('#fluegas_50').val();
                var emission_co = $('#emission_co').val();
                var emission_nox = $('#emission_nox').val();
                var condensed_max = $('#condensed_max').val();
                var condensed_ph = $('#condensed_ph').val();
                var flue_interface = $('#flue_interface').val();
                var gas_interface = $('#gas_interface').val();
                var iowater_interface = $('#iowater_interface').val();
                var gas_type = $('#gas_type').val();
                var gas_press = $('#gas_press').val();
                var gaspre_range = $('#gaspre_range').val();
                var energy_level = $('#energy_level').val();
                var net_weight = $('#net_weight').val();
                var refer_heatarea = $('#refer_heatarea').val();
                var power_supply = $('#power_supply').val();
                var noise = $('#noise').val();
                var electric_power = $('#electric_power').val();

                var reg = /(^[0-9]\d*$)/;
                var floatreg = /^(-?\d+)(\.\d+)?$/;

                // var wxdesc = wxckeditor.getData();
                // var detail_video = $('.detailVideo').attr('src')?$('.detailVideo').attr('src'):'';
                //
                // var detail_placehold =$('#detail_placehold').attr('src')?$('#detail_placehold').attr('src'):'';
                //
                // detail_video = detail_video +';' + detail_placehold +';';
                //
                // var detail_imgs ='';
                // $('.detail_img').each(function () {
                //     detail_imgs += $(this).attr('src')+";"
                // });


                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    $("#version").focus();
                    return false;
                }
                if(vender == ''){
                    layer.tips('厂家不能为空', '#vender');
                    $("#vender").focus();
                    return false;
                }
                if (!(reg.test(vender))) {
                    layer.tips('厂家应为正整数', '#vender');
                    $("#vender").focus();
                    return false;
                }
                if(type == ''){
                    layer.tips('类型不能为空', '#type');
                    $("#type").focus();
                    return false;
                }
                if (!(reg.test(type))) {
                    layer.tips('类型应为正整数', '#type');
                    $("#type").focus();
                    return false;
                }
                if(is_condensate == ''){
                    layer.tips('是否冷凝不能为空', '#is_condensate');
                    $("#is_condensate").focus();
                    return false;
                }
                if (!(reg.test(is_condensate))) {
                    layer.tips('是否冷凝应为正整数', '#is_condensate');
                    $("#is_condensate").focus();
                    return false;
                }
                if(is_lownitrogen == ''){
                    layer.tips('是否低氮不能为空', '#is_lownitrogen');
                    $("#is_lownitrogen").focus();
                    return false;
                }
                if (!(reg.test(is_lownitrogen))) {
                    layer.tips('是否低氮应为正整数', '#is_lownitrogen');
                    $("#is_lownitrogen").focus();
                    return false;
                }
                if(ratedpower == ''){
                    layer.tips('额定热输入不能为空', '#ratedpower');
                    $("#ratedpower").focus();
                    return false;
                }
                if (!(reg.test(ratedpower))) {
                    layer.tips('额定热输入应为正整数', '#ratedpower');
                    $("#ratedpower").focus();
                    return false;
                }
                /*if(min_flow == ''){
                    layer.tips('额定功率下最小燃气流量不能为空', '#min_flow');
                    $("#min_flow").focus();
                    return false;
                }*/
                if (min_flow != '' && !(floatreg.test(min_flow))) {
                    layer.tips('额定功率下最小燃气流量应为数字', '#min_flow');
                    $("#min_flow").focus();
                    return false;
                }
                /*if(max_flow == ''){
                    layer.tips('额定功率下最大燃气流量不能为空', '#max_flow');
                    $("#max_flow").focus();
                    return false;
                }*/
                if (max_flow != '' && !(floatreg.test(max_flow))) {
                    layer.tips('额定功率下最大燃气流量应为数字', '#max_flow');
                    $("#max_flow").focus();
                    return false;
                }

                /*if(heatout_60 == ''){
                    layer.tips('最大负荷80℃~60℃热输出不能为空', '#heatout_60');
                    $("#heatout_60").focus();
                    return false;
                }*/
                if (heatout_60 != '' && !(floatreg.test(heatout_60))) {
                    layer.tips('最大负荷80℃~60℃热输出应为数字', '#heatout_60');
                    $("#heatout_60").focus();
                    return false;
                }
                /*if(heatout_30 == ''){
                    layer.tips('最大负荷50℃~30℃热输出不能为空', '#heatout_30');
                    $("#heatout_30").focus();
                    return false;
                }*/
                if (heatout_30 != '' && !(floatreg.test(heatout_30))) {
                    layer.tips('最大负荷50℃~30℃热输出应为数字', '#heatout_30');
                    $("#heatout_30").focus();
                    return false;
                }
                /*if(heatout_range == ''){
                    layer.tips('热输入调节范围不能为空', '#heatout_range');
                    $("#heatout_range").focus();
                    return false;
                }
                if(heateffi_80 == ''){
                    layer.tips('最大负荷80℃~60℃热效率不能为空', '#heateffi_80');
                    $("#heateffi_80").focus();
                    return false;
                }*/
                if (heateffi_80 != '' && !(floatreg.test(heateffi_80))) {
                    layer.tips('最大负荷80℃~60℃热效率应为数字', '#heateffi_80');
                    $("#heateffi_80").focus();
                    return false;
                }
                /*if(heateffi_50 == ''){
                    layer.tips('最大负荷50℃~30℃热效率不能为空', '#heateffi_50');
                    $("#heateffi_50").focus();
                    return false;
                }*/
                if (heateffi_50 != '' && !(floatreg.test(heateffi_50))) {
                    layer.tips('最大负荷50℃~30℃热效率应为数字', '#heateffi_50');
                    $("#heateffi_50").focus();
                    return false;
                }
                /*if(heateffi_30 == ''){
                    layer.tips('30%负荷50℃~30℃热效率不能为空', '#heateffi_30');
                    $("#heateffi_30").focus();
                    return false;
                }*/
                if (heateffi_30 != '' && !(floatreg.test(heateffi_30))) {
                    layer.tips('30%负荷50℃~30℃热效率应为数字', '#heateffi_30');
                    $("#heateffi_30").focus();
                    return false;
                }
                if(inwater_t == ''){
                    layer.tips('进水温度不能为空', '#inwater_t');
                    $("#inwater_t").focus();
                    return false;
                }
                if (!(reg.test(inwater_t))) {
                    layer.tips('进水温度应为正整数', '#inwater_t');
                    $("#inwater_t").focus();
                    return false;
                }
                if(outwater_t == ''){
                    layer.tips('出水温度不能为空', '#outwater_t');
                    $("#outwater_t").focus();
                    return false;
                }
                if (!(reg.test(outwater_t))) {
                    layer.tips('出水温度应为正整数', '#outwater_t');
                    $("#outwater_t").focus();
                    return false;
                }
                /*if(pressure == ''){
                    layer.tips('工作压力不能为空', '#pressure');
                    return false;
                }
                if (!(reg.test(pressure))) {
                    layer.tips('工作压力应为正整数', '#pressure');
                    return false;
                }
                if(fueltype == ''){
                    layer.tips('燃料类型不能为空', '#fueltype');
                    return false;
                }
                if(gas_consumption == ''){
                    layer.tips('燃气消耗量不能为空', '#gas_consumption');
                    return false;
                }
                if (!(reg.test(gas_consumption))) {
                    layer.tips('燃气消耗量应为正整数', '#gas_consumption');
                    return false;
                }
                if(fuel_consumption == ''){
                    layer.tips('燃油消耗量不能为空', '#fuel_consumption');
                    return false;
                }
                if (!(reg.test(fuel_consumption))) {
                    layer.tips('燃油消耗量应为正整数', '#fuel_consumption');
                    return false;
                }*/

                /*if(syswater_pre == ''){
                    layer.tips('最低/最高系统水压不能为空', '#syswater_pre');
                    $("#syswater_pre").focus();
                    return false;
                }
                if(heat_capacity == ''){
                    layer.tips('供热水能力不能为空', '#heat_capacity');
                    $("#heat_capacity").focus();
                    return false;
                }*/
                if (heat_capacity != '' && !(floatreg.test(heat_capacity))) {
                    layer.tips('供热水能力应为数字', '#heat_capacity');
                    $("#heat_capacity").focus();
                    return false;
                }
                /*if(hot_flow == ''){
                    layer.tips('最大水流量不能为空', '#hot_flow');
                    $("#hot_flow").focus();
                    return false;
                }*/
                if (hot_flow != '' && !(floatreg.test(hot_flow))) {
                    layer.tips('最大水流量应为数字', '#hot_flow');
                    $("#hot_flow").focus();
                    return false;
                }
                /*if(fluegas_80 == ''){
                    layer.tips('烟气温度（最大负荷80℃~60℃）不能为空', '#fluegas_80');
                    $("#fluegas_80").focus();
                    return false;
                }
                if(fluegas_50 == ''){
                    layer.tips('烟气温度（最大负荷50℃~30℃）不能为空', '#fluegas_50');
                    $("#fluegas_50").focus();
                    return false;
                }
                if(emission_co == ''){
                    layer.tips('CO排放不能为空', '#emission_co');
                    $("#emission_co").focus();
                    return false;
                }
                if(emission_nox == ''){
                    layer.tips('NOx排放不能为空', '#emission_nox');
                    $("#emission_nox").focus();
                    return false;
                }

                if(condensed_max == ''){
                    layer.tips('最大冷凝水排量不能为空', '#condensed_max');
                    $("#condensed_max").focus();
                    return false;
                }*/
                if (condensed_max != '' && !(floatreg.test(condensed_max))) {
                    layer.tips('最大冷凝水排量应为数字', '#condensed_max');
                    $("#condensed_max").focus();
                    return false;
                }

                /*if(condensed_ph == ''){
                    layer.tips('冷凝水PH值不能为空', '#condensed_ph');
                    $("#condensed_ph").focus();
                    return false;
                }*/
                if (condensed_ph != '' && !(floatreg.test(condensed_ph))) {
                    layer.tips('冷凝水PH值应为数字', '#condensed_ph');
                    $("#condensed_ph").focus();
                    return false;
                }

                if(flue_interface == ''){
                    layer.tips('烟道接口不能为空', '#flue_interface');
                    $("#flue_interface").focus();
                    return false;
                }
                if (flue_interface != '' && !(reg.test(flue_interface))) {
                    layer.tips('烟道接口应为正整数', '#flue_interface');
                    $("#flue_interface").focus();
                    return false;
                }
                /*if(flue_caliber == ''){
                    layer.tips('管道口径不能为空', '#flue_caliber');
                    $("#flue_caliber").focus();
                    return false;
                }
                if (!(reg.test(flue_caliber))) {
                    layer.tips('管道口径应为正整数', '#flue_caliber');
                    $("#flue_caliber").focus();
                    return false;
                }
                if(gas_interface == ''){
                    layer.tips('燃气接口不能为空', '#gas_interface');
                    $("#gas_interface").focus();
                    return false;
                }
                if(iowater_interface == ''){
                    layer.tips('进出水接口不能为空', '#iowater_interface');
                    $("#iowater_interface").focus();
                    return false;
                }
                if(water == ''){
                    layer.tips('锅炉水容量不能为空', '#water');
                    $("#water").focus();
                    return false;
                }*/
                if (water != '' && !(floatreg.test(water))) {
                    layer.tips('锅炉水容量应为正整数', '#water');
                    $("#water").focus();
                    return false;
                }
                /*if(gas_type == ''){
                    layer.tips('燃气类型不能为空', '#gas_type');
                    $("#gas_type").focus();
                    return false;
                }
                if(gas_press == ''){
                    layer.tips('额定燃气压力(动压)不能为空', '#gas_press');
                    $("#gas_press").focus();
                    return false;
                }*/
                if (gas_press != '' && !(reg.test(gas_press))) {
                    layer.tips('额定燃气压力(动压)应为正整数', '#gas_press');
                    $("#gaspre_range").focus();
                    return false;
                }
                /*if(gaspre_range == ''){
                    layer.tips('燃气工作压力范围(动压)不能为空', '#gaspre_range');
                    $("#gaspre_range").focus();
                    return false;
                }
                if(energy_level == ''){
                    layer.tips('能效等级不能为空', '#energy_level');
                    $("#energy_level").focus();
                    return false;
                }
                if(pressure_drop == ''){
                    layer.tips('压力降不能为空', '#pressure_drop');
                    return false;
                }
                if (!(reg.test(pressure_drop))) {
                    layer.tips('压力降应为正整数', '#pressure_drop');
                    return false;
                }
                if(length == ''){
                    layer.tips('锅炉长度不能为空', '#length');
                    $("#length").focus();
                    return false;
                }*/
                if (length != '' && !(reg.test(length))) {
                    layer.tips('锅炉长度应为正整数', '#length');
                    $("#length").focus();
                    return false;
                }
                /*if(width == ''){
                    layer.tips('锅炉宽度不能为空', '#width');
                    $("#width").focus();
                    return false;
                }*/
                if (width != '' && !(reg.test(width))) {
                    layer.tips('锅炉宽度应为正整数', '#width');
                    $("#width").focus();
                    return false;
                }
                /*if(height == ''){
                    layer.tips('锅炉高度不能为空', '#height');
                    $("#height").focus();
                    return false;
                }*/
                if (height != '' && !(reg.test(height))) {
                    layer.tips('锅炉高度应为正整数', '#height');
                    $("#height").focus();
                    return false;
                }
                /*if(net_weight == ''){
                    layer.tips('重量（净）不能为空', '#net_weight');
                    $("#net_weight").focus();
                    return false;
                }*/
                if (net_weight != '' && !(reg.test(net_weight))) {
                    layer.tips('重量（净）应为正整数', '#net_weight');
                    $("#net_weight").focus();
                    return false;
                }
                /*if(refer_heatarea == ''){
                    layer.tips('参考供热面积不能为空', '#refer_heatarea');
                    $("#refer_heatarea").focus();
                    return false;
                }*/
                if (refer_heatarea != '' && !(reg.test(refer_heatarea))) {
                    layer.tips('参考供热面积应为正整数', '#refer_heatarea');
                    $("#refer_heatarea").focus();
                    return false;
                }
                /*if(smoke_height == ''){
                    layer.tips('出烟口中心高度不能为空', '#smoke_height');
                    return false;
                }
                if (!(reg.test(smoke_height))) {
                    layer.tips('出烟口中心高度应为正整数', '#smoke_height');
                    return false;
                }
                if(power_supply == ''){
                    layer.tips('电源不能为空', '#power_supply');
                    $("#power_supply").focus();
                    return false;
                }
                if(noise == ''){
                    layer.tips('噪音不能为空', '#noise');
                    $("#noise").focus();
                    return false;
                }
                if(electric_power == ''){
                    layer.tips('电功率不能为空', '#electric_power');
                    $("#electric_power").focus();
                    return false;
                }*/
                if (electric_power != '' && !(reg.test(electric_power))) {
                    layer.tips('电功率应为正整数', '#electric_power');
                    $("#electric_power").focus();
                    return false;
                }
                // if(price != ''){
                //     if (!(reg.test(price))) {
                //         layer.tips('价格应为正整数', '#price');
                //         $("#price").focus();
                //         return false;
                //     }
                // }else
                //     price = 0;
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        vender  : vender,
                        type  : type,
                        is_condensate  : is_condensate,
                        is_lownitrogen  : is_lownitrogen,
                        ratedpower  : ratedpower,
                        inwater_t  : inwater_t,
                        outwater_t  : outwater_t,
                        pressure  : pressure,
                        fueltype  : fueltype,
                        gas_consumption  : gas_consumption,
                        fuel_consumption  : fuel_consumption,
                        flue_caliber  : flue_caliber,
                        hauled_weight  : hauled_weight,
                        hot_flow  : hot_flow,
                        interface_diameter  : interface_diameter,
                        pressure_drop  : pressure_drop,
                        length  : length,
                        width  : width,
                        height  : height,
                        smoke_height : smoke_height,
                        water  : water,
                        // price  : price,
                        img  : img,
                        desc  : desc,
                        min_flow  : min_flow,
                        max_flow  : max_flow,
                        heatout_60  : heatout_60,
                        heatout_30  : heatout_30,
                        heatout_range  : heatout_range,
                        heateffi_80  : heateffi_80,
                        heateffi_50  : heateffi_50,
                        heateffi_30  : heateffi_30,
                        syswater_pre  : syswater_pre,
                        heat_capacity  : heat_capacity,
                        fluegas_80  : fluegas_80,
                        fluegas_50  : fluegas_50,
                        emission_co  : emission_co,
                        emission_nox  : emission_nox,
                        condensed_max  : condensed_max,
                        condensed_ph  : condensed_ph,
                        flue_interface  : flue_interface,
                        gas_interface  : gas_interface,
                        iowater_interface  : iowater_interface,
                        gas_type  : gas_type,
                        gas_press  : gas_press,
                        gaspre_range  : gaspre_range,
                        energy_level  : energy_level,
                        net_weight  : net_weight,
                        refer_heatarea  : refer_heatarea,
                        power_supply  : power_supply,
                        noise  : noise,
                        electric_power  : electric_power,
                        // wxdesc  : wxdesc,
                        // detail_video  :  detail_video,
                        // detail_imgs  :  detail_imgs,
                    },
                    dataType :    'json',
                    url :         'guolu_do.php?act=edit',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            });
        });

        function ajaxUpload(value){
            if($('#file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=guluimg';//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#img'+ value).val(msg);
                            $('#val'+ value).html(msg);
                            layer.msg('上传成功');
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                },
                error: function (data, status, e){
                    layer.alert(e);
                }
            })
            return false;
        }
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label style="width: 200px;">型号</label>
        <input type="text" class="text-input input-length-50" name="version" id="version" value="<?php echo $info['version'];?>"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label style="width: 140px;">厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByCat(1,1);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
        <label style="width: 40px;">类型</label>
        <select name="type" class="select-option" id="type">
            <?php
            $list = Dict::getListByParentid(2);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['type'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
        <label style="width: 60px;">是否冷凝</label>
        <select name="is_condensate" class="select-option" id="is_condensate">
            <?php
            $list = Dict::getListByParentid(3);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['is_condensate'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
        <label style="width: 60px;">是否低氮</label>
        <select name="is_lownitrogen" class="select-option" id="is_lownitrogen">
            <?php
            $list = Dict::getListByParentid(4);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['is_lownitrogen'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label style="width: 200px;">额定热输入(KW)</label>
        <input type="text" class="text-input input-length-10" name="ratedpower" id="ratedpower" value="<?php echo $info['ratedpower'];?>"/>
        <span class="warn-inline">* </span>
        <label style="width: 200px;">额定功率下最小燃气流量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="min_flow" id="min_flow" value="<?php echo $info['min_flow'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">额定功率下最大燃气流量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="max_flow" id="max_flow" value="<?php echo $info['max_flow'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">最大负荷80℃~60℃热输出(kw)</label>
        <input type="text" class="text-input input-length-10" name="heatout_60" id="heatout_60" value="<?php echo $info['heatout_60'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">最大负荷50℃~30℃热输出(kw)</label>
        <input type="text" class="text-input input-length-10" name="heatout_30" id="heatout_30" value="<?php echo $info['heatout_30'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">热输入调节范围(kw)</label>
        <input type="text" class="text-input input-length-10" name="heatout_range" id="heatout_range" value="<?php echo $info['heatout_range'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">最大负荷80℃~60℃热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="heateffi_80" id="heateffi_80" value="<?php echo $info['heateffi_80'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">最大负荷50℃~30℃热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="heateffi_50" id="heateffi_50" value="<?php echo $info['heateffi_50'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">30%负荷50℃~30℃热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="heateffi_30" id="heateffi_30" value="<?php echo $info['heateffi_30'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">进水温度(℃)</label>
        <input type="text" class="text-input input-length-10" name="inwater_t" id="inwater_t" value="<?php echo $info['inwater_t'];?>"/>
        <span class="warn-inline">* </span>
        <label style="width: 200px;">出水温度(℃)</label>
        <input type="text" class="text-input input-length-10" name="outwater_t" id="outwater_t" value="<?php echo $info['outwater_t'];?>"/>
        <span class="warn-inline">* </span>
        <label style="width: 200px;">最低/最高系统水压(bar)</label>
        <input type="text" class="text-input input-length-10" name="syswater_pre" id="syswater_pre" value="<?php echo $info['syswater_pre'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">供热水能力(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="heat_capacity" id="heat_capacity" value="<?php echo $info['heat_capacity'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">最大水流量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="hot_flow" id="hot_flow" value="<?php echo $info['hot_flow'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">烟气温度（最大负荷80℃~60℃）(℃)</label>
        <input type="text" class="text-input input-length-10" name="fluegas_80" id="fluegas_80" value="<?php echo $info['fluegas_80'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">烟气温度（最大负荷50℃~30℃）(℃)</label>
        <input type="text" class="text-input input-length-10" name="fluegas_50" id="fluegas_50" value="<?php echo $info['fluegas_50'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">CO排放(mg/m³)</label>
        <input type="text" class="text-input input-length-10" name="emission_co" id="emission_co" value="<?php echo $info['emission_co'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">NOx排放(mg/m³)</label>
        <input type="text" class="text-input input-length-10" name="emission_nox" id="emission_nox" value="<?php echo $info['emission_nox'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">最大冷凝水排量(L/h)</label>
        <input type="text" class="text-input input-length-10" name="condensed_max" id="condensed_max" value="<?php echo $info['condensed_max'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">冷凝水PH值</label>
        <input type="text" class="text-input input-length-10" name="condensed_ph" id="condensed_ph" value="<?php echo $info['condensed_ph'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">烟道接口φ(mm)</label>
        <input type="text" class="text-input input-length-10" name="flue_interface" id="flue_interface" value="<?php echo $info['flue_interface'];?>"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label style="width: 200px;">燃气接口</label>
        <input type="text" class="text-input input-length-10" name="gas_interface" id="gas_interface" value="<?php echo $info['gas_interface'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">进出水接口(DN)</label>
        <input type="text" class="text-input input-length-10" name="iowater_interface" id="iowater_interface" value="<?php echo $info['iowater_interface'];?>"/>
        <span class="warn-inline">* </span>
        <label style="width: 200px;">锅炉水容量(L)</label>
        <input type="text" class="text-input input-length-10" name="water" id="water" value="<?php echo $info['water'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">燃气类型</label>
        <input type="text" class="text-input input-length-10" name="gas_type" id="gas_type" value="<?php echo $info['gas_type'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">额定燃气压力(动压)Pa</label>
        <input type="text" class="text-input input-length-10" name="gas_press" id="gas_press" value="<?php echo $info['gas_press'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">燃气工作压力范围(动压)Pa</label>
        <input type="text" class="text-input input-length-10" name="gaspre_range" id="gaspre_range" value="<?php echo $info['gaspre_range'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">能效等级</label>
        <input type="text" class="text-input input-length-10" name="energy_level" id="energy_level" value="<?php echo $info['energy_level'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">锅炉长度(mm)</label>
        <input type="text" class="text-input input-length-10" name="length" id="length" value="<?php echo $info['length'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">锅炉宽度(mm)</label>
        <input type="text" class="text-input input-length-10" name="width" id="width" value="<?php echo $info['width'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">高度（含脚和烟囱连接）(mm)</label>
        <input type="text" class="text-input input-length-10" name="height" id="height" value="<?php echo $info['height'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">重量（净）(kg)</label>
        <input type="text" class="text-input input-length-10" name="net_weight" id="net_weight" value="<?php echo $info['net_weight'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">参考供热面积(㎡)</label>
        <input type="text" class="text-input input-length-10" name="refer_heatarea" id="refer_heatarea" value="<?php echo $info['refer_heatarea'];?>"/>
    </p>
    <p>
        <label style="width: 200px;">电源(V/Hz)</label>
        <input type="text" class="text-input input-length-10" name="power_supply" id="power_supply" value="<?php echo $info['power_supply'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">噪音(dB)</label>
        <input type="text" class="text-input input-length-10" name="noise" id="noise" value="<?php echo $info['noise'];?>"/>
        <span class="warn-inline">&nbsp;</span>
        <label style="width: 200px;">最大耗电量(W)</label>
        <input type="text" class="text-input input-length-10" name="electric_power" id="electric_power" value="<?php echo $info['electric_power'];?>"/>
    </p>
<!--    <p style="display: none">-->
<!--        <label style="width: 200px;">价格(元)</label>-->
<!--        <input type="text" class="text-input input-length-10" name="price" id="price" value="--><?php //echo $pInfo['price']?$pInfo['price']:'';?><!--"/>-->
<!--    </p>-->
    <p style="display: none">
        <label style="width: 200px;">出烟口中心高度(mm)</label>
        <input type="text" class="text-input input-length-10" name="smoke_height" id="smoke_height" value="0"/>
        <label style="width: 200px;">压力降(kPa)</label>
        <input type="text" class="text-input input-length-10" name="pressure_drop" id="pressure_drop" value="0"/>
    </p>
    <p style="display: none">
        <label style="width: 200px;">工作压力(MPa)</label>
        <input type="text" class="text-input input-length-10" name="pressure" id="pressure" value="0"/>
        <label style="width: 200px;">燃料类型</label>
        <input type="text" class="text-input input-length-10" name="fueltype" id="fueltype" value="0"/>
        <label style="width: 200px;">燃气消耗量(Nm³/h)</label>
        <input type="text" class="text-input input-length-10" name="gas_consumption" id="gas_consumption" value="0"/>
    </p>
    <p style="display: none">
        <label style="width: 200px;">燃油消耗量(Nm³/h)</label>
        <input type="text" class="text-input input-length-10" name="fuel_consumption" id="fuel_consumption" value="0"/>
        <label style="width: 200px;">烟道口径(mm)</label>
        <input type="text" class="text-input input-length-10" name="flue_caliber" id="flue_caliber" value="0"/>
        <label style="width: 200px;">锅炉运输重量(t)</label>
        <input type="text" class="text-input input-length-10" name="hauled_weight" id="hauled_weight" value="0"/>
    </p>

    <p>
        <label>商品图片</label>
        <input id="img1"  name="img1" type="hidden" value="<?php echo !empty($pInfo)?$pInfo['img']:'';?>"/>
        <input id="file1" class="upfile_btn" type="file" name="file" style="height:24px;"/>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
    </p>
    <p style="padding-left:150px;font-size:14px;" id="val1"><?php echo !empty($pInfo)?$HTTP_PATH.$pInfo['img']:'';?></p>

    <p>
        <label style="width: 200px;">产品详情</label>
        <div style="margin-top:40px;margin-left:10%">
        <span class="warn-inline" >*横向图片宽度建议550，纵向图片宽度建议320，并居中；
            文字格式请设置为普通格式，文字第一行开头空格数为2*</span>
            <textarea style="padding:5px;width:70%;height:70px;" name="desc" cols=200 id="desc" value=""><?php echo HTMLDecode(!empty($pInfo)?$pInfo['desc']:''); ?></textarea>
        </div>
    </p>
    <p>
        <label style="width: 350px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>