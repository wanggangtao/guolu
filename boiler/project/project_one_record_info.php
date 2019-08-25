<?php
/**
 * 项目第一阶段修改记录列表 project_one_record.php
 *
 * @version       v0.01
 * @create time   2018/07/01
 * @update time   2018/07/01
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
$record_one = Project_one_record::getInfoById($id);
$project_one_before = Project_one_bak::getInfoById($record_one['before_id']);
$project_one_after = Project_one_bak::getInfoById($record_one['after_id']);
if(!empty($id)) {
    $projectinfo = Project::getInfoById($project_one_after['project_id']);
    if (empty($projectinfo)) {
        echo '非法操作！';
        die();
    } else {
        $userinfo = User::getInfoById($projectinfo['user']);
        if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3 || ($USERINFO['role'] == 4 && $userinfo['department'] == $USERINFO['department']))) {
            echo '没有权限操作！';
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-我的项目-修改查看</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $("body").on('click','.openbigimg',function(){
                var obj = $(this).attr("src");
                $('#bigimgcontent').attr("src",obj);
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area: '600px',
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true,
                    content: $('#bigimgdiv')
                });
            });
        });

    </script>
</head>
<body>
<div class="visit_pop">

    <div class="vist_popCont">
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>项目名称：</span>
            <p class="<?php if($project_one_before['project_name'] != $project_one_after['project_name']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_name']; ?></p>
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>项目地址：</span>
            <p class="<?php if($project_one_before['project_detail'] != $project_one_after['project_detail']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_detail']; ?></p></br>
            <p class="<?php if($project_one_before['project_lat'] != $project_one_after['project_lat']) echo 'programRevise_changeDiv'; ?>">经度：<?php echo $project_one_after['project_lat']; ?></p></br>
            <p class="<?php if($project_one_before['project_long'] != $project_one_after['project_long']) echo 'programRevise_changeDiv'; ?>">纬度：<?php echo $project_one_after['project_long']; ?></p>
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>项目类型：</span>
            <p class="<?php if($project_one_before['project_type'] != $project_one_after['project_type']) echo 'programRevise_changeDiv'; ?>"><?php $typeinfo = Project_type::getInfoById($project_one_after['project_type']); echo $typeinfo?$typeinfo['name']:""; ?></p>
        </div>
        <div class="visit_popDiv">
             <span>甲方单位：</span>
            <p class="<?php if($project_one_before['project_partya'] != $project_one_after['project_partya']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_partya']; ?></p>
        </div>
        <div class="visit_popDiv">
             <span>甲方地址：</span>
            <p class="<?php if($project_one_before['project_partya_address'] != $project_one_after['project_partya_address']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_partya_address']; ?></p>
        </div>
        <div class="visit_popDiv">
            <span>甲方简介：</span>
            <p class="<?php if($project_one_before['project_partya_desc'] != $project_one_after['project_partya_desc']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_partya_desc']; ?></p>
        </div>

        <div class="visit_popDiv3">
            <span>甲方组织架构：</span>

            <div class="managePicture_cont">
                <?php
                if($project_one_after['project_partya_pic']) {
                    $picarr = explode("|", $project_one_after['project_partya_pic']);
                    foreach ($picarr as $pic){
                        ?>
                        <div class="picture_detail">
                            <img  class="openbigimg" src="<?php echo $HTTP_PATH.$pic;?>" alt="">

                        </div>


                        <?php
                    }
                }
                ?>
                <div class="clear"></div>
            </div>

        </div>


        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>联系人：</span>
            <p class="<?php if($project_one_before['project_linkman'] != $project_one_after['project_linkman']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_linkman']; ?></p>
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>电话：</span>
            <p class="<?php if($project_one_before['project_linktel'] != $project_one_after['project_linktel']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_linktel']; ?></p>
        </div>
        <div class="visit_popDiv">
            <img src="images/must_react.png" alt=""> <span>职位：</span>
            <p class="<?php if($project_one_before['project_linkposition'] != $project_one_after['project_linkposition']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_linkposition']; ?></p>
        </div>
        <?php
        //if($project_one_after['project_type'] == 1){
        ?>
            <!--<div class="visit_popDiv">
                <img src="images/must_react.png" alt=""> <span>采暖总面积：</span>
                <p class="<?php /*if($project_one_before['project_boiler_num'] != $project_one_after['project_boiler_num']) echo 'programRevise_changeDiv'; */?>"><?php /*echo $project_one_after['project_boiler_num']; */?></p>
            </div>
            <div class="visit_popDiv">
                <img src="images/must_react.png" alt=""> <span>锅炉总吨位：</span>
                <p class="<?php /*if($project_one_before['project_boiler_tonnage'] != $project_one_after['project_boiler_tonnage']) echo 'programRevise_changeDiv'; */?>"><?php /*echo $project_one_after['project_boiler_tonnage']; */?></p>
            </div>-->
        <?php
        //}else
        if($project_one_after['project_type'] == 2){
        ?>  <div class="visit_popDiv visit_popDiv2">
                <span>原锅炉型号：</span>
                <p class="<?php if($project_one_before['project_xinghao'] != $project_one_after['project_xinghao']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_xinghao']; ?></p>
            </div>
            <div class="visit_popDiv">
                <img src="images/must_react.png" alt=""> <span>壁挂炉总数量：</span>
                <p class="<?php if($project_one_before['project_wallboiler_num'] != $project_one_after['project_wallboiler_num']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_wallboiler_num']; ?></p>
            </div>
        <?php
        }else{?>
        <div class="visit_popDiv visit_popDiv2">
            <span>原锅炉型号：</span>
            <p class="<?php if($project_one_before['project_xinghao'] != $project_one_after['project_xinghao']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_xinghao']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
            <span>原锅炉：</span>
                    <?php
                        $attr1_before = substr($project_one_before['project_history_attr'],0,strrpos($project_one_before['project_history_attr'],"|"));
                        $attr2_before = substr($project_one_before['project_history_attr'],strripos($project_one_before['project_history_attr'],"|")+1);
                        $attr1_after = substr($project_one_after['project_history_attr'],0,strrpos($project_one_after['project_history_attr'],"|"));
                        $attr2_after = substr($project_one_after['project_history_attr'],strripos($project_one_after['project_history_attr'],"|")+1);
                        ?>
            <p class="<?php if($attr1_before != $attr1_after) echo 'programRevise_changeDiv'; ?>"><?php echo $attr1_after;?>kw </p>
            <p class="<?php if($attr1_after != $attr2_after) echo 'programRevise_changeDiv'; ?>"> <?php echo $attr2_after;?>台</p>


        </div>
            <?php
            $burnerListBefore = 0;
            if($record_one['before_id'])
                $burnerListBefore = Project_burner_type_bak::getInfoByPoId($record_one['before_id']);

            $burnerListAfter = 0;
            if($record_one['after_id'])
                $burnerListAfter = Project_burner_type_bak::getInfoByPoId($record_one['after_id']);
            if($burnerListAfter){
                for($i = 0; $i < count($burnerListAfter); $i ++) {
            ?>
                    <div class="visit_popDiv visit_popDiv2">
                        <span>类型<?php echo $i +1; ?>：</span>
                        <p class="<?php if($burnerListBefore[$i]['guolu_tonnage'] != $burnerListAfter[$i]['guolu_tonnage']) echo 'programRevise_changeDiv'; ?>"><?php echo $burnerListAfter[$i]['guolu_tonnage']; ?>  KW  </p>
                        <p class="<?php if($burnerListBefore[$i]['guolu_num'] != $burnerListAfter[$i]['guolu_num']) echo 'programRevise_changeDiv'; ?>"><?php echo $burnerListAfter[$i]['guolu_num']; ?>  台  </p>
                    </div>
            <?php
                }
            }
        }
        ?>
        <div class="visit_popDiv visit_popDiv2">
             <span>拟用锅炉品牌：</span>
            <p class="<?php if($project_one_before['project_brand'] != $project_one_after['project_brand']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_brand']; ?></p>
        </div>

        <div class="visit_popDiv visit_popDiv2">
              <span>建筑类型：</span>
            <p class="<?php if($project_one_before['project_build_type'] != $project_one_after['project_build_type']) echo 'programRevise_changeDiv'; ?>"><?php echo $ARRAY_project_build_type[$project_one_after['project_build_type']]; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
              <span>新建锅炉房：</span>
            <p class="<?php if($project_one_before['project_isnew'] != $project_one_after['project_isnew']) echo 'programRevise_changeDiv'; ?>"><?php if($project_one_after['project_isnew'] == 1) echo "是"; else echo "否";?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
             <span>项目拟开工日期：</span>
            <p class="<?php if($project_one_before['project_pre_buildtime'] != $project_one_after['project_pre_buildtime']) echo 'programRevise_changeDiv'; ?>"><?php echo getDateStrS($project_one_after['project_pre_buildtime']); ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
              <span>竞争品牌：</span>
            <p class="<?php if($project_one_before['project_competitive_brand'] != $project_one_after['project_competitive_brand']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_competitive_brand']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
             <span>竞品情况：</span>
            <p class="<?php if($project_one_before['project_competitive_desc'] != $project_one_after['project_competitive_desc']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_competitive_desc']; ?></p>
        </div>
        <div class="visit_popDiv visit_popDiv2">
              <span>备注：</span>
            <p class="<?php if($project_one_before['project_desc'] != $project_one_after['project_desc']) echo 'programRevise_changeDiv'; ?>"><?php echo $project_one_after['project_desc']; ?></p>
        </div>

    </div>
</div>
<div id="bigimgdiv" style="display: none"><img id="bigimgcontent" src="" width="600px" height="500px"></div>
</body>
</html>