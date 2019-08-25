<?php
/**
 *  更换锅炉 selection_change.php
 *
 * @version       v0.01
 * @create time   2018/12/06
 * @update time   2018/12/06
 * @author        ozqowen
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

$TOP_FLAG = "selection";

$vender = isset($_GET['vender'])?safeCheck($_GET['vender']):0;
$id = isset($_GET['id'])?safeCheck($_GET['id']):0;//获取history的id
$project_id = isset($_GET['project_id'])?safeCheck($_GET['project_id']):0;//获取project的id
if(!empty($id)){$row = Selection_history::getInfoById($id);}
$plan_front_info = Selection_plan_front::getInfoByHistoryId($id);
$plan_front_id = null;
if (!empty($plan_front_info)) {
    $plan_front_id = $plan_front_info['id'];
}
$cainuan = $reshui = $caire = "display: none";
switch ($row['application']){
    case 0 : $cainuan = "display: block";break;
    case 1 : $reshui = "display: block";break;
    case 2 : $caire = "display: block";break;
}
//选型入口
$fromProject = isset($_GET['fromProject'])?safeCheck($_GET['fromProject']):0;
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
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="layer/layer.js"></script>
    <style>
        .GLXXmain_3{
            float:left
        }

    </style>

    <style>
        #step .step-wrap {
            width: 100%;
            position: relative;
        }
        #step .step-wrap .step-list{
            display: inline-block;
            width: 64px;
            text-align: center;
        }
        #step .step-wrap .step-list .step-num{
            display: inline-block;
            position: relative;
            width: 48px;
            height: 48px;
            background: rgba(4,166,254,0.2);
            border-radius: 50%;
        }
        #step .step-wrap .step-list .nums{
            margin: auto;
            width: 32px;
            height: 32px;
            background: #FFC80A;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            color: #fff;
            line-height: 32px;
        }
        #step .step-wrap .step-list .step-num .num-bg{
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 32px;
            height: 32px;
            background: #04A6FE;
            border-radius: 50%;
            text-align: center;
            font-size: 16px;
            color: #fff;
            line-height: 32px;
        }
        #step .step-wrap .step-list .step-name{
            font-size: 16px;
            color: #04A6FE;
        }
        #step .step-wrap .step-list .step-names{
            font-size: 16px;
            color: #293144;
            margin-top: 8px;
            display: block;
        }
        #step .step-wrap .step-line{
            display: inline-block;
            width: 290px;
            height: 2px;
            background: #04A6FE;
            margin: 0 -20px 42px -20px;
        }
        #step .step-wrap .step-lines{
            display: inline-block;
            width: 290px;
            height: 2px;
            background: #FFC80A;
            margin: 0 -20px 42px -20px;
        }
        #step .step-bg {
            width: 100%;
            height: 10px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            left: 0;
            background-color: lightgrey;
        }
        #step .step-progress {
            width: 18%;
            height: 10px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            left: 0;
            background-color: #04A6FE;
        }
        #step .step {
            display: inline-block;
        }
        #step .step-item {
            width: 33.33%;
            margin-bottom: 10px;
            display: inline-block;
            position: absolute;
            top: 0;
        }
        #step .step-item .setp-item-title {
            font-size: 14px;
            text-align: center;
        }
        #step .step-item.active .setp-item-num {
            background-color: #04A6FE;
        }
        #step .step .step-item .setp-item-num {
            line-height: 30px;
            margin-left: 44%;
        }
        #step .step .step-item:nth-child(1) {
            left: 0;
        }
        #step .step .step-item:nth-child(2) {
            left: 33.33%;
        }
        #step .step .step-item:nth-child(3) {
            left: 66.66%;
        }
        #step .step .setp-item-num {
            width: 30px;
            height: 30px;
            background-color: lightgrey;
            border-radius: 50%;
            text-align: center;
            padding: 3px;
        }

    </style>
</head>
<body class="body_2">
<?php include('top.inc.php');?>

<div class="manageHRWJCont_middle_left" style="margin-top: 30px">


    <ul>
        <a href="selection.php?fromProject=<?php echo $fromProject;?>&project_id=<?php echo $project_id;?>"><li>智能选型</li></a>
        <a href="selection_manual_new.php?fromProject=<?php echo $fromProject;?>&project_id=<?php echo $project_id;?>"><li >手动选型</li></a>
        <a href="selection_change.php?fromProject=<?php echo $fromProject;?>&project_id=<?php echo $project_id;?>"><li class="manage_liCheck">更换锅炉</li></a>

    </ul>
</div>
<div class="manageHRWJCont_middle_middle">

    <div id="step" style="margin-top: 30px">
        <div class="step-wrap">
            <div class="step-list">
                <div class="step-num">
                    <div class="num-bg">1</div>
                </div>
                <span class="step-name">选型</span>
            </div>
            <div class="step-line"></div>
            <?php if($row['status']==5){
                ?>
                <div class="step-list">
                    <div class="step-num">
                        <a href="selection_make_price.php?id=<?php echo $id;echo '&isUpdate=1'?>"><div class="num-bg">2</div></a>
                    </div>
                    <span class="step-name">报价</span>
                </div>
                <div class="step-line"></div>
                <div class="step-list">
                    <div class="step-num">
                        <a href="selection_plan_two.php?id=<?php echo $id;?><?php echo'&front_plan_id='?><?php echo $plan_front_id ?>"><div class="num-bg">3</div></a>
                    </div>
                    <span class="step-name">方案</span>
                </div>
            <?}else{?>
                <div class="step-list">
                    <div class="nums">2</div>
                    <span class="step-names">报价</span>
                </div>
                <div class="step-lines"></div>
                <div class="step-list">
                    <div class="nums">3</div>
                    <span class="step-names">方案</span>
                </div>
            <?php }?>
        </div>
    </div>
    <!--        --><?php //if(empty($row)){?>
    <div class="GLXXmain">
        <div class="GLXXmain_1">客户名称</div>
        <div class="GLXXmain_2">
            <input type="text" class="GLXXmain_3" id="customer" value="<?php if(!empty($row)) echo $row['customer']?>" ><button class="GLXXmain_4" id="resetting" >重置</button>
        </div>
        <div class="GLXXmain_1">锅炉是否冷凝</div>
        <div class="GLXXmain_2" id="is_condensate">
            <input type="radio" class="GLXXmain_5" name="is_condensate" value="15" <?php if(!empty($row)&&$row['is_condensate']!=16) echo "checked"; else echo "checked";?>><span class="GLXXmain_6">冷凝</span>
            <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_condensate" value="16" <?php if(!empty($row)&&$row['is_condensate']==16) echo "checked";?>><span class="GLXXmain_6">不冷凝</span>
        </div>
        <div class="GLXXmain_1">锅炉是否低氮</div>
        <div class="GLXXmain_2" id="is_lownitrogen">
            <input type="radio" class="GLXXmain_5" name="is_lownitrogen" value="17" <?php if(!empty($row)&&$row['is_lownitrogen']!=19) echo "checked";else echo "checked";?>><span class="GLXXmain_6">低氮30mg</span>
            <!--<input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="18"><span class="GLXXmain_6">低氮80mg</span>-->
            <input style="margin-left: 60px" type="radio" class="GLXXmain_5"  name="is_lownitrogen" value="19" <?php if(!empty($row)&&$row['is_lownitrogen']==19) echo "checked";?>><span class="GLXXmain_6">不低氮</span>
        </div>
        <?php if (!empty($row)){?>
            <div class="GLXXmain_1">锅炉用途</div>
            <div class="GLXXmain_2">
                <div data-id="nuanqi" data-value="0" class="GLXXmain_7 <?php if($row['application']==0) echo "GLXXmain_check";?>">采暖</div>
                <div data-id="water" data-value="1" class="GLXXmain_7 <?php if($row['application']==1) echo "GLXXmain_check";?>">热水</div>
                <div data-id="WandN" data-value="2" class="GLXXmain_7 <?php if($row['application']==2) echo "GLXXmain_check";?>">采暖和热水</div>
            </div>
        <?php }else{?>
            <div class="GLXXmain_1">锅炉用途</div>
            <div class="GLXXmain_2">
                <div data-id="nuanqi" data-value="0" class="GLXXmain_7 GLXXmain_check">采暖</div>
                <div data-id="water" data-value="1" class="GLXXmain_7 ">热水</div>
                <div data-id="WandN" data-value="2" class="GLXXmain_7 ">采暖和热水</div>
            </div>
        <?php }?>
        <!--采暖-->
        <?
        if($row['application']==0){
        if(!empty($row) and $row['application']==0){?>
        <div id="nuanqi" class="GLXXmain_8" style="<?php echo $cainuan;?>">
            <div id="guolutype1" style="display: block">
                <div class="GLXXmain_1">锅炉形式</div>
                    <div class="GLXXmain_11">
                        <?php
                        foreach ($ARRAY_selection_application['0']['type'] as $key => $val){
                            if($row['heating_type']==$key){
                                echo '<div class="GLXXmain_10 GLXXmain_check" data-value="'.$key.'">'.$val.'</div>';
                            }else{
                                echo '<div class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';
                            }
                        }

                        ?>
                        <!--清除浮动-->
                        <div class="clear"></div>
                    </div>
                <?php }else{?>
                    <div class="GLXXmain_11">
                        <?php
                        foreach ($ARRAY_selection_application['0']['type'] as $key => $val) {
                            echo '<div class="GLXXmain_10" data-value="' . $key . '">' . $val . '</div>';
                        }
                        ?>
                        <!--清除浮动-->
                        <div class="clear"></div>
                    </div>
                <?php }?>
            </div>

            <?php
            if(!empty($row) and $row['application']==0){
            if($row['heating_type']==3){?>
                <div id="heating_dom" style="display: block">
                    <div class="GLXXmain_1">采暖形式</div>
                    <div class="GLXXmain_2">
                        <select type="text" class="GLXXmain_3 heating_type" id="heating_type" style="width: 344px">
                            <?php
                            foreach ($ARRAY_selection_heating_type as $key => $val){
                                $checked = $row["hm_heating_type"] ==$key?"selected='selected'":"";
                                echo "<option value='$key' $checked>$val</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="board_dom" style="display: block">
                    <div class="GLXXmain_1">原有板换总功率</div>
                    <div class="GLXXmain_2">
                        <input type="number" class="GLXXmain_3" id="board_power" value="<?echo $row['board_power'];?>">
                    </div>
                </div>
            <div id="guolu_type" >
            <?php }
            $guolu_style = explode(',',$row['guolu_id']);
            $guolu_num = explode(',',$row['guolu_num']);
            foreach ($guolu_style as $k=>$value){
            ?>
                <div class="insertion">
                    <div  class="GLXXmain_1">锅炉型号</div>
                    <div class="GLXXmain_2">
                        <select type="text" class="GLXXmain_3 new-select" id="guolu_type" style="width: 244px">
                            <option value="-1">请选择厂家</option>
                            <?php
                            $changjia = Dict::getListByParentid(1);
                            $guolu_attr = Guolu_attr::getInfoById($guolu_style[$k]);
                            if(!empty($guolu_attr)){$guolu_vender = $guolu_attr['vender'];}else{
                                $guolu_vender=-1;
                            }
                            if(!empty($changjia)){
                                foreach($changjia as $item){
                                    $id1 = $item['id'];
                                    $name  = $item['name'];
//                                    echo '<option value="'.$id1.'">'.$name.'</option>';
                                    ?>
                                    <option value="<?php echo $item['id']?>" <?php if($guolu_vender == $item['id']) echo 'selected';?>><?php echo $item['name']?></option>
                                    <?php
                                }
                            }else {
                                echo "没有找到合适的型号";
                            }?>
                        </select>
                        <select type="text" class="GLXXmain_3 guolu_version" id="guolu_type_list" style="width: 344px">
                            <option value="0">请选择锅炉型号</option>
                            <?php
                            $guolu_list = Guolu_attr::getList(0, '', 0, '', '', '', '');
                            foreach ($guolu_list as $row){
                                ?>
                                <option value="<?php echo $row['guolu_id']?>" <?php if($guolu_style[$k] == $row['guolu_id']) echo 'selected';?>><?php echo $row['guolu_version']?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div style="padding-top: 3px">
                            <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                            <input type="number" class="GLXXmain_12" id="guolu_num" value="<?php echo $guolu_num[$k];?>" >
                            <div class="GLXXmain_14" style="color: #686868;">台</div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="GLXXmain_16">
                    <span class="addgl" style="display: block"> + 添加锅炉</span>
                    <?php if(count($guolu_style)>1){?>
                        <span class="mougl" style="display: block"> - 删除锅炉</span>
                    <?php }else{?>
                        <span class="mougl" style="display: none"> - 删除锅炉</span>
                    <?php }?>
                </div>
            </div>
            <button class="GLXXmain_17" id="nq_selection">下一步</button>
        </div>
        <?php }else{?>
        <div id="heating_dom" style="display: none">
            <div class="GLXXmain_1">采暖形式</div>
            <div class="GLXXmain_2">
                <select type="text" class="GLXXmain_3 heating_type" id="heating_type" style="width: 344px">
                    <?php
                    foreach ($ARRAY_selection_heating_type as $key => $val){
                        echo '<option value="'.$key.'">'.$val.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>


        <div id="board_dom" style="display: none">
            <div class="GLXXmain_1">原有板换总功率</div>
            <div class="GLXXmain_2">
                <input type="number" class="GLXXmain_3" id="board_power" >
            </div>
        </div>
        <div id="guolu_type" >
            <div class="insertion">
                <div  class="GLXXmain_1">锅炉型号</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3 guolu_version" id="guolu_type_list" style="width: 344px">
                        <option value="0">暂无合适锅炉</option>
                    </select>
                    <div style="padding-top: 3px">
                        <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                        <input type="number" class="GLXXmain_12" id="guolu_num" >
                        <div class="GLXXmain_14" style="color: #686868;">台</div>
                    </div>
                </div>
            </div>
            <div class="GLXXmain_16">
                <span class="addgl" style="display: block" > + 添加锅炉</span>
                <span class="mougl" style="display: none" > - 删除锅炉</span>
            </div>
        </div>
        <button class="GLXXmain_17" id="nq_selection">下一步</button>
    </div>
<?php }
        }elseif ($row['application']==1){?>
    <!--r热水-->
    <?if(!empty($row) and $row['application']==1){?>
    <div id="water" class="GLXXmain_8" style="<?php echo $reshui;?>">
        <div id="guolutype2" style="display: block">
            <div class="GLXXmain_1">锅炉形式</div>
            <div class="GLXXmain_11">
                <?php
                foreach ($ARRAY_selection_application['1']['type'] as $key => $val){
                    if($row['heating_type']==$key){
                        echo '<div class="GLXXmain_10 GLXXmain_check" data-value="'.$key.'">'.$val.'</div>';
                    }else{
                        echo '<div class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';
                    }
                }

                ?>
                <!--清除浮动-->
                <div class="clear"></div>
            </div>
        </div>
        <?php
        $guolu_style = explode(',',$row['guolu_id']);
        $guolu_num = explode(',',$row['guolu_num']);
        foreach ($guolu_style as $k=>$value){
        ?>
        <div id="guolu_type" >
            <div class="insertion">
                <div class="GLXXmain_1">锅炉型号</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3 new-select" id="guolu_type" style="width: 244px">
                        <option value="-1">请选择厂家</option>
                        <?php
                        $changjia = Dict::getListByParentid(1);
                        $guolu_attr = Guolu_attr::getInfoById($guolu_style[$k]);
                        if(!empty($guolu_attr)){$guolu_vender = $guolu_attr['vender'];}else{
                            $guolu_vender=-1;
                        }


                        if(!empty($changjia)){
                            foreach($changjia as $item){
                                $id1 = $item['id'];
                                $name  = $item['name'];
//                                    echo '<option value="'.$id1.'">'.$name.'</option>';
                                ?>
                                <option value="<?php echo $item['id']?>" <?php if($guolu_vender == $item['id']) echo 'selected';?>><?php echo $item['name']?></option>
                                <?php
                            }
                        }else {
                            echo "没有找到合适的型号";
                        }?>
                    </select>
                    <select type="text" class="GLXXmain_3 guolu_version" id="guolu_type_list" style="width: 344px">
                        <option value="0">请选择锅炉型号</option>
                        <?php
                        $guolu_list = Guolu_attr::getList(0, '', 0, '', '', '', '');
                        foreach ($guolu_list as $row){
                            ?>
                            <option value="<?php echo $row['guolu_id']?>" <?php if($guolu_style[$k] == $row['guolu_id']) echo 'selected';?>><?php echo $row['guolu_version']?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <div style="padding-top: 3px">
                        <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                        <input type="number" class="GLXXmain_12" id="guolu_num" value="<?php echo $guolu_num[$k];?>" >
                        <div class="GLXXmain_14" style="color: #686868;">台</div>
                    </div>
                </div>
            </div>
            <?php
        }
            }
            else{?>
            <div class="GLXXmain_11">
                <?php
                foreach ($ARRAY_selection_application['1']['type'] as $key => $val) {
                    echo '<div class="GLXXmain_10" data-value="' . $key . '">' . $val . '</div>';
                }
                ?>
                <!--清除浮动-->
                <div class="clear"></div>
            </div>

        </div>
        <div id="guolu_type" >
            <div class="insertion">
                <div  class="GLXXmain_1">锅炉型号</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3" id="guolu_type_list" style="width: 344px">
                    </select>
                    <div style="padding-top: 3px">
                        <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                        <input type="number" class="GLXXmain_12" id="guolu_num">
                        <div class="GLXXmain_14" style="color: #686868;">台</div>
                    </div>
                </div>
            </div>
            <?php }?>
    <div class="GLXXmain_16">
        <span class="addgl" style="display: block"> + 添加锅炉</span>
        <?php if(count($guolu_style)>1){?>
            <span class="mougl" style="display: block"> - 删除锅炉</span>
        <?php }else{?>
            <span class="mougl" style="display: none"> - 删除锅炉</span>
        <?php }?>
    </div>
        </div>
        <button class="GLXXmain_17" id="hotwater_selection">下一步</button>
    </div>
    <!--热水加采暖-->
    <?}elseif(!empty($row) and $row['application']==2){?>
    <div id="WandN" class="GLXXmain_8" style="<?php echo $caire;?>">
        <div id="guolutype2" style="display: block">
            <div class="GLXXmain_1">锅炉形式</div>
            <div class="GLXXmain_11">
                <?php
                foreach ($ARRAY_selection_application['2']['type'] as $key => $val){
                    if($row['heating_type']==$key){
                        echo '<div class="GLXXmain_10 GLXXmain_check" data-value="'.$key.'">'.$val.'</div>';
                    }else{
                        echo '<div class="GLXXmain_10" data-value="'.$key.'">'.$val.'</div>';
                    }
                }

                ?>
                <!--清除浮动-->
                <div class="clear"></div>
            </div>
        </div>

        <?php if($row['heating_type'] == 3) { ?>
            <div id="heating_dom" style="display: block">
                <div class="GLXXmain_1">采暖形式</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3 heating_type" id="heating_type" style="width: 344px">
                        <?php
                        foreach ($ARRAY_selection_heating_type as $key => $val) {
                            $checked = $row["hm_heating_type"] == $key ? "selected='selected'" : "";
                            echo "<option value='$key' $checked>$val</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div id="board_dom" style="display: block">
                <div class="GLXXmain_1">原有板换总功率</div>
                <div class="GLXXmain_2">
                    <input type="number" class="GLXXmain_3" id="board_power" value="<?
                    echo $row['board_power']; ?>">
                </div>
            </div>
        <?php }
        $guolu_style = explode(',',$row['guolu_id']);
        $guolu_num = explode(',',$row['guolu_num']);
        foreach ($guolu_style as $k=>$value){
        ?>
        <div id="guolu_type">
            <div class="insertion">
                <div class="GLXXmain_1">锅炉型号</div>
                <div class="GLXXmain_2">
                    <select type="text" class="GLXXmain_3 new-select" id="guolu_type" style="width: 244px">
                        <option value="-1">请选择厂家</option>
                        <?php
                        $changjia = Dict::getListByParentid(1);
                        $guolu_attr = Guolu_attr::getInfoById($guolu_style[$k]);
                        if(!empty($guolu_attr)){$guolu_vender = $guolu_attr['vender'];}else{
                            $guolu_vender=-1;
                        }
                        if(!empty($changjia)){
                            foreach($changjia as $item){
                                $id1 = $item['id'];
                                $name  = $item['name'];
//                                    echo '<option value="'.$id1.'">'.$name.'</option>';
                                ?>
                                <option value="<?php echo $item['id']?>" <?php if($guolu_vender == $item['id']) echo 'selected';?>><?php echo $item['name']?></option>
                                <?php
                            }
                        }else {
                            echo "没有找到合适的型号";
                        }?>
                    </select>
                    <select type="text" class="GLXXmain_3 guolu_version" id="guolu_type_list" style="width: 344px">
                        <option value="0">请选择锅炉型号</option>
                        <?php
                        $guolu_list = Guolu_attr::getList(0, '', 0, '', '', '', '');
                        foreach ($guolu_list as $row){
                            ?>
                            <option value="<?php echo $row['guolu_id']?>" <?php if($guolu_style[$k] == $row['guolu_id']) echo 'selected';?>><?php echo $row['guolu_version']?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <div style="padding-top: 3px">
                        <div class="GLXXmain_14"
                             style="margin-left: 50px;padding-right: 10px;color: #686868;"></div>
                        <input type="number" class="GLXXmain_12" id="guolu_num" value="<?php echo $guolu_num[$k];?>">
                        <div class="GLXXmain_14" style="color: #686868;">台</div>
                    </div>
                </div>
            </div>
            <?php }?>
            <div class="GLXXmain_16">
                <span class="addgl" style="display: block"> + 添加锅炉</span>
                <?php if(count($guolu_style)>1){?>
                <span class="mougl" style="display: block"> - 删除锅炉</span>
                <?php }else{?>
                <span class="mougl" style="display: none"> - 删除锅炉</span>
                <?php }?>
            </div>
        </div>
        <button class="GLXXmain_17" id="nextbtn">下一步</button>
    </div>

</div>
<?php
}else{?>
    <div id="guolutype2" style="<?php echo "display:block";?>">
        <div class="GLXXmain_1">锅炉形式</div>
        <div class="GLXXmain_11">
            <?php
            foreach ($ARRAY_selection_application['2']['type'] as $key => $val) {
                echo '<div class="GLXXmain_10" data-value="' . $key . '">' . $val . '</div>';
            }
            ?>
            <!--清除浮动-->
            <div class="clear"></div>
        </div>
    </div>
    <div id="heating_dom" style="display: none">
        <div class="GLXXmain_1">采暖形式</div>
        <div class="GLXXmain_2">
            <select type="text" class="GLXXmain_3 heating_type" id="heating_type" style="width: 344px">
                <?php
                foreach ($ARRAY_selection_heating_type as $key => $val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div id="board_dom" style="display: none">
        <div class="GLXXmain_1">原有板换总功率</div>
        <div class="GLXXmain_2">
            <input type="number" class="GLXXmain_3" id="board_power" >
        </div>
    </div>

    <div id="guolu_type" >
        <div class="insertion">
            <div  class="GLXXmain_1">锅炉型号</div>
            <div class="GLXXmain_2">
                <select type="text" class="GLXXmain_3" id="guolu_type_list" style="width: 344px">
                </select>
                <div style="padding-top: 3px">
                    <div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>
                    <input type="number" class="GLXXmain_12" id="guolu_num">
                    <div class="GLXXmain_14" style="color: #686868;" >台</div>
                </div>
            </div>
        </div>
        <div class="GLXXmain_16">
            <span class="addgl" style="display: block" > + 添加锅炉</span>
            <span class="mougl" style="display: none" > - 删除锅炉</span>
        </div>
    </div>
    <button class="GLXXmain_17" id="nextbtn" >下一步</button>
    </div>

    </div>
<?php }?>



</div>

<script>

    $(function () {

        $(document).on('change','.new-select',function(){
            var vender = $(this).val();
            var is_condensate = $("input[name='is_condensate']:checked").val();
            var is_lownitrogen = $("input[name='is_lownitrogen']:checked").val();
            var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
            var application = $("#" + domId).find('.GLXXmain_check').data('value');
            var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');
            var listDom = $(this).parent().parent().find(".guolu_version");
            listDom.html("<option value='0'>请选择锅炉型号</option>");
            $.ajax({
                type: 'POST',
                data: {
                    is_condensate: is_condensate,
                    is_lownitrogen: is_lownitrogen,
                    application: application,
                    guolu_use: guolu_use,
                    vender: vender
                },
                dataType: 'json',
                url: 'selection_do.php?act=get_guolu_list',
                success: function (data) {
                    var code = data.code;
                    var msg = data.msg;
                    var guolu_list = data.data;
                    switch (code) {
                        case 1:
                            // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数

                            var html = "";
                            for (var i = 0; i < guolu_list.length; i++) {
                                var name = guolu_list[i].guolu_version;
                                var value = guolu_list[i].guolu_id;
                                var new_opt = new Option(name, value);

                                html += "<option value='" + value + "'>" + name + "</option>";
                            }

                            listDom.html(html);
                            break;

                        default:
                            break;
                    }

                },
                error: function () {
                    alert("请求失败");
                }
            });
        });

        $('.indexMtwo_1').hover(function () {
            $(this).find('.mouseset').slideDown('fast');
            var name = $(this).find('.indexMtwo_1_2').text();
            $(this).find('.mouseset').find('span').text(name);
        }, function () {
            $(this).find('.mouseset').slideUp(100);
        });

        $('#resetting').click(function () {
            location.href = 'selection_change.php';
        });

        // 添加锅炉
        $('.addgl').click(function () {
            var newht = $(this).parent().parent().find('.insertion').html();
            <?php
            $vender = Dict::getListByParentid(1);
            $guolu_list = Guolu_attr::getList(0, '', 0, '', '', '', '');
            ?>
            var vender_list = new Array();
            var all_guolu_list = new Array();
            all_guolu_list = <?php echo json_encode($guolu_list) ?>;
            vender_list = <?php echo json_encode($vender) ?>;
            var html1 = "";
            for (var j = 0; j < vender_list.length; j++) {
                var name = vender_list[j]['name'];
                var value = vender_list[j]['id'];
                html1 += "<option value='" + value + "'>" + name + "</option>";
            }
            var html = "";
            for (var i = 0; i < all_guolu_list.length; i++) {
                var name = all_guolu_list[i]['guolu_version'];
                var value = all_guolu_list[i]['guolu_id'];
                html += "<option value='" + value + "'>" + name + "</option>";
            }
            var NHtml =
                '<div  class="insertion">'+
                ' <div  class="GLXXmain_1">锅炉型号</div>'+
                '<div class="GLXXmain_2">'+
                '<select type="text" class="GLXXmain_3 new-select" id="guolu_type" style="width: 244px">'+
                '<option value="-1">请选择厂家</option>' +html1+
                '</select>'+
                '<select type="text" class="GLXXmain_3 guolu_version" id="guolu_type_list" style="width: 344px">'+
                '<option value="0">请选择锅炉型号</option>'+html+
                '</select>'+
                '<div style="padding-top: 3px">'+
                '<div class="GLXXmain_14" style="margin-left: 50px;padding-right: 10px;color: #686868;"> </div>'+
                '<input type="number" class="GLXXmain_12" id="guolu_num">'+
                '<div class="GLXXmain_14" style="color: #686868;" >台</div>'+
                '</div>'+
                '</div>';
            $(this).parent().parent().find('.insertion:nth-last-child(2)').after(NHtml);
            $(this).parent().find('.mougl').css('display', 'block');
        });
        // 删除锅炉
        $('.mougl').click(function () {

            var len = $(this).parent().parent().find(".insertion").length;
            var len = parseFloat(len);
                $(this).parent().parent().find('.insertion:nth-last-child(2)').remove();
                len--;
            if (len <= 1) {
                $(this).css('display', 'none');
            }

        });

        // 采暖选型
        $('.GLXXmain_17').click(function () {

            var customer = $('#customer').val();

            var is_condensate = $('input[name="is_condensate"]:checked ').val();
            var is_lownitrogen = $('input[name="is_lownitrogen"]:checked ').val();

            //输入项检查
            if (customer == '') {
                layer.alert('客户名称不能为空', {icon: 5});
                return false;
            }

            if (is_condensate == '' || is_condensate == undefined) {
                layer.alert('请选择锅炉是否冷凝', {icon: 5});
                return false;
            }
            if (is_lownitrogen == '' || is_lownitrogen == undefined) {
                layer.alert('请选择锅炉是否低氮', {icon: 5});
                return false;
            }
            // var guolu_num = $('#guolu_num').val();
            // alert(guolu_num);
            // if(guolu_num == undefined || guolu_num == ''){
            //     layer.msg('请选择锅炉数量');
            //     return false;
            // }

            var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');
            var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
            var application = $("#" + domId).find('.GLXXmain_check').data('value');

            if (guolu_use == undefined) {
                layer.alert('请选择锅炉用途', {icon: 5});
                return false;
            }

            if (application == undefined) {
                layer.alert('请选择锅炉形式', {icon: 5});
                return false;
            }



            var heating_type = 0;
            var area_num = 0;
            var board_power = 0;


            url = "selection_change_info.php";



            if(guolu_use==0 && application==3)
            {
                heating_type = $("#"+domId).find("#heating_type").val();
//                    area_num = $("#"+domId).find("#area_num").val();
                board_power = $("#"+domId).find("#board_power").val();

                var url = "selection_result_change_fuji.php";
            }

            if(guolu_use==2 && application==3)
            {
                heating_type = $("#"+domId).find("#heating_type").val();
//                    area_num = $("#"+domId).find("#area_num").val();
                board_power = $("#"+domId).find("#board_power").val();
                var url = "selection_result_change_fuji.php";


            }

            var guoluArr = new Array();
            var guoluNumArr = new Array();

            $("#" + domId).find(".insertion").each(function(){

                var currentNum = $(this).find("#guolu_num").val();
                if(currentNum==0){
                    layer.alert('锅炉数量不能为0', {icon: 5});
                    exit;
                }

                if(currentNum=="" &&parseFloat(currentNum).toString() == "NaN"){
                    layer.alert('请填写锅炉数量', {icon: 5});
                    exit;
                }

                if(currentNum!=""&&parseFloat(currentNum)>0)
                {
                    if($(this).find("#guolu_type_list").val()==0){
                        layer.alert('请选择锅炉型号', {icon: 5});
                        exit;
                    }
                    guoluArr.push($(this).find("#guolu_type_list").val());
                    guoluNumArr.push(currentNum);
                }
            });

            if(guoluArr.length<=0||guoluArr==0)
            {
                layer.alert('请至少选择一个锅炉', {icon: 5});
                return false;
            }
            var id = <?php echo $id;?>;
            var project_id = <?php echo $project_id;?>;
            var guoluStr = guoluArr.join(",");
            var guoluNumStr = guoluNumArr.join(",");

            var index = layer.load(0, {shade: false});
            $.ajax({
                type: 'POST',
                data: {
                    id : id,
                    project_id:project_id,
                    customer: customer,
                    guolu_use: guolu_use,
                    application: application,
                    heating_type: heating_type,
                    is_condensate: is_condensate,
                    is_lownitrogen: is_lownitrogen,
                    board_power: board_power,
                    guoluStr: guoluStr,
                    guoluNumStr: guoluNumStr,
                },
                dataType: 'json',
                url: 'selection_do.php?act=change_guolu',
                success: function (data) {
                    layer.close(index);
                    var code = data.code;
                    var msg = data.msg;
                    var historyid = data.historyid;

                    url = url+"?id="+historyid;
                    switch (code) {
                        case 1:
                            location.href = url;
                            break;
                        default:
                            layer.alert(msg, {icon: 5});
                    }
                }
            });
        });


        $('#is_condensate').change(function () {
            getGuoluList();
        });

        $('#is_lownitrogen').change(function () {
            getGuoluList();
        });
    });

</script>

<script type="text/javascript">


    $(".GLXXmain_7").click(function(){
       location.href='selection_change.php';

    });

    $(".GLXXmain_10").click(function(){
        $(this).siblings().removeClass("GLXXmain_check");
        $(this).addClass("GLXXmain_check");
        domChange();
        var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
        var venderDom = $("#" + domId).find(".GLXXmain_2").find(".new-select");
        $.ajax({
            type: 'POST',
            data: {
                vender : 1
            },
            dataType: 'json',
            url: 'selection_do.php?act=get_vender',
            success: function (data) {
                var code = data.code;
                var msg = data.msg;
                var guolu_list = data.data;
                switch (code) {
                    case 1:
                        // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数

                        var html = "<option value='-1'>请选择厂家</option>";

                        for (var i = 0; i < guolu_list.length; i++) {
                            var name = guolu_list[i].name;
                            var value = guolu_list[i].id;

                            html += "<option value='" + value + "'>" + name + "</option>";
                        }

                        venderDom.html(html);
                        break;

                    default:
                        break;
                }
            }
        });
        getGuoluList();
        var a = $('.GLXXmain_10.GLXXmain_check').data('value');
        var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
        var application = $("#" + domId).find('.GLXXmain_check').data('value');
        var id = <?php echo $id;?>;
        $.ajax({
            type: 'POST',
            data: {
                id:id,
                application:application
            },
            dataType: 'json',
            url: 'selection_do.php?act=edit_change',
            success(data){
                var code = data.code;
                var msg = data.msg;
                var type = data.type
                if(code==1 && type ==3){
                    location.href = "selection_change_old.php?id="+id;
                }
            }
        });
    });

    //
    //        $(document).ready(function(){
    //            getGuoluList();
    //        });
</script>

<script type="text/javascript">

    function domChange()
    {
        var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');

        var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
        var application = $("#" + domId).find('.GLXXmain_check').data('value');



        if(guolu_use==0)
        {
            if(application==3)
            {
                $("#"+domId).find("#heating_dom").show();
//                    $("#"+domId).find("#area_dom").show();
                $("#"+domId).find("#board_dom").show();

                $("#"+domId).find(".GLXXmain_17").html("下一步");
            }
            else
            {
                $("#"+domId).find("#heating_dom").hide();
//                    $("#"+domId).find("#area_dom").hide();
                $("#"+domId).find(".GLXXmain_17").html("下一步");
                $("#"+domId).find("#board_dom").hide();

            }
        }


        if(guolu_use==1)
        {
            $("#"+domId).find(".GLXXmain_17").html("下一步");

        }

        if(guolu_use==2)
        {
            if(application==3)
            {
                $("#"+domId).find("#heating_dom").show();
//                    $("#"+domId).find("#area_dom").show();
                $("#"+domId).find("#board_dom").show();
                $("#"+domId).find(".GLXXmain_17").html("下一步");

            }
            else
            {
                $("#"+domId).find("#heating_dom").hide();
//                    $("#"+domId).find("#area_dom").hide();
                $("#"+domId).find("#board_dom").hide();
                $("#"+domId).find(".GLXXmain_17").html("下一步");

            }
        }

    }


    // 获取符合条件的锅炉列表
    function getGuoluList() {
        var is_condensate = $("input[name='is_condensate']:checked").val();
        var is_lownitrogen = $("input[name='is_lownitrogen']:checked").val();

        var guolu_use = $('.GLXXmain_7.GLXXmain_check').data('value');

        var domId = $('.GLXXmain_7.GLXXmain_check').data('id');
        var application = $("#" + domId).find('.GLXXmain_check').data('value');

        var select=[];
        $("select[class='new-select'] option:selected").each(function () {
            select.push($(this).val());
        });
        if(select==''){
            var vender=-1;
        }
        var listDom = $("#" + domId).find(".GLXXmain_2").find("#guolu_type_list");
        listDom.html("<option value='0'>请选择锅炉型号</option>");


        if (application == undefined) return false;
        if (guolu_use == undefined) return false;

        //获取符合条件的锅炉列表，目前假设获取全部符合要求的列表时其他不需要的参数设为0或空
        $.ajax({
            type: 'POST',
            data: {
                is_condensate: is_condensate,
                is_lownitrogen: is_lownitrogen,
                application: application,
                guolu_use: guolu_use,
                vender: vender
            },
            dataType: 'json',
            url: 'selection_do.php?act=get_guolu_list',
            success: function (data) {
                var code = data.code;
                var msg = data.msg;
                var guolu_list = data.data;
                switch (code) {
                    case 1:
                        // 在这里先清空已选的锅炉类型，再更新锅炉类型单选框可选个数

                        var html = "";
                        for (var i = 0; i < guolu_list.length; i++) {
                            var name = guolu_list[i].guolu_version;
                            var value = guolu_list[i].guolu_id;
                            var new_opt = new Option(name, value);

                            html += "<option value='" + value + "'>" + name + "</option>";
                        }

                        listDom.html(html);
                        break;

                    default:
                        break;
                }
            }
        });
    }
</script>

</body>
</html>