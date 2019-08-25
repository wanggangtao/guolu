<?php
/**
 * 锅炉  guolu_info.php
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
    <script src="ckeditor/ckeditor.js"></script><script type="text/javascript">
        $(function() {
            var ckeditor = CKEDITOR.replace('desc', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserFlashUploadUrl: 'ckeditor_upload.php?type=flash',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });

        });
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label style="width: 140px;">型号</label>
        <input type="text" class="text-input input-length-50" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
    </p>
    <p>
        <label style="width: 140px;">厂家</label>
        <?php
            $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-10" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
        <label style="width: 40px;">类型</label>
        <?php
            $infos = Dict::getInfoById($info['type']);
        ?>
        <input type="text" class="text-input input-length-10" name="type" id="type" value="<?php echo $infos['name'];?>" readonly/>
        <label style="width: 60px;">是否冷凝</label>
        <?php
            $infos = Dict::getInfoById($info['is_condensate']);
        ?>
        <input type="text" class="text-input input-length-10" name="is_condensate" id="is_condensate" value="<?php echo $infos['name'];?>" readonly/>
        <label style="width: 60px;">是否低氮</label>
        <?php
            $infos = Dict::getInfoById($info['is_lownitrogen']);
        ?>
        <input type="text" class="text-input input-length-10" name="is_lownitrogen" id="is_lownitrogen" value="<?php echo $infos['name'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">额定热输入(KW)</label>
        <input type="text" class="text-input input-length-10" name="ratedpower" id="ratedpower" value="<?php echo $info['ratedpower'];?>" readonly/>
        <label style="width: 200px;">额定功率下最小燃气流量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="min_flow" id="min_flow" value="<?php echo $info['min_flow'];?>" readonly/>
        <label style="width: 200px;">额定功率下最大燃气流量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="max_flow" id="max_flow" value="<?php echo $info['max_flow'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">最大负荷80℃~60℃热输出(kw)</label>
        <input type="text" class="text-input input-length-10" name="heatout_60" id="heatout_60" value="<?php echo $info['heatout_60'];?>" readonly/>
        <label style="width: 200px;">最大负荷50℃~30℃热输出(kw)</label>
        <input type="text" class="text-input input-length-10" name="heatout_30" id="heatout_30" value="<?php echo $info['heatout_30'];?>" readonly/>
        <label style="width: 200px;">热输入调节范围(kw)</label>
        <input type="text" class="text-input input-length-10" name="heatout_range" id="heatout_range" value="<?php echo $info['heatout_range'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">最大负荷80℃~60℃热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="heateffi_80" id="heateffi_80" value="<?php echo $info['heateffi_80'];?>" readonly/>
        <label style="width: 200px;">最大负荷50℃~30℃热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="heateffi_50" id="heateffi_50" value="<?php echo $info['heateffi_50'];?>" readonly/>
        <label style="width: 200px;">30%负荷50℃~30℃热效率(%)</label>
        <input type="text" class="text-input input-length-10" name="heateffi_30" id="heateffi_30" value="<?php echo $info['heateffi_30'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">进水温度(℃)</label>
        <input type="text" class="text-input input-length-10" name="inwater_t" id="inwater_t" value="<?php echo $info['inwater_t'];?>" readonly/>
        <label style="width: 200px;">出水温度(℃)</label>
        <input type="text" class="text-input input-length-10" name="outwater_t" id="outwater_t" value="<?php echo $info['outwater_t'];?>" readonly/>
        <label style="width: 200px;">最低/最高系统水压(bar)</label>
        <input type="text" class="text-input input-length-10" name="syswater_pre" id="syswater_pre" value="<?php echo $info['syswater_pre'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">供热水能力(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="heat_capacity" id="heat_capacity" value="<?php echo $info['heat_capacity'];?>" readonly/>
        <label style="width: 200px;">最大水流量(m³/h)</label>
        <input type="text" class="text-input input-length-10" name="hot_flow" id="hot_flow" value="<?php echo $info['hot_flow'];?>" readonly/>
        <label style="width: 200px;">烟气温度（最大负荷80℃~60℃）(℃)</label>
        <input type="text" class="text-input input-length-10" name="fluegas_80" id="fluegas_80" value="<?php echo $info['fluegas_80'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">烟气温度（最大负荷50℃~30℃）(℃)</label>
        <input type="text" class="text-input input-length-10" name="fluegas_50" id="fluegas_50" value="<?php echo $info['fluegas_50'];?>" readonly/>
        <label style="width: 200px;">CO排放(mg/m³)</label>
        <input type="text" class="text-input input-length-10" name="emission_co" id="emission_co" value="<?php echo $info['emission_co'];?>" readonly/>
        <label style="width: 200px;">NOx排放(mg/m³)</label>
        <input type="text" class="text-input input-length-10" name="emission_nox" id="emission_nox" value="<?php echo $info['emission_nox'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">最大冷凝水排量(L/h)</label>
        <input type="text" class="text-input input-length-10" name="condensed_max" id="condensed_max" value="<?php echo $info['condensed_max'];?>" readonly/>
        <label style="width: 200px;">冷凝水PH值</label>
        <input type="text" class="text-input input-length-10" name="condensed_ph" id="condensed_ph" value="<?php echo $info['condensed_ph'];?>" readonly/>
        <label style="width: 200px;">烟道接口φ(mm)</label>
        <input type="text" class="text-input input-length-10" name="flue_interface" id="flue_interface" value="<?php echo $info['flue_interface'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">燃气接口</label>
        <input type="text" class="text-input input-length-10" name="gas_interface" id="gas_interface" value="<?php echo $info['gas_interface'];?>" readonly/>
        <label style="width: 200px;">进出水接口(DN)</label>
        <input type="text" class="text-input input-length-10" name="iowater_interface" id="iowater_interface" value="<?php echo $info['iowater_interface'];?>" readonly/>
        <label style="width: 200px;">锅炉水容量(L)</label>
        <input type="text" class="text-input input-length-10" name="water" id="water" value="<?php echo $info['water'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">燃气类型</label>
        <input type="text" class="text-input input-length-10" name="gas_type" id="gas_type" value="<?php echo $info['gas_type'];?>" readonly/>
        <label style="width: 200px;">额定燃气压力(动压)Pa</label>
        <input type="text" class="text-input input-length-10" name="gas_press" id="gas_press" value="<?php echo $info['gas_press'];?>" readonly/>
        <label style="width: 200px;">燃气工作压力范围(动压)Pa</label>
        <input type="text" class="text-input input-length-10" name="gaspre_range" id="gaspre_range" value="<?php echo $info['gaspre_range'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">能效等级</label>
        <input type="text" class="text-input input-length-10" name="energy_level" id="energy_level" value="<?php echo $info['energy_level'];?>" readonly/>
        <label style="width: 200px;">锅炉长度(mm)</label>
        <input type="text" class="text-input input-length-10" name="length" id="length" value="<?php echo $info['length'];?>" readonly/>
        <label style="width: 200px;">锅炉宽度(mm)</label>
        <input type="text" class="text-input input-length-10" name="width" id="width" value="<?php echo $info['width'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">高度（含脚和烟囱连接）(mm)</label>
        <input type="text" class="text-input input-length-10" name="height" id="height" value="<?php echo $info['height'];?>" readonly/>
        <label style="width: 200px;">重量（净）(kg)</label>
        <input type="text" class="text-input input-length-10" name="net_weight" id="net_weight" value="<?php echo $info['net_weight'];?>" readonly/>
        <label style="width: 200px;">参考供热面积(㎡)</label>
        <input type="text" class="text-input input-length-10" name="refer_heatarea" id="refer_heatarea" value="<?php echo $info['refer_heatarea'];?>" readonly/>
    </p>
    <p>
        <label style="width: 200px;">电源(V/Hz)</label>
        <input type="text" class="text-input input-length-10" name="power_supply" id="power_supply" value="<?php echo $info['power_supply'];?>" readonly/>
        <label style="width: 200px;">噪音(dB)</label>
        <input type="text" class="text-input input-length-10" name="noise" id="noise" value="<?php echo $info['noise'];?>" readonly/>
        <label style="width: 200px;">最大耗电量(W)</label>
        <input type="text" class="text-input input-length-10" name="electric_power" id="electric_power" value="<?php echo $info['electric_power'];?>" readonly/>
    </p>
<!--    <p style="display: none">-->
<!--        <label style="width: 200px;">价格(元)</label>-->
<!--        <input type="text" class="text-input input-length-10" name="price" id="price" value="--><?php //echo $pInfo['price']?$pInfo['price']:'';?><!--" readonly/>-->
<!--    </p>-->
    <p style="display: none">
        <label style="width: 200px;">出烟口中心高度(mm)</label>
        <input type="text" class="text-input input-length-10" name="smoke_height" id="smoke_height" value="0" readonly/>
        <label style="width: 200px;">压力降(kPa)</label>
        <input type="text" class="text-input input-length-10" name="pressure_drop" id="pressure_drop" value="0" readonly/>
    </p>
    <p style="display: none">
        <label style="width: 200px;">工作压力(MPa)</label>
        <input type="text" class="text-input input-length-10" name="pressure" id="pressure" value="0" readonly/>
        <label style="width: 200px;">燃料类型</label>
        <input type="text" class="text-input input-length-10" name="fueltype" id="fueltype" value="0" readonly/>
        <label style="width: 200px;">燃气消耗量(Nm³/h)</label>
        <input type="text" class="text-input input-length-10" name="gas_consumption" id="gas_consumption" value="0" readonly/>
    </p>
    <p style="display: none">
        <label style="width: 200px;">燃油消耗量(Nm³/h)</label>
        <input type="text" class="text-input input-length-10" name="fuel_consumption" id="fuel_consumption" value="0" readonly/>
        <label style="width: 200px;">烟道口径(mm)</label>
        <input type="text" class="text-input input-length-10" name="flue_caliber" id="flue_caliber" value="0" readonly/>
        <label style="width: 200px;">锅炉运输重量(t)</label>
        <input type="text" class="text-input input-length-10" name="hauled_weight" id="hauled_weight" value="0" readonly/>
    </p>
    <p>
        <label>商品图片</label>
        <img src="<?php echo !empty($pInfo)?$HTTP_PATH.$pInfo['img']:'';?>" height="100px">
    </p>
    <p>
        <label style="width: 200px;">产品详情</label>
        <div style="margin-top:40px;margin-left:10%">
            <textarea style="padding:5px;width:70%;height:70px;" name="desc" cols=200 id="desc" value="" readonly><?php echo HTMLDecode(!empty($pInfo)?$pInfo['desc']:''); ?></textarea>
        </div>
    </p>

    <div style="height: 30px"></div>



</div>
</body>
</html>