<?php
/**
 * 项目第一阶段 project_stage_one_check.php
 *
 * @version       v0.01
 * @create time   2018/06/28
 * @update time   2018/06/28
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_TAB_NVA = "stage";
$LEFT_TAB_NVA = 'one';
$TOP_FLAG = "projectselect";

$id = isset($_GET['id'])?safeCheck($_GET['id']):'0';
//通过tag值识别选型方案页面
$tag=isset($_GET['tag'])?safeCheck($_GET['tag']):0;

$projectinfo = Project::getInfoById($id);
$project_one = Project_one::getInfoByProjectId($id);
if (empty($projectinfo) || empty($project_one)) {
    echo '非法操作！';
    die();
} else {
    $userinfo = User::getInfoById($projectinfo['user']);
    if (!($projectinfo['user'] == $USERId || $userinfo['parent'] == $USERId || $USERINFO['role'] == 3 || ($USERINFO['role'] == 4 && $userinfo['department'] == $USERINFO['department']))) {
        echo '没有权限操作！';
        die();
    }
}
$samecount1 = Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], '', '', '', '', '', $projectinfo['notsame_id']);
$samecount2 = Project_one::getPageSameList(1, 10, 0, '', $project_one['project_detail'], '', '', '', '', $projectinfo['notsame_id']);
/* $samecount3 = Project_one::getPageSameList(1, 10, 0, '', '', $project_one['project_partya'], '', '', '', $projectinfo['notsame_id']);
$samecount4 = Project_one::getPageSameList(1, 10, 0, '', '', '', $project_one['project_partya_address'], '', '', $projectinfo['notsame_id']);
$samecount5 = Project_one::getPageSameList(1, 10, 0, '', '', '', '', $project_one['project_linkman'], '', $projectinfo['notsame_id']);
$samecount6 = Project_one::getPageSameList(1, 10, 0, '', '', '', '', '', $project_one['project_linktel'], $projectinfo['notsame_id']); */
$samecount3 = 1;
$samecount4 = 1;
$samecount5 = 1;
$samecount6 = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目管理-项目查询一级</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/myprogram.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#record_one').click(function(){
                location.href = 'project_one_record_show.php?id='+'<?php echo $id;?>&tag=3';
            });
            $('#record_same').click(function(){
                location.href = 'project_same_show.php?top_flag=1&id='+'<?php echo $id;?>&tag=3';
            });
            $("body").on('click','.openbigimg',function(){
                var obj = $(this).attr("src");
                $('#bigimgcontent').attr("src",obj);
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area: '800px',
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true,
                    content: $('#bigimgdiv')
                });
            });
        });

    </script>
