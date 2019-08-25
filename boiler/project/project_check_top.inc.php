<div class="guolumain">
    <a href="../home.php">
        <div class="guolumain_1">当前位置：项目管理 ></a>
    <a href="project_list_review.php"><span> 项目审批 > </a><?php echo $projectinfo['name'];?></span></div>
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
        <?php
        if($TOP_TAB_NVA == "stage" && $LEFT_TAB_NVA == "one" && $projectinfo['one_status'] == 2){
            echo '<button class="myprogram_button1 manageHRWJ_topButton" id="project_review_btn">阶段审批</button>';
        }
        if($TOP_TAB_NVA == "stage" && $LEFT_TAB_NVA == "two" && $projectinfo['two_status'] == 2){
            echo '<button class="myprogram_button1 manageHRWJ_topButton" id="project_review_btn">阶段审批</button>';
        }
        if($TOP_TAB_NVA == "stage" && $LEFT_TAB_NVA == "three" && $projectinfo['three_status'] == 2){
            echo '<button class="myprogram_button1 manageHRWJ_topButton" id="project_review_btn">阶段审批</button>';
        }
        if($TOP_TAB_NVA == "stage" && $LEFT_TAB_NVA == "four" && $projectinfo['four_status'] == 2){
            echo '<button class="myprogram_button1 manageHRWJ_topButton" id="project_review_btn">阶段审批</button>';
        }
        if($TOP_TAB_NVA == "stage" && $LEFT_TAB_NVA == "stop" && $projectinfo['stop_flag'] == 1){
            echo '<button class="myprogram_button1 manageHRWJ_topButton" id="project_review_btn">阶段审批</button>';
        }
         ?>
        <div class="clear"></div>
    </div>
    <!--<div class="manageHRWJ_top2"><?php /*echo $projectinfo['reviewopinion']?"驳回原因：".$projectinfo['reviewopinion']:"";*/?>
    </div>-->

</div>
</div>

<script type="text/javascript">
    $(function(){
        $('#project_review_btn').click(function(){
            $.ajax({
                type        : 'POST',
                data        : {
                    id : <?php echo $id;?>,
                    postion : '<?php echo $LEFT_TAB_NVA; ?>'
                },
                dataType :    'json',
                url :         'project_review_do.php?act=project_review_check',
                success :     function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            layer.open({
                                type: 2,
                                title: '阶段审批',
                                shadeClose: true,
                                shade: 0.3,
                                area: ['510px', '400px'],
                                content: 'project_review.php?id='+<?php echo $id;?>+'&postion='+'<?php echo $LEFT_TAB_NVA;?>'
                            });
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });
    });
</script>