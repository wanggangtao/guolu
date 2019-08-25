<?php
/**
 * 选型 selection.php
 *
 * @version       v0.01
 * @create time   2018/07/18
 * @update time   2018/07/18
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";
$id = isset($_GET['id'])?safeCheck($_GET['id']):0;
$pro_id=0;
if($id){
    $pro_id=Selection_history::getInfoById($id)['project_id'];
}
$project_id = isset($_GET['project_id'])?safeCheck($_GET['project_id']):$pro_id;
$pro_name="";
if($project_id){
    $pro_name=Project::getInfoById($project_id)['name'];
}
//选型入口
//$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;

$heating=null;
$hot_Water=null;
$info=null;
$hot_Water_Count=0;
$heating_Count=0;
$all_display_qu=0;
$all_display_hw=0;
$hot_Water_All=null;
$hot_Water_All_Count=0;

if($id) {
    $info = Selection_history::getInfoById($id);
    if ($info['application'] == 1) {
        $hot_Water = Selection_hotwater_attr::getInfoByHistoryId($info['id'],0);
        $hot_Water_Count=Selection_hotwater_attr::getMaxParamId($info['id']);
//        print_r($hot_Water);

    }else if ($info['application'] == 2) {
        $hot_Water = Selection_hotwater_attr::getInfoByHistoryId($info['id'],0);
        $hot_Water_Count=Selection_hotwater_attr::getMaxParamId($info['id']);
        $heating = Selection_heating_attr::getInfoByHistoryId($info['id']);
        $heating_Count=count($heating);
//        print_r($hot_Water);

    } else {
        $heating = Selection_heating_attr::getInfoByHistoryId($info['id']);
        $heating_Count=count($heating);
    }
//    print_r($info);
    if($info['application']==2){
        $all_display_hw=1;
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>锅炉选型</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main_two.css">
    <link rel="stylesheet" href="css/newlayui.css">
    <link rel="stylesheet" href="layui/css/layui.css">
<!--    <link rel="stylesheet" href="css/progress.css">-->
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <style>
        .GLXXmain_3{
            float:left
        }
    </style>
    <style>
        html,body {
            margin: 0;
            padding: 0;
            font-family: "Microsoft YaHei";
        }
        .wrap-dialog {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            font-size: 16px;
            text-align: center;
            background-color: rgba(0, 0, 0, .4);
            z-index: 999;
        }
        .dialog {
            position: relative;
            margin: 15% auto;
            width: 300px;
            background-color: #FFFFFF;
        }
        .dialog .dialog-header {
            height: 20px;
            padding: 10px;
            background-color: #04A6FE;
        }
        .dialog .dialog-body {
            height: 30px;
            padding: 20px;
        }
        .dialog .dialog-footer {
            padding: 8px;
            background-color: whitesmoke;
        }
        .btn {
            width: 70px;
            padding: 2px;
        }
        .hide {
            display: none;
        }
        .ml50 {
            margin-left: 50px;
        }
    </style>
</head>
<body class="body_2">
<?php include('top.inc.php');?>

<div class="manageHRWJCont_middle_left" style="margin-top: 30px">
    <ul>
        <a href="selection.php?project_id=<?php echo $project_id;?>"><li  class="manage_liCheck" >智能选型</li></a>
        <a href="selection_manual_new.php?project_id=<?php echo $project_id;?>"><li>手动选型</li></a>
        <a href="selection_change.php?project_id=<?php echo $project_id;?>"><li>更换锅炉</li></a>
    </ul>
</div>


<div class="manageHRWJCont_middle_middle">
    <div id="step" style="margin-top: 30px">
        <div class="step-list">
            <div class="step-num">
                <?php if(!empty($id)){
                    ?>
                    <a href="selection.php?id=<?php echo $id;?>"><div class="num-bg">1</div></a>
                    <?php  }else{ ?>
                    <a href="selection.php"><div class="num-bg">1</div></a>
                    <?php }?>
                <a href="selection.php?id=<?php echo $id;?>"><div class="num-bg">1</div></a>
            </div>
            <span class="step-name">选型</span>
        </div>

        <?php if($id){
        $history_info=Selection_history::getInfoById($id);
        if($history_info['status']==Selection_history::HISTORY_Pool){?>
            <div class="step-line"></div>
            <div class="step-list">
                <div class="step-num">
                    <?php if($id){
                        $history_info=Selection_history::getInfoById($id);
                        if($history_info['status']==Selection_history::HISTORY_Pool){
                            ?>
                            <a href="selection_plan_one_update.php?id=<?php echo $id;?>"> <div class="num-bg">2</div></a>
                            <?php
                        }else {
                            ?>
                            <div class="nums">2</div>
                            <?php
                        }
                    }else{?>
                        <div class="nums">2</div>
                        <?php
                    }?>
                </div>
                <span class="step-name">报价</span>
            </div>
            <div class="step-line"></div>
            <div class="step-list">
                <div class="step-num">
                    <?php if($id){
                        $history_info=Selection_history::getInfoById($id);
                        $front_plan_id=Selection_plan_front::getInfoByHistoryId($history_info['id'])['id'];
                        if($history_info['status']==Selection_history::HISTORY_Pool){?>
                            <a href="selection_plan_two.php?id=<?php echo $id;?>&&front_plan_id=<?php echo $front_plan_id;?>">  <div class="num-bg">3</div></a>
                            <?php
                        }else {
                            ?>
                            <div class="nums">3</div>
                            <?php
                        }
                    }else{?>
                        <div class="nums">3</div>
                        <?php
                    }?>
                </div>
                <span class="step-name">方案</span>
            </div>
            <?php
        }else{?>
            <div class="step-line"></div>
            <div class="step-list">
                <div class="step-nums">
                    <div class="nums">2</div>
                </div>
                <span class="step-name">报价</span>
            </div>
            <div class="step-lines"></div>
            <div class="step-list">
                <div class="step-nums">
                    <div class="nums">3</div>
                </div>
                <span class="step-name">方案</span>
            </div>

        <?php }}else{ ?>
            <div class="step-line"></div>
            <div class="step-list">
                <div class="step-nums">
                    <div class="nums">2</div>
                </div>
                <span class="step-name">报价</span>
            </div>
            <div class="step-lines"></div>
            <div class="step-list">
                <div class="step-nums">
                    <div class="nums">3</div>
                </div>
                <span class="step-name">方案</span>
            </div>
        <?php }?>

    </div>

    <div class="GLXXmain" style="margin-top: 70px">

        <div class="GLXXmain_1">客户名称</div>
        <div class="GLXXmain_2">
            <input type="text" class="GLXXmain_3" id="customer" value="<?php if($id) echo $info['customer']; else echo $pro_name;?>"><button class="GLXXmain_4" id="resetting">重置</button>
        </div>
        <div class="GLXXmain_1">锅炉房预留位置</div>
        <div class="GLXXmain_2">
            <select type="text" class="GLXXmain_3" id="guolu_position" style="width: 344px">
                <?php
                foreach ($ARRAY_selection_guoluf_position as $key => $val){
                    if($id){
                        if($key==$info['guolu_position']){
                            echo '<option value="'.$key.'" selected>'.$val.'</option>';
                        }else{
                            echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                    }else{
                        echo '<option value="'.$key.'">'.$val.'</option>';
                    }
                }
                ?>
            </select>
            <?php
            if($id and $info['guolu_position']==1){
                ?>
                <div id="underground1" style="display: block;">
                    <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;">地下</div>
                    <input type="text" class="GLXXmain_12" id="underground_unm" value="<?php if($id) echo $info['underground_unm'];?>">
                    <div class="GLXXmain_14" style="color: #686868;">层</div>
                </div>
            <?php }?>
            <div id="underground" style="display: none;">
                <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;">地下</div>
                <input type="text" class="GLXXmain_12" id="underground_unm" value="">
                <div class="GLXXmain_14" style="color: #686868;">层</div>
            </div>
        </div>
        <div class="GLXXmain_1">预计锅炉房高度</div>
        <div class="GLXXmain_2">
            <input type="number" class="GLXXmain_3" id="guolu_height" value="<?php if($id) echo $info['guolu_height'];?>"><div class="GLXXmain_15">m</div>
        </div>
        <div class="GLXXmain_1">锅炉是否冷凝</div>
        <div class="GLXXmain_2">
            <?php if(($id and $info['is_condensate']==15) or empty($id)){?>
                <input type="radio" class="GLXXmain_5" name="is_condensate" value="15" checked><span class="GLXXmain_6">冷凝</span>
            <?php }else{?>
                <input type="radio" class="GLXXmain_5" name="is_condensate" value="15" ><span class="GLXXmain_6">冷凝</span>
            <?php }?>
            <?php if($id and $info['is_condensate']==16){?>
                <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_condensate" value="16" checked><span class="GLXXmain_6">不冷凝</span>
            <?php }else{?>
                <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_condensate" value="16"><span class="GLXXmain_6">不冷凝</span>
            <?php }?>
        </div>
        <div class="GLXXmain_1">锅炉是否低氮</div>
        <div class="GLXXmain_2">
            <?php if(($id and $info['is_lownitrogen']==17) or empty($id)){?>
                <input type="radio" class="GLXXmain_5" name="is_lownitrogen" value="17" checked><span class="GLXXmain_6">低氮30mg</span>
            <?php }else{?>
                <input type="radio" class="GLXXmain_5" name="is_lownitrogen" value="17" ><span class="GLXXmain_6">低氮30mg</span>
            <?php }?>
            <?php if($id and  $info['is_lownitrogen']==19){?>
                <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="19" checked><span class="GLXXmain_6">不低氮</span>
            <?php }else{?>
                <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="19"><span class="GLXXmain_6">不低氮</span>
            <?php }?>
            <!--            <input type="radio" class="GLXXmain_5" name="is_lownitrogen" value="17" checked><span class="GLXXmain_6">低氮30mg</span>-->
            <!--<input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="18"><span class="GLXXmain_6">低氮80mg</span>-->

        </div>
        <div class="GLXXmain_1">锅炉数量</div>
        <div class="GLXXmain_2">
            <input type="number" class="GLXXmain_3" id="guolu_num" value="<?php if($id) echo $info['guolu_num']; ?>"><div class="GLXXmain_15">台</div>
        </div>
        <div class="GLXXmain_1">锅炉用途</div>
        <div class="GLXXmain_2">
            <?php
            $str_0="GLXXmain_7";
            $str_1="GLXXmain_7";
            $str_2="GLXXmain_7";
            if($id and $info['application']==1  ){
                $str_1="GLXXmain_7 GLXXmain_check";

            }
            else if($id and $info['application']==2) {
                $str_2 = "GLXXmain_7 GLXXmain_check";
            }else
                $str_0 = "GLXXmain_7 GLXXmain_check";

            ?>
            <div id="NQ" data-value="0" class="<?php echo $str_0; ?>">采暖</div>

            <div id="rs" data-value="1" class="<?php echo $str_1; ?>">热水</div>
            <div id="wandnq" data-value="2" class="<?php echo $str_2; ?>">采暖和热水</div>
        </div>
        <!--暖气-->
        <?php if((empty($id) or $heating))  {?>
            <?php
            $str_qn="block";
            if($info['application']==2){
                $str_qn="none";
            }
            ?>
            <div id="nuanqi" class="GLXXmain_8" style="display: <?php echo $str_qn?>">
                <div id="guolutype1" style="display: block">
                    <div class="GLXXmain_1">锅炉形式</div>
                    <div class="GLXXmain_11">
                        <?php
                        foreach ($ARRAY_selection_application['0']['type'] as $key => $val){
                            if($id){
                                if($key==$info['heating_type'])
                                    echo '<div class="GLXXmain_10 GLXXmain_check" data-type="nuanqi" data-value="'.$key.'">'.$val.'</div>';
                                else
                                    echo '<div class="GLXXmain_10" data-type="nuanqi" data-value="'.$key.'">'.$val.'</div>';
                            }else{
                                echo '<div class="GLXXmain_10" data-type="nuanqi" data-value="'.$key.'">'.$val.'</div>';
                            }


                        }
                        ?>
                        <!--清除浮动-->
                        <div class="clear"></div>
                    </div>
                </div>
                <?php if(empty($id) or $heating )  {
                    if(empty($id)) {
                        $heating_Count=1;

                    }
                    for($i=0;$i<$heating_Count;$i++){
                        ?>
                        <div class="insertion">
                            <img src="images/fgx_ls.png" class="GLXXmain_9">
                            <div class="GLXXmain_1">建筑类别</div>
                            <div class="GLXXmain_2">
                                <select type="text" class="GLXXmain_3 heating_build_type" style="width: 344px">
                                    <?php
                                    $buildtypelist = Selection_build::getListByParentid(1);
                                    if($buildtypelist){
                                        foreach ($buildtypelist as $thistype){
                                            if($id){
                                                if($thistype['id']==$heating[$i]['build_type'])
                                                    echo '<option value="'.$thistype['id'].'" selected>'.$thistype['name'].'</option>';
                                                else
                                                    echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                            }else{
                                                echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                            }

                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="GLXXmain_1">采暖楼层</div>
                            <div class="GLXXmain_2">
                                <input type="number" class="GLXXmain_12 heating_floor_low" value="<?php if($id and $heating[$i]) echo $heating[$i]['floor_low']?>">
                                <div class="GLXXmain_13"></div>
                                <input type="number" class="GLXXmain_12 heating_floor_high" value="<?php if($id and $heating[$i]) echo $heating[$i]['floor_high']?>">
                                <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                            </div>
                            <div class="GLXXmain_1">单层高度</div>
                            <div class="GLXXmain_2">
                                <input type="number" class="GLXXmain_3 heating_floor_height"  value="<?php if($id and $heating[$i]) echo $heating[$i]['floor_height'];?>"><div class="GLXXmain_15">m</div>
                            </div>
                            <div class="GLXXmain_1">采暖面积</div>
                            <div class="GLXXmain_2">
                                <input type="number" class="GLXXmain_3 heating_area"  value="<?php if($id and $heating[$i]) echo $heating[$i]['area'];?>"><div class="GLXXmain_15">㎡</div>
                            </div>
                            <div class="GLXXmain_1">采暖形式</div>
                            <div class="GLXXmain_2">
                                <select type="text" class="GLXXmain_3 heating_type" style="width: 344px">
                                    <?php
                                    foreach ($ARRAY_selection_heating_type as $key => $val){
                                        if($id and $heating[$i]){
                                            if($key==$heating[$i]['type'])
                                                echo '<option value="'.$key.'" selected>'.$val.'</option>';
                                            else
                                                echo '<option value="'.$key.'">'.$val.'</option>';
                                        }else{
                                            echo '<option value="'.$key.'">'.$val.'</option>';
                                        }

                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="GLXXmain_1">使用时间</div>
                            <div class="GLXXmain_2">
                                <select type="text" class="GLXXmain_3 heating_usetime_type" style="width: 344px;">
                                    <?php
                                    foreach ($ARRAY_selection_usetime_type as $key => $val) {
                                        if ($id and $heating[$i]) {
                                            if ($key == $heating[$i]['usetime_type'])
                                                echo '<option value="' . $key . '" selected>' . $val . '</option>';
                                            else
                                                echo '<option value="' . $key . '">' . $val . '</option>';
                                        }else{
                                            echo '<option value="' . $key . '">' . $val . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                    }
                }?>
                <div class="GLXXmain_16">
                    <span id="addnq">+添加分区</span>
                    <?php
                    $str_mounq_diplay="none";
                    if($id){
                        if($heating_Count>1){
                            $str_mounq_diplay="block";
                        }
                    }

                    ?>
                    <span id="mounq" style="display:<?php echo $str_mounq_diplay?>;">-删除分区</span>
                </div>
                <button class="GLXXmain_17" id="nq_selection">开始选型</button>
            </div>
        <?php }?>
        <div id="nqadd_new" class="GLXXmain_8" style="display: none">
         <div class="insertion">
            <img src="images/fgx_ls.png" class="GLXXmain_9">
            <div class="GLXXmain_1">建筑类别</div>
            <div class="GLXXmain_2">
                <select type="text" class="GLXXmain_3 heating_build_type" style="width: 344px">
                    <?php
                    $buildtypelist = Selection_build::getListByParentid(1);
                    if($buildtypelist){
                        foreach ($buildtypelist as $thistype){
                                echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="GLXXmain_1">采暖楼层</div>
            <div class="GLXXmain_2">
                <input type="number" class="GLXXmain_12 heating_floor_low" >
                <div class="GLXXmain_13"></div>
                <input type="number" class="GLXXmain_12 heating_floor_high">
                <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
            </div>
            <div class="GLXXmain_1">单层高度</div>
            <div class="GLXXmain_2">
                <input type="number" class="GLXXmain_3 heating_floor_height" ><div class="GLXXmain_15">m</div>
            </div>
            <div class="GLXXmain_1">采暖面积</div>
            <div class="GLXXmain_2">
                <input type="number" class="GLXXmain_3 heating_area"  ><div class="GLXXmain_15">㎡</div>
            </div>
            <div class="GLXXmain_1">采暖形式</div>
            <div class="GLXXmain_2">
                <select type="text" class="GLXXmain_3 heating_type" style="width: 344px">
                    <?php
                    foreach ($ARRAY_selection_heating_type as $key => $val){
                            echo '<option value="'.$key.'">'.$val.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="GLXXmain_1">使用时间</div>
            <div class="GLXXmain_2">
                <select type="text" class="GLXXmain_3 heating_usetime_type" style="width: 344px;">
                    <?php
                    foreach ($ARRAY_selection_usetime_type as $key => $val) {

                            echo '<option value="' . $key . '">' . $val . '</option>';

                    }
                    ?>
                </select>
            </div>
        </div>
        </div>
        <!--r热水-->
        <?php if((empty($id) or $hot_Water) )  {
            $str_3="none";
            if($hot_Water and $info['application']==1)
                $str_3="block";
            ?>
            <div id="water" class="GLXXmain_8" style="display: <?php echo $str_3;?>">
                <div id="guolutype2" style="display: block">
                    <div class="GLXXmain_1">锅炉形式</div>
                    <div class="GLXXmain_11">
                        <?php
                        foreach ($ARRAY_selection_application['1']['type'] as $key => $val) {
                            if ($id) {
                                if ($key == $info['water_type']){
                                    echo '<div class="GLXXmain_10 GLXXmain_check" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                                }else{
                                    echo '<div class="GLXXmain_10" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                                }
                            }else{
                                echo '<div class="GLXXmain_10" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                            }
                        }
                        ?>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php if((empty($id) or $hot_Water ) )  {
                    if(empty($id)) {
                        $hot_Water_Count=1;
                    }

                    $t=0;
                    for($i=0;$i<$hot_Water_Count;$i++){

                        if($id){
                            $attr_Count=Selection_hotwater_attr::getAttrCount($info['id'],$i+1);
                            $attr_s=Selection_hotwater_attr::getInfoByHistoryId($info['id'],$i+1);
                            if($attr_Count==2){
                                $t=$t+1;
                            }else if($attr_Count==3){
                                $t=$t+2;
                            }
                        }

                        ?>
                        <div class="insertion">
                            <img src="images/fgx_ls.png" class="GLXXmain_9">
                            <div class="GLXXmain_2">

                                <?php if($id and ($attr_s[0]['use_type'] ==31)) {?>
                                    <input type="radio"  class="GLXXmain_5 water alldaytime" name="water_<?php echo $i?>" value="31" checked><span class="GLXXmain_6">全日供水</span>
                                <?php }else{ ?>
                                    <input type="radio"  class="GLXXmain_5 water alldaytime" name="water_<?php echo $i?>" value="31" ><span class="GLXXmain_6">全日供水</span>

                                <?php }?>
                                <?php if($id and $attr_s[0]['use_type'] ==32) {?>
                                    <input style="margin-left: 60px" type="radio"  class="GLXXmain_5 water"  name="water_<?php echo $i?>" value="32" checked><span class="GLXXmain_6">定时供水</span>
                                <?php }else{ ?>
                                    <input style="margin-left: 60px" type="radio"  class="GLXXmain_5 water"  name="water_<?php echo $i?>" value="32"><span class="GLXXmain_6">定时供水</span>
                                <?php } ?>
                            </div>
                            <!--全天日供水-->

                            <?php
                            $str_display_all_day="none";

                            if($id){
                                if($attr_s){
                                    if($attr_s[0]['use_type']==31){
                                        $str_display_all_day="block";
                                    }
                                }
                            }

                            ?>
                            <div class="fulltimechose" style="display: <?php echo $str_display_all_day ?>">
                                <div class="GLXXmain_1" style="display: none">采暖面积</div>
                                <div class="GLXXmain_2" style="display: none">
                                    <input type="text" class="GLXXmain_3 full_heating_area" value="0"><div class="GLXXmain_15">㎡</div>
                                </div>
                                <div class="GLXXmain_1">建筑类别</div>
                                <div class="GLXXmain_2">
                                    <select type="text" class="GLXXmain_3 alldaybuildtype" style="width: 344px">
                                        <?php
                                        $alldaytype = Selection_build::getListByParentid(31);
                                        if($alldaytype){
                                            foreach ($alldaytype as $thistype){
                                                if($id){
                                                    if($thistype['id']==$attr_s[0]['build_type'])
                                                        echo '<option value="'.$thistype['id'].'" selected>'.$thistype['name'].'</option>';
                                                    else
                                                        echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                                }else{
                                                    echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                                }

                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="GLXXmain_1">热水楼层</div>
                                <div class="GLXXmain_2">
                                    <input type="number" class="GLXXmain_12 heating_floor_low" value="<?php if($id and $attr_s) echo $attr_s[0]['floor_low']?>">
                                    <div class="GLXXmain_13"></div>
                                    <input type="number" class="GLXXmain_12 heating_floor_high" value="<?php if($id and $attr_s) echo $attr_s[0]['floor_high']?>">
                                    <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                                </div>
                                <div class="GLXXmain_1">单层高度</div>
                                <div class="GLXXmain_2">
                                    <input type="number" class="GLXXmain_3 heating_floor_height" value="<?php if($id and $attr_s) echo $attr_s[0]['floor_height']?>"><div class="GLXXmain_15">m</div>
                                </div>

                                <div class="AlldayBuildTypediv attrDiv">
                                    <?php
                                    $num1=0;
                                    $num2=0;
                                    $num3=0;
                                    $detaillist = Selection_build::getListByParentid($alldaytype[0]['id']);
                                    $flag_time_water=0;
                                    if($id and $hot_Water){
                                        $flag_time_water=1;
                                        if($attr_s[0]['build_type']!=21 and $attr_s[0]['build_type']!=22){
                                            if(isset($alldaytype[$attr_s[0]['build_type']-13]['id'])){
                                                $detaillist = Selection_build::getListByParentid($alldaytype[$attr_s[0]['build_type']-13]['id']);
                                            }
                                        }else{
                                            if(isset($alldaytype[$attr_s[0]['build_type']-14]['id'])){
                                                $detaillist = Selection_build::getListByParentid($alldaytype[$attr_s[0]['build_type']-14]['id']);
                                            }
                                        }

                                        if($attr_s[0]['attr_num']){
                                            $num1= $attr_s[0]['attr_num'];
                                        }
                                        if(isset($attr_s[1]['attr_num'])){
                                            $num2= $attr_s[1]['attr_num'];
                                        }
                                        if(isset($attr_s[2]['attr_num'])){
                                            $num3= $attr_s[2]['attr_num'];
                                        }

                                    }

                                    $htmlstr = '';
                                    if($detaillist){
                                        $nums=0;
                                        $attr_nums=0;

                                        foreach ($detaillist as $thisinfo){

                                            if($str_display_all_day=="block"){
                                                if($nums==0){
                                                    $attr_nums=$num1;
                                                }else if($nums==1){
                                                    $attr_nums=$num2;
                                                }else{
                                                    if($attr_Count==2){
                                                        $attr_nums=$num2;
                                                    }else if($attr_Count==3){
                                                        $attr_nums=$num3;
                                                    }

                                                }
                                            }

                                            $childlist = Selection_build::getListByParentid($thisinfo['id']);

                                            if(empty($childlist)){
                                                $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>
                                 <div class="GLXXmain_2">
                                     <input type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'" value="'.$attr_nums.'"><div class="GLXXmain_15"></div>
                                 </div>';
                                            }else{

                                                $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>';
                                                $htmlstr .= '<div class="GLXXmain_2">';
                                                $htmlstr .= '<select type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'" style="width: 344px;">';
                                                foreach ($childlist as $thischild){
                                                    if($id){
                                                        if($thischild['id']==$attr_nums){
                                                            $htmlstr .= '<option value="'.$thischild['id'].'" selected>'.$thischild['name'].'</option>';
                                                        }else{
                                                            $htmlstr .= '<option value="'.$thischild['id'].'">'.$thischild['name'].'</option>';
                                                        }
                                                    }else{
                                                        $htmlstr .= '<option value="'.$thischild['id'].'">'.$thischild['name'].'</option>';
                                                    }


                                                }
                                                $htmlstr .= '</select></div>';
                                            }
                                            $nums++;
                                        }
                                    }
                                    echo $htmlstr;
                                    ?>
                                </div>

                            </div>
                            <!--定时供水-->
                            <?php
                            $str_display="none";

                            if($id){
                                if($attr_s){
                                    if($attr_s[0]['use_type']==32){
                                        $str_display="block";
                                    }
                                }
                            }

                            ?>
                            <div class="timingchose" style="display: <?php echo $str_display?>">
                                <div class="GLXXmain_1"  style="display: none">采暖面积</div>
                                <div class="GLXXmain_2"  style="display: none">
                                    <input type="text" class="GLXXmain_3 timing_heating_area" value="0"><div class="GLXXmain_15">㎡</div>
                                </div>
                                <div class="GLXXmain_1">建筑类别</div>
                                <div class="GLXXmain_2">
                                    <select type="text" class="GLXXmain_3 timingbuildtype" style="width: 344px">
                                        <?php
                                        $alldaytype = Selection_build::getListByParentid(32);
                                        if($alldaytype){
                                            foreach ($alldaytype as $thistype){
                                                if($id){
                                                    if($thistype['id']==$attr_s[0]['build_type'])
                                                        echo '<option value="'.$thistype['id'].'" selected>'.$thistype['name'].'</option>';
                                                    else
                                                        echo '<option value="'.$thistype['id'].'">'.$thistype['name'].'</option>';
                                                }else
                                                    echo '<option value="'.$thistype['id'].'">'.$thistype['name'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="GLXXmain_1">热水楼层</div>
                                <div class="GLXXmain_2">
                                    <input type="number" class="GLXXmain_12 time_heating_floor_low" value="<?php if($id and $attr_s) echo $attr_s[0]['floor_low']?>">
                                    <div class="GLXXmain_13"></div>
                                    <input type="number" class="GLXXmain_12 time_heating_floor_high" value="<?php if($id and $attr_s) echo $attr_s[0]['floor_high']?>">
                                    <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                                </div>
                                <div class="GLXXmain_1">单层高度</div>
                                <div class="GLXXmain_2">
                                    <input type="number" class="GLXXmain_3 time_heating_floor_height" value="<?php if($id and $attr_s) echo $attr_s[0]['floor_height']?>"><div class="GLXXmain_15">m</div>
                                </div>
                                <!--  多少个-->
                                <div class="timing_1 attrDiv">
                                    <?php
                                    $num1=0;
                                    $num2=0;
                                    $num3=0;
                                    $num4=0;
                                    $num5=0;
                                    $same_use=0;
                                    $detaillist = Selection_build::getListByParentid($alldaytype[0]['id']);
                                    if($id and $hot_Water){
                                        if(isset($alldaytype[$attr_s[0]['build_type']-33]['id'])){
                                            $detaillist = Selection_build::getListByParentid($alldaytype[$attr_s[0]['build_type']-33]['id']);

                                        }

                                        if($attr_s[0]['attr_num']){
                                            $num1= $attr_s[0]['attr_num'];
                                        }
                                        if(isset($attr_s[1]['attr_num'])){
                                            $num2= $attr_s[1]['attr_num'];
                                        }
                                        if(isset($attr_s[2]['attr_num'])){
                                            $num3= $attr_s[2]['attr_num'];
                                        }
                                        if(isset($attr_s[3]['attr_num'])){
                                            $num4= $attr_s[3]['attr_num'];
                                        }
                                        if(isset($attr_s[4]['attr_num'])){
                                            $num5= $attr_s[4]['attr_num'];
                                        }
                                        if(isset($attr_s[0]['same_use'])){
                                            $same_use= $attr_s[0]['same_use'];
                                        }

                                    }


                                    if($detaillist){

                                        $nums=0;
                                        $attr_nums=0;
                                        foreach ($detaillist as $thisinfo){
                                            if($str_display=="block") {
                                                if($nums==0){
                                                    $attr_nums=$num1;
                                                }else if($nums==1){
                                                    $attr_nums=$num2;
                                                }else if($nums==2){
                                                    $attr_nums=$num3;
                                                }else if($nums==3){
                                                    $attr_nums=$num4;
                                                }else if($nums==4){
                                                    $attr_nums=$num5;
                                                }
                                            }
                                            $stylestr = 'style="width: 200px"';
                                            echo '<div class="timing_2" '.$stylestr.'><span class="timing_3">'.$thisinfo['name'].'</span><input class="timing_4 hotwaterattr" data-value="'.$thisinfo['id'].'" type="number" value='.$attr_nums.'><span class="timing_5">个</span></div>';
                                            $nums++;
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="GLXXmain_1">卫生器具同时使用百分数b</div>
                                <div class="GLXXmain_2">
                                    <input type="text" id="use_percent" name="use_percent" class="GLXXmain_3 timing_same_use" value="<?php echo $same_use*100?>"><div class="GLXXmain_15">%</div>
                                </div>
                            </div>


                        </div>
                        <?php

                    }
                }?>
                <div class="GLXXmain_16">
                    <span id="addrs">+添加分区</span>
                    <?php
                    $str_mours_diplay="none";
                    if($id){
                        if($hot_Water_Count>1){
                            $str_mours_diplay="block";
                        }
                    }

                    ?>
                    <span id="mours" style="display: <?php echo $str_mours_diplay?>">-删除分区</span>
                </div>
                <button class="GLXXmain_17" id="hotwater_selection">开始选型</button>
            </div>
        <?php }?>
        <!--热水加采暖-->
        <?php if( empty($id) or $heating or $info['application']==2 )  {?>


            <div id="WandN" class="GLXXmain_8" style="display:none">
                <div id="guolutype3" style="display: block">
                    <div class="GLXXmain_1">锅炉形式</div>
                    <div class="GLXXmain_11">
                        <?php
                        foreach ($ARRAY_selection_application['0']['type'] as $key => $val) {
                            if ($id) {
                                if ($key == $info['heating_type']){
                                    echo '<div class="GLXXmain_10 GLXXmain_check" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                                }else{
                                    echo '<div class="GLXXmain_10" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                                }
                            }else{
                                echo '<div class="GLXXmain_10" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                            }
                        }
                        ?>
                        <!--清除浮动-->
                        <div class="clear"></div>
                    </div>
                </div>
                <?php if(empty($id) or $heating )  {
                    if(empty($id)) {
                        $heating_Count=1;

                    }
                    for($i=0;$i<$heating_Count;$i++){
                        ?>
                        <div class="insertion">
                            <img src="images/fgx_ls.png" class="GLXXmain_9">

                            <div class="GLXXmain_1">建筑类别</div>
                            <div class="GLXXmain_2">
                                <select type="text" class="GLXXmain_3 heating_build_type" style="width: 344px">
                                    <?php
                                    $buildtypelist = Selection_build::getListByParentid(1);
                                    if($buildtypelist){
                                        foreach ($buildtypelist as $thistype){
                                            if($id){
                                                if($thistype['id']==$heating[$i]['build_type'])
                                                    echo '<option value="'.$thistype['id'].'" selected>'.$thistype['name'].'</option>';
                                                else
                                                    echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                            }else{
                                                echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                            }

                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="GLXXmain_1">采暖楼层</div>
                            <div class="GLXXmain_2">
                                <input type="number" class="GLXXmain_12 heating_floor_low" value="<?php if($id and $heating[$i]) echo $heating[$i]['floor_low']?>">
                                <div class="GLXXmain_13"></div>
                                <input type="number" class="GLXXmain_12 heating_floor_high" value="<?php if($id and $heating[$i]) echo $heating[$i]['floor_high']?>">
                                <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                            </div>
                            <div class="GLXXmain_1">单层高度</div>
                            <div class="GLXXmain_2">
                                <input type="number" class="GLXXmain_3 heating_floor_height"  value="<?php if($id and $heating[$i]) echo $heating[$i]['floor_height'];?>"><div class="GLXXmain_15">m</div>
                            </div>
                            <div class="GLXXmain_1">采暖面积</div>
                            <div class="GLXXmain_2">
                                <input type="number" class="GLXXmain_3 heating_area"  value="<?php if($id and $heating[$i]) echo $heating[$i]['area'];?>"><div class="GLXXmain_15">㎡</div>
                            </div>
                            <div class="GLXXmain_1">采暖形式</div>
                            <div class="GLXXmain_2">
                                <select type="text" class="GLXXmain_3 heating_type" style="width: 344px">
                                    <?php
                                    foreach ($ARRAY_selection_heating_type as $key => $val){
                                        if($id and $heating[$i]){
                                            if($key==$heating[$i]['type'])
                                                echo '<option value="'.$key.'" selected>'.$val.'</option>';
                                            else
                                                echo '<option value="'.$key.'">'.$val.'</option>';
                                        }else{
                                            echo '<option value="'.$key.'">'.$val.'</option>';
                                        }

                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="GLXXmain_1">使用时间</div>
                            <div class="GLXXmain_2">
                                <select type="text" class="GLXXmain_3 heating_usetime_type" style="width: 344px;">
                                    <?php
                                    foreach ($ARRAY_selection_usetime_type as $key => $val) {
                                        if ($id and $heating[$i]) {
                                            if ($key == $heating[$i]['usetime_type'])
                                                echo '<option value="' . $key . '" selected>' . $val . '</option>';
                                            else
                                                echo '<option value="' . $key . '">' . $val . '</option>';
                                        }else{
                                            echo '<option value="' . $key . '">' . $val . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                    }
                }?>
                <div class="GLXXmain_16">
                    <span id="addIner">+添加分区</span>
                    <?php
                    $str_mouIner_diplay="none";
                    if($id){
                        if($heating_Count>1){
                            $str_mouIner_diplay="block";
                        }
                    }

                    ?>
                    <span id="mouIner" style="display: <?php echo $str_mouIner_diplay?>;">-删除分区</span>

                </div>

                <button id="nextbtn" class="GLXXmain_17">下一步</button>
            </div>
        <?php }?>
    </div>
    <!--热水下一步-->

    <?php  if(empty($id) or $all_display_hw) {

        $str_all_cv_display="none";
        $str_cv_display="none";
        if($all_display_hw){
            $str_all_cv_display="display";
        }
        ?>
        <div id="nextWandN" class="GLXXmain_8" style="display: <?php echo $str_all_cv_display?>">
            <div id="guolutype4" style="display: block">
                <div class="GLXXmain_1">锅炉形式</div>
                <div class="GLXXmain_11">
                    <?php
                    foreach ($ARRAY_selection_application['1']['type'] as $key => $val) {
                        if ($id) {
                            if ($key == $info['water_type']){
                                echo '<div class="GLXXmain_10 GLXXmain_check" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                            }else{
                                echo '<div class="GLXXmain_10" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                            }
                        }else{
                            echo '<div class="GLXXmain_10" data-type="water" data-value="' . $key . '">' . $val . '</div>';
                        }
                    }
                    ?>
                    <!--清除浮动-->
                    <div class="clear"></div>
                </div>
            </div>
            <?php
            if((empty($id) or $hot_Water ) )  {
            if(empty($id)) {
                $hot_Water_Count=1;
            }

            $t=0;

            for($i=0;$i<$hot_Water_Count;$i++){
                if($id){
                    $attr_Count=Selection_hotwater_attr::getAttrCount($info['id'],$i+1);
                    $attr_s=Selection_hotwater_attr::getInfoByHistoryId($info['id'],$i+1);
//                    print_r($attr_s);

                    if($attr_Count==2){
                        $t=$t+1;
                    }else if($attr_Count==3){
                        $t=$t+2;
                    }
                }
                ?>

                <div class="insertion">
                    <img src="images/fgx_ls.png" class="GLXXmain_9">
                    <div class="GLXXmain_2">
                        <?php if($id and ($attr_s[0]['use_type'] ==31)) {?>
                            <input type="radio"  class="GLXXmain_5 water alldaytime" name="water_<?php echo $i?>" value="31" checked><span class="GLXXmain_6">全日供水</span>
                        <?php }else{ ?>
                            <input type="radio"  class="GLXXmain_5 water alldaytime" name="water_<?php echo $i?>" value="31" ><span class="GLXXmain_6">全日供水</span>
                        <?php }?>
                        <?php if($id and $attr_s[0]['use_type'] ==32) {?>
                            <input style="margin-left: 60px" type="radio"  class="GLXXmain_5 water"  name="water_<?php echo $i?>" value="32" checked><span class="GLXXmain_6">定时供水</span>
                        <?php }else{ ?>
                            <input style="margin-left: 60px" type="radio"  class="GLXXmain_5 water"  name="water_<?php echo $i?>" value="32"><span class="GLXXmain_6">定时供水</span>
                        <?php } ?>
                    </div>
                    <!--全天日供水-->

                    <?php
                    $str_display_all_day="none";

                    if($id){
                        if($attr_s){
                            if($attr_s[0]['use_type']==31){
                                $str_display_all_day="block";
                            }
                        }
                    }

                    ?>
                    <div class="fulltimechose" style="display: <?php echo $str_display_all_day ?>">
                        <div class="GLXXmain_1" style="display: none">采暖面积</div>
                        <div class="GLXXmain_2" style="display: none">
                            <input type="text" class="GLXXmain_3 full_heating_area" value="0"><div class="GLXXmain_15">㎡</div>
                        </div>
                        <div class="GLXXmain_1">建筑类别</div>
                        <div class="GLXXmain_2">
                            <select type="text" class="GLXXmain_3 alldaybuildtype" style="width: 344px">
                                <?php
                                $alldaytype = Selection_build::getListByParentid(31);

                                if($alldaytype){
                                    foreach ($alldaytype as $thistype){
                                        if($id){
                                            if($thistype['id']==$attr_s[0]['build_type'])
                                                echo '<option value="'.$thistype['id'].'" selected>'.$thistype['name'].'</option>';
                                            else
                                                echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                        }else{
                                            echo '<option value="'.$thistype['id'].'" >'.$thistype['name'].'</option>';
                                        }

                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="GLXXmain_1">热水楼层</div>
                        <div class="GLXXmain_2">
                            <input type="number" class="GLXXmain_12 heating_floor_low"  value="<?php if($id and $attr_s) echo $attr_s[0]['floor_low']?>">
                            <div class="GLXXmain_13"></div>
                            <input type="number" class="GLXXmain_12 heating_floor_high"  value="<?php if($id and $attr_s) echo $attr_s[0]['floor_high']?>">
                            <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                        </div>
                        <div class="GLXXmain_1">单层高度</div>
                        <div class="GLXXmain_2">
                            <input type="number" class="GLXXmain_3 heating_floor_height"  value="<?php if($id and $attr_s) echo $attr_s[0]['floor_height']?>"><div class="GLXXmain_15">m</div>
                        </div>

                        <div class="AlldayBuildTypediv attrDiv">
                            <?php
                            $num1=0;
                            $num2=0;
                            $num3=0;
                            $detaillist = Selection_build::getListByParentid($alldaytype[0]['id']);
                            if($id and $hot_Water){
                                if($attr_s[0]['build_type']!=21 and $attr_s[0]['build_type']!=22){
                                    if(isset($alldaytype[$attr_s[0]['build_type']-13]['id'])){
                                        $detaillist = Selection_build::getListByParentid($alldaytype[$attr_s[0]['build_type']-13]['id']);
                                    }
                                }else{
                                    if(isset($alldaytype[$attr_s[0]['build_type']-14]['id'])){
                                        $detaillist = Selection_build::getListByParentid($alldaytype[$attr_s[0]['build_type']-14]['id']);
                                    }
                                }

                                if($attr_s[0]['attr_num']){
                                    $num1= $attr_s[0]['attr_num'];
                                }
                                if(isset($attr_s[1]['attr_num'])){
                                    $num2= $attr_s[1]['attr_num'];
                                }
                                if(isset($attr_s[2]['attr_num'])){
                                    $num3= $attr_s[2]['attr_num'];
                                }

                            }

                            $htmlstr = '';
                            if($detaillist){

                                $nums=0;
                                $attr_nums=0;
                                foreach ($detaillist as $thisinfo){
                                    if( $str_display_all_day=="block"){
                                        if($nums==0){
                                            $attr_nums=$num1;
                                        }else if($nums==1){
                                            $attr_nums=$num2;
                                        }else{
                                            if($attr_Count==2){
                                                $attr_nums=$num2;
                                            }else if($attr_Count==3){
                                                $attr_nums=$num3;
                                            }

                                        }
                                    }

                                    $childlist = Selection_build::getListByParentid($thisinfo['id']);

                                    if(empty($childlist)){
                                        $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>
                                 <div class="GLXXmain_2">
                                     <input type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'" value="'.$attr_nums.'"><div class="GLXXmain_15"></div>
                                 </div>';
                                    }else{
                                        $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>';
                                        $htmlstr .= '<div class="GLXXmain_2">';
                                        $htmlstr .= '<select type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'" style="width: 344px;">';
                                        foreach ($childlist as $thischild){
                                            if($id){
                                                if($thischild['id']==$attr_nums){
                                                    $htmlstr .= '<option value="'.$thischild['id'].'" selected>'.$thischild['name'].'</option>';
                                                }else{
                                                    $htmlstr .= '<option value="'.$thischild['id'].'">'.$thischild['name'].'</option>';
                                                }
                                            }else{
                                                $htmlstr .= '<option value="'.$thischild['id'].'">'.$thischild['name'].'</option>';
                                            }


                                        }
                                        $htmlstr .= '</select></div>';
                                    }
                                    $nums++;
                                }
                            }
                            echo $htmlstr;
                            ?>
                        </div>

                    </div>
                    <!--定时供水-->
                    <?php
                    $str_display="none";

                    if($id){
                        if($attr_s){
                            if($attr_s[0]['use_type']==32){
                                $str_display="block";
                            }
                        }
                    }

                    ?>
                    <div class="timingchose" style="display: <?php echo $str_display?>">
                        <div class="GLXXmain_1"  style="display: none">采暖面积</div>
                        <div class="GLXXmain_2"  style="display: none">
                            <input type="text" class="GLXXmain_3 timing_heating_area" value="0"><div class="GLXXmain_15">㎡</div>
                        </div>
                        <div class="GLXXmain_1">建筑类别</div>
                        <div class="GLXXmain_2">
                            <select type="text" class="GLXXmain_3 timingbuildtype" style="width: 344px">
                                <?php
                                $alldaytype = Selection_build::getListByParentid(32);
                                if($alldaytype){
                                    foreach ($alldaytype as $thistype){
                                        if($id){
                                            if($thistype['id']==$attr_s[0]['build_type'])
                                                echo '<option value="'.$thistype['id'].'" selected>'.$thistype['name'].'</option>';
                                            else
                                                echo '<option value="'.$thistype['id'].'">'.$thistype['name'].'</option>';
                                        }else
                                            echo '<option value="'.$thistype['id'].'">'.$thistype['name'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="GLXXmain_1">热水楼层</div>
                        <div class="GLXXmain_2">
                            <input type="number" class="GLXXmain_12 time_heating_floor_low"  value="<?php if($id and $attr_s) echo $attr_s[0]['floor_low']?>">
                            <div class="GLXXmain_13"></div>
                            <input type="number" class="GLXXmain_12 time_heating_floor_high"  value="<?php if($id and $attr_s) echo $attr_s[0]['floor_high']?>">
                            <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                        </div>
                        <div class="GLXXmain_1">单层高度</div>
                        <div class="GLXXmain_2">
                            <input type="number" class="GLXXmain_3 time_heating_floor_height"  value="<?php if($id and $attr_s) echo $attr_s[0]['floor_height']?>"><div class="GLXXmain_15">m</div>
                        </div>
                        <!--  多少个-->
                        <div class="timing_1 attrDiv">
                            <?php
                            $num1=0;
                            $num2=0;
                            $num3=0;
                            $num4=0;
                            $num5=0;
                            $same_use=0;
                            $detaillist = Selection_build::getListByParentid($alldaytype[0]['id']);
                            if($id and $hot_Water){
                                if(isset($alldaytype[$attr_s[0]['build_type']-33]['id'])){
                                    $detaillist = Selection_build::getListByParentid($alldaytype[$attr_s[0]['build_type']-33]['id']);

                                }
                                if($attr_s[0]['attr_num']){
                                    $num1= $attr_s[0]['attr_num'];
                                }
                                if(isset($attr_s[1]['attr_num'])){
                                    $num2= $attr_s[1]['attr_num'];
                                }
                                if(isset($attr_s[2]['attr_num'])){
                                    $num3= $attr_s[2]['attr_num'];
                                }
                                if(isset($attr_s[3]['attr_num'])){
                                    $num4= $attr_s[3]['attr_num'];
                                }
                                if(isset($attr_s[4]['attr_num'])){
                                    $num5= $attr_s[4]['attr_num'];
                                }
                                if(isset($attr_s[0]['same_use'])){
                                    $same_use= $attr_s[0]['same_use'];
                                }

                            }


                            if($detaillist){

                                $nums=0;
                                $attr_nums=0;
                                foreach ($detaillist as $thisinfo){
                                    if($str_display=="block"){
                                        if($nums==0){
                                            $attr_nums=$num1;
                                        }else if($nums==1){
                                            $attr_nums=$num2;
                                        }else if($nums==2){
                                            $attr_nums=$num3;
                                        }else if($nums==3){
                                            $attr_nums=$num4;
                                        }else if($nums==4){
                                            $attr_nums=$num5;
                                        }
                                    }

                                    $stylestr = 'style="width: 200px"';
                                    echo '<div class="timing_2" '.$stylestr.'><span class="timing_3">'.$thisinfo['name'].'</span><input class="timing_4 hotwaterattr" data-value="'.$thisinfo['id'].'" type="number" value='.$attr_nums.'><span class="timing_5">个</span></div>';
                                    $nums++;
                                }   
                            }
                            ?>
                        </div>
                        <div class="GLXXmain_1">卫生器具同时使用百分数b</div>
                        <div class="GLXXmain_2">
                            <input type="text" id="use_percent" name="use_percent" class="GLXXmain_3 timing_same_use" value="<?php echo $same_use*100?>"><div class="GLXXmain_15">%</div>
                        </div>
                    </div>

                </div>


            <?php }}?>
            <div class="GLXXmain_16" >
                <span id="addIner_1">+添加分区</span>
                <?php
                $str_mouIner_1_diplay="none";
                if($id){
                    if($hot_Water_Count>1){
                        $str_mouIner_1_diplay="block";
                    }
                }

                ?>
                <span id="mouIner_1" style="display: <?php echo $str_mouIner_1_diplay;?>">-删除分区</span>

            </div>
            <div id="buttoning1" >
                <button id="priorbtn" class="GLXXmain_4" style="background: #04A6FE;
    border: 0;
    box-shadow: 0 6px 24px 0 rgba(10,122,182,0.30);
    border-radius: 6px;
    width: 346px;
    height: 60px;
    line-height: 60px;
    font-family: PingFangSC-Regular;
    font-size: 24px;
    color: #FFFFFF;
    letter-spacing: 0.93px;
    text-align: center;
    margin-left: -15% !important;">上一步</button>
                <button id="nq_rs_selection" class="GLXXmain_4" style="background: #04A6FE;
    border: 0;
    box-shadow: 0 6px 24px 0 rgba(10,122,182,0.30);
    border-radius: 6px;
    width: 346px;
    height: 60px;
    line-height: 60px;
    font-family: PingFangSC-Regular;
    font-size: 24px;
    color: #FFFFFF;
    letter-spacing: 0.93px;
    text-align: center;
    margin-left: 30% !important;">开始选型</button>
            </div>
        </div>
    <?php }?>
        <!--       热水-->

    <div id="wateradd_new" class="GLXXmain_8" style="display: none">

        <div class="insertion_new">
            <img src="images/fgx_ls.png" class="GLXXmain_9">
            <div class="GLXXmain_2">
                <input type="radio" class="GLXXmain_5 water alldaytime" name="water_1" value="31" ><span class="GLXXmain_6">全日供水</span>
                <input style="margin-left: 60px" type="radio" class="GLXXmain_5 water"  name="water_1" value="32" > <span class="GLXXmain_6">定时供水</span>
            </div>
            <!--全天日供水-->
            <div class="fulltimechose" >
                <div class="GLXXmain_1" style="display: none">采暖面积</div>
                <div class="GLXXmain_2" style="display: none">
                    <input type="text" class="GLXXmain_3 full_heating_area" value="0"><div class="GLXXmain_15">㎡</div>
                </div>
                <div class="GLXXmain_1">建筑类别</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3 alldaybuildtype" style="width: 344px">
                        <?php
                        $alldaytype = Selection_build::getListByParentid(31);
                        if($alldaytype){
                            foreach ($alldaytype as $thistype){
                                echo '<option value="'.$thistype['id'].'">'.$thistype['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="GLXXmain_1">热水楼层</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_12 heating_floor_low" >
                    <div class="GLXXmain_13"></div>
                    <input type="number" class="GLXXmain_12 heating_floor_high">
                    <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                </div>
                <div class="GLXXmain_1">单层高度</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3 heating_floor_height" ><div class="GLXXmain_15">m</div>
                </div>
                <div class="AlldayBuildTypediv attrDiv">
                    <?php
                    $detaillist = Selection_build::getListByParentid($alldaytype[0]['id']);

                    $htmlstr = '';
                    if($detaillist){
                        $i = 0;
                        foreach ($detaillist as $thisinfo){
                            $childlist = Selection_build::getListByParentid($thisinfo['id']);
                            if(empty($childlist)){
                                $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>
                                 <div class="GLXXmain_2">
                                     <input type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'"><div class="GLXXmain_15"></div>
                                 </div>';
                            }else{
                                $htmlstr .= '<div class="GLXXmain_1">'.$thisinfo['name'].'</div>';
                                $htmlstr .= '<div class="GLXXmain_2">';
                                $htmlstr .= '<select type="text" class="GLXXmain_3 hotwaterattr" data-value="'.$thisinfo['id'].'" style="width: 344px;">';
                                foreach ($childlist as $thischild){
                                    $htmlstr .= '<option value="'.$thischild['id'].'">'.$thischild['name'].'</option>';
                                }
                                $htmlstr .= '</select></div>';
                            }
                        }
                    }
                    echo $htmlstr;
                    ?>
                </div>
            </div>
            <!--定时供水-->
            <div class="timingchose" style="display: none">
                <div class="GLXXmain_1" style="display: none">采暖面积</div>
                <div class="GLXXmain_2" style="display: none">
                    <input type="text" class="GLXXmain_3 timing_heating_area" value="0"><div class="GLXXmain_15">㎡</div>
                </div>
                <div class="GLXXmain_1">建筑类别</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3 timingbuildtype" style="width: 344px">
                        <?php

                        $alldaytype = Selection_build::getListByParentid(32);
                        if($alldaytype){
                            foreach ($alldaytype as $thistype){
                                echo '<option value="'.$thistype['id'].'">'.$thistype['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="GLXXmain_1">热水楼层</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_12 time_heating_floor_low" >
                    <div class="GLXXmain_13"></div>
                    <input type="number" class="GLXXmain_12 time_heating_floor_high">
                    <div class="GLXXmain_14">（若单层采暖，则两个空格填写相同楼层）</div>
                </div>
                <div class="GLXXmain_1">单层高度</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3 time_heating_floor_height" ><div class="GLXXmain_15">m</div>
                </div>
                <div class="timing_1 attrDiv">
                    <?php
                    $detaillist = Selection_build::getListByParentid($alldaytype[0]['id']);
                    if($detaillist){
                        $i = 0;
                        foreach ($detaillist as $thisinfo){
                            $i ++ ;
                            $stylestr = "";
                            if($i == 5){
                                $stylestr = 'style="width: 200px"';
                            }
                            echo '<div class="timing_2" '.$stylestr.'><span class="timing_3">'.$thisinfo['name'].'</span><input class="timing_4 hotwaterattr" data-value="'.$thisinfo['id'].'" type="number"><span class="timing_5">个</span></div>';
                        }
                    }
                    ?>
                </div>
                <div class="GLXXmain_1">卫生器具同时使用百分数b</div>
                <div class="GLXXmain_2">
                    <input type="text" id="use_percent" class="GLXXmain_3 timing_same_use" name="use_percent" ><div class="GLXXmain_15">%</div>
                </div>

            </div>

        </div>

    </div>


</div>
<div class="wrap-dialog hide">
    <div class="dialog">
        <div class="dialog-header">
            <span class="dialog-title">分区确认</span>
        </div>
        <div class="dialog-body">
            <span class="dialog-message">建议您添加分区，是否添加？？</span>
        </div>
        <div class="dialog-footer">
            <input type="button" class="btn" id="confirm" value="是" />
            <input type="button" class="btn ml50" id="cancel" value="否" />
        </div>
    </div>
</div>
<script type="text/javascript" src="js/xuanxing.js"></script>
<script src="http://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script>
    function dialogBox(message, yesCallback, noCallback){
        if(message){
            $('.dialog-message').html(message);
        }
        // 显示遮罩和对话框
        $('.wrap-dialog').removeClass("hide");
        // 确定按钮
        $('#confirm').click(function(){
            $('.wrap-dialog').addClass("hide");
            yesCallback();
        });
        // 取消按钮
        $('#cancel').click(function(){
            $('.wrap-dialog').addClass("hide");
            noCallback();
        });
    }
    //暖气加热水中热水切换

    // $(document).on('click',$('.GLXXmain_2').find('input'),
    $(document).on('click','.Nwater',function () {
        var naval = parseInt($(this).val());
        if (naval== 31)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','block');
            $(this).parent().parent().find('.timingchose').css('display','none');
        }
        else if (naval== 32)
        {
            $(this).parent().parent().find('.fulltimechose').css('display','none');
            $(this).parent().parent().find('.timingchose').css('display','block');
        }
    });
    $('#nextbtn').click(function () {

        var newht =  $('#wateradd_new').find('.insertion_new').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';

     //   $("#guolutype4").after(NHtml);
        $('#WandN').css('display','none');
        $('#nextWandN').css('display','block');
        $("#wateradd").css('display','block');
//        $("#wateradd").find('.insertion').css('display','none');


    });
    $('#wandnq').click(function () {

        <?php if($id){?>
        location.href = 'selection.php';
        <?php  }?>
        $('.GLXXmain_8').css('display','none');
        $('#WandN').css('display','block');
        $('#guolutype3').show();
        $('#guolutype1').hide();
        $('#guolutype2').hide();
    });
    $('#NQ').click(function () {
        <?php if($id){?>
        location.href = 'selection.php';
        <?php  }?>
        $('.GLXXmain_8').css('display','none');
        $('#nuanqi').css('display','block');
        $('#guolutype1').show();
        $('#guolutype2').hide();
        $('#guolutype3').hide();
    });
    $('#rs').click(function () {
        <?php if($id){?>
        location.href = 'selection.php';
        <?php  }?>
        $('.GLXXmain_8').css('display','none');
        $('#water').css('display','block');
        $('#guolutype2').show();
        $('#guolutype1').hide();
        $('#guolutype3').hide();
    });
    $(document).ready(function(){
        var type=0;
        <?php if($info and $info['water_type']){ ?>;
        type=<?php echo $info['water_type']?>;
        <?php } ?>;

        if(type==1){
            $('.alldaytime').attr("disabled",true);
            $('.alldaytime').attr("checked",false);
        }
    });
    $(document).on('click','.water',function () {

        var naval = parseInt($(this).val());
        var water_type = $('#guolutype2').find('.GLXXmain_check').data('value');
        var water_type_2 = $('#guolutype4').find('.GLXXmain_check').data('value');

        if (naval== 31)
        {

            if(water_type==1 && water_type_2==1){
                $('.alldaytime').attr("disabled",true);
                $('.alldaytime').attr("checked",false);


            }else{

                $(this).parent().parent().find('.fulltimechose').css('display','block');
                $(this).parent().parent().find('.timingchose').css('display','none');
            }

        }
        else if (naval== 32)
        {

            $(this).parent().parent().find('.fulltimechose').css('display','none');
            $(this).parent().parent().find('.timingchose').css('display','block');
        }
    });
    //取暖的增删
    $('.GLXXmain_10').click(function () {
        $(this).parent().find('.GLXXmain_10').removeClass('GLXXmain_check');
        $(this).addClass('GLXXmain_check');
        if($(this).attr("data-value") == 1 && $(this).attr("data-type") == "water"){
            $('.alldaytime').attr("checked",false);
            $('.alldaytime').attr("disabled",true);

//            layer.msg('当锅炉形式为常压热水锅炉+板换时，只能选择“定时供水”');
        }else{
            $('.alldaytime').attr("disabled",false);

        }
    });

    //热水+取暖的增删
    $('#addIner_1').click(function () {
        var valnum = parseInt($(this).parent().prev().find('.GLXXmain_2').find('input').prop('name').replace(/[^0-9]/ig,""));
        valnum= valnum+1;
        var newht =  $('#wateradd_new').find('.insertion_new').html();
        var NHtml ='<div  class="insertion">'+newht+'</div>';
        NHtml = NHtml.replace('name="water_1"','name="water_' + valnum + '"').replace('name="water_1"','name="water_' + valnum + '"');
        console.log($("#nextWandN").find('.insertion'));
        $("#nextWandN").find('.insertion:last').after(NHtml);
        $('#mouIner_1').css('display','block');
    });


    $('#mouIner_1').click(function () {
        var len = $('#nextWandN').find('.insertion');
        if(len.length<=2)
        {
            $("#nextWandN").find('.insertion:last').remove();
            $('#mouIner_1').css('display','none');
        }
        else
        {
            $("#nextWandN").find('.insertion:last').remove();
        }

    });

    $(function () {
        $('.indexMtwo_1').hover(function () {
            $(this).find('.mouseset').slideDown('fast');
            var name = $(this).find('.indexMtwo_1_2').text();
            $(this).find('.mouseset').find('span').text(name);
        },function () {
            $(this).find('.mouseset').slideUp(100);
        });

        $('#guolu_position').change(function () {
            var thisval = $(this).val();
            if(thisval == 1){
                $('#underground').show();
            }else{
                $('#underground').hide();
                $('#underground1').hide();
            }

            if(thisval != 0){
                $('#guolutype1').find('.GLXXmain_10').each(function () {
                    if($(this).data('value') == 1){

                        $(this).addClass('GLXXmain_disable');
                    }
                });
                $('#guolutype2').find('.GLXXmain_10').each(function () {
                    if($(this).data('value') == 2 || $(this).data('value') == 4){

                        $(this).addClass('GLXXmain_disable');
                    }
                });
                $('#guolutype3').find('.GLXXmain_10').each(function () {
                    if($(this).data('value') == 1){

                        $(this).addClass('GLXXmain_disable');
                    }
                });
                $('#guolutype4').find('.GLXXmain_10').each(function () {
                    if($(this).data('value') == 2 || $(this).data('value') == 4){
                        $(this).addClass('GLXXmain_disable');
                    }
                });
            }
        });



        $('#nq_selection').click(function () {

            var customer = $('#customer').val();
            var guolu_position = $('#guolu_position').val();
            var guolu_height = $('#guolu_height').val();
            var guolu_num = $('#guolu_num').val();
            var underground_unm = $('#underground_unm').val();
            var application = $('.GLXXmain_7.GLXXmain_check').data('value');
            var guolu_heating_type = $('#guolutype1').find('.GLXXmain_check').data('value');
            var is_condensate = $('input[name="is_condensate"]:checked ').val();
            var is_lownitrogen = $('input[name="is_lownitrogen"]:checked ').val();

            var project_id=<?php echo $project_id?>;
            //输入项检查
            if(customer == ''){
                layer.alert('客户名称不能为空', {icon: 5});
                return false;
            }
            if(is_condensate == '' || is_condensate == undefined){
                layer.alert('请选择锅炉是否冷凝', {icon: 5});
                return false;
            }
            if(is_lownitrogen == '' || is_lownitrogen == undefined){
                layer.alert('请选择锅炉是否低氮', {icon: 5});
                return false;
            }
            if(application == undefined){
                layer.alert('请选择锅炉用途', {icon: 5});
                return false;
            }
            if(guolu_heating_type == undefined){
                layer.alert('请选择锅炉形式', {icon: 5});
                return false;
            }

            if(guolu_num == undefined || guolu_num == '' || isNaN(guolu_num) || guolu_num<=0){
                layer.alert('请选择锅炉数量', {icon: 5});
                return false;
            }
            if(guolu_height == undefined || guolu_height == '' || isNaN(guolu_height) || guolu_height<=0){
                layer.alert('请输入正确的锅炉房高度', {icon: 5});
                return false;
            }

            var length = $("#nuanqi").children(".insertion").length;
            var all_build_type = '';
            var all_floor_low = '';
            var all_floor_high = '';
            var all_floor_height = '';
            var all_area = '';
            var all_type = '';
            var all_usetime_type = '';
            var flag=0;
            var flag_jump=false;
            for(i=0;i<length;i++){

                var thisE = $("#nuanqi").children(".insertion").eq(i);
                var heating_build_type = thisE.find('.heating_build_type').val();
                all_build_type = heating_build_type + '||' + all_build_type;
                var heating_floor_low = thisE.find('.heating_floor_low').val();

                all_floor_low = heating_floor_low + '||' + all_floor_low;
                var heating_floor_high = thisE.find('.heating_floor_high').val();

                all_floor_high = heating_floor_high + '||' + all_floor_high;
                if(flag==0){
                    if((heating_floor_high-heating_floor_low)>=16){
                            flag_jump = true;
                    }
                }
                var heating_floor_height = thisE.find('.heating_floor_height').val();

                all_floor_height = heating_floor_height + '||' + all_floor_height;
                var heating_area = thisE.find('.heating_area').val();

                all_area = heating_area + '||' + all_area;
                var heating_type = thisE.find('.heating_type').val();
                all_type = heating_type + '||' + all_type;
                var heating_usetime_type = thisE.find('.heating_usetime_type').val();
                all_usetime_type = heating_usetime_type + '||' + all_usetime_type;

                var fenqu = i + 1;
                if((heating_floor_high-heating_floor_low)<0){
                    layer.alert('分区'+ fenqu +'采暖楼层输入错误', {icon: 5});
                    return false;
                }
                if(heating_floor_low == undefined || heating_floor_low == '' || isNaN(heating_floor_low) || heating_floor_low<=0){
                    layer.alert('分区'+ fenqu +'采暖楼层不能为空', {icon: 5});
                    return false;
                }
                if(heating_floor_high == undefined || heating_floor_high == '' || isNaN(heating_floor_high) || heating_floor_high<=0){
                    layer.alert('分区'+ fenqu +'采暖楼层不能为空', {icon: 5});
                    return false;
                }
                if(heating_floor_height == undefined || heating_floor_height == '' || isNaN(heating_floor_height) || heating_floor_height<=0){
                    layer.alert('分区'+ fenqu +'单层高度不能为空', {icon: 5});
                    return false;
                }
                if(heating_area == undefined || heating_area == '' || isNaN(heating_area) || heating_area<=0){
                    layer.alert('分区'+ fenqu +'采暖面积不能为空', {icon: 5});
                    return false;
                }

            }

            <?php
                $str_flag="nq_selection";
            if($id){
                $str_flag="edit_nq_selection";
            ?>
            var id=<?php echo $id;?>;
            <?php
            }
            else{
            ?>
            var id = 0;
            <?php    } ?>

            if(flag_jump){
                var message = "建议您添加分区，是否添加？";
                dialogBox(message,
                    function () {
                    },
                    function(){
                        $(this).unbind('click');
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                customer : customer,
                                guolu_position : guolu_position,
                                guolu_height : guolu_height,
                                guolu_num : guolu_num,
                                underground_unm : underground_unm,
                                application : application,
                                heating_type : guolu_heating_type,
                                is_condensate : is_condensate,
                                is_lownitrogen : is_lownitrogen,
                                all_build_type : all_build_type,
                                all_floor_low  : all_floor_low,
                                all_floor_high  : all_floor_high,
                                all_floor_height  : all_floor_height,
                                id : id,
                                all_area  : all_area,
                                all_type  : all_type,
                                project_id:project_id,
                                all_usetime_type  : all_usetime_type
                            },
                            dataType :    'json',
                            url :         'selection_do.php?act=<?php echo $str_flag;?>',
                            success :     function(data){

                                layer.close(index);
                                var code = data.code;
                                var msg  = data.msg;
                                var historyid = data.historyid;

                                switch(code){
                                    case 1:
                                        location.href = 'selection_result.php?id=' + historyid;
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    });
            }else{
                $(this).unbind('click');
                var index = layer.load(0, {shade: false});
                $.ajax({
                    type        : 'POST',
                    data        : {
                        customer : customer,
                        guolu_position : guolu_position,
                        guolu_height : guolu_height,
                        guolu_num : guolu_num,
                        underground_unm : underground_unm,
                        application : application,
                        heating_type : guolu_heating_type,
                        is_condensate : is_condensate,
                        is_lownitrogen : is_lownitrogen,
                        all_build_type : all_build_type,
                        all_floor_low  : all_floor_low,
                        all_floor_high  : all_floor_high,
                        all_floor_height  : all_floor_height,
                        id : id,
                        project_id:project_id,
                        all_area  : all_area,
                        all_type  : all_type,
                        all_usetime_type  : all_usetime_type
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=<?php echo $str_flag;?>',
                    success :     function(data){

                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        var historyid = data.historyid;

                        switch(code){
                            case 1:
                                location.href = 'selection_result.php?id=' + historyid;
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            }




        });
        //热水选型
        $('#hotwater_selection').click(function () {
            var customer = $('#customer').val();
            var guolu_position = $('#guolu_position').val();
            var guolu_height = $('#guolu_height').val();
            var guolu_num = $('#guolu_num').val();
            var underground_unm = $('#underground_unm').val();
            var application = $('.GLXXmain_7.GLXXmain_check').data('value');
            var water_type = $('#guolutype2').find('.GLXXmain_check').data('value');
            var is_condensate = $('input[name="is_condensate"]:checked ').val();
            var is_lownitrogen = $('input[name="is_lownitrogen"]:checked ').val();
            var project_id=<?php echo $project_id?>;
            //输入项检查
            if(customer == ''){
                layer.alert('客户名称不能为空', {icon: 5});
                return false;
            }
            if(is_condensate == '' || is_condensate == undefined){
                layer.alert('请选择锅炉是否冷凝', {icon: 5});
                return false;
            }
            if(is_lownitrogen == '' || is_lownitrogen == undefined){
                layer.alert('请选择锅炉是否低氮', {icon: 5});
                return false;
            }
            if(application == undefined){
                layer.alert('请选择锅炉用途', {icon: 5});
                return false;
            }
            if(water_type == undefined){
                layer.alert('请选择锅炉形式', {icon: 5});
                return false;
            }

            if(guolu_num == undefined || guolu_num == '' || isNaN(guolu_num) || guolu_num<=0){
                layer.alert('请选择锅炉数量', {icon: 5});
                return false;
            }
            if(guolu_height == undefined || guolu_height == '' || isNaN(guolu_height) || guolu_height<=0){
                layer.alert('请输入正确的锅炉房高度', {icon: 5});
                return false;
            }
            var length = $("#water").children(".insertion").length;
            var all_usetime_type = '';
            var all_build_type = '';
            var all_buildattr_id = '';
            var all_attr_num = '';
            var all_heating_area = '';
            var all_timing_same_use = '';
            var all_hotwater_floor_low = '';

            var all_hotwater_floor_high =  '';

            var all_hotwater_floor_height =  '';
            var flag=0;
            var flag_jump=false;

            for(i=0;i<length;i++){

                var thisE = $("#water").children(".insertion").eq(i);
                var usetime_type = thisE.find('input[name="water_'+ parseInt(i ) +'"]:checked ').val();
                var fenqu = i + 1;
                all_usetime_type = usetime_type + '||' + all_usetime_type;
                if(usetime_type == 31){
                    var build_type = thisE.find('.alldaybuildtype').val();
                    all_build_type = build_type + '||' + all_build_type;
                    var heating_area = thisE.find('.full_heating_area').val();
                    all_heating_area = heating_area + '||' + all_heating_area;

                    var heating_floor_low = thisE.find('.heating_floor_low').val();
                    all_hotwater_floor_low = heating_floor_low + '||' + all_hotwater_floor_low;
                    var heating_floor_high = thisE.find('.heating_floor_high').val();
                    all_hotwater_floor_high = heating_floor_high + '||' + all_hotwater_floor_high;
                    var heating_floor_height = thisE.find('.heating_floor_height').val();
                    all_hotwater_floor_height = heating_floor_height + '||' + all_hotwater_floor_height;

                    if((heating_floor_high-heating_floor_low)<0){
                        layer.alert('分区'+ fenqu +'热水楼层输入错误', {icon: 5});
                        return false;
                    }
                    if(heating_floor_low == undefined || heating_floor_low == '' || isNaN(heating_floor_low) || heating_floor_low<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_high == undefined || heating_floor_high == '' || isNaN(heating_floor_high) || heating_floor_high<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_height == undefined || heating_floor_height == '' || isNaN(heating_floor_height) || heating_floor_height<=0){
                        layer.alert('分区'+ fenqu +'单层高度不能为空', {icon: 5});
                        return false;
                    }

                    if(flag==0){
                        if((heating_floor_high-heating_floor_low)>=16){
                            flag_jump = true;
                        }

                    }
                    var attrlength = thisE.find(".AlldayBuildTypediv").find(".hotwaterattr").length;
                    var buildattr_id = "";
                    var attr_num = '';
                    for(j=0;j<attrlength;j++){
                        var thisA = thisE.find(".AlldayBuildTypediv").find(".hotwaterattr").eq(j);
                        var this_buildattr_id = thisA.data("value");
                        buildattr_id = this_buildattr_id + '##' + buildattr_id;
                        var this_attr_num = thisA.val();
                        attr_num = this_attr_num + '##' + attr_num;
                        if(this_attr_num == '' || this_attr_num == undefined){
                            layer.msg('分区'+ fenqu +'属性值不能为空');
                            return false;
                        }

                    }
                    all_buildattr_id = buildattr_id + '||' + all_buildattr_id;
                    all_attr_num = attr_num + '||' + all_attr_num;
                    all_timing_same_use = '0' + '||' + all_timing_same_use;

                }else{
                    var build_type = thisE.find('.timingbuildtype').val();
                    all_build_type = build_type + '||' + all_build_type;

                    var heating_floor_low = thisE.find('.time_heating_floor_low').val();
                    all_hotwater_floor_low = heating_floor_low + '||' + all_hotwater_floor_low;
                    var heating_floor_high = thisE.find('.time_heating_floor_high').val();
                    all_hotwater_floor_high = heating_floor_high + '||' + all_hotwater_floor_high;
                    var heating_floor_height = thisE.find('.time_heating_floor_height').val();
                    all_hotwater_floor_height = heating_floor_height + '||' + all_hotwater_floor_height;
                    if(flag==0){
                        if((heating_floor_high-heating_floor_low)>=16){
                            flag_jump = true;
                        }

                    }
                    if((heating_floor_high-heating_floor_low)<0){
                        layer.alert('分区'+ fenqu +'热水楼层输入错误', {icon: 5});
                        return false;
                    }
                    if(heating_floor_low == undefined || heating_floor_low == '' || isNaN(heating_floor_low) || heating_floor_low<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_high == undefined || heating_floor_high == '' || isNaN(heating_floor_high) || heating_floor_high<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_height == undefined || heating_floor_height == '' || isNaN(heating_floor_height) || heating_floor_height<=0){
                        layer.alert('分区'+ fenqu +'单层高度不能为空', {icon: 5});
                        return false;
                    }
                    var timing_same_use = thisE.find('.timing_same_use').val();
                    if(timing_same_use == '' || timing_same_use == undefined){
                        layer.msg('分区'+ fenqu +'属性值不能为空');
                        return false;
                    }
                    all_timing_same_use = timing_same_use + '||' + all_timing_same_use;

                    var heating_area = thisE.find('.timing_heating_area').val();
                    all_heating_area = heating_area + '||' + all_heating_area;

                    var attrlength = thisE.find(".timing_1").find(".hotwaterattr").length;
                    var buildattr_id = "";
                    var attr_num = '';
                    for(j=0;j<attrlength;j++){
                        var thisA = thisE.find(".timing_1").find(".hotwaterattr").eq(j);
                        var this_buildattr_id = thisA.data("value");
                        buildattr_id = this_buildattr_id + '##' + buildattr_id;
                        var this_attr_num = thisA.val();
                        attr_num = this_attr_num + '##' + attr_num;
                        if(this_attr_num == '' || this_attr_num == undefined){
                            layer.msg('分区'+ fenqu +'属性值不能为空');
                            return false;
                        }
                    }
                    all_buildattr_id = buildattr_id + '||' + all_buildattr_id;
                    all_attr_num = attr_num + '||' + all_attr_num;
                }

                if(usetime_type == '' || usetime_type == undefined){
                    layer.msg('分区'+ fenqu +'供水类型不能为空');
                    return false;
                }

            }
//            alert(all_hotwater_floor_low);
//            alert(all_hotwater_floor_high);
//            alert(all_hotwater_floor_height);

            <?php
            $str_flag_hw="hotwater_selection";
            if($id){
                $str_flag_hw="edit_hotwater_selection";
            ?>
            var id=<?php echo $id;?>;
            <?php
            }
            else{
            ?>
            var id = 0;
            <?php    } ?>

            if(flag_jump){
                var message = "建议您添加分区，是否添加？";
                dialogBox(message,
                    function () {
                    },
                    function(){
                        $(this).unbind('click');
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                customer : customer,
                                guolu_position : guolu_position,
                                guolu_height : guolu_height,
                                guolu_num : guolu_num,
                                id : id,
                                underground_unm : underground_unm,
                                application : application,
                                water_type : water_type,
                                is_condensate : is_condensate,
                                is_lownitrogen : is_lownitrogen,
                                all_build_type : all_build_type,
                                all_buildattr_id  : all_buildattr_id,
                                all_attr_num  : all_attr_num,
                                all_usetime_type  : all_usetime_type,
                                all_heating_area : all_heating_area,
                                project_id:project_id,
                                all_hotwater_floor_low:all_hotwater_floor_low,
                                all_hotwater_floor_high:all_hotwater_floor_high,
                                all_hotwater_floor_height:all_hotwater_floor_height,
                                all_timing_same_use : all_timing_same_use
                            },
                            dataType :    'json',
                            url :         'selection_do.php?act=<?php echo $str_flag_hw;?>',
                            success :     function(data){
//                    alert(data);
                                layer.close(index);
                                var code = data.code;
                                var msg  = data.msg;
                                var historyid = data.historyid;

                                switch(code){
                                    case 1:
                                        location.href = 'selection_result.php?id=' + historyid;
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    });
            }else{
                $(this).unbind('click');
                var index = layer.load(0, {shade: false});
                $.ajax({
                    type        : 'POST',
                    data        : {
                        customer : customer,
                        guolu_position : guolu_position,
                        guolu_height : guolu_height,
                        guolu_num : guolu_num,
                        id : id,
                        underground_unm : underground_unm,
                        application : application,
                        water_type : water_type,
                        is_condensate : is_condensate,
                        is_lownitrogen : is_lownitrogen,
                        all_build_type : all_build_type,
                        all_buildattr_id  : all_buildattr_id,
                        all_attr_num  : all_attr_num,
                        all_usetime_type  : all_usetime_type,
                        all_heating_area : all_heating_area,
                        all_hotwater_floor_low:all_hotwater_floor_low,
                        all_hotwater_floor_high:all_hotwater_floor_high,
                        all_hotwater_floor_height:all_hotwater_floor_height,
                        all_timing_same_use : all_timing_same_use
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=<?php echo $str_flag_hw;?>',
                    success :     function(data){
//                    alert(data);
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        var historyid = data.historyid;

                        switch(code){
                            case 1:
                                location.href = 'selection_result.php?id=' + historyid;
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            }

        });

        //热水+采暖选型
        $('#nq_rs_selection').click(function () {


            var customer = $('#customer').val();
            var guolu_position = $('#guolu_position').val();
            var guolu_height = $('#guolu_height').val();
            var guolu_num = $('#guolu_num').val();
            var underground_unm = $('#underground_unm').val();
            var application = $('.GLXXmain_7.GLXXmain_check').data('value');
            var guolu_heating_type = $('#guolutype3').find('.GLXXmain_check').data('value');
            var water_type = $('#guolutype4').find('.GLXXmain_check').data('value');
            var is_condensate = $('input[name="is_condensate"]:checked ').val();
            var is_lownitrogen = $('input[name="is_lownitrogen"]:checked ').val();
            var project_id=<?php echo $project_id?>;

            //输入项检查
            if(customer == ''){
                layer.alert('客户名称不能为空', {icon: 5});
                return false;
            }
            if(is_condensate == '' || is_condensate == undefined){
                layer.alert('请选择锅炉是否冷凝', {icon: 5});
                return false;
            }
            if(is_lownitrogen == '' || is_lownitrogen == undefined){
                layer.alert('请选择锅炉是否低氮', {icon: 5});
                return false;
            }
            if(application == undefined){
                layer.alert('请选择锅炉用途', {icon: 5});
                return false;
            }
            if(guolu_heating_type == undefined){
                layer.alert('请选择采暖锅炉形式', {icon: 5});
                return false;
            }

            if(guolu_num == undefined || guolu_num == '' || isNaN(guolu_num) || guolu_num<=0){
                layer.alert('请选择锅炉数量', {icon: 5});
                return false;
            }
            if(guolu_height == undefined || guolu_height == '' || isNaN(guolu_height) || guolu_height<=0){
                layer.alert('请输入正确的锅炉房高度', {icon: 5});
                return false;
            }
            if(water_type == undefined || water_type == '' || isNaN(water_type) || water_type<=0){
                layer.alert('请选择热水锅炉形式', {icon: 5});
                return false;
            }

            //采暖
            var lengthnq = $("#WandN").children(".insertion").length;


            var all_build_type_nq = '';
            var all_floor_low = '';
            var all_floor_high = '';
            var all_floor_height = '';
            var all_area = '';
            var all_type = '';
            var all_usetime_type_nq = '';
            var flag=0;
            var flag_jump=false;
            for(k=0;k<lengthnq;k++){
                var thisE = $("#WandN").children(".insertion").eq(k);
                var heating_build_type = thisE.find('.heating_build_type').val();
//                alert(heating_build_type);
                all_build_type_nq = heating_build_type + '||' + all_build_type_nq;
                var heating_floor_low = thisE.find('.heating_floor_low').val();
                all_floor_low = heating_floor_low + '||' + all_floor_low;
                var heating_floor_high = thisE.find('.heating_floor_high').val();
                all_floor_high = heating_floor_high + '||' + all_floor_high;
                var heating_floor_height = thisE.find('.heating_floor_height').val();
                all_floor_height = heating_floor_height + '||' + all_floor_height;
                if(flag==0){
                    if((heating_floor_high-heating_floor_low)>=16){
                        flag_jump = true;
                    }

                }
                var heating_area = thisE.find('.heating_area').val();
                all_area = heating_area + '||' + all_area;
                var heating_type = thisE.find('.heating_type').val();
                all_type = heating_type + '||' + all_type;
                var heating_usetime_type = thisE.find('.heating_usetime_type').val();
                all_usetime_type_nq = heating_usetime_type + '||' + all_usetime_type_nq;

                var fenqu = k + 1;
                if((heating_floor_high-heating_floor_low)<0){
                    layer.alert('分区'+ fenqu +'采暖楼层输入错误', {icon: 5});
                    return false;
                }
                if(heating_floor_low == undefined || heating_floor_low == '' || isNaN(heating_floor_low) || heating_floor_low<=0){
                    layer.alert('分区'+ fenqu +'采暖楼层不能为空', {icon: 5});
                    return false;
                }
                if(heating_floor_high == undefined || heating_floor_high == '' || isNaN(heating_floor_high) || heating_floor_high<=0){
                    layer.alert('分区'+ fenqu +'采暖楼层不能为空', {icon: 5});
                    return false;
                }
                if(heating_floor_height == undefined || heating_floor_height == '' || isNaN(heating_floor_height) || heating_floor_height<=0){
                    layer.alert('分区'+ fenqu +'单层高度不能为空', {icon: 5});
                    return false;
                }
                if(heating_area == undefined || heating_area == '' || isNaN(heating_area) || heating_area<=0){
                    layer.alert('分区'+ fenqu +'采暖面积不能为空', {icon: 5});
                    return false;
                }
            }
            //热水
            var length = $("#nextWandN").children(".insertion").length;

            var all_usetime_type = '';
            var all_build_type = '';
            var all_buildattr_id = '';
            var all_attr_num = '';
            var all_heating_area = '';
            var all_timing_same_use = '';
            var all_hotwater_floor_low = '';

            var all_hotwater_floor_high =  '';

            var all_hotwater_floor_height =  '';

            for(i=0;i<length;i++){
                var thisE = $("#nextWandN").children(".insertion").eq(i);
                var usetime_type = thisE.find('input[name="water_'+ parseInt(i) +'"]:checked ').val();
                all_usetime_type = usetime_type + '||' + all_usetime_type;
                var fenqu=i+1;
                if(usetime_type == 31){
                    var build_type = thisE.find('.alldaybuildtype').val();
                    all_build_type = build_type + '||' + all_build_type;
                    var heating_floor_low = thisE.find('.heating_floor_low').val();
                    all_hotwater_floor_low = heating_floor_low + '||' + all_hotwater_floor_low;
                    var heating_floor_high = thisE.find('.heating_floor_high').val();
                    all_hotwater_floor_high = heating_floor_high + '||' + all_hotwater_floor_high;
                    var heating_floor_height = thisE.find('.heating_floor_height').val();
                    all_hotwater_floor_height = heating_floor_height + '||' + all_hotwater_floor_height;
                    if(flag==0){
                        if((heating_floor_high-heating_floor_low)>=16){
                            flag_jump = true;
                        }

                    }
                    if((heating_floor_high-heating_floor_low)<0){
                        layer.alert('分区'+ fenqu +'热水楼层输入错误', {icon: 5});
                        return false;
                    }
                    if(heating_floor_low == undefined || heating_floor_low == '' || isNaN(heating_floor_low) || heating_floor_low<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_high == undefined || heating_floor_high == '' || isNaN(heating_floor_high) || heating_floor_high<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_height == undefined || heating_floor_height == '' || isNaN(heating_floor_height) || heating_floor_height<=0){
                        layer.alert('分区'+ fenqu +'单层高度不能为空', {icon: 5});
                        return false;
                    }
                    var heating_area = thisE.find('.full_heating_area').val();
                    all_heating_area = heating_area + '||' + all_heating_area;
                    all_timing_same_use = '0' + '||' + all_timing_same_use;
                    var attrlength = thisE.find(".AlldayBuildTypediv").find(".hotwaterattr").length;
                    var buildattr_id = "";
                    var attr_num = '';
                    for(j=0;j<attrlength;j++){
                        var thisA = thisE.find(".AlldayBuildTypediv").find(".hotwaterattr").eq(j);
                        var this_buildattr_id = thisA.data("value");
                        buildattr_id = this_buildattr_id + '##' + buildattr_id;
                        var this_attr_num = thisA.val();
                        attr_num = this_attr_num + '##' + attr_num;
                        if(this_attr_num == '' || this_attr_num == undefined){
                            layer.msg('分区'+ fenqu +'属性值不能为空');
                            return false;
                        }

                    }
                    all_buildattr_id = buildattr_id + '||' + all_buildattr_id;
                    all_attr_num = attr_num + '||' + all_attr_num;
                }else{
                    var build_type = thisE.find('.timingbuildtype').val();
                    all_build_type = build_type + '||' + all_build_type;

                    var heating_floor_low = thisE.find('.time_heating_floor_low').val();
                    all_hotwater_floor_low = heating_floor_low + '||' + all_hotwater_floor_low;
                    var heating_floor_high = thisE.find('.time_heating_floor_high').val();
                    all_hotwater_floor_high = heating_floor_high + '||' + all_hotwater_floor_high;
                    var heating_floor_height = thisE.find('.time_heating_floor_height').val();
                    all_hotwater_floor_height = heating_floor_height + '||' + all_hotwater_floor_height;
                    if((heating_floor_high-heating_floor_low)<0){
                        layer.alert('分区'+ fenqu +'热水楼层输入错误', {icon: 5});
                        return false;
                    }
                    if(heating_floor_low == undefined || heating_floor_low == '' || isNaN(heating_floor_low) || heating_floor_low<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_high == undefined || heating_floor_high == '' || isNaN(heating_floor_high) || heating_floor_high<=0){
                        layer.alert('分区'+ fenqu +'热水楼层不能为空', {icon: 5});
                        return false;
                    }
                    if(heating_floor_height == undefined || heating_floor_height == '' || isNaN(heating_floor_height) || heating_floor_height<=0){
                        layer.alert('分区'+ fenqu +'单层高度不能为空', {icon: 5});
                        return false;
                    }
                    if(flag==0){
                        if((heating_floor_high-heating_floor_low)>=16){
                            flag_jump = true;
                        }

                    }
                    var timing_same_use = thisE.find('.timing_same_use').val();
                    if(timing_same_use == '' || timing_same_use == undefined){
                        layer.msg('分区'+ fenqu +'属性值不能为空');
                        return false;
                    }
                    all_timing_same_use = timing_same_use + '||' + all_timing_same_use;
                    var heating_area = thisE.find('.timing_heating_area').val();
                    all_heating_area = heating_area + '||' + all_heating_area;
                    var attrlength = thisE.find(".timing_1").find(".hotwaterattr").length;
                    var buildattr_id = "";
                    var attr_num = '';
                    for(j=0;j<attrlength;j++){
                        var thisA = thisE.find(".timing_1").find(".hotwaterattr").eq(j);
                        var this_buildattr_id = thisA.data("value");
                        buildattr_id = this_buildattr_id + '##' + buildattr_id;
                        var this_attr_num = thisA.val();
                        attr_num = this_attr_num + '##' + attr_num;
                        if(this_attr_num == '' || this_attr_num == undefined){
                            layer.msg('分区'+ fenqu +'属性值不能为空');
                            return false;
                        }

                    }
                    all_buildattr_id = buildattr_id + '||' + all_buildattr_id;
                    all_attr_num = attr_num + '||' + all_attr_num;
                }

                if(usetime_type == '' || usetime_type == undefined){
                    layer.msg('分区'+ fenqu +'供水类型不能为空');
                    return false;
                }
            }

            <?php
            $str_flag_all="nq_rs_selection";
            if($id){
                $str_flag_all="edit_nq_rs_selection";
            ?>
            var id=<?php echo $id;?>;
            <?php
            }
            else{
            ?>
            var id = 0;
            <?php    } ?>
            if(flag_jump){
                var message = "建议您添加分区，是否添加？";
                dialogBox(message,
                    function () {
                    },
                    function(){
                        $(this).unbind('click');
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                customer : customer,
                                guolu_position : guolu_position,
                                guolu_height : guolu_height,
                                guolu_num : guolu_num,
                                underground_unm : underground_unm,
                                application : application,
                                water_type : water_type,
                                heating_type : guolu_heating_type,
                                is_condensate : is_condensate,
                                is_lownitrogen : is_lownitrogen,
                                all_build_type_nq : all_build_type_nq,
                                all_floor_low  : all_floor_low,
                                all_floor_high  : all_floor_high,
                                id:id,
                                project_id:project_id,
                                all_floor_height  : all_floor_height,
                                all_area  : all_area,
                                all_type  : all_type,
                                all_usetime_type_nq  : all_usetime_type_nq,
                                all_build_type : all_build_type,
                                all_buildattr_id  : all_buildattr_id,
                                all_attr_num  : all_attr_num,
                                all_usetime_type  : all_usetime_type,
                                all_heating_area : all_heating_area,
                                all_hotwater_floor_low:all_hotwater_floor_low,
                                all_hotwater_floor_high:all_hotwater_floor_high,
                                all_hotwater_floor_height:all_hotwater_floor_height,
                                all_timing_same_use : all_timing_same_use
                            },
                            dataType :    'json',
                            url :         'selection_do.php?act=<?php echo $str_flag_all;?>',
                            success :     function(data){
//                    alert(data);
                                layer.close(index);
                                var code = data.code;
                                var msg  = data.msg;
                                var historyid = data.historyid;

                                switch(code){
                                    case 1:
                                        location.href = 'selection_result.php?id=' + historyid;
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    });
            }else{
                $(this).unbind('click');
                var index = layer.load(0, {shade: false});
                $.ajax({
                    type        : 'POST',
                    data        : {
                        customer : customer,
                        guolu_position : guolu_position,
                        guolu_height : guolu_height,
                        guolu_num : guolu_num,
                        underground_unm : underground_unm,
                        application : application,
                        water_type : water_type,
                        heating_type : guolu_heating_type,
                        is_condensate : is_condensate,
                        is_lownitrogen : is_lownitrogen,
                        all_build_type_nq : all_build_type_nq,
                        all_floor_low  : all_floor_low,
                        all_floor_high  : all_floor_high,
                        id:id,
                        project_id:project_id,
                        all_floor_height  : all_floor_height,
                        all_area  : all_area,
                        all_type  : all_type,
                        all_usetime_type_nq  : all_usetime_type_nq,
                        all_build_type : all_build_type,
                        all_buildattr_id  : all_buildattr_id,
                        all_attr_num  : all_attr_num,
                        all_usetime_type  : all_usetime_type,
                        all_heating_area : all_heating_area,
                        all_hotwater_floor_low:all_hotwater_floor_low,
                        all_hotwater_floor_high:all_hotwater_floor_high,
                        all_hotwater_floor_height:all_hotwater_floor_height,
                        all_timing_same_use : all_timing_same_use
                    },
                    dataType :    'json',
                    url :         'selection_do.php?act=<?php echo $str_flag_all;?>',
                    success :     function(data){
//                    alert(data);
                        layer.close(index);
                        var code = data.code;
                        var msg  = data.msg;
                        var historyid = data.historyid;

                        switch(code){
                            case 1:
                                location.href = 'selection_result.php?id='+ historyid;
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });
            }

        });
        $('#resetting').click(function () {
            location.href = 'selection.php?project_id=<?php echo $project_id?>';
        });
        $('#priorbtn').click(function () {

            $("#WandN").css('display','block');
            $("#wateradd").css('display','none');
            $("#nextWandN").css('display','none');


        });
    })
</script>

</body>
</html>