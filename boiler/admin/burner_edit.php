<?php
/**
 * 修改燃烧器  burner_edit.php
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
$info = Burner_attr::getInfoById($id);
if(empty($info))
    die();
$pInfo = Products::getInfoById($info['proid']);
if(empty($pInfo))
    die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#guoluvender').change(function () {
                var guoluvender = $(this).val();
                $.ajax({
                    type        : 'POST',
                    data        : {
                        vender  : guoluvender
                    },
                    dataType :    'json',
                    url :         'burner_do.php?act=getGuoluList',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        $('#guoluid').html();
                        $('#guoluid').html(msg);
                    }
                });
            });
            $('#btn_sumit').click(function(){

                var version = $('#version').val();
                var vender = $('#vender').val();
                var is_lownitrogen = $('#is_lownitrogen').val();
                var power = $('#power').val();
                var boilerpower = $('#boilerpower').val();
                //var price = $('#price').val();
                var price = 0;
                var guoluid = $('#guoluid').val();
                var reg = /^(-?\d+)(\.\d+)?$/;
                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    return false;
                }
                if(vender == ''){
                    layer.tips('厂家不能为空', '#vender');
                    return false;
                }
                if (!(reg.test(vender))) {
                    layer.tips('厂家应为数字', '#vender');
                    return false;
                }
                if(is_lownitrogen == ''){
                    layer.tips('是否低氮不能为空', '#is_lownitrogen');
                    return false;
                }
                if (!(reg.test(is_lownitrogen))) {
                    layer.tips('是否低氮应为数字', '#is_lownitrogen');
                    return false;
                }
                if(power == ''){
                    layer.tips('功率不能为空', '#power');
                    return false;
                }
                if (!(reg.test(power))) {
                    layer.tips('功率应为数字', '#power');
                    return false;
                }
                if(boilerpower == ''){
                    layer.tips('对应锅炉热功率不能为空', '#boilerpower');
                    return false;
                }
                if (!(reg.test(boilerpower))) {
                    layer.tips('对应锅炉热功率应为数字', '#boilerpower');
                    return false;
                }
                /* if(price != ''){
                    if (!(reg.test(price))) {
                        layer.tips('价格应为数字', '#price');
                        return false;
                    }
                }else
                    price = 0; */


                if(guoluid == 0 || guoluid == undefined){
                    layer.tips('对应锅炉不能为空', '#guoluid');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        vender  : vender,
                        is_lownitrogen  : is_lownitrogen,
                        power  : power,
                        boilerpower  : boilerpower,
                        price  : price,
                        guoluid  : guoluid
                    },
                    dataType :    'json',
                    url :         'burner_do.php?act=edit',
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
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label style="width: 130px;">型号</label>
        <input type="text" class="text-input input-length-30" name="version" id="version" value="<?php echo $info['version'];?>"/>
        <label style="width: 100px;">厂家</label>
        <select name="vender" class="select-option" id="vender">
            <?php
            $list = Dict::getListByParentid(5);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
    </p>
    <p>
        <label style="width: 130px;">是否低氮</label>
        <select name="is_lownitrogen" class="select-option" id="is_lownitrogen">
            <?php
            $list = Dict::getListByParentid(6);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $info['is_lownitrogen'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <label style="width: 100px;">功率(KW)</label>
        <input type="text" class="text-input input-length-10" name="power" id="power" value="<?php echo $info['power'];?>"/>
    </p>
    <p>
        <label style="width: 130px;">对应锅炉热功率(KW)</label>
        <input type="text" class="text-input input-length-10" name="boilerpower" id="boilerpower" value="<?php echo $info['boilerpower'];?>"/>
        <!-- <label style="width: 100px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>"/> -->
    </p>
    <p>
        <label style="width: 130px;">对应锅厂家</label>
        <select name="guoluvender" class="select-option" id="guoluvender">
            <?php
            $list = Dict::getListByParentid(1);
            $guoluinfo = Guolu_attr::getInfoById($info['guoluid']);
            if($list)
                foreach($list as $thisValue){
                    $isselect = '';
                    if($thisValue['id'] == $guoluinfo['vender'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['id'].'" '.$isselect.'>'.$thisValue['name'].'</option>';
                }
            ?>
        </select>
        <label style="width: 130px;">对应锅炉型号</label>
        <select name="guoluid" class="select-option" id="guoluid">
            <?php
            $guolulist = Guolu_attr::getList(0, 0, 0, $guoluinfo['vender'], 0, 0, 0);
            if($guolulist)
                foreach($guolulist as $thisValue){
                    $isselect = '';
                    if($thisValue['guolu_id'] == $info['guoluid'])
                        $isselect = 'selected';
                    echo '<option value="'.$thisValue['guolu_id'].'" '.$isselect.'>'.$thisValue['guolu_version'].'</option>';
                }
            ?>
        </select>
    </p>
    <p>
        <label style="width: 350px;">　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>