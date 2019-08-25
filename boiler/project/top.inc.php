<div class="indexTop">
    <div class="indexTop_1">
        <a href="../home.php"><div class="indexTop_logo">
                <img src="images/top_logo.png" alt="">
        </div>
        </a>
        <?php
            if(in_array($USERINFO['role'], array(1,2,3))){
                $topcss1 = "";
                if($TOP_FLAG == "myproject")
                    $topcss1 = "indexTop_checked";

                echo '
                  <a href="project_list_my.php?tag=1">
                    <div class="indexTop_2 '.$topcss1.'">
                        <img src="images/myprogram.png" >
                        <img src="images/myprogram_check.png">
                        <span>我的项目</span>
                    </div>
                </a>
                ';
            }
            if(in_array($USERINFO['role'], array(2,3))){
                $topcss2 = "";
                if($TOP_FLAG == "projectreview")
                    $topcss2 = "indexTop_checked";
                echo '
                      <a href="project_list_review.php?tag=2">
                        <div class="indexTop_2 '.$topcss2.'">
                            <img src="images/programSP.png">
                            <img src="images/programSP_check.png" >
                            <span>项目审批</span>
                        </div>
                    </a>
                    ';
            }
        if(in_array($USERINFO['role'], array(2,3,4))){
            $topcss3 = "";
            if($TOP_FLAG == "projectselect")
                $topcss3 = "indexTop_checked";
            echo '
                    <a href="project_list_select.php?tag=3">
                        <div class="indexTop_2 '.$topcss3.'">
                            <img src="images/programFind.png">
                            <img src="images/programFind_check.png" >
                            <span>项目查询</span>
            
                        </div>
                      </a>
                    ';
        }
        $message_notopen_count = Message_info::getPageList(1, 10, 0, $USERId, 0, '', message_info::UNREAD);
        ?>
        <a href="message_unread.php"><img src="images/emil.png" class="indexTop_3"><span class="indexTop_5"><?php if($message_notopen_count > 0)echo '<div class="num">'.$message_notopen_count.'</div>'; ?></span></a>

        <a href="../logout.php"><img src="images/backlogon.png" class="indexTop_4"><span class="indexTop_6"></span></a>
        <a href=""><span class="indexTop_7"><?php echo $USERINFO['name']; ?>，欢迎您！</span></a>
    </div>
</div>