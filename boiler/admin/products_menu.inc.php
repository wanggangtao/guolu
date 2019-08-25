<div id="leftmenu">
    <div class="menu1"><a href="guolu_list.php">产品管理</a></div>
    <div class="menu2"><a href="guolu_list.php" <?php if($FLAG_LEFTMENU == 'guolu_list') echo ' class="active"';?>>锅炉</a></div><div class="menuline"></div>
    <div class="menu2"><a href="burner_list.php" <?php if($FLAG_LEFTMENU == 'burner_list') echo ' class="active"';?>>辅机</a></div><div class="menuline"></div>
    <div class="menu2"><a href="water_box_list.php" <?php if($FLAG_LEFTMENU == 'waterbox_list') echo ' class="active"';?>>管件</a></div><div class="menuline"></div>
    <div class="menu1"><a href="#">属性管理</a></div>
    <?php
    $catrows      = Category::getPageList(0, 0, 1, 0);
    if($catrows){
        foreach ($catrows as $catrow){
            $flagname = 'cat_dict'.$catrow['id'];
            echo '<div class="menu2"><a';
            if($FLAG_LEFTMENU == $flagname) echo ' class="active"';
            echo ' href="dict_list.php?cat='.$catrow['id'].'">'.$catrow['name'].'</a></div><div class="menuline"></div>';
        }
    }

    ?>
</div>