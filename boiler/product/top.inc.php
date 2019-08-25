<div class="indexTop">
    <div class="indexTop_1">
        <a href="../index.php"><div class="indexTop_logo">
                <img src="images/top_logo.png" alt="">
        </div></a>
        <a href="index.php">
            <div class="indexTop_2 indexTop_checked">
                <img src="images/chapin.png" >
                <img src="images/chapin_check.png">
                <span>产品中心</span>
            </div></a><!--
        <a href="GLxuanxing.html">
            <div class="indexTop_2">
                <img src="images/guolu.png">
                <img src="images/guolu_check.png" >
                <span>锅炉选型</span>
            </div>
        </a>
        <a href="XXhistory.html">
            <div class="indexTop_2">
                <img src="images/xuanx.png">
                <img src="images/xuanx_check.png" >
                <span>选型历史</span>

            </div></a>-->
        <?php $message_notopen_count = Message_info::getPageList(1, 10, 0, $USERId, 0, '', 0); ?>
        <a href="message_unread.php"><img src="images/emil.png" class="indexTop_3"><span class="indexTop_5"><?php if($message_notopen_count > 0)echo '<div class="num">'.$message_notopen_count.'</div>'; ?></span></a>
        <a href="../logout.php"><img src="images/backlogon.png" class="indexTop_4"><span class="indexTop_6"></span></a>
        <a href=""><span class="indexTop_7"><?php echo $USERINFO['name']; ?>，欢迎您！</span></a>
    </div>
</div>