<div class="guolumain">
    <a href="../home.php">
        <div class="guolumain_1">当前位置：项目管理 ></a>
    <a href="project_list_select.php"><span> 项目查询 > </a><?php echo $projectinfo['name'];?></span></div>
<div class="clear"></div>
<div class="guolumain_2 manageHRWJTop">
    <div class="manageHRWJ_top">
        <div>
            <ul>
                <li>项目名称：<?php echo $projectinfo['name'];?></li>
                <li>项目类型：<?php $typeinfo = Project_type::getInfoById($projectinfo['type']); echo $typeinfo?$typeinfo['name']:"";?></li>
                <div class="clear"></div>
            </ul>
            <ul>
                <li>报备时间：<?php echo getDateStrS($projectinfo['addtime']);?></li>
                <li>项目状态：
                    <?php
                    if($projectinfo['stop_flag'] == 1 && $projectinfo['status'] == 3){
                        echo $ARRAY_project_level_stop[$projectinfo['level']];
                    }else{
                        echo $ARRAY_project_level[$projectinfo['level']];
                    }
                    ?>
                </li>
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <!--<div class="manageHRWJ_top2"><?php /*echo $projectinfo['reviewopinion']?"驳回原因：".$projectinfo['reviewopinion']:"";*/?>
    </div>-->

				<?php 
                if(isset($THIS_PAGE) && $THIS_PAGE == 'same_check'){
                	$userinfo = User::getInfoById($projectinfo['user']);
                	echo '<span style="position: relative;top: 10px;font-size: 24px;font-family: PingFangSC-Regular;color: #686868;">项目负责人：'.$userinfo['name'].'</span>';
                }
                ?>
</div>
</div>