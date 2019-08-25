<?php
/**
 * 锅炉处理  guolu_do.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'add'://添加
        $version   =  safeCheck($_POST['version'],0);
        $vender   =  safeCheck($_POST['vender']);
        $type   =  safeCheck($_POST['type']);
        $is_condensate   =  safeCheck($_POST['is_condensate']);
        $is_lownitrogen   =  safeCheck($_POST['is_lownitrogen']);
        $ratedpower   =  $_POST['ratedpower']?safeCheck($_POST['ratedpower']):0;
        $inwater_t   =  safeCheck($_POST['inwater_t']);
        $outwater_t   =  safeCheck($_POST['outwater_t']);
        $pressure   =  $_POST['pressure']?safeCheck($_POST['pressure']):0;
        $fueltype   =  safeCheck($_POST['fueltype'],0);
        $gas_consumption   =  $_POST['gas_consumption']?safeCheck($_POST['gas_consumption']):0;
        $fuel_consumption   =  $_POST['fuel_consumption']?safeCheck($_POST['fuel_consumption']):0;
        $flue_caliber   =  $_POST['flue_caliber']?safeCheck($_POST['flue_caliber']):0;
        $hauled_weight   =  $_POST['hauled_weight']?safeCheck($_POST['hauled_weight']):0;
        $hot_flow   =  $_POST['hot_flow']?safeCheck($_POST['hot_flow']):0;
        $pressure_drop   =  $_POST['pressure_drop']?safeCheck($_POST['pressure_drop']):0;
        $length   =  $_POST['length']?safeCheck($_POST['length']):0;
        $width   =  $_POST['width']?safeCheck($_POST['width']):0;
        $height   =  $_POST['height']?safeCheck($_POST['height']):0;
        $smoke_height   =  $_POST['smoke_height']?safeCheck($_POST['smoke_height']):0;
        $water  =  $_POST['water']?safeCheck($_POST['water']):0;
//        $price   =  $_POST['price']?safeCheck($_POST['price']):0;
        $img   =  safeCheck($_POST['img'], 0);
        $desc   =   $_POST['desc'];

//        $wxdesc = $_POST['wxdesc'];

        $min_flow   =  $_POST['min_flow']?safeCheck($_POST['min_flow']):0;
        $max_flow   =  $_POST['max_flow']?safeCheck($_POST['max_flow']):0;
        $heatout_60   =  $_POST['heatout_60']?safeCheck($_POST['heatout_60']):0;
        $heatout_30   =  $_POST['heatout_30']?safeCheck($_POST['heatout_30']):0;
        $heatout_range   =  safeCheck($_POST['heatout_range'], 0);
        $heateffi_80   =  $_POST['heateffi_80']?safeCheck($_POST['heateffi_80']):0;
        $heateffi_50   =  $_POST['heateffi_50']?safeCheck($_POST['heateffi_50']):0;
        $heateffi_30   =  $_POST['heateffi_30']?safeCheck($_POST['heateffi_30']):0;
        $syswater_pre   =  safeCheck($_POST['syswater_pre'], 0);
        $heat_capacity   =  $_POST['heat_capacity']?safeCheck($_POST['heat_capacity']):0;
        $fluegas_80   =  safeCheck($_POST['fluegas_80'], 0);
        $fluegas_50   =  safeCheck($_POST['fluegas_50'], 0);
        $emission_co   =  safeCheck($_POST['emission_co'], 0);
        $emission_nox   =  safeCheck($_POST['emission_nox'], 0);
        $condensed_max   =  $_POST['condensed_max']?safeCheck($_POST['condensed_max']):0;
        $condensed_ph   =  $_POST['condensed_ph']?safeCheck($_POST['condensed_ph']):0;
        $flue_interface   =  $_POST['flue_interface']?safeCheck($_POST['flue_interface']):0;
        $gas_interface   =  safeCheck($_POST['gas_interface'], 0);
        $iowater_interface   =  safeCheck($_POST['iowater_interface'], 0);
        $gas_type   =  safeCheck($_POST['gas_type'], 0);
        $gas_press   =  $_POST['gas_press']?safeCheck($_POST['gas_press']):0;
        $gaspre_range   =  safeCheck($_POST['gaspre_range'], 0);
        $energy_level   =  safeCheck($_POST['energy_level'], 0);
        $net_weight   =  $_POST['net_weight']?safeCheck($_POST['net_weight']):0;
        $refer_heatarea   =  $_POST['refer_heatarea']?safeCheck($_POST['refer_heatarea']):0;
        $power_supply   =  safeCheck($_POST['power_supply'], 0);
        $noise   =  safeCheck($_POST['noise'], 0);
        $electric_power   =  $_POST['electric_power']?safeCheck($_POST['electric_power']):0;

//        $detail_video  =  safeCheck($_POST['detail_video'], 0);
//        $detail_imgs  =  safeCheck($_POST['detail_imgs'], 0);

        try {

            $attrsPro = array(
                "name" => '',
                "img" => $img,
                "brief" => '',
                "desc" => $desc,
//                "wxdesc" => $wxdesc,
//                "price" => $price,
                "addtime" => time(),
                "lastupdate" => 0,
                "weight" => 0,
                "modelid" => 1,
//                "detail_video" => $detail_video,
//                "detail_imgs" => $detail_imgs,
            );
            $rs = Products::add($attrsPro);
            if($rs > 0){
                $attrs= array(
                    "version" => $version,
                    "vender" => $vender,
                    "type" => $type,
                    "is_condensate" => $is_condensate,
                    "is_lownitrogen" => $is_lownitrogen,
                    "ratedpower" => $ratedpower,
                    "inwater_t" => $inwater_t,
                    "outwater_t" => $outwater_t,
                    "pressure" => $pressure,
                    "fueltype" => $fueltype,
                    "gas_consumption" => $gas_consumption,
                    "fuel_consumption" => $fuel_consumption,
                    "flue_caliber" => $flue_caliber,
                    "hauled_weight" => $hauled_weight,
                    "hot_flow" => $hot_flow,
                    "pressure_drop" => $pressure_drop,
                    "length" => $length,
                    "width" => $width,
                    "height" => $height,
                    "smoke_height" => $smoke_height,
                    "water" => $water,
                    "proid" => $rs,
                    "min_flow"=>$min_flow,
                    "max_flow"=>$max_flow,
                    "heatout_60"=>$heatout_60,
                    "heatout_30"=>$heatout_30,
                    "heatout_range"=>$heatout_range,
                    "heateffi_80"=>$heateffi_80,
                    "heateffi_50"=>$heateffi_50,
                    "heateffi_30"=>$heateffi_30,
                    "syswater_pre"=>$syswater_pre,
                    "heat_capacity"=>$heat_capacity,
                    "fluegas_80"=>$fluegas_80,
                    "fluegas_50"=>$fluegas_50,
                    "emission_co"=>$emission_co,
                    "emission_nox"=>$emission_nox,
                    "condensed_max"=>$condensed_max,
                    "condensed_ph"=>$condensed_ph,
                    "flue_interface"=>$flue_interface,
                    "gas_interface"=>$gas_interface,
                    "iowater_interface"=>$iowater_interface,
                    "gas_type"=>$gas_type,
                    "gas_press"=>$gas_press,
                    "gaspre_range"=>$gaspre_range,
                    "energy_level"=>$energy_level,
                    "net_weight"=>$net_weight,
                    "refer_heatarea"=>$refer_heatarea,
                    "power_supply"=>$power_supply,
                    "noise"=>$noise,
                    "electric_power"=>$electric_power
                );
                Guolu_attr::add($attrs);
                echo action_msg('添加成功', 1);
            }else
                echo action_msg('添加失败', 101);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;


    case 'addsmall'://添加
        $version   =  safeCheck($_POST['version'],0);
        $vender   =  safeCheck($_POST['vender']);

        $heat_temperature   =  $_POST['heat_temperature']?safeCheck($_POST['heat_temperature'],0):'';
        $live_temperature   =  $_POST['live_temperature']?safeCheck($_POST['live_temperature'],0):'';
        $power  =  $_POST['power']?safeCheck($_POST['power']):0;

        $efficiency_level   =  $_POST['efficiency_level']?safeCheck($_POST['efficiency_level'],0):'';
        $size   =  $_POST['size']?safeCheck($_POST['size'],0):'';
        $weight  =  $_POST['weight']?safeCheck($_POST['weight']):0;

        $protection_level   =  $_POST['protection_level']?safeCheck($_POST['protection_level'],0):'';
        $thermal_efficiency  =  $_POST['thermal_efficiency']?safeCheck($_POST['thermal_efficiency'],0):'';

        $img   =  safeCheck($_POST['img'], 0);
        $wxdesc = $_POST['wxdesc'];
        $detail_video  =  safeCheck($_POST['detail_video'], 0);
        $detail_imgs  =  safeCheck($_POST['detail_imgs'], 0);

        try {

            $attrsPro = array(
                "name" => '',
                "img" => $img,
                "brief" => '',
                "wxdesc" => $wxdesc,
                "addtime" => time(),
                "lastupdate" => 0,
                "weight" => 0,
                "modelid" => 1,
                "detail_video" => $detail_video,
                "detail_imgs" => $detail_imgs,
            );
            $rs = Products::add($attrsPro);
            if($rs > 0){
                $attrs= array(
                    "version" => $version,
                    "vender" => $vender,
                    "heat_temperature" => $heat_temperature,
                    "live_temperature" => $live_temperature,
                    "efficiency_level" => $efficiency_level,
                    "size" => $size,
                    "weight" => $weight,
                    "protection_level" => $protection_level,
                    "thermal_efficiency"=>$thermal_efficiency,
                    "proid" => $rs,
                    "power"=>$power
                );
                Smallguolu::add($attrs);
                echo action_msg('添加成功', 1);
            }else
                echo action_msg('添加失败', 101);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'edit'://修改
        $id   =  safeCheck($_POST['id']);
        $pid   =  safeCheck($_POST['pid']);
        $version   =  safeCheck($_POST['version'],0);
        $vender   =  safeCheck($_POST['vender']);
        $type   =  safeCheck($_POST['type']);
        $is_condensate   =  safeCheck($_POST['is_condensate']);
        $is_lownitrogen   =  safeCheck($_POST['is_lownitrogen']);
        $ratedpower   =  $_POST['ratedpower']?safeCheck($_POST['ratedpower']):0;
        $inwater_t   =  safeCheck($_POST['inwater_t']);
        $outwater_t   =  safeCheck($_POST['outwater_t']);
        $pressure   =  $_POST['pressure']?safeCheck($_POST['pressure']):0;
        $fueltype   =  safeCheck($_POST['fueltype'],0);
        $gas_consumption   =  $_POST['gas_consumption']?safeCheck($_POST['gas_consumption']):0;
        $fuel_consumption   =  $_POST['fuel_consumption']?safeCheck($_POST['fuel_consumption']):0;
        $flue_caliber   =  $_POST['flue_caliber']?safeCheck($_POST['flue_caliber']):0;
        $hauled_weight   =  $_POST['hauled_weight']?safeCheck($_POST['hauled_weight']):0;
        $hot_flow   =  $_POST['hot_flow']?safeCheck($_POST['hot_flow']):0;
        $pressure_drop   =  $_POST['pressure_drop']?safeCheck($_POST['pressure_drop']):0;
        $length   =  $_POST['length']?safeCheck($_POST['length']):0;
        $width   =  $_POST['width']?safeCheck($_POST['width']):0;
        $height   =  $_POST['height']?safeCheck($_POST['height']):0;
        $smoke_height   =  $_POST['smoke_height']?safeCheck($_POST['smoke_height']):0;
        $water  =  $_POST['water']?safeCheck($_POST['water']):0;
//        $price   =  $_POST['price']?safeCheck($_POST['price']):0;
        $img   =  safeCheck($_POST['img'], 0);
        $desc   =   $_POST['desc'];

        $min_flow   =  $_POST['min_flow']?safeCheck($_POST['min_flow']):0;
        $max_flow   =  $_POST['max_flow']?safeCheck($_POST['max_flow']):0;
        $heatout_60   =  $_POST['heatout_60']?safeCheck($_POST['heatout_60']):0;
        $heatout_30   =  $_POST['heatout_30']?safeCheck($_POST['heatout_30']):0;
        $heatout_range   =  safeCheck($_POST['heatout_range'], 0);
        $heateffi_80   =  $_POST['heateffi_80']?safeCheck($_POST['heateffi_80']):0;
        $heateffi_50   =  $_POST['heateffi_50']?safeCheck($_POST['heateffi_50']):0;
        $heateffi_30   =  $_POST['heateffi_30']?safeCheck($_POST['heateffi_30']):0;
        $syswater_pre   =  safeCheck($_POST['syswater_pre'], 0);
        $heat_capacity   =  $_POST['heat_capacity']?safeCheck($_POST['heat_capacity']):0;
        $fluegas_80   =  safeCheck($_POST['fluegas_80'], 0);
        $fluegas_50   =  safeCheck($_POST['fluegas_50'], 0);
        $emission_co   =  safeCheck($_POST['emission_co'], 0);
        $emission_nox   =  safeCheck($_POST['emission_nox'], 0);
        $condensed_max   =  $_POST['condensed_max']?safeCheck($_POST['condensed_max']):0;
        $condensed_ph   =  $_POST['condensed_ph']?safeCheck($_POST['condensed_ph']):0;
        $flue_interface   =  $_POST['flue_interface']?safeCheck($_POST['flue_interface']):0;
        $gas_interface   =  safeCheck($_POST['gas_interface'], 0);
        $iowater_interface   =  safeCheck($_POST['iowater_interface'], 0);
        $gas_type   =  safeCheck($_POST['gas_type'], 0);
        $gas_press   =  $_POST['gas_press']?safeCheck($_POST['gas_press']):0;
        $gaspre_range   =  safeCheck($_POST['gaspre_range'], 0);
        $energy_level   =  safeCheck($_POST['energy_level'], 0);
        $net_weight   =  $_POST['net_weight']?safeCheck($_POST['net_weight']):0;
        $refer_heatarea   =  $_POST['refer_heatarea']?safeCheck($_POST['refer_heatarea']):0;
        $power_supply   =  safeCheck($_POST['power_supply'], 0);
        $noise   =  safeCheck($_POST['noise'], 0);
        $electric_power   =  $_POST['electric_power']?safeCheck($_POST['electric_power']):0;

//        $wxdesc = $_POST['wxdesc'];
//        $detail_video  =  safeCheck($_POST['detail_video'], 0);
//        $detail_imgs  =  safeCheck($_POST['detail_imgs'], 0);

        $attrs= array(
            "version" => $version,
            "vender" => $vender,
            "type" => $type,
            "is_condensate" => $is_condensate,
            "is_lownitrogen" => $is_lownitrogen,
            "ratedpower" => $ratedpower,
            "inwater_t" => $inwater_t,
            "outwater_t" => $outwater_t,
            "pressure" => $pressure,
            "fueltype" => $fueltype,
            "gas_consumption" => $gas_consumption,
            "fuel_consumption" => $fuel_consumption,
            "flue_caliber" => $flue_caliber,
            "hauled_weight" => $hauled_weight,
            "hot_flow" => $hot_flow,
            "pressure_drop" => $pressure_drop,
            "length" => $length,
            "width" => $width,
            "height" => $height,
            "smoke_height" => $smoke_height,
            "water" => $water,
            "min_flow"=>$min_flow,
            "max_flow"=>$max_flow,
            "heatout_60"=>$heatout_60,
            "heatout_30"=>$heatout_30,
            "heatout_range"=>$heatout_range,
            "heateffi_80"=>$heateffi_80,
            "heateffi_50"=>$heateffi_50,
            "heateffi_30"=>$heateffi_30,
            "syswater_pre"=>$syswater_pre,
            "heat_capacity"=>$heat_capacity,
            "fluegas_80"=>$fluegas_80,
            "fluegas_50"=>$fluegas_50,
            "emission_co"=>$emission_co,
            "emission_nox"=>$emission_nox,
            "condensed_max"=>$condensed_max,
            "condensed_ph"=>$condensed_ph,
            "flue_interface"=>$flue_interface,
            "gas_interface"=>$gas_interface,
            "iowater_interface"=>$iowater_interface,
            "gas_type"=>$gas_type,
            "gas_press"=>$gas_press,
            "gaspre_range"=>$gaspre_range,
            "energy_level"=>$energy_level,
            "net_weight"=>$net_weight,
            "refer_heatarea"=>$refer_heatarea,
            "power_supply"=>$power_supply,
            "noise"=>$noise,
            "electric_power"=>$electric_power
        );
        $rs = Guolu_attr::update($id, $attrs);

        if($rs >= 0){
            $attrsPro = array(
//                "price" => $price,
                "img" => $img,
                "desc" => $desc,
//                "wxdesc" => $wxdesc,
                "lastupdate" => time(),
//                "detail_video" => $detail_video,
//                "detail_imgs" => $detail_imgs,
            );
            $rs = Products::update($pid, $attrsPro);
            echo action_msg('修改成功', 1);
        }else
            echo action_msg('修改失败', 101);

        break;

    case 'editsmall'://修改
        $id   =  safeCheck($_POST['id']);
        $pid   =  safeCheck($_POST['pid']);
        $version   =  safeCheck($_POST['version'],0);
        $vender   =  safeCheck($_POST['vender']);

        $heat_temperature   =  $_POST['heat_temperature']?safeCheck($_POST['heat_temperature'],0):'';
        $live_temperature   =  $_POST['live_temperature']?safeCheck($_POST['live_temperature'],0):'';
        $power  =  $_POST['power']?safeCheck($_POST['power']):0;

        $efficiency_level   =  $_POST['efficiency_level']?safeCheck($_POST['efficiency_level'],0):'';
        $size   =  $_POST['size']?safeCheck($_POST['size'],0):'';
        $weight  =  $_POST['weight']?safeCheck($_POST['weight']):0;

        $protection_level   =  $_POST['protection_level']?safeCheck($_POST['protection_level'],0):'';
        $thermal_efficiency  =  $_POST['thermal_efficiency']?safeCheck($_POST['thermal_efficiency'],0):'';

        $img   =  safeCheck($_POST['img'], 0);
//        $desc   =   $_POST['desc'];
        $wxdesc = $_POST['wxdesc'];
        $detail_video  =  safeCheck($_POST['detail_video'], 0);
        $detail_imgs  =  safeCheck($_POST['detail_imgs'], 0);
        $attrsPro = array(
            "version" => $version,
            "vender" => $vender,
            "heat_temperature" => $heat_temperature,
            "live_temperature" => $live_temperature,
            "efficiency_level" => $efficiency_level,
            "size" => $size,
            "weight" => $weight,
            "protection_level" => $protection_level,
            "thermal_efficiency"=>$thermal_efficiency,
            "power"=>$power
        );
        $rs = Smallguolu::update($id,$attrsPro);
        if($rs >= 0){
            $attrs= array(
                "img" => $img,
//                "desc" => $desc,
                "wxdesc" => $wxdesc,
                "lastupdate" => time(),
                "detail_video" => $detail_video,
                "detail_imgs" => $detail_imgs,
            );
            Products::update($pid,$attrs);
            echo action_msg('修改成功', 1);
        }else
            echo action_msg('修改失败', 101);


        break;



    case 'del'://删除
        $id = safeCheck($_POST['id']);
        $pid = safeCheck($_POST['pid']);

        $rs = Guolu_attr::del($id);
        if($rs){
            Products::del($pid);
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);

        break;

    case 'delsmall'://删除壁挂
        $id = safeCheck($_POST['id']);
        $pid = safeCheck($_POST['pid']);

        $rs = Smallguolu::del($id);
        if($rs){
            Products::del($pid);
            echo action_msg("删除成功", 1);
        }else
            echo action_msg("删除失败",101);

        break;



}
?>