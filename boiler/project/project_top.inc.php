<div class="guolumain">
    <a href="../home.php">
        <div class="guolumain_1">当前位置：项目管理 ></a>
            <a href="project_list_my.php"><span> 我的项目</a><?php if(!empty($projectinfo['name']))echo ' > '.$projectinfo['name'];?></span></div>
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
                <li>报备时间：<?php echo $projectinfo['addtime']?getDateStrS($projectinfo['addtime']):'';?></li>
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

        <?php if($projectinfo['export_flag'] == 1){?><button class="myprogram_button1 manageHRWJ_topButton" id="<?php if($TOP_TAB_NVA == 'stage' || $TOP_TAB_NVA == 'visitlog'){ echo 'export_btn';}?>">导出</button><?php }?>
        <div class="clear"></div>
    </div>
    <div class="manageHRWJ_top2">
        <?php
        if($projectinfo['status'] == 3){
            echo $projectinfo['reviewopinion']?"通过批注：".$projectinfo['reviewopinion']:"";
        }elseif($projectinfo['status'] == 4){
            echo $projectinfo['reviewopinion']?"驳回原因：".$projectinfo['reviewopinion']:"";
        }
        ?>
    </div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        $('#export_btn').click(function(){
            var index = layer.load(0, {shade: false});
            $.ajax({
                type	: 'POST',
                data	: {
                    topnva : '<?php echo $TOP_TAB_NVA;?>',
                    leftnva : '<?php echo $LEFT_TAB_NVA;?>',
                    id      : '<?php echo $id;?>'
                },
                dataType : 'json',
                url : 'project_export_do.php',
                success : function(data){
                    layer.close(index);
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            location.href='<?php echo $HTTP_PATH;?>'+msg;
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });
    });
</script>