</head>
<body class="body_1">
    <?php include('top.inc.php');?>
        <?php include('project_show_top.inc.php');?>
    <div class="manageHRWJCont">
        <?php include('project_show_tab.inc.php');?>
        <div class="manageHRWJCont_middle">
            <?php include('project_show_left.inc.php');?>
            <div class="manageHRWJCont_middle_middle">

                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>项目名称</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_name'] ?>
                            <?php if( $samecount1 - 1 > 0) echo '<span class="similar_program">注：已有'.($samecount1 - 1).'个相似项目</span>';?></p>
                        <?php if($USERINFO['role'] == 3 || $USERINFO['role'] == 2){ echo '<button id="record_same" style="right: 250px">相似项目</button>';} ?>
                        <button id="record_one">修改记录</button></div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>项目地址</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_detail']; ?><?php if( $samecount2 - 1 > 0) echo '<span class="similar_program">注：已有'.($samecount2 - 1).'个相似项目</span>';?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine middleDiv_one_Two">
                    <div class="middleDiv_one_div"><p>纬度</p><?php echo $project_one['project_lat']; ?></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_one_div"><p>经度</p> <?php echo $project_one['project_long']; ?></div>

                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>项目类型</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php $typeinfo = Project_type::getInfoById($projectinfo['type']); echo $typeinfo?$typeinfo['name']:"";?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>联系人</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_linkman']; ?><?php if( $samecount5 - 1 > 0) echo '<span class="similar_program">注：已有'.($samecount5 - 1).'个相似项目</span>';?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>电话</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_linktel']; ?><?php if( $samecount6 - 1 > 0) echo '<span class="similar_program">注：已有'.($samecount6 - 1).'个相似项目</span>';?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>职位</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_linkposition']; ?></p>
                    </div>
                    <div class="clear"></div>
                </div>

                <?php
                //if($projectinfo['type'] == 1){
                ?>
                    <!--<div class="middleDiv_nine">
                        <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>采暖总面积</p></div>
                        <div class="middleDiv_nine_right">
                            <p><?php /*echo $project_one['project_boiler_num']; */?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="middleDiv_nine">
                        <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>锅炉总吨位</p></div>
                        <div class="middleDiv_nine_right">
                            <p><?php /*echo $project_one['project_boiler_tonnage']; */?></p>
                        </div>
                        <div class="clear"></div>
                    </div>-->
                <?php
                //}else
                if ($projectinfo['type'] == 2){
                ?>
                    <div class="middleDiv_nine">
                        <div class="middleDiv_nine_left"><p>原锅炉型号</p></div>
                        <div class="middleDiv_nine_right">
                            <p><?php echo $project_one['project_xinghao']; ?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="middleDiv_nine">
                        <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>壁挂炉总数量</p></div>
                        <div class="middleDiv_nine_right">
                            <p><?php echo $project_one['project_wallboiler_num']; ?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php
                }else{?>
                    <div class="middleDiv_nine">
                        <div class="middleDiv_nine_left"> <p>原锅炉型号</p></div>
                        <div class="middleDiv_nine_right">
                            <p><?php echo $project_one['project_xinghao']; ?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="middleDiv_nine">
                        <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>原锅炉</p></div>
                        <div class="middleDiv_nine_right">
                            <?php if (!empty($project_one['project_history_attr'])){?>
                                <p><?php echo substr($project_one['project_history_attr'],0,strrpos($project_one['project_history_attr'],"|"));?>kw
                                    <?php echo substr($project_one['project_history_attr'],strripos($project_one['project_history_attr'],"|")+1);?>台


                                </p>
                            <?php }?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php
                    $burnerlist = Project_burner_type::getInfoByPoId($id);
                    if($burnerlist){
                    $i = 0;
                    foreach ($burnerlist as $thisburner) {
                        $i++;
                ?>
                        <div class="middleDiv_nine">
                            <div class="middleDiv_nine_left"><img src="images/must_react.png" alt="">   <p>类型<?php echo $i;?></p></div>
                            <div class="middleDiv_nine_right">
                                <p>锅炉功率：<?php echo $thisburner['guolu_tonnage'] ?>  KW</p> <p>锅炉数量：<?php echo $thisburner['guolu_num'] ?>  台  </p>
                            </div>
                            <div class="clear"></div>
                        </div>
                <?php
                        }
                    }
                }
                ?>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left">   <p>甲方单位</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_partya']; ?><?php if( $samecount3 - 1 > 0) echo '<span class="similar_program">注：已有'.($samecount3 - 1).'个相似项目</span>';?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left">  <p>甲方地址</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_partya_address']; ?><?php if( $samecount4 - 1 > 0) echo '<span class="similar_program">注：已有'.($samecount4 - 1).'个相似项目</span>';?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left">   <p>甲方简介</p></div>


                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_partya_desc']; ?></p>
                    </div>
                    <div class="clear"></div>

                </div>

                <div class="managePicture_cont">
                    <div class="middleDiv_nine">
                        <div class="  middleDiv_nine_left2"> <p> 甲方组织架构</p></div>
                        <div class="middleDiv_nine_right">
                            <?php
                            if($project_one['project_partya_pic']) {
                                $picarr = explode("|", $project_one['project_partya_pic']);
                                foreach ($picarr as $pic){
                                    ?>
                                    <div class="picture_detail">
                                        <img  class="openbigimg" src="<?php echo $HTTP_PATH.$pic;?>" alt="">
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>拟用锅炉品牌</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_brand'] ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>建筑类型</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $ARRAY_project_build_type[$project_one['project_build_type']] ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="middleDiv_nine_left2"> <p>新建锅炉房</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php if($project_one['project_isnew'] == 1) echo "是"; else echo "否";?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>项目拟开工日期</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo getDateStrS($project_one['project_pre_buildtime']); ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>竞争品牌</p></div>
                    <div class="middleDiv_nine_right">
                        <p><?php echo $project_one['project_competitive_brand']; ?></p>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>竞品情况</p></div>
                    <div class="middleDiv_nine_right">
                        <div><?php echo $project_one['project_competitive_desc']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="middleDiv_nine">
                    <div class="  middleDiv_nine_left2"> <p>备注</p></div>
                    <div class="middleDiv_nine_right">
                        <div><?php echo $project_one['project_desc']; ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div id="bigimgdiv" style="display: none"><img id="bigimgcontent" src="" width="800px" height="600px"></div>
</body>
</html>