<?php
/**
 * 修改分集水器  diversity_water_add.php
 *
 * @create time   2018/5/31
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('web_init.php');
require_once('usercheck.php');

if(!isset($_GET['id']))
    die();

$id = safeCheck($_GET['id']);
$info = Diversity_water_attr::getInfoById($id);
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
            $('#btn_sumit').click(function(){
                var version = $('#version').val();
                var vender = $('#vender').val();
                var dgmm = $('#dgmm').val();
                var head_height = $('#head_height').val();
                var blowdown_pipe = $('#blowdown_pipe').val();
                var price = $('#price').val();
                var reg = /(^[1-9]\d*$)/;
                if(version == ''){
                    layer.tips('型号不能为空', '#version');
                    return false;
                }
                if(vender == ''){
                    layer.tips('厂家不能为空', '#vender');
                    return false;
                }
                if (!(reg.test(vender))) {
                    layer.tips('厂家应为正整数', '#vender');
                    return false;
                }
                if(dgmm == ''){
                    layer.tips('筒体直径不能为空', '#dgmm');
                    return false;
                }
                if (!(reg.test(dgmm))) {
                    layer.tips('筒体直径应为正整数', '#dgmm');
                    return false;
                }
                if(head_height == ''){
                    layer.tips('封头高度不能为空', '#head_height');
                    return false;
                }
                if (!(reg.test(head_height))) {
                    layer.tips('封头高度应为正整数', '#head_height');
                    return false;
                }
                if(blowdown_pipe == ''){
                    layer.tips('排污管规格不能为空', '#blowdown_pipe');
                    return false;
                }
                if (!(reg.test(blowdown_pipe))) {
                    layer.tips('排污管规格应为正整数', '#blowdown_pipe');
                    return false;
                }
                if(price == ''){
                    layer.tips('价格不能为空', '#price');
                    return false;
                }
                if (!(reg.test(price))) {
                    layer.tips('价格应为正整数', '#price');
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : <?php echo $id;?>,
                        pid  : <?php echo $info['proid'];?>,
                        version : version,
                        vender  : vender,
                        dgmm  : dgmm,
                        head_height  : head_height,
                        blowdown_pipe  : blowdown_pipe,
                        price  : price
                    },
                    dataType :    'json',
                    url :         'diversity_water_do.php?act=edit',
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
        <input type="text" class="text-input input-length-20" name="version" id="version" value="<?php echo $info['version'];?>" readonly/>
        <label style="width: 100px;">厂家</label>
        <?php
        $infos = Dict::getInfoById($info['vender']);
        ?>
        <input type="text" class="text-input input-length-20" name="vender" id="vender" value="<?php echo $infos['name'];?>" readonly/>
    </p>
    <p>
        <label style="width: 130px;">筒体直径(mm)</label>
        <input type="text" class="text-input input-length-10" name="dgmm" id="dgmm" value="<?php echo $info['dgmm'];?>" readonly/>
        <label style="width: 210px;">封头高度(mm)</label>
        <input type="text" class="text-input input-length-10" name="head_height" id="head_height" value="<?php echo $info['head_height'];?>" readonly/>
    </p>
    <p>
        <label style="width: 130px;">排污管规格(mm)</label>
        <input type="text" class="text-input input-length-10" name="blowdown_pipe" id="blowdown_pipe" value="<?php echo $info['blowdown_pipe'];?>" readonly/>
        <!-- <label style="width: 210px;">价格(元)</label>
        <input type="text" class="text-input input-length-10" name="price" id="price" value="<?php echo $pInfo['price']?$pInfo['price']:'';?>" readonly/> -->
    </p>
</div>
</body>
</html>