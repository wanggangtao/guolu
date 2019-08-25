<div class="manageHRWJCont_middle_left">
    <ul>
        <a href="project_stage_one.php?tag=1&id=<?php echo $id;?>">
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
        <a href="<?php if(!empty($id)) echo 'project_stage_two.php?tag=1&id='.$id; else echo 'javascript:viod(0);';?>">
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
        <a href="<?php if(!empty($id)) echo 'project_stage_three.php?tag=1&id='.$id; else echo 'javascript:viod(0);';?>">
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
        <a href="<?php if(!empty($id)) echo 'project_stage_four.php?tag=1&id='.$id; else echo 'javascript:viod(0);';?>">
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
        <a href="<?php if(!empty($id)) echo 'project_stage_five.php?tag=1&id='.$id; else echo 'javascript:viod(0);';?>">
            <li <?php if($LEFT_TAB_NVA == 'five') echo 'class="manage_liCheck"'; ?>>
                <?php
                if($projectinfo['level'] == 5){
                    echo '<img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt=""><img src="images/xingxing.png" alt="">';
                }else{
                    echo '<img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt=""><img src="images/grayxingxing.png" alt="">';
                }
                ?>
            </li>
        </a>

        <a href="<?php if(!empty($id)) echo 'project_stop.php?tag=1&id='.$id; else echo 'javascript:viod(0);';?>">
            <li <?php if($LEFT_TAB_NVA == 'stop') echo 'class="manage_liCheck"'; ?>>项目终止</li>
        </a>
    </ul>
</div>