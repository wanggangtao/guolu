<?php
//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
?>
<div class="manageHRWJCont_top">
    <ul>
        <a href="project_stage_turn_check.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "stage") echo 'class="HRWJCont_li"';?>>项目阶段</li></a>
        <a href="project_select_plan.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "select_plan") echo 'class="HRWJCont_li"';?>>选型方案</li></a>
        <a href="project_visitlog_check.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "visitlog") echo 'class="HRWJCont_li"';?>>交流记录</li></a>
        <a href="project_pics_check.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "pics") echo 'class="HRWJCont_li"';?>>图库</li></a>
        <a href="project_summarize_check.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "summarize") echo 'class="HRWJCont_li"';?>>项目沉淀</li></a>
        <a href="project_check_setting.php?tag=<?php echo $tag;?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "setting") echo 'class="HRWJCont_li"';?>>设置</li></a>
    </ul>
</div>