<div class="manageHRWJCont_middle_left">
    <ul>
        <a href="project_stage_one_check.php?tag=2&id=<?php echo $id;?>">
            <li <?php if($LEFT_TAB_NVA == 'one') echo 'class="manage_liCheck"'; ?>>
                <?php
                if($projectinfo['level'] >= 1){
                    echo '<img src="images/xingxing.png" alt="">';
                }else{
                    echo '<img src="images/grayxingxing.png" alt="">';
                }
                ?>
            </li>
        </a>
        <a href="<?php echo 'project_stage_two_check.php?tag=2&id='.$id;?>">
            <li <?php if($LEFT_TAB_NVA == 'two') echo 'class="manage_liCheck"'; ?>>
                <?php
                if($projectinfo['level'] >= 2){
                    echo '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">';
                }else{
                    echo '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">';
                }
                ?>
            </li>
        </a>
        <a href="<?php echo 'project_stage_three_check.php?tag=2&id='.$id; ?>">
            <li <?php if($LEFT_TAB_NVA == 'three') echo 'class="manage_liCheck"'; ?>>
                <?php
                if($projectinfo['level'] >= 3){
                    echo '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">';
                }else{
                    echo '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">';
                }
                ?>
            </li>
        </a>
        <a href="<?php echo 'project_stage_four_check.php?tag=2&id='.$id;?>">
            <li <?php if($LEFT_TAB_NVA == 'four') echo 'class="manage_liCheck"'; ?>>
                <?php
                if($projectinfo['level'] >= 4){
                    echo '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">';
                }else{
                    echo '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">';
                }
                ?>
            </li>
        </a>
        <a href="<?php echo 'project_stage_five_check.php?tag=2&id='.$id;?>">
            <li <?php if($LEFT_TAB_NVA == 'five') echo 'class="manage_liCheck"'; ?>>
                <?php
                if($projectinfo['level'] >= 5){
                    echo '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">';
                }else{
                    echo '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">';
                }
                ?>
            </li>
        </a>

        <a href="project_stop_check.php?tag=2&id=<?php echo $id;?>">
            <li <?php if($LEFT_TAB_NVA == 'stop') echo 'class="manage_liCheck"'; ?>>项目终止</li>
        </a>
    </ul>
</div>