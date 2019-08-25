<div id="topmenu">
    <ul>
        <li><a href="index.php" <?php if($FLAG_TOPNAV=='index') echo ' class="active"';?> title="系统首页">系统首页</a></li>
        <li><a href="knowledge_list.php" <?php if($FLAG_TOPNAV == 'knowledge') echo 'class="active"';?> title="产品中心">知识库管理</a></li>
        <li><a href="guolu_list.php" <?php if($FLAG_TOPNAV == 'products') echo 'class="active"';?> title="产品中心">产品中心</a></li>
        <li><a href="company_list.php" <?php if($FLAG_TOPNAV == 'seletion') echo ' class="active"';?> title="选型方案">选型方案</a></li>
        <li><a href="projectcase_type_list.php" <?php if($FLAG_TOPNAV == 'common') echo ' class="active"';?> title="通用设置">通用设置</a></li>
        <li><a href="user_list.php" <?php if($FLAG_TOPNAV == 'user') echo ' class="active"';?> title="用户管理">用户管理</a></li>
        <li><a href="content_indexpic.php" <?php if($FLAG_TOPNAV == 'webcontent') echo ' class="active"';?> title="前端内容管理">前端内容管理</a></li>
        <li><a href="weixin_product.php" <?php if($FLAG_TOPNAV == 'weixincontent') echo ' class="active"';?> title="公众号管理">公众号管理</a></li>
        <li><a href="admingroup.php" <?php if($FLAG_TOPNAV == 'system') echo ' class="active"';?> title="系统设置">系统设置</a></li>
    </ul>
</div>