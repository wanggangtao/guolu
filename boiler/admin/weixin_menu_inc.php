<div id="leftmenu">
    <div class="menu1"><a href="weixin_product.php">产品管理</a></div>
    <div class="menu1"><a href="weixin_user_account.php">用户管理</a></div>
    <div class="menuline"></div>
    <div class="menu1"><a href="weixin_community_info.php" <?php if($FLAG_LEFTMENU == 'weixin_info') echo ' class="active"';?>>信息管理</a></div>
    <div class="menu2"><a href="weixin_community_info.php" <?php if($FLAG_LEFTMENU == 'weixin_community_info') echo ' class="active"';?>>地址管理</a></div>

    <div class="menu2"><a href="weixin_customer_info.php" <?php if($FLAG_LEFTMENU == 'weixin_customer_info') echo ' class="active"';?>>客服人员</a></div>
    <div class="menu2"><a href="weixin_employees_info.php" <?php if($FLAG_LEFTMENU == 'weixin_employees_info') echo ' class="active"';?>>服务人员</a></div>
    <div class="menu1"><a href="weixin_repair_parts.php">配件管理</a></div>
    <div class="menu1"><a href="weixin_service_fee.php">上门费管理</a></div>

    <div class="menuline"></div>
    <div class="menu1"><a href="weixin_repair_untreated.php" <?php if($FLAG_LEFTMENU == 'weixin_repair') echo ' class="active"';?>>报修管理</a></div>
    <div class="menu2"><a href="weixin_repair_untreated.php" <?php if($FLAG_LEFTMENU == 'weixin_repair_untreated') echo ' class="active"';?>>待派单</a></div>
    <div class="menu2"><a href="weixin_repair_treating.php" <?php if($FLAG_LEFTMENU == 'weixin_repair_treating') echo ' class="active"';?>>待完工</a></div>
    <div class="menu2"><a href="weixin_repair_treated.php" <?php if($FLAG_LEFTMENU == 'weixin_repair_treated') echo ' class="active"';?>>已完工</a></div>
    <div class="menuline"></div>
    <div class="menu1"><a href="weixin_industry_info.php" <?php if($FLAG_LEFTMENU == 'weixin_industry_info') echo ' class="active"';?>>资讯管理</a></div>
    <div class="menuline"></div>
    <div class="menu1"><a href="weixin_coupon_list.php" <?php if($FLAG_LEFTMENU == '1') echo ' class="active"';?>>优惠券管理</a></div>
    <div class="menu2"><a href="weixin_coupon_list.php" <?php if($FLAG_LEFTMENU == 'weixin_coupon_list') echo ' class="active"';?>>添加优惠券</a></div>
    <div class="menu2"><a href="weixin_coupon_rule_list.php" <?php if($FLAG_LEFTMENU == 'weixin_coupon_rule_list') echo ' class="active"';?>>优惠券使用规则</a></div>
    <div class="menu2"><a href="weixin_user_coupon_list.php" <?php if($FLAG_LEFTMENU == 'weixin_user_coupon_list') echo ' class="active"';?>>优惠券发放列表</a></div>
    <div class="menuline"></div>
    <div class="menu1"><a href="weixin_service_type_info.php" <?php if($FLAG_LEFTMENU == 'weixin_service_type_info') echo ' class="active"';?>>服务类型管理</a></div>
<!--    <div class="menu1"><a href="weixin_service_record.php">机器人聊天管理</a></div>-->
</div>