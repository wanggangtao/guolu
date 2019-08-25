<div id="leftmenu">
    <div class="menu1"><a href="user_list.php">用户管理</a></div>
    <div class="menu2"><a href="user_list.php" <?php if($FLAG_LEFTMENU == 'user_list') echo ' class="active"';?>>用户</a></div><div class="menuline"></div>
    <div class="menu2"><a href="user_department_list.php" <?php if($FLAG_LEFTMENU == 'department_list') echo ' class="active"';?>>部门</a></div><div class="menuline"></div>
    <div class="menu2"><a href="user_role_list.php" <?php if($FLAG_LEFTMENU == 'role_list') echo ' class="active"';?>>角色</a></div><div class="menuline"></div>
</div>