<?php
//通过tag值识别项目模块的选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;
?>
<div class="manageHRWJCont_top">
    <ul>
        <a href="project_stage_turn.php?tag=<?php echo $tag?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "stage") echo 'class="HRWJCont_li"';?>>项目阶段</li></a>
        <?php
        if(!empty($id)){
        ?>
            <a href="project_select_plan.php?tag=<?php echo $tag?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "select_plan") echo 'class="HRWJCont_li"';?>>选型方案</li></a>
            <a href="project_visitlog.php?tag=<?php echo $tag?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "visitlog") echo 'class="HRWJCont_li"';?>>交流记录</li></a>
        <a href="project_pics.php?tag=<?php echo $tag?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "pics") echo 'class="HRWJCont_li"';?>>图库</li></a>
        <a href="project_summarize.php?tag=<?php echo $tag?>&id=<?php echo $id;?>"><li <?php if($TOP_TAB_NVA == "summarize") echo 'class="HRWJCont_li"';?>>项目沉淀</li></a>
        <?php
        }
        ?>
    </ul>
</div>