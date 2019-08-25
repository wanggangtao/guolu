<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/26
 * Time: 18:08
 */

require_once('admin_init.php');
require_once('admincheck.php');

$params=array();
$id=$_GET['id'];
$rule = Weixin_coupon_dx_rule::getInfoById($id);

$community_list = Weixin_dx_community_item::getCommunityByRuleId($rule['id']);
$coupon_list = Weixin_dx_coupon_item::getCouponByRuleId($rule['id']);
//print_r($coupon_list);

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
    <script type="text/javascript" src="js/upload.js"></script>

    <script type="text/javascript">
        $(function(){

            $("#btn_cacal").click(function () {
                parent.location.href = "weixin_coupon_rule_list.php?dx_type=1";
            });

        });
    </script>
</head>
<body>
<div id="formlist">


    <p>

        <label><font color="#dc143c">*</font>规则名称：</label>
        <label name="name"  style="width: auto"/><?php echo $rule['name']; ?></label>

    </p>
    <p>
        <label style="float: left;"><font color="#dc143c">*</font>已选优惠券：</label>
        <span style="width: 280px;float: left;">
            <?php
            foreach($coupon_list as $row){
                $coupon = Weixin_coupon::getInfoById($row);
                echo '<label style="width: auto;margin-right: 15px;" >'.$coupon['name'].'</label>';
            }
            ?>
        </span>
    </p>

    <p>
        <label style="float: left;"><font color="#dc143c">*</font>已选小区：</label>
        <span style="width: 280px;float: left;">
            <?php
            $community_str = implode(",",$community_list);
            if($community_str === "-1"){
                echo '<label style="width: auto;margin-right: 15px;" >全部小区</label>';

            }else{

                foreach($community_list as $item){
                    if($item == -1) continue;
                    $community = Community::getInfoById($item);
                    if(empty($community)) continue;
                    echo '<label style="width: auto;margin-right: 15px;" >'.$community['name'].'</label>';
                }

            }
            ?>
        </span>
    </p>

    <p>
        <?php
        if($rule['send_type'] == 1){
            $send_type=  "立即发送";
        }else{
            $send_type  =  "定时发送";};
        ?>
        <label><font color="#dc143c">*</font>发送方式：</label>
        <span style="width: 280px;float: left;">
            <label style="width: auto;margin-right: 15px;" ><?php echo $send_type?></label>
        </span>
    </p>
    <p>
        <label><font color="#dc143c">*</font>发送时间：</label>
        <label ><?php echo date('Y-m-d H:i:s',$rule['send_time']); ?></label>
    </p>

    <p>

        <label style="width: 150px;">　　</label>
        <input type="reset" id="btn_cacal" class="btn_submit" value="返 回" />
    </p>
</div>
</body>
</html>