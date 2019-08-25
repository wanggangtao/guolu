<?php
/**
 * 查看优惠券规则  weixin_coupon_rule_detail.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$params=array();
$id=$_GET['id'];
$servicetype = Weixin_coupon::getList($params);
$rule = Weixin_coupon_register_rule::getInfoById($id);
$item = Weixin_register_rule_item::getInfoByRuleId($id);
$community_list = Weixin_community_item::getCommunityByRuleId($id);

$starttime= date("Y-m-d H:i:s",$rule['starttime']);
$endtime=date('Y-m-d H:i:s',$rule['endtime'])
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
                parent.location.reload();
            });
            var item_array=new Array();


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
            foreach($item as $row){
                $coupon = Weixin_coupon::getInfoById($row['coupon_id']);
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
            if($community_str == -1){
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

        <label><font color="#dc143c">*</font>活动开始时间：</label>
        <label name="starttime" ><?php echo $starttime;?></label>
    </p>
    <p>
        <label><font color="#dc143c">*</font>活动结束时间：</label>
        <label name="stoptime" ><?php echo $endtime; ?></label>
    </p>

    <p>

        <label style="width: 150px;">　　</label>
<!--        <input type="submit" id="btn_sumit" class="btn_submit" value="提 交" />-->
        <input type="reset" id="btn_cacal" class="btn_submit" value="返 回" />
    </p>
</div>
</body>
</html>