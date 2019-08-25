<?php
/**
 * 项目处理  project_do.php
 *
 * @version       v0.01
 * @create time   2018/6/29
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('web_init.php');
require_once('usercheck.php');

//require_once '../lib/common/PHPWord/PHPWord/Autoloader.php';
error_reporting(0);
$act = safeCheck($_GET['act'], 0);
switch($act) {

    // 手动选型 根据是否冷凝，是否低氮，厂家选择锅炉类型
    case 'get_guolu_list':
        $is_condensate  = safeCheck($_POST['is_condensate']);   //锅炉是否冷凝，
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);  //锅炉是否低氮，

        $guolu_use      = isset($_POST['guolu_use'])?safeCheck($_POST['guolu_use']):-1;       //锅炉用途，0 采暖， 1 热水， 2 采暖+热水
        $application    = isset($_POST['application'])?safeCheck($_POST['application']):-1;  ;     //锅炉应用形式
        $guolu_type = 0;  //锅炉是否低氮，


        if($guolu_use!=-1 && $application!=-1)
        {
            if( ($guolu_use == 0 and $application == 4) or ($guolu_use == 2 and $application == 4) ){
                $guolu_type = 13; //真空锅炉
            }elseif( ($guolu_use == 0 and $application == 1) or ($guolu_use == 2 and $application == 1)
                or ($guolu_use == 1 and ($application == 2 or $application == 4) ) ){
                $guolu_type = 12; //承压锅炉
            }else{
                $guolu_type = 11; //常压锅炉
            }
        }




        $vender = safeCheck($_POST['vender'],0);

        try {
            $rs = Guolu_attr::getList(0,'', 0, $vender, $guolu_type, $is_condensate, $is_lownitrogen);
           // print_r($rs);
            if (count($rs) > 0) {
                echo action_msg_data("查询成功", 1, $rs);
              //  return  json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'rs' => $rs));
            }else{
                echo action_msg_data("没有该条件下的锅炉类型", 101, $rs);
            }

        } catch (MyException $e) {
            echo $e->jsonMsg();
          //  return action_msg_data("查询错误", 101, $rs);
        }
        break;

    case 'nq_selection'://采暖选型
        $customer = safeCheck($_POST['customer'], 0);
        $guolu_position = safeCheck($_POST['guolu_position']);
        $guolu_height = safeCheck($_POST['guolu_height']);
        $underground_unm = $_POST['underground_unm'] ? safeCheck($_POST['underground_unm']) : 0;
        $guolu_num = safeCheck($_POST['guolu_num']);
        $application = safeCheck($_POST['application']);
        $heating_type = safeCheck($_POST['heating_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);

        $project_id = safeCheck($_POST['project_id']);

        $all_build_type = safeCheck($_POST['all_build_type'], 0);
        $all_floor_low = safeCheck($_POST['all_floor_low'], 0);
        $all_floor_high = safeCheck($_POST['all_floor_high'], 0);
        $all_floor_height = safeCheck($_POST['all_floor_height'], 0);
        $all_area = safeCheck($_POST['all_area'], 0);
        $all_type = safeCheck($_POST['all_type'], 0);
        $all_usetime_type = safeCheck($_POST['all_usetime_type'], 0);

        $all_build_type = trim($all_build_type, '||');
        $all_build_typeA = explode('||', $all_build_type);

        $all_floor_low = trim($all_floor_low, '||');
        $all_floor_lowA = explode('||', $all_floor_low);

        $all_floor_high = trim($all_floor_high, '||');
        $all_floor_highA = explode('||', $all_floor_high);

        $all_floor_height = trim($all_floor_height, '||');
        $all_floor_heightA = explode('||', $all_floor_height);

        $all_area = trim($all_area, '||');
        $all_areaA = explode('||', $all_area);

        $all_type = trim($all_type, '||');
        $all_typeA = explode('||', $all_type);

        $all_usetime_type = trim($all_usetime_type, '||');
        $all_usetime_typeA = explode('||', $all_usetime_type);
        $nowtime = time();
        try {
            $attrsHistory = array(
                "customer" => $customer,
                "guolu_position" => $guolu_position,
                "guolu_height" => $guolu_height,
                "guolu_num" => $guolu_num,
                "underground_unm" => $underground_unm,
                "application" => $application,
                "heating_type" => $heating_type,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "user" => $USERId,
                "addtime" => $nowtime,
                "status" => 0,
                "project_id" => $project_id,
                "lastupdate" => $nowtime
            );
            $historyid = Selection_history::add($attrsHistory);
            if ($historyid > 0) {
                for ($i = 0; $i < count($all_build_typeA); $i++) {
                    if ($all_build_typeA[$i]) {
                        $heatingArray = array(
                            "history_id" => $historyid,
                            "build_type" => $all_build_typeA[$i],
                            "floor_low" => $all_floor_lowA[$i],
                            "floor_high" => $all_floor_highA[$i],
                            "floor_height" => $all_floor_heightA[$i],
                            "area" => $all_areaA[$i],
                            "type" => $all_typeA[$i],
                            "usetime_type" => $all_usetime_typeA[$i],
                            "addtime" => $nowtime
                        );
                        Selection_heating_attr::add($heatingArray);
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $historyid));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'hotwater_selection'://热水选型
        $customer = safeCheck($_POST['customer'], 0);
        $guolu_position = safeCheck($_POST['guolu_position']);
        $guolu_height = safeCheck($_POST['guolu_height']);
        $underground_unm = $_POST['underground_unm'] ? safeCheck($_POST['underground_unm']) : 0;
        $guolu_num = safeCheck($_POST['guolu_num']);
        $application = safeCheck($_POST['application']);
        $water_type = safeCheck($_POST['water_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);

        $all_build_type = safeCheck($_POST['all_build_type'], 0);
        $all_buildattr_id = safeCheck($_POST['all_buildattr_id'], 0);
        $all_attr_num = safeCheck($_POST['all_attr_num'], 0);
        $all_usetime_type = safeCheck($_POST['all_usetime_type'], 0);
        $all_heating_area = safeCheck($_POST['all_heating_area'], 0);
        $all_timing_same_use = safeCheck($_POST['all_timing_same_use'], 0);

        $all_hotwater_floor_low = safeCheck($_POST['all_hotwater_floor_low'], 0);
        $all_hotwater_floor_high= safeCheck($_POST['all_hotwater_floor_high'], 0);
        $all_hotwater_floor_height = safeCheck($_POST['all_hotwater_floor_height'], 0);
        $project_id = safeCheck($_POST['project_id']);
        $all_hotwater_floor_low = trim($all_hotwater_floor_low, '||');
        $all_hotwater_floor_low_value = explode('||', $all_hotwater_floor_low);
        $all_hotwater_floor_high = trim($all_hotwater_floor_high, '||');
        $all_hotwater_floor_high_value = explode('||', $all_hotwater_floor_high);
        $all_hotwater_floor_height = trim($all_hotwater_floor_height, '||');
        $all_hotwater_floor_height_value = explode('||', $all_hotwater_floor_height);

        $all_build_type = trim($all_build_type, '||');
        $all_build_typeA = explode('||', $all_build_type);

        $all_buildattr_id = trim($all_buildattr_id, '||');
        $all_buildattr_idA = explode('||', $all_buildattr_id);

        $all_attr_num = trim($all_attr_num, '||');
        $all_attr_numA = explode('||', $all_attr_num);

        $all_usetime_type = trim($all_usetime_type, '||');
        $all_usetime_typeA = explode('||', $all_usetime_type);

        $all_heating_area = trim($all_heating_area, '||');
        $all_heating_areaA = explode('||', $all_heating_area);
        
        $all_timing_same_use = trim($all_timing_same_use, '||');
        $all_timing_same_useA = explode('||', $all_timing_same_use);
        $nowtime = time();
        try {
            $attrsHistory = array(
                "customer" => $customer,
                "guolu_position" => $guolu_position,
                "guolu_height" => $guolu_height,
                "guolu_num" => $guolu_num,
                "underground_unm" => $underground_unm,
                "application" => $application,
                "water_type" => $water_type,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "user" => $USERId,
                "status" => 0,
                "project_id"=>$project_id,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime
            );
            $historyid = Selection_history::add($attrsHistory);
            if ($historyid > 0) {
                for ($i = 0; $i < count($all_usetime_typeA); $i++) {
                    if ($all_usetime_typeA[$i]) {
                        $buildattr_id = trim($all_buildattr_idA[$i], '##');
                        $buildattr_idA = explode('##', $buildattr_id);

                        $attr_num = trim($all_attr_numA[$i], '##');
                        $attr_numA = explode('##', $attr_num);
                        for ($j = 0; $j < count($buildattr_idA); $j++) {
                            if ($buildattr_idA[$j]) {
                                $hotArray = array(
                                    "history_id" => $historyid,
                                    "param_id" => count($all_usetime_typeA) - $i,
                                    "build_type" => $all_build_typeA[$i],
                                    "heating_area" => $all_heating_areaA[$i],
                                    "use_type" => $all_usetime_typeA[$i],
                                    "buildattr_id" => $buildattr_idA[$j],
                                    "attr_num" => $attr_numA[$j],
                                    "same_use" => ($all_timing_same_useA[$i] / 100),
                                    "floor_low" => $all_hotwater_floor_low_value[$i],
                                    "floor_high" => $all_hotwater_floor_high_value[$i],
                                    "floor_height" => $all_hotwater_floor_height_value[$i],
                                    "addtime" => $nowtime
                                );
                                Selection_hotwater_attr::add($hotArray);
                            }
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $historyid));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'nq_rs_selection'://采暖+热水选型
        $customer = safeCheck($_POST['customer'], 0);
        $guolu_position = safeCheck($_POST['guolu_position']);
        $guolu_height = safeCheck($_POST['guolu_height']);
        $underground_unm = $_POST['underground_unm'] ? safeCheck($_POST['underground_unm']) : 0;
        $guolu_num = safeCheck($_POST['guolu_num']);
        $application = safeCheck($_POST['application']);
        $heating_type = safeCheck($_POST['heating_type']);
        $water_type = safeCheck($_POST['water_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);
        //采暖
        $all_build_type_nq = safeCheck($_POST['all_build_type_nq'], 0);
        $all_floor_low = safeCheck($_POST['all_floor_low'], 0);
        $all_floor_high = safeCheck($_POST['all_floor_high'], 0);
        $all_floor_height = safeCheck($_POST['all_floor_height'], 0);
        $all_area = safeCheck($_POST['all_area'], 0);
        $all_type = safeCheck($_POST['all_type'], 0);
        $all_usetime_type_nq = safeCheck($_POST['all_usetime_type_nq'], 0);
        $project_id = safeCheck($_POST['project_id']);
        $all_build_type_nq = trim($all_build_type_nq, '||');
        $all_build_type_nqA = explode('||', $all_build_type_nq);

        $all_usetime_type_nq = trim($all_usetime_type_nq, '||');
        $all_usetime_type_nqA = explode('||', $all_usetime_type_nq);

        $all_floor_low = trim($all_floor_low, '||');
        $all_floor_lowA = explode('||', $all_floor_low);

        $all_floor_high = trim($all_floor_high, '||');
        $all_floor_highA = explode('||', $all_floor_high);

        $all_floor_height = trim($all_floor_height, '||');
        $all_floor_heightA = explode('||', $all_floor_height);

        $all_area = trim($all_area, '||');
        $all_areaA = explode('||', $all_area);

        $all_type = trim($all_type, '||');
        $all_typeA = explode('||', $all_type);
        //热水
        $all_build_type = safeCheck($_POST['all_build_type'], 0);
        $all_buildattr_id = safeCheck($_POST['all_buildattr_id'], 0);
        $all_attr_num = safeCheck($_POST['all_attr_num'], 0);
        $all_usetime_type = safeCheck($_POST['all_usetime_type'], 0);
        $all_heating_area = safeCheck($_POST['all_heating_area'], 0);
        $all_timing_same_use = safeCheck($_POST['all_timing_same_use'], 0);

        $all_hotwater_floor_low = safeCheck($_POST['all_hotwater_floor_low'], 0);
        $all_hotwater_floor_high= safeCheck($_POST['all_hotwater_floor_high'], 0);
        $all_hotwater_floor_height = safeCheck($_POST['all_hotwater_floor_height'], 0);

        $all_hotwater_floor_low = trim($all_hotwater_floor_low, '||');
        $all_hotwater_floor_low_value = explode('||', $all_hotwater_floor_low);
        $all_hotwater_floor_high = trim($all_hotwater_floor_high, '||');
        $all_hotwater_floor_high_value = explode('||', $all_hotwater_floor_high);
        $all_hotwater_floor_height = trim($all_hotwater_floor_height, '||');
        $all_hotwater_floor_height_value = explode('||', $all_hotwater_floor_height);



        $all_build_type = trim($all_build_type, '||');
        $all_build_typeA = explode('||', $all_build_type);

        $all_buildattr_id = trim($all_buildattr_id, '||');
        $all_buildattr_idA = explode('||', $all_buildattr_id);

        $all_attr_num = trim($all_attr_num, '||');
        $all_attr_numA = explode('||', $all_attr_num);

        $all_usetime_type = trim($all_usetime_type, '||');
        $all_usetime_typeA = explode('||', $all_usetime_type);

        $all_heating_area = trim($all_heating_area, '||');
        $all_heating_areaA = explode('||', $all_heating_area);
        
        $all_timing_same_use = trim($all_timing_same_use, '||');
        $all_timing_same_useA = explode('||', $all_timing_same_use);
        $nowtime = time();
        try {
            $attrsHistory = array(
                "customer" => $customer,
                "guolu_position" => $guolu_position,
                "guolu_height" => $guolu_height,
                "guolu_num" => $guolu_num,
                "underground_unm" => $underground_unm,
                "application" => $application,
                "heating_type" => $heating_type,
                "water_type" => $water_type,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "user" => $USERId,
                "status" => 0,
                "project_id"=>$project_id,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime
            );
            $historyid = Selection_history::add($attrsHistory);
            if ($historyid > 0) {
                //采暖
                for ($k = 0; $k < count($all_build_type_nqA); $k++) {
                    if ($all_build_type_nqA[$k]) {
                        $heatingArray = array(
                            "history_id" => $historyid,
                            "build_type" => $all_build_type_nqA[$k],
                            "floor_low" => $all_floor_lowA[$k],
                            "floor_high" => $all_floor_highA[$k],
                            "floor_height" => $all_floor_heightA[$k],
                            "area" => $all_areaA[$k],
                            "type" => $all_typeA[$k],
                            "usetime_type" => $all_usetime_type_nqA[$k],
                            "addtime" => $nowtime
                        );
                        Selection_heating_attr::add($heatingArray);
                    }
                }
                //热水
                for ($i = 0; $i < count($all_usetime_typeA); $i++) {
                    if ($all_usetime_typeA[$i]) {
                        $buildattr_id = trim($all_buildattr_idA[$i], '##');
                        $buildattr_idA = explode('##', $buildattr_id);

                        $attr_num = trim($all_attr_numA[$i], '##');
                        $attr_numA = explode('##', $attr_num);
                        for ($j = 0; $j < count($buildattr_idA); $j++) {
                            if ($buildattr_idA[$j]) {
                                $hotArray = array(
                                    "history_id" => $historyid,
                                    "param_id" => count($all_usetime_typeA) - $i,
                                    "heating_area" => $all_heating_areaA[$i],
                                    "build_type" => $all_build_typeA[$i],
                                    "use_type" => $all_usetime_typeA[$i],
                                    "buildattr_id" => $buildattr_idA[$j],
                                    "attr_num" => $attr_numA[$j],
                                    "same_use" => ($all_timing_same_useA[$i] / 100),
                                    "floor_low" => $all_hotwater_floor_low_value[$i],
                                    "floor_high" => $all_hotwater_floor_high_value[$i],
                                    "floor_height" => $all_hotwater_floor_height_value[$i],
                                    "addtime" => $nowtime
                                );
                                Selection_hotwater_attr::add($hotArray);
                            }
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $historyid));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'guolu_selected'://选择锅炉方案
        $id = safeCheck($_POST['id']);
        $guolu_id = safeCheck($_POST['guolu_id']);
        $guolu_num = safeCheck($_POST['guolu_num']);
        $total_exchange_q = $_POST['total_exchange_q'];
        $guolu_attr = $_POST['guolu_attr'];
        $water_box_attr = $_POST['str_x'];
        $water_box_attr = ltrim($water_box_attr, '||');
        try {
            $attrsHistory = array(
                "guolu_id" => $guolu_id,
                "guolu_num" => $guolu_num,
                "total_exchange_q" => $total_exchange_q,
                "guolu_attr" => $guolu_attr,
                "guolu_context" => "",
                "water_box_attr"=>$water_box_attr,
                "lastupdate" => time()
            );
            Selection_history::update($id, $attrsHistory);
            Selection_fuji::delByHistoryId($id);
            echo action_msg("保存成功！", 1);
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'fuji_selected'://选择辅机方案
        $id = safeCheck($_POST['id']);
        $heating = safeCheck($_POST['heating']);
        $hotwater = safeCheck($_POST['hotwater']);
        $application = safeCheck($_POST['application']);

        $guolu_info=Selection_history::getInfoById($id);
        /*******************************采暖备注****************************************/

         $heating_burner_context="";
        $heating_hdys_contxet="";
        $heating_water_box_context="";
        $pipeline_pump_context="";
        $heating_pump_context="";
        $water_pump_context="";
        $dirt_separater_context="";
        $board_context="";
        $chimney_context="";
        $diversity_water_context="";
        $water_pump_control_context="";
        $powe_box_context="";


        /*********************************热水备注*****************************************/
        $hw_burner_context="";
        $hw_hdys_context="";
        $hw_water_box_context="";
        $hw_pipeline_pump_context="";
        $hw_dirt_separater_context="";
        $hw_water_board_context="";
        $hw_water_pump_control_context="";
        $hw_chimney_context="";
        $hw_powe_box_context="";
        $hw_hot_water_box_context="";
        $hw_hotwater_pump_context="";
        $hw_dynamic_water_pump_context="";
        $heating_pump_control_context="";
        //辅机------------------------采暖----------------------------
        //燃烧器
        $heating_burner_id = safeCheck($_POST['heating_burner_id']);
        $heating_burner_count = safeCheck($_POST['heating_burner_count']);
        $heating_burner_name = safeCheck($_POST['heating_burner_name'], 0);
        if(isset($_POST['heating_burner_context']))
        $heating_burner_context = safeCheck($_POST['heating_burner_context'], 0);
        //软水机
        $heating_hdys_id = safeCheck($_POST['heating_hdys_id']);
        $heating_hdys_count = safeCheck($_POST['heating_hdys_count']);
        $heating_hdys_name = safeCheck($_POST['heating_hdys_name'],0);
        if(isset($_POST['heating_hdys_contxet']))
        $heating_hdys_contxet = safeCheck($_POST['heating_hdys_contxet'], 0);
        //水箱
        $heating_water_box_id = safeCheck($_POST['heating_water_box_id']);
        $heating_water_box_count = safeCheck($_POST['heating_water_box_count']);
        $heating_water_box_name = safeCheck($_POST['heating_water_box_name'], 0);
        if(isset($_POST['heating_water_box_context']))
        $heating_water_box_context = safeCheck($_POST['heating_water_box_context'], 0);
        //除污器
        $dirt_separater_id = safeCheck($_POST['dirt_separater_id'], 0);
        $dirt_separater_count = safeCheck($_POST['dirt_separater_count'], 0);
        $dirt_separater_name = safeCheck($_POST['dirt_separater_name'], 0);
        $dirt_separater_check = safeCheck($_POST['dirt_separater_check'], 0);
        if(isset($_POST['dirt_separater_context']))
        $dirt_separater_context = safeCheck($_POST['dirt_separater_context'], 0);
        //锅炉循环泵
        $pipeline_pump_id = safeCheck($_POST['pipeline_pump_id'],0);
        $pipeline_pump_count = safeCheck($_POST['pipeline_pump_count'],0);
        $pipeline_pump_name = safeCheck($_POST['pipeline_pump_name'], 0);
        if(isset($_POST['pipeline_pump_context']))
        $pipeline_pump_context = safeCheck($_POST['pipeline_pump_context'], 0);
       //采暖循环泵
        $heating_pump_id = safeCheck($_POST['heating_pump_id'], 0);
        $heating_pump_count = safeCheck($_POST['heating_pump_count'], 0);
        $heating_pump_name = safeCheck($_POST['heating_pump_name'], 0);
        $heating_pump_check = safeCheck($_POST['heating_pump_check'], 0);
        if(isset($_POST['heating_pump_context'])){
            $heating_pump_context = safeCheck($_POST['heating_pump_context'], 0);
        }


        //补水泵
        $water_pump_id = safeCheck($_POST['water_pump_id'], 0);
        $water_pump_count = safeCheck($_POST['water_pump_count'], 0);
        $water_pump_check = safeCheck($_POST['water_pump_check'], 0);
        $water_pump_name = safeCheck($_POST['water_pump_name'], 0);
        if(isset($_POST['heating_pump_context'])){
            $heating_pump_context = safeCheck($_POST['heating_pump_context'], 0);
        }
        if(isset($_POST['water_pump_context'])){
            $water_pump_context = safeCheck($_POST['water_pump_context'], 0);
        }

        //板换
        $board_value = safeCheck($_POST['board_value'], 0);
        $board_count = safeCheck($_POST['board_count'], 0);
        $board_name = safeCheck($_POST['board_name'], 0);
        $board_check = safeCheck($_POST['board_check'], 0);
        if(isset($_POST['board_context'])){
            $board_context = safeCheck($_POST['board_context'], 0);
        }

        $diversity_water_num = safeCheck($_POST['diversity_water_num']);
        $diversity_water_length = safeCheck($_POST['diversity_water_length']);
        $diversity_water_diameter = safeCheck($_POST['diversity_water_diameter']);
        if(isset($_POST['diversity_water_context']))
        $diversity_water_context = safeCheck($_POST['diversity_water_context'],0);

        $chimney_height = safeCheck($_POST['chimney_height']);
        $chimney_diameter = safeCheck($_POST['chimney_diameter']);
        if(isset($_POST['chimney_context']))
        $chimney_context = safeCheck($_POST['chimney_context'],0);

        $heating_pump_control = safeCheck($_POST['heating_pump_control'], 0);
        if(isset($_POST['heating_pump_control_context']) or !empty($_POST['heating_pump_control_context']))
        $heating_pump_control_context= safeCheck($_POST['heating_pump_control_context'], 0);


        $heating_powe_box = safeCheck($_POST['heating_powe_box'],0);
        if(isset($_POST['powe_box_context']))
        $powe_box_context = safeCheck($_POST['powe_box_context'],0);

        //辅机---------------------------热水-----------------------------
       //燃烧器
        $water_burner_id = safeCheck($_POST['water_burner_id']);
        $water_burner_count = safeCheck($_POST['water_burner_count']);
        $water_burner_name = safeCheck($_POST['water_burner_name'], 0);
        if(isset($_POST['hw_burner_context']))
        $hw_burner_context = safeCheck($_POST['hw_burner_context'], 0);
        //软水机
        $water_hdys_id = safeCheck($_POST['water_hdys_id']);
        $water_hdys_count = safeCheck($_POST['water_hdys_count']);
        $water_hdys_name = safeCheck($_POST['water_hdys_name'], 0);
        $hw_hdys_check= safeCheck($_POST['hw_hdys_check'], 0);
        if(isset($_POST['hw_hdys_context']))
        $hw_hdys_context = safeCheck($_POST['hw_hdys_context'], 0);
        //软化水箱
        $water_water_box_id = safeCheck($_POST['water_water_box_id']);
        $water_water_box_count = safeCheck($_POST['water_water_box_count']);
        $water_water_box_name = safeCheck($_POST['water_water_box_name'], 0);
        $water_box_check_hw= safeCheck($_POST['water_box_check_hw'], 0);
        if(isset($_POST['hw_water_box_context']))
        $hw_water_box_context = safeCheck($_POST['hw_water_box_context'], 0);
        //除污器
        $water_dirt_separater_id = safeCheck($_POST['water_dirt_separater_id'], 0);
        $water_dirt_separater_count = safeCheck($_POST['water_dirt_separater_count'], 0);
        $water_dirt_separater_name = safeCheck($_POST['water_dirt_separater_name'], 0);
        $water_dirt_separater_check = safeCheck($_POST['water_dirt_separater_check'], 0);
        if(isset($_POST['hw_dirt_separater_context']))
        $hw_dirt_separater_context = safeCheck($_POST['hw_dirt_separater_context'], 0);
        //锅炉循环泵
        $water_pipeline_pump_id = safeCheck($_POST['water_pipeline_pump_id']);
        $water_pipeline_pump_count = safeCheck($_POST['water_pipeline_pump_count']);
        $water_pipeline_pump_name = safeCheck($_POST['water_pipeline_pump_name'], 0);
        if(isset($_POST['hw_pipeline_pump_context']))
        $hw_pipeline_pump_context = safeCheck($_POST['hw_pipeline_pump_context'], 0);
        //保温热水箱
        $hot_water_box_id = safeCheck($_POST['hot_water_box_id'],0);
        $hot_water_box_count = safeCheck($_POST['hot_water_box_count'],0);
        $hot_water_box_name = safeCheck($_POST['hot_water_box_name'], 0);
        if(isset($_POST['hw_hot_water_box_context']))
        $hw_hot_water_box_context = safeCheck($_POST['hw_hot_water_box_context'], 0);
        //热水循环泵
        $hotwater_pump_id = safeCheck($_POST['hotwater_pump_id'], 0);
        $hotwater_pump_count = safeCheck($_POST['hotwater_pump_count'], 0);
        $hotwater_pump_name = safeCheck($_POST['hotwater_pump_name'], 0);

        if(isset($_POST['hw_hotwater_pump_context']))
            $hw_hotwater_pump_context = safeCheck($_POST['hw_hotwater_pump_context'], 0);
        if(isset($_POST['hw_water_pump_control_context']))
        $hw_water_pump_control_context = safeCheck($_POST['hw_water_pump_control_context'], 0);
       //补水泵
        $hotwater_water_pump_id = safeCheck($_POST['hotwater_water_pump_id'], 0);
        $hotwater_water_pump_count = safeCheck($_POST['hotwater_water_pump_count'], 0);
        $hotwater_water_pump_name = safeCheck($_POST['hotwater_water_pump_name'], 0);
        $hotwater_water_pump_check = safeCheck($_POST['hotwater_water_pump_check'], 0);
        if(isset($_POST['hw_dynamic_water_pump_context']))
        $hw_dynamic_water_pump_context = safeCheck($_POST['hw_dynamic_water_pump_context'], 0);

       //板换
        $water_board_value = safeCheck($_POST['water_board_value'], 0);
        $water_board_count = safeCheck($_POST['water_board_count'], 0);
        $water_board_name = safeCheck($_POST['water_board_name'], 0);
        $water_board_check = safeCheck($_POST['water_board_check'], 0);
        if(isset($_POST['hw_water_board_context']))
            $hw_water_board_context = safeCheck($_POST['hw_water_board_context'], 0);




        $water_pump_control = safeCheck($_POST['water_pump_control'], 0);
        $water_powe_box = safeCheck($_POST['water_powe_box'],0);
        if(isset($_POST['hw_powe_box_context']))
        $hw_powe_box_context = safeCheck($_POST['hw_powe_box_context'],0);

        $chimney_height2 = safeCheck($_POST['chimney_height2']);
        $chimney_diameter2 = safeCheck($_POST['chimney_diameter2']);
        if(isset($_POST['hw_chimney_context']))
        $hw_chimney_context = safeCheck($_POST['hw_chimney_context'],0);


        //-----------------------获取辅机的计算参数----------------------------
        //----------采暖--------
        //软水器
        $heating_hdys_parameter="";
        $heating_water_box_parameter="";
        $dirt_separater_param="";
        $pipeline_pump_param="";
        $heating_pump_param="";
        $water_pump_param="";
        if(isset($_POST['heating_hdys_parameter']))
        $heating_hdys_parameter=safeCheck($_POST['heating_hdys_parameter'],0);
        //水箱
        if(isset($_POST['heating_water_box_parameter']))
        $heating_water_box_parameter=safeCheck($_POST['heating_water_box_parameter'],0);
        //除污器
        if(isset($_POST['dirt_separater_parameter']))
        $dirt_separater_param=safeCheck($_POST['dirt_separater_parameter'],0);

        //锅炉循环泵
        if(isset($_POST['pipeline_pump_parameter']))
        $pipeline_pump_param=safeCheck($_POST['pipeline_pump_parameter'],0);
        //采暖循环泵
        if(isset($_POST['heating_pump_parameter']))
        $heating_pump_param=safeCheck($_POST['heating_pump_parameter'],0);
        //补水泵
        if(isset($_POST['water_pump_parameter']))
        $water_pump_param=safeCheck($_POST['water_pump_parameter'],0);

        //-------------------热水-----------------
        $water_hdys_parameter="";
        $water_water_box_parameter="";
        $hot_water_box_parameter="";
        $water_dirt_separater_param="";
        $water_pipeline_pump_param="";
        $hotwater_pump_param="";
        $hotwater_water_pump_param="";

        //软水器
        if(isset($_POST['water_hdys_parameter']))
        $water_hdys_parameter=safeCheck($_POST['water_hdys_parameter'],0);
        //软化水箱
        if(isset($_POST['water_water_box_parameter']))
        $water_water_box_parameter=safeCheck($_POST['water_water_box_parameter'],0);
        //保温水箱
        if(isset($_POST['hot_water_box_parameter']))
        $hot_water_box_parameter=safeCheck($_POST['hot_water_box_parameter'],0);
        //除污器
        if(isset($_POST['water_dirt_separater_parameter']))
        $water_dirt_separater_param=safeCheck($_POST['water_dirt_separater_parameter'],0);
        //锅炉循环泵
        if(isset($_POST['water_pipeline_pump_parameter']))
        $water_pipeline_pump_param=safeCheck($_POST['water_pipeline_pump_parameter'],0);
        //热水循环泵
        if(isset($_POST['hotwater_pump_parameter']))
        $hotwater_pump_param=safeCheck($_POST['hotwater_pump_parameter'],0);
        //补水泵
        if(isset($_POST['hotwater_water_pump_parameter']))
        $hotwater_water_pump_param=safeCheck($_POST['hotwater_water_pump_parameter'],0);



        //------------------辅机选择---------------------
        //燃烧机
        $burner_check = safeCheck($_POST['burner_check']);
        //软水器
        $hdys_check = safeCheck($_POST['hdys_check']);
        //水箱
        $water_box_check = safeCheck($_POST['water_box_check'],0);
        //锅炉循环泵
        $pipeline_pump_check = safeCheck($_POST['pipeline_pump_check']);
        //采暖循环泵
        //$heating_pump_check = safeCheck($_POST['heating_pump_check']);
        //补水泵
        //$water_pump_check = safeCheck($_POST['water_pump_check']);
        //除污器
        //$dirt_separater_check = safeCheck($_POST['dirt_separater_check']);
        //采暖板换
        //$board_data_check = safeCheck($_POST['board_data_check']);
        //热水板换
        //$water_board_check = safeCheck($_POST['water_board_check']);

        //钢制烟囱
        $chimney_check = safeCheck($_POST['chimney_check']);
        //分集水器
        $diversity_water_check = safeCheck($_POST['diversity_water_check']);
        //采暖水泵控制柜
        $heating_pump_control_check = safeCheck($_POST['heating_pump_control_check']);
        //热水水泵控制柜
        $water_pump_control_check = safeCheck($_POST['water_pump_control_check']);
        //配电柜
        $powe_box_check = safeCheck($_POST['powe_box_check']);
        //保温热水箱
        $hot_water_box_check = safeCheck($_POST['hot_water_box_check'],0);
        //热水循环泵
        $hotwater_pump_check = safeCheck($_POST['hotwater_pump_check'], 0);

        $hw_chimney_check = safeCheck($_POST['hw_chimney_check']);

        $hw_powe_box_check = safeCheck($_POST['hw_powe_box_check']);

        $remark = HTMLEncode($_POST['remark']);
        $time = time();
        try {
            //备注
            $attrsHistory = array(
                "guolu_context" => $remark,
                "status" => 1,
                "lastupdate" => $time
            );
            Selection_history::update($id, $attrsHistory);
            $info = Selection_history::getInfoById($id);
            Selection_fuji::delByHistoryId($id);
            //辅机-采暖
            if ($heating == 1) {
                $pwerstr = '';
                $boxpower = 0;
                //燃烧机
                if ($heating_burner_id) {
                    $burner_info = Burner_attr::getInfoById($heating_burner_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $heating_burner_name,
                        "num" => $heating_burner_count,
                        "value" => $heating_burner_id,
                        "modelid" => 2,
                        "version_show" => $burner_info['version'],
                        "addtime" => $time,
                        "add_type" =>$burner_check,
                        "context" =>$heating_burner_context
                    );
                    Selection_fuji::add($fujiAttr);
                }
                //软水器
                if ($heating_hdys_id) {
                    $hdys_info = Hdys_attr::getInfoById($heating_hdys_id);
                    //将软水器的计算参数以json格式输出
                   $heating_hdys_parameter=json_encode(array('heating_hdys_outwater'=> $heating_hdys_parameter));

                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $heating_hdys_name,
                        "param"=>$heating_hdys_parameter,
                        "num" => $heating_hdys_count,
                        "value" => $heating_hdys_id,
                        "modelid" => 3,
                        "version_show" => $hdys_info['version'],
                        "addtime" => $time,
                        "add_type" =>$hdys_check,
                        "context" =>$heating_hdys_contxet

                    );
                    Selection_fuji::add($fujiAttr);
                }
                //水箱
                if ($heating_water_box_id) {
                    $water_box_info = Water_box_attr::getInfoById($heating_water_box_id);
                    //将水箱的计算参数以json格式输出
                    $heating_water_box_parameter=json_encode(array('heating_water_box_capacity'=> $heating_water_box_parameter));

                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $heating_water_box_name,
                        "param"=>$heating_water_box_parameter,
                        "num" => $heating_water_box_count,
                        "value" => $heating_water_box_id,
                        "modelid" => 8,
                        "version_show" => $water_box_info['version'],
                        "addtime" => $time,
                        "add_type" =>$water_box_check,
                        "context" =>$heating_water_box_context
                    );
                    Selection_fuji::add($fujiAttr);
                }
                //锅炉循环泵
                if ($pipeline_pump_id) {
                    $pipeline_info = Pipeline_attr::getInfoById($pipeline_pump_id);
                    //将锅炉循环泵的计算参数以json格式输出
                    $pipeline_pump_attr=explode("-",$pipeline_pump_param);
                    $pipeline_pump_parameter=json_encode(array('pipeline_pump_flow'=> $pipeline_pump_attr[0],'pipeline_pump_lift'=> $pipeline_pump_attr[1]));

                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $pipeline_pump_name,
                        "param"=>$pipeline_pump_parameter,
                        "num" => $pipeline_pump_count,
                        "value" => $pipeline_pump_id,
                        "modelid" => 4,
                        "version_show" => $pipeline_info['version'],
                        "addtime" => $time,
                        "add_type" =>$pipeline_pump_check,
                        "context" =>$pipeline_pump_context
                    );
                    Selection_fuji::add($fujiAttr);
                }

                //采暖循环泵
                $heating_pump_name = ltrim($heating_pump_name, '||');
                if ($heating_pump_name) {
                    $heating_pump_id = ltrim($heating_pump_id, '||');
                    $heating_pump_count = ltrim($heating_pump_count, '||');
                    $heating_pump_check = ltrim($heating_pump_check, '||');
                    $heating_pump_context= ltrim($heating_pump_context, '||');
                    $hp_id = explode('||', $heating_pump_id);
                    $hp_name = explode('||', $heating_pump_name);
                    $hp_count = explode('||', $heating_pump_count);
                    $hp_check = explode('||', $heating_pump_check);
                    $hp_context= explode('||', $heating_pump_context);

                    //将采暖循环泵的计算参数以json格式输出
                    $heating_pump_param = ltrim($heating_pump_param, '||');
                    $hp_param  = explode('||', $heating_pump_param);


                    for ($i = 0; $i < count($hp_id); $i++) {
                        $heating_pump_attr=explode("-",$hp_param[$i]);
                        $heating_pump_parameter=json_encode(array('heating_pump_flow'=> $heating_pump_attr[0],'heating_pump_lift'=> $heating_pump_attr[1]));
                        $pump_info = Pipeline_attr::getInfoById($hp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 1,
                            "name" => $hp_name[$i],
                            "param"=>$heating_pump_parameter,
                            "num" => $hp_count[$i],
                            "value" => $hp_id[$i],
                            "modelid" => 4,
                            "version_show" => $pump_info['version'],
                            "addtime" => $time,
                            "add_type" =>$hp_check[$i],
                            "context" =>$hp_context[count($hp_id)-$i-1]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                //补水泵
                $water_pump_name = ltrim($water_pump_name, '||');
                if ($water_pump_name) {
                    $water_pump_id = ltrim($water_pump_id, '||');
                    $water_pump_count = ltrim($water_pump_count, '||');
                    $water_pump_check = ltrim($water_pump_check, '||');

                    $water_pump_context= ltrim($water_pump_context, '||');
                    $wp_id = explode('||', $water_pump_id);
                    $wp_name = explode('||', $water_pump_name);
                    $wp_count = explode('||', $water_pump_count);
                    $wp_check = explode('||', $water_pump_check);
                    $wp_context= explode('||', $water_pump_context);

                   // 将补水泵的计算参数以json格式输出
                    $water_pump_param = ltrim($water_pump_param, '||');
                    $wp_param = explode('||', $water_pump_param);


                    for ($i = 0; $i < count($wp_id); $i++) {
                        $water_pump_attr = explode("-", $wp_param[$i]);
                        $water_pump_parameter = json_encode(array('water_pump_flow' => $water_pump_attr[0], 'water_pump_lift' => $water_pump_attr[1]));
                        $water_pump_info = Syswater_pump_attr::getInfoById($wp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 1,
                            "name" => $wp_name[$i],
                            "param"=>$water_pump_parameter,
                            "num" => $wp_count[$i],
                            "value" => $wp_id[$i],
                            "version_show" => $water_pump_info['version'],
                            "modelid" => 5,
                            "addtime" => $time,
                            "add_type" =>$wp_check[$i],
                            "context" =>$wp_context[count($wp_id)-$i-1]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                //除污器
                $dirt_separater_name = ltrim($dirt_separater_name, '||');
                if ($dirt_separater_name) {
                    $dirt_separater_id = ltrim($dirt_separater_id, '||');
                    $dirt_separater_count = ltrim($dirt_separater_count, '||');
                    $dirt_separater_check = ltrim($dirt_separater_check, '||');
                    $dirt_separater_context= ltrim($dirt_separater_context, '||');
                    $dirt_id = explode('||', $dirt_separater_id);
                    $dirt_name = explode('||', $dirt_separater_name);
                    $dirt_count = explode('||', $dirt_separater_count);
                    $dirt_check = explode('||', $dirt_separater_check);
                    $dirt_context= explode('||', $dirt_separater_context);

                    //将除污器的计算参数以json格式输出
//                    $dirt_separater_param = ltrim($dirt_separater_param, '||');
//                    $dirt_parameter = explode('||', $dirt_separater_param);
//                    $dirt_separater_parameter=json_encode(array('dirt_separater_DN'=> $dirt_parameter));
                    $dirt_separater_parameter=json_encode(array('dirt_separater_DN'=> $dirt_separater_param));
                    for ($i = 0; $i < count($dirt_id); $i++) {
                        $dirt_separater_info = Dirt_separator_attr::getInfoById($dirt_id[$i]);

                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 1,
                            "name" => $dirt_name[$i],
                            "param"=>$dirt_separater_parameter,
                            "num" => $dirt_count[$i],
                            "value" => $dirt_id[$i],
                            "version_show" => $dirt_separater_info['version'],
                            "modelid" => 9,
                            "addtime" => $time,
                            "add_type" =>$dirt_check[$i],
                            "context" =>$dirt_context[count($dirt_id)-$i-1]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                //板换
                $board_separater_name = ltrim($board_name, '||');
                $board_context= ltrim($board_context, '||');
                $board_context_value = explode('||', $board_context);
                if ($board_separater_name) {
                    $board_separater_value = ltrim($board_value, '||');
                    $board_separater_count = ltrim($board_count, '||');
                    $board_separater_check = ltrim($board_check, '||');
                    $b_value = explode('||', $board_separater_value);
                    $b_name = explode('||', $board_separater_name);
                    $b_count = explode('||', $board_separater_count);
                    $b_check = explode('||', $board_separater_check);
                    for ($i = 0; $i < count($b_name); $i++) {
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 2,
                            "name" => $b_name[$i],
                            "num" => $b_count[$i],
                            "value" => $b_value[$i],
                            "addtime" => $time,
                            "add_type" =>$b_check[$i],
                            "context" =>$board_context_value[count($b_name)-$i-1]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                //水泵控制柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 2,
                    "name" => '水泵控制柜',
                    "num" => 1,
                    "value" => $heating_pump_control,
                    "addtime" => $time,
                    "add_type" =>$heating_pump_control_check,
                    "context" =>$heating_pump_control_context
                );
                Selection_fuji::add($fujiAttr);

                //钢制烟囱
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 2,
                    "name" => '钢制烟囱',
                    "num" => $guolu_info['guolu_num'],
                    "value" => '高度' . $chimney_height . 'm 直径'.$chimney_diameter.'mm',
                    "addtime" => $time,
                    "add_type" =>$chimney_check,
                    "context" =>$chimney_context
                );
                Selection_fuji::add($fujiAttr);

                //分集水器
                if ($diversity_water_num) {
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 2,
                        "name" => '分集水器',
                        "num" => 1,
                        "value" => '接口数量' . $diversity_water_num . ' 直径' . $diversity_water_diameter . 'mm' . ' 长度' . $diversity_water_length . 'm',
                        "addtime" => $time,
                        "add_type" =>$diversity_water_check,
                        "context" =>$diversity_water_context
                    );
                    Selection_fuji::add($fujiAttr);
                }

                //配电柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 2,
                    "name" => '配电柜',
                    "num" => 1,
                    "value" => '电负荷' . $heating_powe_box . 'kW',
                    "addtime" => $time,
                    "add_type" =>$powe_box_check,
                    "context" =>$powe_box_context
                );
                Selection_fuji::add($fujiAttr);
            }
            //辅机----------------热水-------------------------
            if ($hotwater == 1) {
                //燃烧机
                if ($water_burner_id) {
                    $water_burner_info = Burner_attr::getInfoById($water_burner_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_burner_name,
                        "num" => $water_burner_count,
                        "value" => $water_burner_id,
                        "modelid" => 2,
                        "version_show" => $water_burner_info['version'],
                        "addtime" => $time,
                        "add_type" =>$burner_check,
                        "context" =>$hw_burner_context
                    );
                    Selection_fuji::add($fujiAttr);
                }
                //软水器
                if ($water_hdys_id) {
                    $water_hyds_info = Hdys_attr::getInfoById($water_hdys_id);
                    //将锅炉循环泵的计算参数以json格式输出
                    $water_hdys_parameter=json_encode(array('water_hdys_outwater'=> $water_hdys_parameter));
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_hdys_name,
                        "param"=>$water_hdys_parameter,
                        "num" => $water_hdys_count,
                        "value" => $water_hdys_id,
                        "modelid" => 3,
                        "version_show" => $water_hyds_info['version'],
                        "addtime" => $time,
                        "add_type" =>$hw_hdys_check,
                        "context" =>$hw_hdys_context
                    );
                    Selection_fuji::add($fujiAttr);
                }

                //水箱
                if ($water_water_box_id) {
                    $water_water_box_info = Water_box_attr::getInfoById($water_water_box_id);
                    //将水箱的计算参数以json格式输出
                    $water_water_box_parameter=json_encode(array('water_water_box_capacity'=> $water_water_box_parameter));

                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_water_box_name,
                        "param"=>$water_water_box_parameter,
                        "num" => $water_water_box_count,
                        "value" => $water_water_box_id,
                        "modelid" => 8,
                        "version_show" => $water_water_box_info['version'],
                        "addtime" => $time,
                        "add_type" =>$water_box_check_hw,
                        "context" =>$hw_water_box_context
                    );
                    Selection_fuji::add($fujiAttr);
                }
                //锅炉循环泵
                if ($water_pipeline_pump_id) {
                    $water_pipeline_pump_info = Pipeline_attr::getInfoById($water_pipeline_pump_id);
                    //将锅炉循环泵的计算参数以json格式输出
                    $water_pipeline_pump_attr=explode("-",$water_pipeline_pump_param);
                    $water_pipeline_pump_parameter=json_encode(array('water_pipeline_pump_flow'=> $water_pipeline_pump_attr[0],'water_pipeline_pump_lift'=> $water_pipeline_pump_attr[1]));

                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_pipeline_pump_name,
                        "param"=>$water_pipeline_pump_parameter,
                        "num" => $water_pipeline_pump_count,
                        "value" => $water_pipeline_pump_id,
                        "modelid" => 4,
                        "version_show" => $water_pipeline_pump_info['version'],
                        "addtime" => $time,
                        "add_type" =>$pipeline_pump_check,
                        "context" =>$hw_pipeline_pump_context
                    );
                    Selection_fuji::add($fujiAttr);
                }

                //除污器
                $water_dirt_separater_name = ltrim($water_dirt_separater_name, '||');
                if ($water_dirt_separater_name) {
                    $water_dirt_separater_id = ltrim($water_dirt_separater_id, '||');
                    $water_dirt_separater_count = ltrim($water_dirt_separater_count, '||');
                    $water_dirt_separater_check = ltrim($water_dirt_separater_check, '||');
                    $water_dirt_id = explode('||', $water_dirt_separater_id);
                    $water_dirt_name = explode('||', $water_dirt_separater_name);
                    $water_dirt_count = explode('||', $water_dirt_separater_count);
                    $water_dirt_check = explode('||', $water_dirt_separater_check);
                    //将除污器的计算参数以json格式输出
//                    $water_dirt_separater_param = ltrim($water_dirt_separater_param, '||');
//                    $water_dirt_parameter = explode('||', $water_dirt_separater_param);
                    $water_dirt_separater_parameter=json_encode(array('water_dirt_separater_DN'=> $water_dirt_separater_param));

                    for ($i = 0; $i < count($water_dirt_id); $i++) {
                        $water_dirt_separater_info = Dirt_separator_attr::getInfoById($water_dirt_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 1,
                            "name" => $water_dirt_name[$i],
                            "param"=>$water_dirt_separater_parameter,
                            "num" => $water_dirt_count[$i],
                            "value" => $water_dirt_id[$i],
                            "modelid" => 9,
                            "version_show" => empty($water_dirt_separater_info['version'])?0:$water_dirt_separater_info['version'],
                            "addtime" => $time,
                            "add_type" =>$water_dirt_check[$i],
                            "context" =>$hw_dirt_separater_context
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                //板换
                $water_separater_board_name = ltrim($water_board_name, '||');
                if ($water_separater_board_name) {
                    $water_separater_board_value = ltrim($water_board_value, '||');
                    $water_separater_board_count = ltrim($water_board_count, '||');
                    $water_separater_board_check = ltrim($water_board_check, '||');
                    $hw_water_board_context= ltrim($hw_water_board_context, '||');

                    $water_b_value = explode('||', $water_separater_board_value);
                    $water_b_name = explode('||', $water_separater_board_name);
                    $water_b_count = explode('||', $water_separater_board_count);
                    $water_b_check = explode('||', $water_separater_board_check);
                    $board_context= explode('||', $hw_water_board_context);


                    for ($i = 0; $i < count($water_b_name); $i++) {
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 2,
                            "name" => $water_b_name[$i],
                            "num" => $water_b_count[$i],
                            "value" => $water_b_value[$i],
                            "addtime" => $time,
                            "add_type" =>$water_b_check[$i],
                            "context" =>$board_context[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                //水泵控制柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 1,
                    "data_type" => 2,
                    "name" => '水泵控制柜',
                    "num" => 1,
                    "value" => $water_pump_control,
                    "addtime" => $time,
                    "add_type" =>$water_pump_control_check,
                    "context" =>$hw_water_pump_control_context
                );
                Selection_fuji::add($fujiAttr);

                //钢制烟囱
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 1,
                    "data_type" => 2,
                    "name" => '钢制烟囱',
                    "num" =>  $guolu_info['guolu_num'],
                    "value" => '高度' . $chimney_height2 . 'm 直径'.$chimney_diameter2.'mm',
                    "addtime" => $time,
                    "add_type" =>$hw_chimney_check,
                    "context" =>$hw_chimney_context
                );
                Selection_fuji::add($fujiAttr);

                //配电柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 1,
                    "data_type" => 2,
                    "name" => '配电柜',
                    "num" => 1,
                    "value" => '电负荷' . $water_powe_box . 'kW',
                    "addtime" => $time,
                    "add_type" =>$hw_powe_box_check,
                    "context" =>$hw_powe_box_context
                );
                Selection_fuji::add($fujiAttr);


                //保温热水箱
                if ($hot_water_box_id) {
                    $hw_water_board_context= ltrim($hw_water_board_context, '||');

                    $water_b_value = explode('||', $water_separater_board_value);
                    $hot_water_box_id= ltrim($hot_water_box_id, '||');
                    $hot_water_box_name= ltrim($hot_water_box_name, '||');
                    $hot_water_box_count= ltrim($hot_water_box_count, '||');
                    $hw_hot_water_box_context= ltrim($hw_hot_water_box_context, '||');
                    $hot_water_box_parameter= ltrim($hot_water_box_parameter, '||');
                    $hot_water_box_check= ltrim($hot_water_box_check, '||');

                    $hot_water_box_id_value= explode('||', $hot_water_box_id);
                    $hot_water_box_name_value= explode('||', $hot_water_box_name);
                    $hot_water_box_count_value= explode('||', $hot_water_box_count);
                    $hw_hot_water_box_context_value= explode('||', $hw_hot_water_box_context);
                    $hot_water_box_parameter_value= explode('||', $hot_water_box_parameter);
                    $hot_water_box_check_value= explode('||', $hot_water_box_check);


//                    $hot_water_box_info = Water_box_attr::getInfoById($hot_water_box_id);
                    //将保温热水箱的计算参数以json格式输出

                    for ($i = 0; $i < count($hot_water_box_name_value); $i++) {
                        $hot_water_box_parameter_data=json_encode(array('hot_water_box_capacity'=> $hot_water_box_parameter_value[$i]));
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 2,
                        "name" => $hot_water_box_name_value[$i],
                        "param"=>$hot_water_box_parameter_data,
                        "num" => $hot_water_box_count_value[$i],
                        "value" => $hot_water_box_id_value[$i],
                        "version_show" => $hot_water_box_id_value[$i],
                        "addtime" => $time,
                        "add_type" =>$hot_water_box_check_value[$i],
                        "context" =>$hw_hot_water_box_context_value[$i]
                    );
                    Selection_fuji::add($fujiAttr);
                    }
                }

                //热水循环泵
                $hotwater_pump_name = ltrim($hotwater_pump_name, '||');
                if ($hotwater_pump_name) {
                	$hotwater_pump_id = ltrim($hotwater_pump_id, '||');
                	$hotwater_pump_count = ltrim($hotwater_pump_count, '||');
                	$hotwater_pump_check = ltrim($hotwater_pump_check, '||');
                    $hw_hotwater_pump_context= ltrim($hw_hotwater_pump_context, '||');
                	$htp_id = explode('||', $hotwater_pump_id);
                	$htp_name = explode('||', $hotwater_pump_name);
                	$htp_count = explode('||', $hotwater_pump_count);
                	$htp_check = explode('||', $hotwater_pump_check);
                    $hotwater_pump_contexts = explode('||', $hw_hotwater_pump_context);

                    //将热水循环泵的计算参数以json格式输出
                	$hotwater_pump_param = ltrim($hotwater_pump_param, '||');
                    $htp_param = explode('||', $hotwater_pump_param);
                    foreach ( $htp_param as  $htp_param_value) {
                        $hotwater_pump_attr = explode("-", $htp_param_value);

                    }

                    for ($i = 0; $i < count($htp_id); $i++) {
                        $hotwater_pump_attr = explode("-", $htp_param[$i]);
                        $hotwater_pump_parameter = json_encode(array('hotwater_pump_flow' => $hotwater_pump_attr[0], 'hotwater_pump_lift' => $hotwater_pump_attr[1]));
                		$pipeline_Pump_info = Pipeline_attr::getInfoById($htp_id[$i]);
                		$fujiAttr = array(
                				"history_id" => $id,
                				"use_type" => 1,
                				"data_type" => 1,
                				"name" => $htp_name[$i],
                                "param"=>$hotwater_pump_parameter,
                				"num" => $htp_count[$i],
                				"value" => $htp_id[$i],
                				"modelid" => 4,
                				"version_show" => $pipeline_Pump_info['version'],
                				"addtime" => $time,
                				"add_type" =>$htp_check[$i],
                            "context" =>$hotwater_pump_contexts[$i]
                		);
                		Selection_fuji::add($fujiAttr);
                	}
                }
                //补水泵   变频供水泵
                $hotwater_water_pump_name = ltrim($hotwater_water_pump_name, '||');
                if ($hotwater_water_pump_name) {
                    $hotwater_water_pump_id = ltrim($hotwater_water_pump_id, '||');
                    $hotwater_water_pump_count = ltrim($hotwater_water_pump_count, '||');
                    $hotwater_water_pump_check = ltrim($hotwater_water_pump_check, '||');
                    $hw_dynamic_water_pump_context= ltrim($hw_dynamic_water_pump_context, '||');
                    $hwp_id = explode('||', $hotwater_water_pump_id);
                    $hwp_name = explode('||', $hotwater_water_pump_name);
                    $hwp_count = explode('||', $hotwater_water_pump_count);
                    $hwp_check = explode('||', $hotwater_water_pump_check);
                    $pump_context= explode('||', $hw_dynamic_water_pump_context);

                    //将补水泵的计算参数以json格式输出
                    $hotwater_water_pump_param = ltrim($hotwater_water_pump_param, '||');
                    $hwp_param = explode('||', $hotwater_water_pump_param);

                    for ($i = 0; $i < count($hwp_id); $i++) {
                        $hotwater_water_pump_attr = explode("-", $hwp_param[$i]);
                        $hotwater_water_pump_parameter = json_encode(array('hotwater_water_pump_flow' => $hotwater_water_pump_attr[0], 'hotwater_water_pump_lift' => $hotwater_water_pump_attr[1]));
                        $hot_water_pump_info = Syswater_pump_attr::getInfoById($hwp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 1,
                            "name" => $hwp_name[$i],
                            "param"=>$hotwater_water_pump_parameter,
                            "num" => $hwp_count[$i],
                            "value" => $hwp_id[$i],
                            "modelid" => 5,
                            "version_show" => $hot_water_pump_info['version'],
                            "addtime" => $time,
                            "add_type" =>$hwp_check[$i],
                            "context" =>$pump_context[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

            }
            $selection_plans = Selection_plan::getInfoByHistoryId($id);
            if (count($selection_plans) > 0){
                Selection_plan::delByHistoryId($id);
            }
            echo action_msg("保存成功！", 1);
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'remark_update'://修改备注
        $id = safeCheck($_POST['id']);
        $remark = HTMLEncode($_POST['remark']);
        try {
            $attrsHistory = array(
                "remark" => $remark,
                "lastupdate" => time()
            );
            Selection_history::update($id, $attrsHistory);

            echo action_msg("保存成功！", 1);
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_new_pipeline'://取得新的管道泵
        $pump_flow = safeCheck($_POST['pump_flow']);
        $pump_lift = safeCheck($_POST['pump_lift']);
        $inputname = safeCheck($_POST['inputname'], 0);
        try {
            $pipelinelist = Pipeline_attr::getInfoByFlowLift($pump_flow, $pump_lift);
            $str = "";
            if ($pipelinelist) {
                foreach ($pipelinelist as $pipeline) {
                    $venderinfo = Dict::getInfoById($pipeline['vender']);
                    $str .= '
                        <div class="XXRmain_8">
                            <input type="radio" class="XXRmain_9 power_compute" name="' . $inputname . '" value="' . $pipeline['id'] . '">
                            <input type="hidden" class="motorpower" value="' . $pipeline['motorpower'] . '">
                            <div class="XXRmain_10">' . $venderinfo['name'] . '</div>
                            <div class="XXRmain_11">' . $pipeline['version'] . '</div>
                            <a href="javascript:void(0);" onclick="pipeline_detail('.$pipeline['id'].')">详情</a>
                        </div>
                    ';
                }
            } else {
                $str = "没有找到合适的循环泵";
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_new_hot_pipeline'://热水取得新的管道泵
        $pump_flow = safeCheck($_POST['pump_flow']);
        $pump_lift = safeCheck($_POST['pump_lift']);
        $inputname = safeCheck($_POST['inputname'], 0);
        try {
            $pipelinelist = Pipeline_attr::getInfoByFlowLift($pump_flow, $pump_lift);

            $str = "";
            if ($pipelinelist) {
                foreach ($pipelinelist as $pipeline) {
                    $venderinfo = Dict::getInfoById($pipeline['vender']);
                    $str .= '
                        <div class="XXRmain_8">
                            <input type="radio" class="XXRmain_9 water_power_compute" name="' . $inputname . '" value="' . $pipeline['id'] . '">
                            <input type="hidden" class="motorpower" value="' . $pipeline['motorpower'] . '">
                            <div class="XXRmain_10">' . $venderinfo['name'] . '</div>
                            <div class="XXRmain_11">' . $pipeline['version'] . '</div>
                            <a href="javascript:void(0);" onclick="pipeline_detail('.$pipeline['id'].')">详情</a>
                        </div>
                    ';
                }
            } else {
                $str = "没有找到合适的循环泵";
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_new_waterpump'://取得新的补水泵
        $water_flow = safeCheck($_POST['water_flow']);
        $water_lift = safeCheck($_POST['water_lift']);
        $inputname = safeCheck($_POST['inputname'], 0);
        try {
            $pipelinelist = Syswater_pump_attr::getInfoByFlowLift($water_flow, $water_lift);
            $str = "";
            if ($pipelinelist) {
                foreach ($pipelinelist as $pipeline) {
                    $venderinfo = Dict::getInfoById($pipeline['vender']);
                    $str .= '
                        <div class="XXRmain_8">
                            <input type="radio" class="XXRmain_9 power_compute" name="' . $inputname . '" value="' . $pipeline['id'] . '">
                            <input type="hidden" class="motorpower" value="' . $pipeline['motorpower'] . '">
                            <div class="XXRmain_10">' . $venderinfo['name'] . '</div>
                            <div class="XXRmain_11">' . $pipeline['version'] . '</div>
                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipeline['id'].')">详情</a>
                        </div>
                    ';
                }
            } else {
                $str = "没有找到合适的补水泵";
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_new_hot_waterpump'://取得新的热水补水泵
        $water_flow = safeCheck($_POST['water_flow']);
        $water_lift = safeCheck($_POST['water_lift']);
        $inputname = safeCheck($_POST['inputname'], 0);
        try {
            $pipelinelist = Syswater_pump_attr::getInfoByFlowLift($water_flow, $water_lift);
            $str = "";
            if ($pipelinelist) {
                foreach ($pipelinelist as $pipeline) {
                    $venderinfo = Dict::getInfoById($pipeline['vender']);
                    $str .= '
                        <div class="XXRmain_8">
                            <input type="radio" class="XXRmain_9 water_power_compute" name="' . $inputname . '" value="' . $pipeline['id'] . '">
                            <input type="hidden" class="motorpower" value="' . $pipeline['motorpower'] . '">
                            <div class="XXRmain_10">' . $venderinfo['name'] . '</div>
                            <div class="XXRmain_11">' . $pipeline['version'] . '</div>
                            <a href="javascript:void(0);" onclick="syswater_pump_detail('.$pipeline['id'].')">详情</a>
                        </div>
                    ';
                }
            } else {
                $str = "没有找到合适的补水泵";
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_new_hot_water_box'://取得新的保暖水箱
        $box_capacity = safeCheck($_POST['box_capacity']);
        $inputname = safeCheck($_POST['inputname'], 0);
        try {
            $wboxlist = Water_box_attr::getInfoByCapacity($box_capacity);
            $str = "";
            if ($wboxlist) {
                foreach ($wboxlist as $wbox) {
                    $str .= '
                        <div class="XXRmain_8">
                            <input type="radio" class="XXRmain_9" name="' . $inputname . '" value="' . $wbox['id'] . '">
                            <div class="XXRmain_10">现场制作</div>
                            <div class="XXRmain_11">' . $wbox['version'] . '</div>
                            <a href="javascript:void(0);" onclick="water_box_detail('.$wboxinfo['id'].')">详情</a>
                        </div>
                    ';
                }
            } else {
                $str = "没有找到合适的保温热水箱";
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'getTplContent'://获取制定模板ID的内容
        $tplId = safeCheck($_POST['id']);
        try {
            $tplcontent = Table_case_tplcontent::getInfoByTplid($tplId);
            $str = "";
            if ($tplcontent) {
                if (!empty($tplcontent[0]['content'])) {
                    $str = HTMLDecode($tplcontent[0]['content']);
                } else {
                    $str = "ID=" . $tplId . "的模板内容为空！";
                }
            } else {
                $str = "没有找到ID=" . $tplId . "的模板！";
            }
            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'add_plan_front'://添加前端选型方案
        $history_id = safeCheck($_POST['history_id']);
        $history_info = Selection_history::getInfoById($history_id);
        $name = safeCheck($_POST['name'], 0);
        $tplcontent = $_POST['tplcontent'];

        try {


            $attrs = array(
                "history_id" => $history_id,
                "project_id" => $history_info['project_id'],
                "name" => $name,
                "addtime" => time(),
                "tplcontent" => $tplcontent
            );

            $rs = Selection_plan_front::add($attrs);

            $attrsHistory = array(
                "status" => Selection_history::HISTORY_Finish
            );

            $update_history_status_rs = Selection_history::update($history_id,$attrsHistory);



            system("nohup php {$FILE_PATH}commands/selection_plan_do.php > /dev/null &");


            if ($rs > 0) {
                echo action_msg("添加成功", 1);
            }

        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'update_plan_front'://修改前端选型方案
        $plan_id = safeCheck($_POST['id']);
        $plan_front = Selection_plan_front::getInfoById($plan_id);
        $name = safeCheck($_POST['name'], 0);
        $tplcontent = $_POST['tplcontent'];

        try {
            $attrs = array(
                "name" => $name,
                "addtime" => time(),
                "tplcontent" => $tplcontent,
                "status"=>selection_plan_front::WAIT_SOLVE
            );



            $rs = Selection_plan_front::update($plan_id,$attrs);


            system("nohup php {$FILE_PATH}commands/selection_plan_do.php > /dev/null &");

            $attrsHistory = array(
                "status" => Selection_history::HISTORY_Finish
            );

            $update_history_status_rs = Selection_history::update($plan_front['history_id'],$attrsHistory);

            if ($rs > 0) {
                echo action_msg("修改成功", 1);
            }else{
                echo action_msg("修改失败！", 211);
            }

        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'delFrontPlan'://删除前端选型方案
        $planId = safeCheck($_POST['planId']);

        try {
            //先删除内容，再删除属性名称
            $attr = array(
                "status" => -1,
            );
            Selection_plan_front::update($planId,$attr);
            echo action_msg("删除成功", 1);
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'addprice'://添加选型方案1报价

        $history_id = safeCheck($_POST['id']);
        $guolu_dataStr=safeCheck($_POST['guolu_dataStr'],0);

        $fuji_sel_dataStr=safeCheck($_POST['fuji_sel_dataStr'],0);
        $fuji_text_dataStr=safeCheck($_POST['fuji_text_dataStr'],0);
        $water_sel_dataStr=safeCheck($_POST['water_sel_dataStr'],0);
        $water_text_dataStr=safeCheck($_POST['water_text_dataStr'],0);
        $dataStr = safeCheck($_POST['dataStr'],0);
//        echo $fuji_text_dataStr;
//        exit;

        $guolu_rs = null;
        $guolu_rs1 = null;
        $fuji_sel_rs = null;
        $fuji_sel_rs1 = null;
        $water_sel_rs = null;
        $water_sel_rs1 = null;
        $rs=null;
        $rs1=null;





        try {
            global $mypdo;
            $mypdo->pdo->beginTransaction();



            if($guolu_dataStr!="")
            {

                $guolus = explode("#", $guolu_dataStr);//锅炉表单只有一行数据

                foreach ($guolus as $guolu)
                {
                    /*锅炉表单的价格添加*/
                    $guolu_str = explode("-", $guolu);//锅炉表单只有一行数据
                    $guolu_attrs = array(//将锅炉表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type" => 0,//锅炉表单
                        "pro_id" => $guolu_str[2],
                        "pro_price" => $guolu_str[1]
                    );
                    $guolu_rs = Selection_plan::add($guolu_attrs);

                    //将锅炉表单中自定义价格将添加到pricelog表中
//                if ($guolu_str[2] == 2)
                    $type = 1;
                    $addtype = 1;
                    $guolu_attrs1 = array(
                        "objectid" => $guolu_str[0],
                        "type" => $type,
                        "addtype" => $addtype,
                        "price" => $guolu_str[1],
                        "addtime" => time()
                    );

                    $guolu_rs1 = Case_pricelog::add($guolu_attrs1);
                }

            }

//                }else{
//                    $guolu_rs1 = -1;
//                }

            /*采暖辅机*/
            //后台原有数据
            if($fuji_sel_dataStr!= "")
            //辅机表单可以没有数据
                {
                $fuji_sel_attr = explode("#", $fuji_sel_dataStr);//将每一行的数据分割开
                foreach ($fuji_sel_attr as $fuji_sel_value){
                        $fuji_sel_str = explode("-", $fuji_sel_value);

                        $fuji_sel_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type"=>1,   //采暖辅机
                        "pro_id" => $fuji_sel_str[0],
                        "pro_price" => $fuji_sel_str[1]
                        );
                        $fuji_sel_rs = Selection_plan::add($fuji_sel_attrs);

                        //将辅机表单中自定义价格将添加到pricelog表中
//                        if ($fuji_sel_str[2] == 2)
//                        {

                            $type = 1;//表示产品中心的报价
                            $addtype = 1;//表示前台添加
                            $fuji_sel_attrs1 = array
                            (
                                "objectid" => $fuji_sel_str[2],
                                "type" => $type,
                                "addtype" => $addtype,
                                "price" => $fuji_sel_str[1],
                                "addtime" => time()
                            );
                            $fuji_sel_rs1 = Case_pricelog::add($fuji_sel_attrs1);
//                        }else{
//                            $fuji_sel_rs1 = -1;
//                        }
                }
            }else{
                $fuji_sel_rs = -1;
                $fuji_sel_rs1 = -1;
            }
            //前端重新添加的，只有自定义价格
            if($fuji_text_dataStr!= ""){
            //辅机表单可以没有数据

                $fuji_text_attr = explode("#", $fuji_text_dataStr);//将每一行的数据分割开
                foreach ($fuji_text_attr as $fuji_text_value)
                    {
                        $fuji_text_str = explode("-", $fuji_text_value);
//
                        $fuji_text_attrs = array(//将辅机表单中数据添加到selection_plan表中
                            "history_id" => $history_id,
                            "tab_type"=>1,   //采暖辅机
                            "pro_id" => $fuji_text_str[0],
                            "pro_price" => $fuji_text_str[1]
                        );
                        $fuji_text_rs = Selection_plan::add($fuji_text_attrs);

                    //将辅机表单中自定义价格将添加到pricelog表中
//                        $type = 1;//表示产品中心的报价
//                        $addtype = 1;//表示前台添加
//                        $fuji_text_attrs1 = array(
//                            "objectid" => $fuji_text_str[0],
//                            "type" => $type,
//                            "addtype" => $addtype,
//                            "price" => $fuji_text_str[1],
//                            "addtime" => time()
//                        );
//                        $fuji_text_rs1 = Case_pricelog::add($fuji_text_attrs1);
                    }
            }else{
                $fuji_text_rs = -1;
//                $fuji_text_rs1 = -1;
            }

            /*热水辅机*/
            //后台原有数据
            if($water_sel_dataStr!= "")
                //辅机表单可以没有数据
            {
                $water_sel_attr = explode("#", $water_sel_dataStr);//将每一行的数据分割开
                foreach ($water_sel_attr as $water_sel_value){
                    $water_sel_str = explode("-", $water_sel_value);
                    $water_sel_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type"=>2,   //热水辅机
                        "pro_id" => $water_sel_str[0],
                        "pro_price" => $water_sel_str[1]
                    );
                    $water_sel_rs = Selection_plan::add($water_sel_attrs);

//                    if ($water_sel_str[2] == 2) {//将辅机表单中自定义价格将添加到pricelog表中
                        $type = 1;//表示产品中心的报价
                        $addtype = 1;//表示前台添加
                        $water_sel_attrs1 = array(
                            "objectid" => $water_sel_str[2],
                            "type" => $type,
                            "addtype" => $addtype,
                            "price" => $water_sel_str[1],
                            "addtime" => time()
                        );
                        $water_sel_rs1 = Case_pricelog::add($water_sel_attrs1);
//                    }else{
//                        $water_sel_rs1 = -1;
//                    }
                }
            }else{
                $water_sel_rs = -1;
                $water_sel_rs1 = -1;
            }
            //前端重新添加的，只有自定义价格
            if($water_text_dataStr!= ""){
                //辅机表单可以没有数据
                $water_text_attr = explode("#", $water_text_dataStr);//将每一行的数据分割开
                foreach ($water_text_attr as $water_text_value) {
                    $water_text_str = explode("-", $water_text_value);
                    $water_text_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type"=>2,   //热水辅机
                        "pro_id" => $water_text_str[0],
                        "pro_price" => $water_text_str[1]
                    );
                    $water_text_rs = Selection_plan::add($water_text_attrs);

                    //将辅机表单中自定义价格将添加到pricelog表中
//                    $type = 1;//表示产品中心的报价
//                    $addtype = 1;//表示前台添加
//                    $water_text_attrs1 = array(
//                        "objectid" => $water_text_str[0],
//                        "type" => $type,
//                        "addtype" => $addtype,
//                        "price" => $water_text_str[1],
//                        "addtime" => time()
//                    );
//                    $water_text_rs1 = Case_pricelog::add($water_text_attrs1);
                }
            }else{
                $water_text_rs = -1;
//                $water_text_rs1 = -1;
            }


            if($dataStr != ""){
                /*其他项表单的价格添加*/
                $other_attr = explode("#", $dataStr);//将每一行的数据分割开
                foreach ($other_attr as $value) {
                    $other_str = explode("-", $value);

                    $attrs = array(
                        "history_id" => $history_id,
                        "tab_type"=>3,//其他项
                        "pro_id" => $other_str[0],
                        "pro_price" => !empty($other_str[1])?$other_str[1]:0,
                    );
                    $rs = Selection_plan::add($attrs);

//                    if ($other_str[2] == 2) {//如果是自定义价格将价格添加到pricelog表中
                        $type = 2;//表示其他报价
                        $addtype = 1;//表示前台添加
                        $attrs1 = array(
                            "objectid" => $other_str[0],
                            "type" => $type,
                            "addtype" => $addtype,
                            "price" => $other_str[1],
                            "addtime" => time()
                        );
                        $rs1 = Case_pricelog::add($attrs1);
//                    }else{
//                        $rs1 = -1;
//                    }
                }
            }else{
                $rs = -1;
                $rs1 = -1;
            }


            $mypdo->pdo->commit();
            if(null != $guolu_rs && null != $guolu_rs1 && null != $fuji_sel_rs && null != $fuji_sel_rs1  && null != $water_sel_rs && null != $water_sel_rs1 && null != $rs && null != $rs1){
                echo action_msg("成功", 1);
            }else{
                echo action_msg("添加失败", 212);
            }

        } catch (MyException $e) {
            $mypdo->pdo->rollBack();
            echo $e->jsonMsg();
        }
        break;

    case 'change_guolu'://更换锅炉
        $id= safeCheck($_POST['id'],0);
        $project_id= safeCheck($_POST['project_id'],0);
        $customer = safeCheck($_POST['customer'], 0);
        $application = safeCheck($_POST['application']);
        $heating_type = safeCheck($_POST['heating_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);
        $guolu_use = safeCheck($_POST['guolu_use']);
        $board_power = safeCheck($_POST['board_power'],0);
        $guoluStr = safeCheck($_POST['guoluStr'],0);
        $guoluNumStr = safeCheck($_POST['guoluNumStr'],0);
        $nowtime = time();
        //合并同类锅炉
        $guoluStr = explode(',',$guoluStr);
        $guoluNumStr = explode(',',$guoluNumStr);
        $guoluStr_length = count($guoluStr);
        $guoluNumStr_length = count($guoluNumStr);
        for($i=0;$i<$guoluStr_length;$i++){
            for($j=$i+1;$j<$guoluNumStr_length;$j++){
                if($guoluStr[$i]==$guoluStr[$j]){
                    $guoluNumStr[$i] = $guoluNumStr[$i]+$guoluNumStr[$j];
                    unset($guoluNumStr[$j]);
                    unset($guoluStr[$j]);
                }
            }
        }

        $guoluStr = implode(',',$guoluStr);
        $guoluNumStr = implode(',',$guoluNumStr);
        try {

            //如果存在多个锅炉，那么校验多个锅炉的供水回温度要一样
            if(strpos($guoluStr,","))
            {
                $check = guolu_attr::checkForChange($guoluStr);


                if(!$check) throw new MyException("锅炉供水回温度必须一致",103);

            }

            $board_power = empty($board_power)?0:$board_power;

            $attrsHistory = array(
                "customer" => $customer,
                "guolu_id" => $guoluStr,
                "guolu_num" => $guoluNumStr,
                "heating_type" => $application,
                "application" => $guolu_use,
                "status" => 1,
                "hm_heating_type" => $heating_type,
                "board_power" => $board_power,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "type"=>Selection_history::HISTORY_CHANGE,
                "user" => $USERId,
                "addtime" => $nowtime,
                "guolu_context"=>null,
                "lastupdate" => $nowtime,
                "project_id"=>$project_id
            );

            if($id==0){
                $historyid = Selection_history::add($attrsHistory);
                echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $historyid));
            }else{
                Selection_history::update($id,$attrsHistory);
                Selection_fuji::delByHistoryId($id);
//                Selection_fuji::update($id);
                echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $id));
            }

        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'select_guolu_manual': // 手动选型
        $customer = safeCheck($_POST['customer'], 0);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);
        $application = safeCheck($_POST['application']);
        $heating_type = safeCheck($_POST['heating_type']);
        $area_num_nuan_qi = safeCheck($_POST['area_num_nuan_qi']);
        $water_type = safeCheck($_POST['water_type']);
        $area_num_water = safeCheck($_POST['area_num_water']);
        $guoluStr = safeCheck($_POST['guoluStr'],0);
        $guoluNumStr = safeCheck($_POST['guoluNumStr'],0);


        $nowtime = time();
        try {
            $attrsHistory = array(
                "user" => $USERId,
                "customer" => $customer,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "application" => $application,
                "heating_type" => $heating_type,
                "area_num_nuan_qi" => $area_num_nuan_qi,
                "water_type" => $water_type,
                "area_num_water" => $area_num_water,
                "guolu_id" => $guoluStr,
                "guolu_num" => $guoluNumStr,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime
            );

            $historyid = Selection_history::add($attrsHistory);

            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $historyid));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'fuji_change_selected'://选择辅机方案

        $id = safeCheck($_POST['id']);

        $pipeline_pump_id = safeCheck($_POST['pipeline_pump_id'],0);
        $pipeline_pump_count = safeCheck($_POST['pipeline_pump_count'],0);
        $pipeline_pump_name = safeCheck($_POST['pipeline_pump_name'], 0);
        $pipeline_pump_flow = safeCheck($_POST['pipeline_pump_flow'], 0);
        $pipeline_pump_lift = safeCheck($_POST['pipeline_pump_lift'], 0);
        $board_value = safeCheck($_POST['board_value'], 0);
        $board_count = safeCheck($_POST['board_count'], 0);
        $board_name = safeCheck($_POST['board_name'], 0);
        $board_check = safeCheck($_POST['board_check'], 0);
        //锅炉循环泵
        $pipeline_pump_check = safeCheck($_POST['pipeline_pump_check']);

        $pipeline_pump_parameter=json_encode(array('pipeline_pump_flow'=> $pipeline_pump_flow,'pipeline_pump_lift'=> $pipeline_pump_lift));

        $fuji_remark = HTMLEncode($_POST['fuji_remark']);
        $banhuan_remark = HTMLEncode($_POST['banhuan_remark']);
        $time = time();

        try {
            $context = $_POST['context'];
            if(!empty($context)){
                $remark = implode(",",$context);
            }else{
                $remark=null;
            }
            //备注
            $attrsHistory = array(
                "status" => 1,
                "lastupdate" => $time,
                "guolu_context" => $remark
            );
            Selection_history::update($id, $attrsHistory);
            $info = Selection_history::getInfoById($id);
            Selection_fuji::delByHistoryId($id);
            $selection_plans = Selection_plan::getInfoByHistoryId($id);
            if (count($selection_plans) > 0){
                Selection_plan::delByHistoryId($id);
            }



            //锅炉循环泵
            if ($pipeline_pump_id) {
                $pipeline_info = Pipeline_attr::getInfoById($pipeline_pump_id);
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 1,
                    "name" => $pipeline_pump_name,
                    "param" =>$pipeline_pump_parameter,
                    "num" => $pipeline_pump_count,
                    "value" => $pipeline_pump_id,
                    "modelid" => 4,
                    "version_show" => $pipeline_info['version'],
                    "addtime" => $time,
                    "add_type" =>$pipeline_pump_check,
                    "context" =>$fuji_remark
                );
                Selection_fuji::add($fujiAttr);
            }




            //板换
            $board_separater_name = ltrim($board_name, '||');
            if ($board_separater_name) {
                $board_separater_value = ltrim($board_value, '||');
                $board_separater_count = ltrim($board_count, '||');
                $board_separater_check = ltrim($board_check, '||');
                $b_value = explode('||', $board_separater_value);
                $b_name = explode('||', $board_separater_name);
                $b_count = explode('||', $board_separater_count);
                $b_check = explode('||', $board_separater_check);
                for ($i = 0; $i < count($b_name); $i++) {
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 2,
                        "name" => $b_name[$i],
                        "num" => $b_count[$i],
                        "value" => $b_value[$i],
                        "addtime" => $time,
                        "add_type" =>$b_check[$i],
                        "context" =>$banhuan_remark
                    );
                    Selection_fuji::add($fujiAttr);
                }
            }
            echo action_msg("保存成功！", 1);
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_burner_vender_list': // 获取指定厂家的燃气燃烧器
        $heat_burner_vender = safeCheck($_POST['heat_burner_vender'], 0);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);

        try{
            $rs = Burner_attr::getList('','',1,$heat_burner_vender,$is_lownitrogen);
            if (count($rs) > 0) {
                echo action_msg_data("获取成功", 1, $rs);
            }else{
                echo action_msg_data("没有数据", 101, $rs);
            }
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_pipeline_version_list': // 获取指定厂家的管道泵
        $pipeline_vender = safeCheck($_POST['pipeline_vender'], 0);

        try{
            $rs = Pipeline_attr::getList('','',0,$pipeline_vender);
            if (count($rs) > 0) {
                echo action_msg_data("获取成功", 1, $rs);
            }else{
                echo action_msg_data("没有数据", 101, $rs);
            }
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_syswater_pump_version_list': // 获取指定厂家的系统补水泵
        $system_water_pump_vender = safeCheck($_POST['system_water_pump_vender'], 0);

        try{
            $rs = Syswater_pump_attr::getList('','',0,$system_water_pump_vender);
            if (count($rs) > 0) {
                echo action_msg_data("获取成功", 1, $rs);
            }else{
                echo action_msg_data("没有数据", 101, $rs);
            }
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'fuji_manual_selected': // 手动选型辅机方案 -- 已弃用；保留以防万一

        $id = safeCheck($_POST['id']);
        $heating = safeCheck($_POST['heating']);
        $hotwater = safeCheck($_POST['hotwater']);
        $application = safeCheck($_POST['application']);

        //采暖
        $heating_burner_id = safeCheck($_POST['heating_burner_id']);
        $heating_burner_count = safeCheck($_POST['heating_burner_count']);
        $heating_burner_name = safeCheck($_POST['heating_burner_name'], 0);

        $heating_hdys_id = safeCheck($_POST['heating_hdys_id']);
        $heating_hdys_count = safeCheck($_POST['heating_hdys_count']);
        $heating_hdys_name = safeCheck($_POST['heating_hdys_name'],0);

        $heating_water_box_id = safeCheck($_POST['heating_water_box_id']);
        $heating_water_box_count = safeCheck($_POST['heating_water_box_count']);
        $heating_water_box_name = safeCheck($_POST['heating_water_box_name'], 0);

        $dirt_separater_id = safeCheck($_POST['dirt_separater_id'], 0);
        $dirt_separater_count = safeCheck($_POST['dirt_separater_count'], 0);
        $dirt_separater_name = safeCheck($_POST['dirt_separater_name'], 0);
        $dirt_separater_check = safeCheck($_POST['dirt_separater_check'], 0);

        $pipeline_pump_id = safeCheck($_POST['pipeline_pump_id'],0);
        $pipeline_pump_count = safeCheck($_POST['pipeline_pump_count'],0);
        $pipeline_pump_name = safeCheck($_POST['pipeline_pump_name'], 0);

        $heating_pump_id = safeCheck($_POST['heating_pump_id'], 0);
        $heating_pump_count = safeCheck($_POST['heating_pump_count'], 0);
        $heating_pump_name = safeCheck($_POST['heating_pump_name'], 0);
        $heating_pump_check = safeCheck($_POST['heating_pump_check'], 0);

        $water_pump_id = safeCheck($_POST['water_pump_id'], 0);
        $water_pump_count = safeCheck($_POST['water_pump_count'], 0);
        $water_pump_check = safeCheck($_POST['water_pump_check'], 0);
        $water_pump_name = safeCheck($_POST['water_pump_name'], 0);

        $board_value = safeCheck($_POST['board_value'], 0);
        $board_count = safeCheck($_POST['board_count'], 0);
        $board_name = safeCheck($_POST['board_name'], 0);
        $board_check = safeCheck($_POST['board_check'], 0);

        $diversity_water_num = safeCheck($_POST['diversity_water_num']);
        $diversity_water_length = safeCheck($_POST['diversity_water_length']);
        $diversity_water_diameter = safeCheck($_POST['diversity_water_diameter']);

        $chimney_height = safeCheck($_POST['chimney_height']);
        $chimney_diameter = safeCheck($_POST['chimney_diameter']);

        $heating_pump_control = safeCheck($_POST['heating_pump_control'], 0);
        $heating_pump_control_name = safeCheck($_POST['heating_pump_control_name']);
        $heating_powe_box = safeCheck($_POST['heating_powe_box'],0);

        //热水
        $water_burner_id = safeCheck($_POST['water_burner_id']);
        $water_burner_count = safeCheck($_POST['water_burner_count']);
        $water_burner_name = safeCheck($_POST['water_burner_name'], 0);

        $water_hdys_id = safeCheck($_POST['water_hdys_id']);
        $water_hdys_count = safeCheck($_POST['water_hdys_count']);
        $water_hdys_name = safeCheck($_POST['water_hdys_name'], 0);

        $water_water_box_id = safeCheck($_POST['water_water_box_id']);
        $water_water_box_count = safeCheck($_POST['water_water_box_count']);
        $water_water_box_name = safeCheck($_POST['water_water_box_name'], 0);

        $water_dirt_separater_id = safeCheck($_POST['water_dirt_separater_id'], 0);
        $water_dirt_separater_count = safeCheck($_POST['water_dirt_separater_count'], 0);
        $water_dirt_separater_name = safeCheck($_POST['water_dirt_separater_name'], 0);
        $water_dirt_separater_check = safeCheck($_POST['water_dirt_separater_check'], 0);

        $water_pipeline_pump_id = safeCheck($_POST['water_pipeline_pump_id']);
        $water_pipeline_pump_count = safeCheck($_POST['water_pipeline_pump_count']);
        $water_pipeline_pump_name = safeCheck($_POST['water_pipeline_pump_name'], 0);


        $hot_water_box_id = safeCheck($_POST['hot_water_box_id'],0);
        $hot_water_box_count = safeCheck($_POST['hot_water_box_count']);
        $hot_water_box_name = safeCheck($_POST['hot_water_box_name'], 0);

        $hotwater_pump_id = safeCheck($_POST['hotwater_pump_id'], 0);
        $hotwater_pump_count = safeCheck($_POST['hotwater_pump_count'], 0);
        $hotwater_pump_name = safeCheck($_POST['hotwater_pump_name'], 0);

        $hotwater_water_pump_id = safeCheck($_POST['hotwater_water_pump_id'], 0);
        $hotwater_water_pump_count = safeCheck($_POST['hotwater_water_pump_count'], 0);
        $hotwater_water_pump_name = safeCheck($_POST['hotwater_water_pump_name'], 0);
        $hotwater_water_pump_check = safeCheck($_POST['hotwater_water_pump_check'], 0);

        $water_board_value = safeCheck($_POST['water_board_value'], 0);
        $water_board_count = safeCheck($_POST['water_board_count'], 0);
        $water_board_name = safeCheck($_POST['water_board_name'], 0);
        $water_board_check = safeCheck($_POST['water_board_check'], 0);

        $water_pump_control = safeCheck($_POST['water_pump_control'], 0);
        $water_powe_box = safeCheck($_POST['water_powe_box'],0);

        $chimney_height2 = safeCheck($_POST['chimney_height2']);
        $chimney_diameter2 = safeCheck($_POST['chimney_diameter2']);

        //------------------采暖辅机选择---------------------
        //燃烧机
        $burner_check = safeCheck($_POST['burner_check']);
        //软水器
        $hdys_check = safeCheck($_POST['hdys_check']);
        //水箱
        $water_box_check = safeCheck($_POST['water_box_check']);
        //锅炉循环泵
        $pipeline_pump_check = safeCheck($_POST['pipeline_pump_check']);

        //钢制烟囱
        $chimney_check = safeCheck($_POST['chimney_check']);
        //分集水器
        $diversity_water_check = safeCheck($_POST['diversity_water_check']);
        //采暖水泵控制柜
        $heating_pump_control_check = safeCheck($_POST['heating_pump_control_check']);
        //热水水泵控制柜
        $water_pump_control_check = safeCheck($_POST['water_pump_control_check']);
        //配电柜
        $powe_box_check = safeCheck($_POST['powe_box_check']);
        //保温热水箱
        $hot_water_box_check = safeCheck($_POST['hot_water_box_check']);
        //热水循环泵
        $hotwater_pump_check = safeCheck($_POST['hotwater_pump_check'], 0);

        //------------------热水辅机选择---------------------
        //燃烧机
        $burner_check = safeCheck($_POST['burner_check']);
        //软水器
        $hdys_check = safeCheck($_POST['hdys_check']);
        //水箱
        $water_box_check = safeCheck($_POST['water_box_check']);
        //锅炉循环泵
        $pipeline_pump_check = safeCheck($_POST['pipeline_pump_check']);
        //采暖循环泵
        //$heating_pump_check = safeCheck($_POST['heating_pump_check']);
        //补水泵
        //$water_pump_check = safeCheck($_POST['water_pump_check']);
        //除污器
        //$dirt_separater_check = safeCheck($_POST['dirt_separater_check']);
        //采暖板换
        //$board_data_check = safeCheck($_POST['board_data_check']);
        //热水板换
        //$water_board_check = safeCheck($_POST['water_board_check']);
        //钢制烟囱
        $chimney_check = safeCheck($_POST['chimney_check']);
        //分集水器
        $diversity_water_check = safeCheck($_POST['diversity_water_check']);
        //采暖水泵控制柜
        $heating_pump_control_check = safeCheck($_POST['heating_pump_control_check']);
        //热水水泵控制柜
        $water_pump_control_check = safeCheck($_POST['water_pump_control_check']);
        //配电柜
        $powe_box_check = safeCheck($_POST['powe_box_check']);
        //保温热水箱
        $hot_water_box_check = safeCheck($_POST['hot_water_box_check']);
        //热水循环泵
        $hotwater_pump_check = safeCheck($_POST['hotwater_pump_check'], 0);

        $remark = HTMLEncode($_POST['remark']);
        $time = time();

        try {
            // 备注
            $attrsHistory = array(
                "remark" => $remark,
                "status" => 1,
                "lastupdate" => $time
            );
            Selection_history::update($id, $attrsHistory);
            $info = Selection_history::getInfoById($id);
            Selection_fuji::delByHistoryId($id);

            // 辅机------------------ 采暖 ---------------------
            if ($heating == 1) {
                $pwerstr = '';
                $boxpower = 0;
                // 燃烧机
                if ($heating_burner_id) {
                    $burner_info = Burner_attr::getInfoById($heating_burner_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $heating_burner_name,
                        "num" => $heating_burner_count,
                        "value" => $heating_burner_id,
                        "modelid" => 2,
                        "version_show" => $burner_info['version'],
                        "addtime" => $time,
                        "add_type" =>$burner_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 软水器
                if ($heating_hdys_id) {
                    $hdys_info = Hdys_attr::getInfoById($heating_hdys_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $heating_hdys_name,
                        "num" => $heating_hdys_count,
                        "value" => $heating_hdys_id,
                        "modelid" => 3,
                        "version_show" => $hdys_info['version'],
                        "addtime" => $time,
                        "add_type" =>$hdys_check

                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 水箱
                if ($heating_water_box_id) {
                    $water_box_info = Water_box_attr::getInfoById($heating_water_box_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $heating_water_box_name,
                        "num" => $heating_water_box_count,
                        "value" => $heating_water_box_id,
                        "modelid" => 8,
                        "version_show" => $water_box_info['version'],
                        "addtime" => $time,
                        "add_type" =>$water_box_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 锅炉循环泵
                if ($pipeline_pump_id) {
                    $pipeline_info = Pipeline_attr::getInfoById($pipeline_pump_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 1,
                        "name" => $pipeline_pump_name,
                        "num" => $pipeline_pump_count,
                        "value" => $pipeline_pump_id,
                        "modelid" => 4,
                        "version_show" => $pipeline_info['version'],
                        "addtime" => $time,
                        "add_type" =>$pipeline_pump_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 采暖循环泵
                $heating_pump_name = ltrim($heating_pump_name, '||');
                if ($heating_pump_name) {
                    $heating_pump_id = ltrim($heating_pump_id, '||');
                    $heating_pump_count = ltrim($heating_pump_count, '||');
                    $heating_pump_check = ltrim($heating_pump_check, '||');
                    $hp_id = explode('||', $heating_pump_id);
                    $hp_name = explode('||', $heating_pump_name);
                    $hp_count = explode('||', $heating_pump_count);
                    $hp_check = explode('||', $heating_pump_check);
                    for ($i = 0; $i < count($hp_id); $i++) {
                        $pump_info = Pipeline_attr::getInfoById($hp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 1,
                            "name" => $hp_name[$i],
                            "num" => $hp_count[$i],
                            "value" => $hp_id[$i],
                            "modelid" => 4,
                            "version_show" => $pump_info['version'],
                            "addtime" => $time,
                            "add_type" =>$hp_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                // 补水泵
                $water_pump_name = ltrim($water_pump_name, '||');
                if ($water_pump_name) {
                    $water_pump_id = ltrim($water_pump_id, '||');
                    $water_pump_count = ltrim($water_pump_count, '||');
                    $water_pump_check = ltrim($water_pump_check, '||');
                    $wp_id = explode('||', $water_pump_id);
                    $wp_name = explode('||', $water_pump_name);
                    $wp_count = explode('||', $water_pump_count);
                    $wp_check = explode('||', $water_pump_check);
                    for ($i = 0; $i < count($wp_id); $i++) {
                        $water_pump_info = Syswater_pump_attr::getInfoById($wp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 1,
                            "name" => $wp_name[$i],
                            "num" => $wp_count[$i],
                            "value" => $wp_id[$i],
                            "version_show" => $water_pump_info['version'],
                            "modelid" => 5,
                            "addtime" => $time,
                            "add_type" =>$wp_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                // 锅炉除污器

                // 分区除污器
                $dirt_separater_name = ltrim($dirt_separater_name, '||');
                if ($dirt_separater_name) {
                    $dirt_separater_id = ltrim($dirt_separater_id, '||');
                    $dirt_separater_count = ltrim($dirt_separater_count, '||');
                    $dirt_separater_check = ltrim($dirt_separater_check, '||');
                    $dirt_id = explode('||', $dirt_separater_id);
                    $dirt_name = explode('||', $dirt_separater_name);
                    $dirt_count = explode('||', $dirt_separater_count);
                    $dirt_check = explode('||', $dirt_separater_check);
                    for ($i = 0; $i < count($dirt_id); $i++) {
                        $dirt_separater_info = Dirt_separator_attr::getInfoById($dirt_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 1,
                            "name" => $dirt_name[$i],
                            "num" => $dirt_count[$i],
                            "value" => $dirt_id[$i],
                            "version_show" => $dirt_separater_info['version'],
                            "modelid" => 9,
                            "addtime" => $time,
                            "add_type" =>$dirt_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                // 采暖板换
                $board_separater_name = ltrim($board_name, '||');
                if ($board_separater_name) {
                    $board_separater_value = ltrim($board_value, '||');
                    $board_separater_count = ltrim($board_count, '||');
                    $board_separater_check = ltrim($board_check, '||');
                    $b_value = explode('||', $board_separater_value);
                    $b_name = explode('||', $board_separater_name);
                    $b_count = explode('||', $board_separater_count);
                    $b_check = explode('||', $board_separater_check);
                    for ($i = 0; $i < count($b_name); $i++) {
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 0,
                            "data_type" => 2,
                            "name" => $b_name[$i],
                            "num" => $b_count[$i],
                            "value" => $b_value[$i],
                            "addtime" => $time,
                            "add_type" =>$b_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                // 水泵控制柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 2,
                    "name" => '水泵控制柜',
                    "num" => 1,
                    "value" => $heating_pump_control,
                    "addtime" => $time,
                    "add_type" =>$heating_pump_control_check
                );
                Selection_fuji::add($fujiAttr);

                // 钢制烟囱
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 2,
                    "name" => '钢制烟囱',
                    "num" => 1,
                    "value" => '高度' . $chimney_height . 'm 直径'.$chimney_diameter.'mm',
                    "addtime" => $time,
                    "add_type" =>$chimney_check
                );
                Selection_fuji::add($fujiAttr);

                // 分集水器
                if ($diversity_water_num) {
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 0,
                        "data_type" => 2,
                        "name" => '分集水器',
                        "num" => 0,
                        "value" => '接口数量' . $diversity_water_num . ' 直径' . $diversity_water_diameter . 'mm' . ' 长度' . $diversity_water_length . 'm',
                        "addtime" => $time,
                        "add_type" =>$diversity_water_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 配电柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 0,
                    "data_type" => 2,
                    "name" => '配电柜',
                    "num" => 0,
                    "value" => '电负荷' . $heating_powe_box . 'kW',
                    "addtime" => $time,
                    "add_type" =>$powe_box_check
                );
                Selection_fuji::add($fujiAttr);
            }

            // 辅机 ---------------- 热水 -------------------------
            if ($hotwater == 1) {
                // 燃烧机
                if ($water_burner_id) {
                    $water_burner_info = Burner_attr::getInfoById($water_burner_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_burner_name,
                        "num" => $water_burner_count,
                        "value" => $water_burner_id,
                        "modelid" => 2,
                        "version_show" => $water_burner_info['version'],
                        "addtime" => $time,
                        "add_type" =>$burner_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 软水器
                if ($water_hdys_id) {
                    $water_hyds_info = Hdys_attr::getInfoById($water_hdys_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_hdys_name,
                        "num" => $water_hdys_count,
                        "value" => $water_hdys_id,
                        "modelid" => 3,
                        "version_show" => $water_hyds_info['version'],
                        "addtime" => $time,
                        "add_type" =>$hdys_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 水箱
                if ($water_water_box_id) {
                    $water_water_box_info = Water_box_attr::getInfoById($water_water_box_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_water_box_name,
                        "num" => $water_water_box_count,
                        "value" => $water_water_box_id,
                        "modelid" => 8,
                        "version_show" => $water_water_box_info['version'],
                        "addtime" => $time,
                        "add_type" =>$water_box_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 锅炉循环泵
                if ($water_pipeline_pump_id) {
                    $water_pipeline_pump_info = Pipeline_attr::getInfoById($water_pipeline_pump_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $water_pipeline_pump_name,
                        "num" => $water_pipeline_pump_count,
                        "value" => $water_pipeline_pump_id,
                        "modelid" => 4,
                        "version_show" => $water_pipeline_pump_info['version'],
                        "addtime" => $time,
                        "add_type" =>$pipeline_pump_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 除污器
                $water_dirt_separater_name = ltrim($water_dirt_separater_name, '||');
                if ($water_dirt_separater_name) {
                    $water_dirt_separater_id = ltrim($water_dirt_separater_id, '||');
                    $water_dirt_separater_count = ltrim($water_dirt_separater_count, '||');
                    $water_dirt_separater_check = ltrim($water_dirt_separater_check, '||');
                    $water_dirt_id = explode('||', $water_dirt_separater_id);
                    $water_dirt_name = explode('||', $water_dirt_separater_name);
                    $water_dirt_count = explode('||', $water_dirt_separater_count);
                    $water_dirt_check = explode('||', $water_dirt_separater_check);
                    for ($i = 0; $i < count($water_dirt_id); $i++) {
                        $water_dirt_separater_info = Dirt_separator_attr::getInfoById($water_dirt_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 1,
                            "name" => $water_dirt_name[$i],
                            "num" => $water_dirt_count[$i],
                            "value" => $water_dirt_id[$i],
                            "modelid" => 9,
                            "version_show" => $water_dirt_separater_info['version'],
                            "addtime" => $time,
                            "add_type" =>$water_dirt_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                // 板换
                $water_separater_board_name = ltrim($water_board_name, '||');
                if ($water_separater_board_name) {
                    $water_separater_board_value = ltrim($water_board_value, '||');
                    $water_separater_board_count = ltrim($water_board_count, '||');
                    $water_separater_board_check = ltrim($water_board_check, '||');
                    $water_b_value = explode('||', $water_separater_board_value);
                    $water_b_name = explode('||', $water_separater_board_name);
                    $water_b_count = explode('||', $water_separater_board_count);
                    $water_b_check = explode('||', $water_separater_board_check);
                    for ($i = 0; $i < count($water_b_name); $i++) {
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 2,
                            "name" => $water_b_name[$i],
                            "num" => $water_b_count[$i],
                            "value" => $water_b_value[$i],
                            "addtime" => $time,
                            "add_type" =>$water_b_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }

                // 水泵控制柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 1,
                    "data_type" => 2,
                    "name" => '水泵控制柜',
                    "num" => 1,
                    "value" => $water_pump_control,
                    "addtime" => $time,
                    "add_type" =>$water_pump_control_check
                );
                Selection_fuji::add($fujiAttr);

                // 钢制烟囱
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 1,
                    "data_type" => 2,
                    "name" => '钢制烟囱',
                    "num" => 1,
                    "value" => '高度' . $chimney_height2 . 'm 直径'.$chimney_diameter2.'mm',
                    "addtime" => $time,
                    "add_type" =>$chimney_check
                );
                Selection_fuji::add($fujiAttr);

                // 配电柜
                $fujiAttr = array(
                    "history_id" => $id,
                    "use_type" => 1,
                    "data_type" => 2,
                    "name" => '配电柜',
                    "num" => 0,
                    "value" => '电负荷' . $water_powe_box . 'kW',
                    "addtime" => $time,
                    "add_type" =>$powe_box_check
                );
                Selection_fuji::add($fujiAttr);

                // 保温热水箱
                if ($hot_water_box_id) {
                    $hot_water_box_info = Water_box_attr::getInfoById($hot_water_box_id);
                    $fujiAttr = array(
                        "history_id" => $id,
                        "use_type" => 1,
                        "data_type" => 1,
                        "name" => $hot_water_box_name,
                        "num" => $hot_water_box_count,
                        "value" => $hot_water_box_id,
                        "modelid" => 8,
                        "version_show" => $hot_water_box_info['version'],
                        "addtime" => $time,
                        "add_type" =>$hot_water_box_check
                    );
                    Selection_fuji::add($fujiAttr);
                }

                // 热水循环泵
                $hotwater_pump_name = ltrim($hotwater_pump_name, '||');
                if ($hotwater_pump_name) {
                    $hotwater_pump_id = ltrim($hotwater_pump_id, '||');
                    $hotwater_pump_count = ltrim($hotwater_pump_count, '||');
                    $hotwater_pump_check = ltrim($hotwater_pump_check, '||');
                    $htp_id = explode('||', $hotwater_pump_id);
                    $htp_name = explode('||', $hotwater_pump_name);
                    $htp_count = explode('||', $hotwater_pump_count);
                    $htp_check = explode('||', $hotwater_pump_check);
                    for ($i = 0; $i < count($htp_id); $i++) {
                        $pipeline_Pump_info = Pipeline_attr::getInfoById($htp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 1,
                            "name" => $htp_name[$i],
                            "num" => $htp_count[$i],
                            "value" => $htp_id[$i],
                            "modelid" => 4,
                            "version_show" => $pipeline_Pump_info['version'],
                            "addtime" => $time,
                            "add_type" =>$htp_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }
                // 变频供水泵
                $hotwater_water_pump_name = ltrim($hotwater_water_pump_name, '||');
                if ($hotwater_water_pump_name) {
                    $hotwater_water_pump_id = ltrim($hotwater_water_pump_id, '||');
                    $hotwater_water_pump_count = ltrim($hotwater_water_pump_count, '||');
                    $hotwater_water_pump_check = ltrim($hotwater_water_pump_check, '||');
                    $hwp_id = explode('||', $hotwater_water_pump_id);
                    $hwp_name = explode('||', $hotwater_water_pump_name);
                    $hwp_count = explode('||', $hotwater_water_pump_count);
                    $hwp_check = explode('||', $hotwater_water_pump_check);
                    for ($i = 0; $i < count($hwp_id); $i++) {
                        $hot_water_pump_info = Syswater_pump_attr::getInfoById($hwp_id[$i]);
                        $fujiAttr = array(
                            "history_id" => $id,
                            "use_type" => 1,
                            "data_type" => 1,
                            "name" => $hwp_name[$i],
                            "num" => $hwp_count[$i],
                            "value" => $hwp_id[$i],
                            "modelid" => 5,
                            "version_show" => $hot_water_pump_info['version'],
                            "addtime" => $time,
                            "add_type" =>$hwp_check[$i]
                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }
            }

            echo action_msg("保存成功！", 1);
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case  'edit_guolu_context':
        $id  = safeCheck($_POST['id'],0);
        $context = $_POST['context'];
        if(!empty($context)){
            $remark = implode(",",$context);
        }else{
            $remark=null;
        }
        $attrs = array("id" => $id,
            "guolu_context" => $remark);
        Selection_history::update($id,$attrs);
        echo action_msg("保存成功！", 1);
        break;

    /**
     * 初次进入手动选型页面点击报价时调用
     */
    case 'select_guolu_manual_new':

        $history_id = safeCheck($_POST['history_id']);
        $project_id = safeCheck($_POST['project_id']);
        $customer = safeCheck($_POST['customer'],0);

        $guoluStr = safeCheck($_POST['guoluStr'],0);
        $guoluNumStr = safeCheck($_POST['guoluNumStr'],0);
        $guoluContextStr = safeCheck($_POST['guoluContextStr'],0);

        $fujiNameStr = safeCheck($_POST['fujiNameStr'],0);
        $fujiVersionStr = safeCheck($_POST['fujiVersionStr'],0);
        $fujiNumStr = safeCheck($_POST['fujiNumStr'],0);
        $fujiContextStr = safeCheck($_POST['fujiContextStr'],0);

        // 去掉最后一个字符
        substr($guoluStr, 0, -1);
        substr($guoluNumStr, 0, -1);
        substr($guoluContextStr, 0, -1);
        // 去掉最后一个字符
        substr($fujiNameStr, 0, -1);
        substr($fujiVersionStr, 0, -1);
        substr($fujiNumStr, 0, -1);
        substr($fujiContextStr, 0, -1);

        $nowtime = time();
        try {

            // 先保存选型历史
            $attrsHistory = array(
                "user" => $USERId,
                "project_id"=>$project_id,
                "type" => selection_history::HISTORY_HAND,
                "customer" => $customer,
                "guolu_id" => $guoluStr,
                "guolu_num" => $guoluNumStr,
                "guolu_context" => $guoluContextStr,
                "status" => 1,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime
            );

            // 保存成功则得到选型历史id
            $history_id = Selection_history::add($attrsHistory);
            // 若选型历史id存在，则说明保存成功，继续存储辅机信息
            if (!empty($history_id)){
                // 存储辅机信息
                $fujiNameStr = ltrim($fujiNameStr, '||');
                if ($fujiNameStr) {
                    $fujiVersionStr = ltrim($fujiVersionStr, '||');
                    $fujiNumStr = ltrim($fujiNumStr, '||');
                    $fujiContextStr = ltrim($fujiContextStr, '||');
                    $fujiNameArr = explode('||', $fujiNameStr);
                    $fujiVersionArr = explode('||', $fujiVersionStr);
                    $fujiNumArr = explode('||', $fujiNumStr);
                    $fujiContextArr = explode('||', $fujiContextStr);
                    for ($i = 0; $i < count($fujiNameArr); $i++) {
                        //  "value" => $fujiVersionArr[$i], 不知道辅机的具体种类与id，只保存文本与version_show字段
                        $fujiAttr = array(
                            "history_id" => $history_id,
                            "data_type" => 2,
                            "add_type" => 1,
                            "name" => $fujiNameArr[$i],
                            "num" => $fujiNumArr[$i],
                            "version_show" => $fujiVersionArr[$i],
                            "context" =>$fujiContextArr[$i],
                            "addtime" => $nowtime

                        );
                        Selection_fuji::add($fujiAttr);
                    }
                }
                echo action_msg_data(  "保存成功",1, $history_id);
            }else{
                echo json_encode_cn(array('code' => 1, 'msg' => "保存失败！"));
            }
        } catch (MyException $e) {
            echo json_encode_cn(array('code' => 1, 'msg' => "保存失败！"));
            echo $e->jsonMsg();
        }
        break;

    /**
     * 从报价页面点击返回上一步回到手动选型页面，编辑后再次点击报价时调用
     */
    case 'update_guolu_manual_new':

        $history_id = safeCheck($_POST['history_id']);
        $customer = safeCheck($_POST['customer'],0);

        $guoluStr = safeCheck($_POST['guoluStr'],0);
        $guoluNumStr = safeCheck($_POST['guoluNumStr'],0);
        $guoluContextStr = safeCheck($_POST['guoluContextStr'],0);

        $fujiNameStr = safeCheck($_POST['fujiNameStr'],0);
        $fujiVersionStr = safeCheck($_POST['fujiVersionStr'],0);
        $fujiNumStr = safeCheck($_POST['fujiNumStr'],0);
        $fujiContextStr = safeCheck($_POST['fujiContextStr'],0);

        // 去掉最后一个字符
        substr($guoluStr, 0, -1);
        substr($guoluNumStr, 0, -1);
        substr($guoluContextStr, 0, -1);
        // 去掉最后一个字符
        substr($fujiNameStr, 0, -1);
        substr($fujiVersionStr, 0, -1);
        substr($fujiNumStr, 0, -1);
        substr($fujiContextStr, 0, -1);

        $nowtime = time();
        try {

            // 先更新选型历史
            $attrsHistory = array(
                "user" => $USERId,
                "customer" => $customer,
                "guolu_id" => $guoluStr,
                "guolu_num" => $guoluNumStr,
                "guolu_context" => $guoluContextStr,
                "status" => 1,
                "lastupdate" => $nowtime
            );

            // 更新数据
            $update_res = Selection_history::update($history_id,$attrsHistory);

            // 若更新历史成功
            if (!empty($update_res) && $update_res == 1){
                // 先删除辅机历史中的全部数据
                $delete_res = Selection_fuji::delByHistoryId($history_id);
                if ($delete_res >= 0) {
                    // 再重新存储辅机信息
                    $fujiNameStr = ltrim($fujiNameStr, '||');
                    if ($fujiNameStr) {
                        $fujiVersionStr = ltrim($fujiVersionStr, '||');
                        $fujiNumStr = ltrim($fujiNumStr, '||');
                        $fujiContextStr = ltrim($fujiContextStr, '||');
                        $fujiNameArr = explode('||', $fujiNameStr);
                        $fujiVersionArr = explode('||', $fujiVersionStr);
                        $fujiNumArr = explode('||', $fujiNumStr);
                        $fujiContextArr = explode('||', $fujiContextStr);
                        for ($i = 0; $i < count($fujiNameArr); $i++) {
                            //  "value" => $fujiVersionArr[$i], 不知道辅机的具体种类与id，只保存文本与version_show字段
                            $fujiAttr = array(
                                "history_id" => $history_id,
                                "data_type" => 2,
                                "add_type" => 1,
                                "name" => $fujiNameArr[$i],
                                "num" => $fujiNumArr[$i],
                                "version_show" => $fujiVersionArr[$i],
                                "context" => $fujiContextArr[$i],
                                "addtime" => $nowtime

                            );
                            Selection_fuji::add($fujiAttr);
                        }
                    }
                }
                echo action_msg_data(  "保存成功",1, $history_id);
            }else{
                echo json_encode_cn(array('code' => 1, 'msg' => "更新选型历史纪录失败！"));
            }

            // 清空selection_plan表中history_id为该history_id的数据
            $selection_plans = Selection_plan::getInfoByHistoryId($history_id);
            if (count($selection_plans) > 0){
                Selection_plan::delByHistoryId($history_id);
            }

        } catch (MyException $e) {
            echo json_encode_cn(array('code' => 1, 'msg' => "保存异常！"));
            echo $e->jsonMsg();
        }
        break;

    /**
     * 手动选型报价页面 根据厂家选择锅炉类型
     */
    case 'get_guolu_list_vender':

        $vender = safeCheck($_POST['vender'],0);

        try {
            $rs = Guolu_attr::getList(0,'', 0, $vender, '', '', '');
            if (count($rs) > 0) {
                echo action_msg_data("查询成功", 1, $rs);
            }else{
                echo action_msg_data("没有该条件下的锅炉类型", 101, $rs);
            }
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    /**
     * 手动选型报价页面 根据锅炉id获取锅炉历史价格数据
     */
    case 'get_guolu_price':

        $guolu_id = safeCheck($_POST['guolu_id']);

        try {
            $guoluinfo = Guolu_attr::getInfoById($guolu_id);
            $guoluItem = array();
            if (count($guoluinfo) > 0) {
                $countarr  = Case_pricelog::getPageList('', '', 0, 1, $guoluinfo['proid'], '', '', '');
                $count  = $countarr['ct'];
                $prices = Case_pricelog::getPageList('', '', 1, 1, $guoluinfo['proid'], '', '', '');

                $guoluItem["guoluinfo"] = $guoluinfo;
                $guoluItem["prices"] = $prices;
                $guoluItem["countarr"] = $countarr;
                echo action_msg_data("查询成功", 1, $guoluItem);
            }else{
                echo action_msg_data("没有该条件下的锅炉历史价格", 101, $guoluItem);
            }
        } catch (MyException $e) {
            echo action_msg_data("查询异常", 101, $guoluItem);
            echo $e->jsonMsg();
        }
        break;


    /**
     * 添加手动选型方案1 报价
     */
    case 'add_price_manual':

        $is_update = safeCheck($_POST['is_update']);

        $history_id = safeCheck($_POST['id']);
        // 锅炉价格
        $guolu_dataStr = safeCheck($_POST['guolu_dataStr'],0);
        // 已有辅机价格
        $fuji_sel_dataStr = safeCheck($_POST['fuji_sel_dataStr'],0);
        // 新增辅机价格
        $fuji_new_dataStr = safeCheck($_POST['fuji_new_dataStr'],0);
        // 已有其他项价格
        $other_dataStr = safeCheck($_POST['other_dataStr'],0);
        // 新增其他项价格
        $other_new_dataStr = safeCheck($_POST['other_new_dataStr'],0);

        $nowtime = time();
        $isPrepared = false;
        try {


            //-------------删除该history_id下面已有的plan_front（若存在）--------------------

            $history_plan_front = Selection_plan_front::getInfoByHistoryId($history_id);
            if(!empty($history_plan_front)){
                    $del_rs = Selection_plan_front::del($history_plan_front['id']);
            }

            //----------------------------------------------------------------------------

            if ($is_update != 0){
                $del_res = Selection_plan::delByHistoryId($history_id);
                if ($del_res >= 0){
                    $isPrepared = true;
                }
            }else{
                $isPrepared = true;
            }

            if ($isPrepared) {

                // 锅炉价格
                if ($guolu_dataStr != "") {
                    $guolus = explode("#", $guolu_dataStr);
                    foreach ($guolus as $guolu) {
                        /*锅炉表单的价格添加*/
                        $guolu_str = explode("||", $guolu);
                        // 将锅炉表单中数据添加到selection_plan表中
                        $guolu_attrs = array(
                            "history_id" => $history_id,
                            "equ_name" => "锅炉",
                            "version" => $guolu_str[3],
                            "nums" => $guolu_str[1],
                            "remark" => $guolu_str[2],
                            "tab_type" => 0, // 锅炉表单
                            "attrid" => $guolu_str[0],
                            "pro_price" => $guolu_str[4]
                        );
                        $guolu_rs = Selection_plan::add($guolu_attrs);
                    }
                }

                // 已有辅机价格
                if ($fuji_sel_dataStr != "") {

                    $fuji_sel_attr = explode("#", $fuji_sel_dataStr); // 将每一行的数据分割开

                    foreach ($fuji_sel_attr as $fuji_sel_value) {
                        $fuji_sel_str = explode("||", $fuji_sel_value);
                        $current_fuji_id = $fuji_sel_str[0];
                        $current_fuji_info = Selection_fuji::getInfoById($current_fuji_id);
                        if (!empty($current_fuji_info)) {
                            // 将辅机表单中数据添加到selection_plan表中
                            $fuji_sel_attrs = array(
                                "history_id" => $history_id,
                                "equ_name" => $current_fuji_info['name'],
                                "version" => $current_fuji_info['version_show'],
                                "nums" => $current_fuji_info['num'],
                                "remark" => $current_fuji_info['context'],
                                "tab_type" => 4, // 手动选型辅机
                                "attrid" => $current_fuji_info['id'],
                                "pro_price" => $fuji_sel_str[1]
                            );
                            $fuji_sel_rs = Selection_plan::add($fuji_sel_attrs);
                        }
                    }
                }

                // 新增辅机价格
                if ($fuji_new_dataStr != "") {

                    $fuji_new_attr = explode("#", $fuji_new_dataStr); // 将每一行的数据分割开

                    foreach ($fuji_new_attr as $fuji_new_value) {
                        $fuji_new_str = explode("||", $fuji_new_value);

                        // 将辅机表单中数据添加到selection_plan表中
                        $fuji_sel_attrs = array(
                            "history_id" => $history_id,
                            "equ_name" => $fuji_new_str[0],
                            "version" => $fuji_new_str[1],
                            "nums" => $fuji_new_str[2],
                            "remark" => $fuji_new_str[3],
                            "tab_type" => 4, // 手动选型辅机
                            "pro_price" => $fuji_new_str[4]
                        );
                        $fuji_new_rs = Selection_plan::add($fuji_sel_attrs);

                    }
                }

                // 已有其他项价格
                if ($other_dataStr != "") {

                    /*其他项表单的价格添加*/
                    $other_attr = explode("#", $other_dataStr); // 将每一行的数据分割开
                    foreach ($other_attr as $value) {
                        $other_str = explode("||", $value);

                        $attrs = array(
                            "history_id" => $history_id,
                            "equ_name" => $other_str[0],
                            "version" => $other_str[1],
                            "nums" => $other_str[2],
                            "remark" => $other_str[3],
                            "tab_type" => 3,//其他项
                            "attrid" => $other_str[5],
                            "pro_price" => !empty($other_str[4]) ? $other_str[4] : 0,
                        );
                        $rs = Selection_plan::add($attrs);
                    }
                }

                // 新增其他项价格
                if ($other_new_dataStr != "") {

                    /*其他项表单的价格添加*/
                    $other_new_attr = explode("#", $other_new_dataStr); // 将每一行的数据分割开
                    foreach ($other_new_attr as $new_value) {
                        $other_new_str = explode("||", $new_value);

                        $attrs = array(
                            "history_id" => $history_id,
                            "equ_name" => $other_new_str[0],
                            "version" => $other_new_str[1],
                            "nums" => $other_new_str[2],
                            "remark" => $other_new_str[3],
                            "tab_type" => 3,//其他项
                            "pro_price" => (!empty($other_new_str[4]) ? $other_new_str[4] : 0),
                        );
                        $rs = Selection_plan::add($attrs);
                    }
                }

                // 更新选型历史状态
                $attrsHistory = array(
                    "user" => $USERId,
                    "status" => 3,
                    "lastupdate" => $nowtime
                );

                // 更新数据
                $update_res = Selection_history::update($history_id,$attrsHistory);

                echo action_msg("成功", 1);
            }else{
                echo action_msg("未知异常", 212);
            }

        } catch (MyException $e) {
            echo action_msg("添加异常", 212);
            echo $e->jsonMsg();
        }
        break;




    case 'copy_history':
        $data = array();
        $data_fuji = array();
        $data_plan = array();
        $data_plan_front = $data_heating_attr = $data_hotwater_attr = array();
        $history_id = safeCheck($_POST['id']);

        //复制history表，返回复制后的history_id
        $attr = Selection_history::getInfoById($history_id);
        unset($attr['id']);
        foreach ($attr as $key=>$value)
        {
            if ($key=='status'){
                $data[$key] = 5;
            }
            elseif(is_numeric($value)){
                $data[$key] = $value;
            } elseif(empty($value)) {
                continue;
            }else{
                $data[$key] = $value;
            }
        }
        $history_newId = Selection_history::add($data);

        //复制plan表
        $attr_plan = Selection_plan::getInfoByHistoryId($history_id);
        if(!empty($attr_plan)) {
            foreach ($attr_plan as $item_plan) {
                unset($item_plan['id']);
                foreach ($item_plan as $key => $value) {
                    if(is_numeric($value)){
                        $data_plan[$key] = $value;
                    } elseif (empty($value)) continue;
                    if ($key == 'history_id') {
                        $data_plan[$key] = $history_newId;
                    } else {
                        $data_plan[$key] = $value;
                    }
                }
                $plan_NewId = Selection_plan::add($data_plan);

                $new_plan = Selection_plan::getInfoById($plan_NewId);
                if(!empty($new_plan)){
                    $fuji_id = $new_plan['attrid'];
                    $plan_type = $new_plan['tab_type'];
                    if($plan_type==1 or $plan_type==2 or $plan_type==4) {
                        if (!empty($fuji_id)) {
                            $attr_fuji = Selection_fuji::getInfoById($fuji_id);
                            if (!empty($attr_fuji)) {
                                unset($attr_fuji["id"]);
                                foreach ($attr_fuji as $k => $val) {
                                    if (is_numeric($val)) {
                                        $data_fuji[$k] = $val;
                                    } elseif (empty($val)) continue;
                                    if ($k == 'history_id') {
                                        $data_fuji[$k] = $history_newId;
                                    } else {
                                        $data_fuji[$k] = $val;
                                    }
                                }
                                $new_fuji = Selection_fuji::add($data_fuji);
                                $attrs = array("attrid" => $new_fuji);

                                Selection_plan::update($plan_NewId, $attrs);
                                unset($data_fuji);
                            }
                        }
                    }

                }
                unset($data_plan);
            }
        }

        //复制plan_front
        $attr_plan_front = Selection_plan_front::getInfoByHistoryId($history_id);
        if(!empty($attr_plan_front)) {
            unset($attr_plan_front['id']);
            foreach ($attr_plan_front as $key => $value) {
                if (empty($value)) continue;
                if ($key == 'history_id') {
                    $data_plan_front[$key] = $history_newId;
                } elseif ($key == 'status') {
                    $data_plan_front[$key] = 5;
                } elseif($key=='url') {
                    $data_plan_front[$key]=null;
                }else{
                    $data_plan_front[$key] = $value;
                }
            }
            Selection_plan_front::add($data_plan_front);
        }

//        复制heating_attr表
        $attr_heating_attr = Selection_heating_attr::getInfoByHistoryId($history_id);
        if(!empty($attr_heating_attr)) {
            foreach ($attr_heating_attr as $item) {
                unset($item['id']);
                foreach ($item as $key => $value) {
                    if(is_numeric($value)){
                        $data_heating_attr[$key] = $value;
                    } elseif (empty($value)) continue;
                    if ($key == 'history_id') {
                        $data_heating_attr[$key] = $history_newId;
                    } else {
                        $data_heating_attr[$key] = $value;
                    }
                }
                Selection_heating_attr::add($data_heating_attr);
                unset($data_heating_attr);
            }
        }

        //复制hotwater_attr表
        $attr_hotwater_attr = Selection_hotwater_attr::getCopyByHistoryId($history_id);
        if (!empty($attr_hotwater_attr)){
            foreach ($attr_hotwater_attr as $item){
                unset($item['id']);
                foreach ($item as $key=>$value){
                    if(is_numeric($value)){
                        $data_hotwater_attr[$key] = $value;
                    }
                    elseif (empty($value)) continue;

                    if($key=='history_id'){
                        $data_hotwater_attr[$key] = $history_newId;
                    } else{
                        $data_hotwater_attr[$key] = $value;
                    }
                }
                Selection_hotwater_attr::add($data_hotwater_attr);
                unset($data_hotwater_attr);
            }
        }

        echo json_encode_cn(array('code' => 1, 'msg' => "请稍后！", 'historyid' => $history_newId));
        break;

    /**
     * 修改
     *
     */
    case 'edit_nq_selection'://采暖选型

        $id= safeCheck($_POST['id'],0);
        $customer = safeCheck($_POST['customer'], 0);
        $guolu_position = safeCheck($_POST['guolu_position']);
        $guolu_height = safeCheck($_POST['guolu_height']);
        $underground_unm = $_POST['underground_unm'] ? safeCheck($_POST['underground_unm']) : 0;
        $guolu_num = safeCheck($_POST['guolu_num']);
        $application = safeCheck($_POST['application']);
        $heating_type = safeCheck($_POST['heating_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);

        $all_build_type = safeCheck($_POST['all_build_type'], 0);
        $all_floor_low = safeCheck($_POST['all_floor_low'], 0);
        $all_floor_high = safeCheck($_POST['all_floor_high'], 0);
        $all_floor_height = safeCheck($_POST['all_floor_height'], 0);
        $all_area = safeCheck($_POST['all_area'], 0);
        $all_type = safeCheck($_POST['all_type'], 0);
        $all_usetime_type = safeCheck($_POST['all_usetime_type'], 0);

        $all_build_type = trim($all_build_type, '||');
        $all_build_typeA = explode('||', $all_build_type);

        $all_floor_low = trim($all_floor_low, '||');
        $all_floor_lowA = explode('||', $all_floor_low);

        $all_floor_high = trim($all_floor_high, '||');
        $all_floor_highA = explode('||', $all_floor_high);

        $all_floor_height = trim($all_floor_height, '||');
        $all_floor_heightA = explode('||', $all_floor_height);

        $all_area = trim($all_area, '||');
        $all_areaA = explode('||', $all_area);

        $all_type = trim($all_type, '||');
        $all_typeA = explode('||', $all_type);

        $all_usetime_type = trim($all_usetime_type, '||');
        $all_usetime_typeA = explode('||', $all_usetime_type);
        $nowtime = time();
        try {
            $attrsHistory = array(
                "customer" => $customer,
                "guolu_position" => $guolu_position,
                "guolu_height" => $guolu_height,
                "guolu_num" => $guolu_num,
                "underground_unm" => $underground_unm,
                "application" => $application,
                "heating_type" => $heating_type,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "user" => $USERId,
                "status" => 0,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime,
                "status"=>Selection_history::HISTORY_Selection
            );
            $historyid = Selection_history::update($id,$attrsHistory);

            if ($historyid >= 0) {
                Selection_heating_attr::delByHistoryId($id);
                for ($i = 0; $i < count($all_build_typeA); $i++) {
                    if ($all_build_typeA[$i]) {
                        $heatingArray = array(
                            "history_id" => $id,
                            "build_type" => $all_build_typeA[$i],
                            "floor_low" => $all_floor_lowA[$i],
                            "floor_high" => $all_floor_highA[$i],
                            "floor_height" => $all_floor_heightA[$i],
                            "area" => $all_areaA[$i],
                            "type" => $all_typeA[$i],
                            "usetime_type" => $all_usetime_typeA[$i],
                            "addtime" => $nowtime
                        );
                        Selection_heating_attr::add($heatingArray);
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $id));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;
    case 'edit_hotwater_selection'://热水选型
        $id= safeCheck($_POST['id'],0);
        $customer = safeCheck($_POST['customer'], 0);
        $guolu_position = safeCheck($_POST['guolu_position']);
        $guolu_height = safeCheck($_POST['guolu_height']);
        $underground_unm = $_POST['underground_unm'] ? safeCheck($_POST['underground_unm']) : 0;
        $guolu_num = safeCheck($_POST['guolu_num']);
        $application = safeCheck($_POST['application']);
        $water_type = safeCheck($_POST['water_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);

        $all_build_type = safeCheck($_POST['all_build_type'], 0);
        $all_buildattr_id = safeCheck($_POST['all_buildattr_id'], 0);
        $all_attr_num = safeCheck($_POST['all_attr_num'], 0);
        $all_usetime_type = safeCheck($_POST['all_usetime_type'], 0);
        $all_heating_area = safeCheck($_POST['all_heating_area'], 0);
        $all_timing_same_use = safeCheck($_POST['all_timing_same_use'], 0);

        $all_hotwater_floor_low = safeCheck($_POST['all_hotwater_floor_low'], 0);
        $all_hotwater_floor_high= safeCheck($_POST['all_hotwater_floor_high'], 0);
        $all_hotwater_floor_height = safeCheck($_POST['all_hotwater_floor_height'], 0);

        $all_hotwater_floor_low = trim($all_hotwater_floor_low, '||');
        $all_hotwater_floor_low_value = explode('||', $all_hotwater_floor_low);
        $all_hotwater_floor_high = trim($all_hotwater_floor_high, '||');
        $all_hotwater_floor_high_value = explode('||', $all_hotwater_floor_high);
        $all_hotwater_floor_height = trim($all_hotwater_floor_height, '||');
        $all_hotwater_floor_height_value = explode('||', $all_hotwater_floor_height);


        $all_build_type = trim($all_build_type, '||');
        $all_build_typeA = explode('||', $all_build_type);

        $all_buildattr_id = trim($all_buildattr_id, '||');
        $all_buildattr_idA = explode('||', $all_buildattr_id);

        $all_attr_num = trim($all_attr_num, '||');
        $all_attr_numA = explode('||', $all_attr_num);

        $all_usetime_type = trim($all_usetime_type, '||');
        $all_usetime_typeA = explode('||', $all_usetime_type);

        $all_heating_area = trim($all_heating_area, '||');
        $all_heating_areaA = explode('||', $all_heating_area);

        $all_timing_same_use = trim($all_timing_same_use, '||');
        $all_timing_same_useA = explode('||', $all_timing_same_use);
        $nowtime = time();
        try {
            $attrsHistory = array(
                "customer" => $customer,
                "guolu_position" => $guolu_position,
                "guolu_height" => $guolu_height,
                "guolu_num" => $guolu_num,
                "underground_unm" => $underground_unm,
                "application" => $application,
                "water_type" => $water_type,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "user" => $USERId,
                "status" => 0,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime,
                "status"=>Selection_history::HISTORY_Selection
            );
            $historyid = Selection_history::update($id,$attrsHistory);
            if ($historyid >= 0) {
                Selection_hotwater_attr::delByHistoryId($id);
                for ($i = 0; $i < count($all_usetime_typeA); $i++) {
                    if ($all_usetime_typeA[$i]) {
                        $buildattr_id = trim($all_buildattr_idA[$i], '##');
                        $buildattr_idA = explode('##', $buildattr_id);

                        $attr_num = trim($all_attr_numA[$i], '##');
                        $attr_numA = explode('##', $attr_num);
                        for ($j = 0; $j < count($buildattr_idA); $j++) {
                            if ($buildattr_idA[$j]) {
                                $hotArray = array(
                                    "history_id" => $id,
                                    "param_id" => count($all_usetime_typeA) - $i,
                                    "build_type" => $all_build_typeA[$i],
                                    "heating_area" => $all_heating_areaA[$i],
                                    "use_type" => $all_usetime_typeA[$i],
                                    "buildattr_id" => $buildattr_idA[$j],
                                    "attr_num" => $attr_numA[$j],
                                    "same_use" => ($all_timing_same_useA[$i] / 100),
                                    "floor_low" => $all_hotwater_floor_low_value[$i],
                                    "floor_high" => $all_hotwater_floor_high_value[$i],
                                    "floor_height" => $all_hotwater_floor_height_value[$i],
                                    "addtime" => $nowtime
                                );
                                Selection_hotwater_attr::add($hotArray);
                            }
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $id));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'edit_nq_rs_selection'://采暖+热水选型
        $id= safeCheck($_POST['id'],0);
        $customer = safeCheck($_POST['customer'], 0);
        $guolu_position = safeCheck($_POST['guolu_position']);
        $guolu_height = safeCheck($_POST['guolu_height']);
        $underground_unm = $_POST['underground_unm'] ? safeCheck($_POST['underground_unm']) : 0;
        $guolu_num = safeCheck($_POST['guolu_num']);
        $application = safeCheck($_POST['application']);
        $heating_type = safeCheck($_POST['heating_type']);
        $water_type = safeCheck($_POST['water_type']);
        $is_condensate = safeCheck($_POST['is_condensate']);
        $is_lownitrogen = safeCheck($_POST['is_lownitrogen']);
        //采暖
        $all_build_type_nq = safeCheck($_POST['all_build_type_nq'], 0);
        $all_floor_low = safeCheck($_POST['all_floor_low'], 0);
        $all_floor_high = safeCheck($_POST['all_floor_high'], 0);
        $all_floor_height = safeCheck($_POST['all_floor_height'], 0);
        $all_area = safeCheck($_POST['all_area'], 0);
        $all_type = safeCheck($_POST['all_type'], 0);
        $all_usetime_type_nq = safeCheck($_POST['all_usetime_type_nq'], 0);

        $all_build_type_nq = trim($all_build_type_nq, '||');
        $all_build_type_nqA = explode('||', $all_build_type_nq);

        $all_usetime_type_nq = trim($all_usetime_type_nq, '||');
        $all_usetime_type_nqA = explode('||', $all_usetime_type_nq);

        $all_floor_low = trim($all_floor_low, '||');
        $all_floor_lowA = explode('||', $all_floor_low);

        $all_floor_high = trim($all_floor_high, '||');
        $all_floor_highA = explode('||', $all_floor_high);

        $all_floor_height = trim($all_floor_height, '||');
        $all_floor_heightA = explode('||', $all_floor_height);

        $all_area = trim($all_area, '||');
        $all_areaA = explode('||', $all_area);

        $all_type = trim($all_type, '||');
        $all_typeA = explode('||', $all_type);
        //热水
        $all_build_type = safeCheck($_POST['all_build_type'], 0);
        $all_buildattr_id = safeCheck($_POST['all_buildattr_id'], 0);
        $all_attr_num = safeCheck($_POST['all_attr_num'], 0);
        $all_usetime_type = safeCheck($_POST['all_usetime_type'], 0);
        $all_heating_area = safeCheck($_POST['all_heating_area'], 0);
        $all_timing_same_use = safeCheck($_POST['all_timing_same_use'], 0);

        $all_hotwater_floor_low = safeCheck($_POST['all_hotwater_floor_low'], 0);
        $all_hotwater_floor_high= safeCheck($_POST['all_hotwater_floor_high'], 0);
        $all_hotwater_floor_height = safeCheck($_POST['all_hotwater_floor_height'], 0);

        $all_hotwater_floor_low = trim($all_hotwater_floor_low, '||');
        $all_hotwater_floor_low_value = explode('||', $all_hotwater_floor_low);
        $all_hotwater_floor_high = trim($all_hotwater_floor_high, '||');
        $all_hotwater_floor_high_value = explode('||', $all_hotwater_floor_high);
        $all_hotwater_floor_height = trim($all_hotwater_floor_height, '||');
        $all_hotwater_floor_height_value = explode('||', $all_hotwater_floor_height);

        $all_build_type = trim($all_build_type, '||');
        $all_build_typeA = explode('||', $all_build_type);

        $all_buildattr_id = trim($all_buildattr_id, '||');
        $all_buildattr_idA = explode('||', $all_buildattr_id);

        $all_attr_num = trim($all_attr_num, '||');
        $all_attr_numA = explode('||', $all_attr_num);

        $all_usetime_type = trim($all_usetime_type, '||');
        $all_usetime_typeA = explode('||', $all_usetime_type);

        $all_heating_area = trim($all_heating_area, '||');
        $all_heating_areaA = explode('||', $all_heating_area);

        $all_timing_same_use = trim($all_timing_same_use, '||');
        $all_timing_same_useA = explode('||', $all_timing_same_use);
        $nowtime = time();
        try {
            $attrsHistory = array(
                "customer" => $customer,
                "guolu_position" => $guolu_position,
                "guolu_height" => $guolu_height,
                "guolu_num" => $guolu_num,
                "underground_unm" => $underground_unm,
                "application" => $application,
                "heating_type" => $heating_type,
                "water_type" => $water_type,
                "is_condensate" => $is_condensate,
                "is_lownitrogen" => $is_lownitrogen,
                "user" => $USERId,
                "status" => 0,
                "addtime" => $nowtime,
                "lastupdate" => $nowtime
            );
            $historyid = Selection_history::update($id,$attrsHistory);
            if ($historyid >= 0) {
                //采暖
                Selection_heating_attr::delByHistoryId($id);
                Selection_hotwater_attr::delByHistoryId($id);
                for ($k = 0; $k < count($all_build_type_nqA); $k++) {
                    if ($all_build_type_nqA[$k]) {
                        $heatingArray = array(
                            "history_id" => $id,
                            "build_type" => $all_build_type_nqA[$k],
                            "floor_low" => $all_floor_lowA[$k],
                            "floor_high" => $all_floor_highA[$k],
                            "floor_height" => $all_floor_heightA[$k],
                            "area" => $all_areaA[$k],
                            "type" => $all_typeA[$k],
                            "usetime_type" => $all_usetime_type_nqA[$k],
                            "addtime" => $nowtime
                        );
                        Selection_heating_attr::add($heatingArray);
                    }
                }
                //热水
                for ($i = 0; $i < count($all_usetime_typeA); $i++) {
                    if ($all_usetime_typeA[$i]) {
                        $buildattr_id = trim($all_buildattr_idA[$i], '##');
                        $buildattr_idA = explode('##', $buildattr_id);

                        $attr_num = trim($all_attr_numA[$i], '##');
                        $attr_numA = explode('##', $attr_num);
                        for ($j = 0; $j < count($buildattr_idA); $j++) {
                            if ($buildattr_idA[$j]) {
                                $hotArray = array(
                                    "history_id" => $id,
                                    "param_id" => count($all_usetime_typeA) - $i,
                                    "heating_area" => $all_heating_areaA[$i],
                                    "build_type" => $all_build_typeA[$i],
                                    "use_type" => $all_usetime_typeA[$i],
                                    "buildattr_id" => $buildattr_idA[$j],
                                    "attr_num" => $attr_numA[$j],
                                    "same_use" => ($all_timing_same_useA[$i] / 100),
                                    "floor_low" => $all_hotwater_floor_low_value[$i],
                                    "floor_high" => $all_hotwater_floor_high_value[$i],
                                    "floor_height" => $all_hotwater_floor_height_value[$i],
                                    "addtime" => $nowtime
                                );
                                Selection_hotwater_attr::add($hotArray);
                            }
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'historyid' => $id));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }

        break;

    //通过tplContentId获取tplId
    case 'getOtherTplcontentIdByTplId':

        $tplContentId = safeCheck($_POST['tplContentId']);
        $attrId = safeCheck($_POST['attrId']);


        try {

            $contentInfo = Case_tplcontent::getContentByTplcontentId($tplContentId);
            $tplId = $contentInfo[0]['tplid'];
            $companyCompetencyContentId = Table_case_tplcontent::getInfoByAttridAndTplid($attrId,$tplId);
            $resultId = $companyCompetencyContentId[0]['id'];
            echo action_msg_data("查询成功", 1, $resultId);

        } catch (MyException $e) {
            echo action_msg_data("查询异常", 101, $resultId);
            echo $e->jsonMsg();
        }
        break;

    case  'edit_water_box_value':

        $box_capacity = $_POST['box_capacity'];
        $hot_water_box_capacity = $_POST['hot_water_box_capacity'];
        $number = $_POST['number'];
        $box_capacity_value=$hot_water_box_capacity*$box_capacity*0.001;
        try {

            $str = '<div class="XXRmain_8">
                             <div class="XXRmain_10">&nbsp;</div>
                              <div class="XXRmain_11">公称容积<span id="hw_hot_water_box_value'.$number.'">'.$box_capacity_value.'</span>m³</div>
                      </div>';

            echo json_encode_cn(array("msg" => $str, "code" => 1));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'edit_change':
        $id= safeCheck($_POST['id'],0);
        $application = safeCheck($_POST['application']);
        $nowtime = time();
        $info = Selection_history::getInfoById($id);
        if($application!=$info['heating_type']){
            $attrsHistory = array(
                "guolu_id"=>'0',
                "guolu_num"=>null,
                "heating_type" => $application,
                "hm_heating_type"=>0,
                "board_power"=>0.00,
                "lastupdate" => $nowtime,
                "guolu_context"=>null
            );
            Selection_history::update($id,$attrsHistory);
        }
        try {
            $row = Selection_history::getInfoById($id);
        echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'type' => $row['heating_type']));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    case 'get_vender':
        $vender = safeCheck($_POST['vender'],0);

        try {
           $rs = Dict::getListByParentid($vender);
            if (count($rs) > 0) {
                echo action_msg_data("查询成功", 1, $rs);
                //  return  json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'rs' => $rs));
            }else{
                echo action_msg_data("没有该条件下的锅炉类型", 101, $rs);
            }

        } catch (MyException $e) {
            echo $e->jsonMsg();
            //  return action_msg_data("查询错误", 101, $rs);
        }
        break;

}
?>