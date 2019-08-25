<div id="leftmenu">
    <div class="menu1"><a href="knowledge_list.php">知识库管理</a></div>
    <?php
    $sessionAuth = explode('|', $ADMINAUTH);
    if(in_array('7001', $sessionAuth)){
        echo '<div class="menu2"><a';
        if($FLAG_LEFTMENU == 'knowledge_list') echo ' class="active"';
        echo ' href="knowledge_list.php">知识库列表</a></div><div class="menuline"></div>';
    }
    if(in_array('7002', $sessionAuth)){
        echo '<div class="menu2"><a';
        if($FLAG_LEFTMENU == 'rule_list') echo ' class="active"';
        echo ' href="rule_list.php">规则列表</a></div><div class="menuline"></div>';
    }

    if(in_array('7002', $sessionAuth)){
        echo '<div class="menu2"><a';
        if($FLAG_LEFTMENU == 'knowledge_category_list') echo ' class="active"';
        echo ' href="knowledge_category_list.php">知识分类</a></div><div class="menuline"></div>';
    }

    ?>
    <div class="menu1"><a href="service_problem_list.php">智能客服管理</a></div>
    <?php
    $sessionAuth = explode('|', $ADMINAUTH);
    if(in_array('7001', $sessionAuth)){
        echo '<div class="menu2"><a';
        if($FLAG_LEFTMENU == 'service_problem_list') echo ' class="active"';
        echo ' href="service_problem_list.php">知识库列表</a></div><div class="menuline"></div>';
    }
    if(in_array('7002', $sessionAuth)){
        echo '<div class="menu2"><a';
        if($FLAG_LEFTMENU == 'services_label_list') echo ' class="active"';
        echo ' href="services_label_list.php">规则列表</a></div><div class="menuline"></div>';
    }

    if(in_array('7002', $sessionAuth)){
        echo '<div class="menu2"><a';
        if($FLAG_LEFTMENU == 'service_category_list') echo ' class="active"';
        echo ' href="service_category_list.php">知识分类</a></div><div class="menuline"></div>';
    }

    ?>

</div>