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

$act = safeCheck($_GET['act'], 0);
switch($act) {
    case 'addprice'://添加选型方案1报价
        $history_id = safeCheck($_POST['id']);
        $guolu_dataStr = safeCheck($_POST['guolu_dataStr'], 0);
        $fuji_sel_dataStr = safeCheck($_POST['fuji_sel_dataStr'], 0);
        $fuji_text_dataStr = safeCheck($_POST['fuji_text_dataStr'], 0);
        $water_sel_dataStr = safeCheck($_POST['water_sel_dataStr'], 0);
        $water_text_dataStr = safeCheck($_POST['water_text_dataStr'], 0);
//        $_SESSION['fuji_sel_dataStr']=safeCheck($_POST['fuji_sel_dataStr'], 0);
        $other_dataStr = safeCheck($_POST['other_dataStr'], 0);


//----------------自定义设备参数--------------------------------------------

//采暖辅机参数
        $heat_addStr = safeCheck($_POST['heat_addStr'], 0);
//热水辅机参数
        $water_addStr = safeCheck($_POST['water_addStr'], 0);
//其他项参数
        $other_new_dataStr = safeCheck($_POST['other_new_dataStr'], 0);







        try {
            global $mypdo;
            $mypdo->pdo->beginTransaction();

            if ($guolu_dataStr != "") {

                $guolus = explode("#", $guolu_dataStr);//锅炉表单
//                print_r($guolus);

                foreach ($guolus as $guolu) {
                    /*锅炉表单的价格添加*/
                    $guolu_str = explode("||", $guolu);//锅炉表单
                    $guolu_attrs = array(//将锅炉表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type" => 0,   //锅炉
                        "remark"=>"$guolu_str[4]",
                        "version"=>"$guolu_str[3]",
                        "nums"=>$guolu_str[2],
                        "attrid" => $guolu_str[1],
                        "pro_price" => $guolu_str[0]
                    );

                     Selection_plan::add($guolu_attrs);
                }
            }

            /*-----------------采暖辅机-----------------------*/
             //后台原有数据
            if ($fuji_sel_dataStr != "") {
               //辅机表单可以没有数据

                $fuji_sel_attr = explode("#", $fuji_sel_dataStr);//将每一行的数据分割开
                foreach ($fuji_sel_attr as $fuji_sel_value) {
                    $fuji_sel_str = explode("-", $fuji_sel_value);
                    $fuji_sel_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type" => 1,   //采暖辅机
                        "attrid" => $fuji_sel_str[0],
                        "modelid" => $fuji_sel_str[2],
                        "pro_price" => $fuji_sel_str[1]
                    );
                     Selection_plan::add($fuji_sel_attrs);
                }
            }

          //前端重新添加的，只有自定义价格
            if ($fuji_text_dataStr != "") {//辅机表单可以没有数据
                $fuji_text_attr = explode("#", $fuji_text_dataStr);//将每一行的数据分割开
                foreach ($fuji_text_attr as $fuji_text_value) {
                    $fuji_text_str = explode("-", $fuji_text_value);
//
                    $fuji_text_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type" => 1,   //采暖辅机
                        "attrid" => $fuji_text_str[0],
                        "pro_price" => $fuji_text_str[1]
                    );
                     Selection_plan::add($fuji_text_attrs);
                }
            }

            /*-------------------热水辅机-----------*/
            //后台原有数据
            if ($water_sel_dataStr != "") {//辅机表单可以没有数据
                $water_sel_attr = explode("#", $water_sel_dataStr);//将每一行的数据分割开
                foreach ($water_sel_attr as $water_sel_value) {
                    $water_sel_str = explode("-", $water_sel_value);
                    $water_sel_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type" => 2,   //热水辅机
                        "attrid" => $water_sel_str[0],
                        "modelid" => $water_sel_str[2],
                        "pro_price" => $water_sel_str[1]
                    );
                     Selection_plan::add($water_sel_attrs);

                }
            }
            ;
          //前端重新添加的，只有自定义价格
            if ($water_text_dataStr != "") {//辅机表单可以没有数据
                $water_text_attr = explode("#", $water_text_dataStr);//将每一行的数据分割开
                foreach ($water_text_attr as $water_text_value) {
                    $water_text_str = explode("-", $water_text_value);
                    $water_text_attrs = array(//将辅机表单中数据添加到selection_plan表中
                        "history_id" => $history_id,
                        "tab_type" => 2,   //热水辅机
                        "attrid" => $water_text_str[0],
                        "pro_price" => $water_text_str[1]
                    );
                     Selection_plan::add($water_text_attrs);
                }
            }
            /*-------------其他项表单的价格添加--------------------------*/
            if ($other_dataStr != "") {

                $other_attr = explode("#", $other_dataStr);//将每一行的数据分割开
                foreach ($other_attr as $value) {
                    $other_str = explode("-", $value);
                    $attrs = array(
                        "history_id" => $history_id,
                        "tab_type" => 3,//其他项
                        "equ_name"=>"$other_str[0]",
                        "version"=>"$other_str[1]",
                        "remark" => "$other_str[3]",
                        "nums"=>$other_str[2],
                        "attrid" => $other_str[5],
                        "pro_price" => !empty($other_str[4]) ? $other_str[4] : 0,
                    );
                    Selection_plan::add($attrs);
                }
            }

//----------------------------------自定义设备添加------------------------------------------


            //自定义采暖辅机参数
            if ($heat_addStr != "") {
                $heat_str = explode('#', $heat_addStr);
                foreach ($heat_str as $heat_Value) {
                    $heatinfo = explode('||', $heat_Value);
//   不知道辅机的具体种类与id，只保存文本；设备的名称，规格型号和备注格式是字符串形式。
                    $heat_Attr = array(
                        "history_id" => $history_id,
                        "equ_name" => "$heatinfo[0]",
                        "tab_type" => 1,
                        "nums" => $heatinfo[2],
                        "version" => "$heatinfo[1]",
                        "remark" => "$heatinfo[3]",
                        "pro_price" => $heatinfo[4],

                    );
                    Selection_plan::add($heat_Attr);
                }
            }
            //自定义热水辅机修改参数
            if ($water_addStr != "") {
                $water_str = explode('#', $water_addStr);
                foreach ($water_str as $water_Value) {
                    $waterinfo = explode('||', $water_Value);
//  不知道辅机的具体种类与id，只保存文本;设备的名称，规格型号和备注格式是字符串形式。
                    $water_Attr = array(
                        "history_id" => $history_id,
                        "equ_name" => "$waterinfo[0]",
                        "tab_type" => 2,
                        "nums" => $waterinfo[2],
                        "version" => "$waterinfo[1]",
                        "remark" => "$waterinfo[3]",
                        "pro_price" => $waterinfo[4],

                    );
                    Selection_plan::add($water_Attr);
                }
            }
            //自定义其他项参数
            if ($other_new_dataStr != "") {

                $other_new_attr = explode('#', $other_new_dataStr);
                foreach($other_new_attr as $other_new_value){
                    $other_new_info = explode('-', $other_new_value);
//  不知道辅机的具体种类与id，只保存文本
                    $other_Attr = array(
                        "history_id" => $history_id,
                        "equ_name" => "$other_new_info[0]",
                        "tab_type" => 3,
                        "version" => "$other_new_info[1]",
                        "nums" => $other_new_info[2],
                        "remark" => "$other_new_info[3]",
                        "pro_price" => $other_new_info[4],

                    );
                      Selection_plan::add($other_Attr);
                }
            }
            //更新selection_history表的状态
            $status_attr = array(
                "user" => $USERId,
                "status" => 3,
                "lastupdate" => time()
            );
            Selection_history::update($history_id, $status_attr);


            $mypdo->pdo->commit();
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'history_id' => $history_id ));

        } catch (MyException $e) {
            $mypdo->pdo->rollBack();
            echo $e->jsonMsg();
        }
        break;

    case 'updateprice'://添加选型方案1报价


        $history_id = safeCheck($_POST['id']);
        $guolu_dataStr = safeCheck($_POST['guolu_dataStr'], 0);
        $fuji_sel_dataStr = safeCheck($_POST['fuji_sel_dataStr'], 0);
        $fuji_text_dataStr = safeCheck($_POST['fuji_text_dataStr'], 0);
        $water_sel_dataStr = safeCheck($_POST['water_sel_dataStr'], 0);
        $water_text_dataStr = safeCheck($_POST['water_text_dataStr'], 0);
//        $_SESSION['fuji_sel_dataStr']=safeCheck($_POST['fuji_sel_dataStr'], 0);
        $other_dataStr = safeCheck($_POST['other_dataStr'], 0);

//----------------自定义设备参数--------------------------------------------
//修改参数

        $heat_addStr = safeCheck($_POST['heat_addStr'], 0);
        $water_addStr = safeCheck($_POST['water_addStr'], 0);
//   添加

        $heat_newStr = safeCheck($_POST['heat_newStr'], 0);
        $water_newStr = safeCheck($_POST['water_newStr'], 0);
        $other_new_dataStr = safeCheck($_POST['other_new_dataStr'], 0);




        try {
            global $mypdo;
            $mypdo->pdo->beginTransaction();

            /*-------------------------清除报价方案页面数据------------------------------------*/
            // 清空selection_plan表中history_id为该history_id的数据
            $selection_plans = Selection_plan::getInfoByHistoryId($history_id);
            if (count($selection_plans) > 0){
                $del_rs=Selection_plan::delByHistoryId($history_id);
            }
            if($del_rs >= 0) {//清除之前的数据后再进行添加操作

                /*--------------------锅炉-----------------------------*/
                if ($guolu_dataStr != "") {

                    $guolus = explode("#", $guolu_dataStr);//锅炉表单

                    foreach ($guolus as $guolu) {
                        /*锅炉表单的价格添加*/
                        $guolu_str = explode("||", $guolu);
                        $guolu_attrs = array(//将锅炉表单中数据添加到selection_plan表中
                            "history_id" => $history_id,
                            "tab_type" => 0,   //锅炉
                            "nums" => $guolu_str[0],
                            "remark" => "$guolu_str[1]",
                            "version" => "$guolu_str[2]",
                            "attrid" => $guolu_str[4],
                            "pro_price" => $guolu_str[3]
                        );

                        Selection_plan::add($guolu_attrs);
                    }

                }


                /*--------------------采暖辅机-----------------------------*/
//后台原有数据
                if ($fuji_sel_dataStr != "") {
//辅机表单可以没有数据
                    $fuji_sel_attr = explode("#", $fuji_sel_dataStr);//将每一行的数据分割开
//                print_r($fuji_sel_attr);
//                exit;
                    foreach ($fuji_sel_attr as $fuji_sel_value) {
                        $fuji_sel_str = explode("-", $fuji_sel_value);
                        $fuji_sel_attrs = array(//将辅机表单中数据添加到selection_plan表中
                            "history_id" => $history_id,
                            "tab_type" => 1,   //采暖辅机
                            "attrid" => $fuji_sel_str[0],
                            "modelid" => $fuji_sel_str[2],
                            "pro_price" => $fuji_sel_str[1]
                        );
                        Selection_plan::add($fuji_sel_attrs);


                    }
                }


//前端重新添加的，只有自定义价格
                if ($fuji_text_dataStr != "") {
//辅机表单可以没有数据

                    $fuji_text_attr = explode("#", $fuji_text_dataStr);//将每一行的数据分割开
                    foreach ($fuji_text_attr as $fuji_text_value) {
                        $fuji_text_str = explode("-", $fuji_text_value);
                        $fuji_text_attrs = array(//将辅机表单中数据添加到selection_plan表中
                            "history_id" => $history_id,
                            "tab_type" => 1,   //采暖辅机
                            "attrid" => $fuji_text_str[0],
                            "pro_price" => $fuji_text_str[1]
                        );
                        Selection_plan::add($fuji_text_attrs);
                    }
                }

                /*---------------热水辅机------------------------------*/
//后台原有数据
                if ($water_sel_dataStr != "") //辅机表单可以没有数据
                {
                    $water_sel_attr = explode("#", $water_sel_dataStr);//将每一行的数据分割开
                    foreach ($water_sel_attr as $water_sel_value) {
                        $water_sel_str = explode("-", $water_sel_value);
                        $water_sel_attrs = array(//将辅机表单中数据添加到selection_plan表中
                            "history_id" => $history_id,
                            "tab_type" => 2,   //热水辅机
                            "attrid" => $water_sel_str[0],
                            "modelid" => $water_sel_str[2],
                            "pro_price" => $water_sel_str[1]
                        );
                        Selection_plan::add($water_sel_attrs);
                    }
                }


//前端重新添加的，只有自定义价格
                if ($water_text_dataStr != "") {
//辅机表单可以没有数据
                    $water_text_attr = explode("#", $water_text_dataStr);//将每一行的数据分割开
                    foreach ($water_text_attr as $water_text_value) {
                        $water_text_str = explode("-", $water_text_value);
                        $water_text_attrs = array(//将辅机表单中数据添加到selection_plan表中
                            "history_id" => $history_id,
                            "tab_type" => 2,   //热水辅机
                            "attrid" => $water_text_str[0],
                            "pro_price" => $water_text_str[1]
                        );
                        Selection_plan::add($water_text_attrs);
                    }
                }

                if ($other_dataStr != "") {
                    /*其他项表单的价格添加*/
                    $other_attr = explode("#", $other_dataStr);//将每一行的数据分割开
                    foreach ($other_attr as $value) {
                        $other_str = explode("||", $value);

                        $attrs = array(
                            "history_id" => $history_id,
                            "tab_type" => 3,//其他项
                            "equ_name" => "$other_str[0]",
                            "version" => "$other_str[1]",
                            "nums" => $other_str[2],
                            "remark" => "$other_str[3]",
                            "attrid" => $other_str[5],
                            "pro_price" => !empty($other_str[4]) ? $other_str[4] : 0,
                        );
                        Selection_plan::add($attrs);
                    }
                }


//----------------------------------自定义设备添加------------------------------------------
                //自定义采暖辅机修改参数
                if ($heat_addStr != "") {
                    $heat_str = explode('#', $heat_addStr);
                    foreach ($heat_str as $heat_Value) {
                        $heatinfo = explode('-', $heat_Value);
//   不知道辅机的具体种类与id，只保存文本；设备的名称，规格型号和备注格式是字符串形式。
                        $heat_Attr = array(
                            "history_id" => $history_id,
                            "equ_name" => "$heatinfo[0]",
                            "tab_type" => 1,
                            "nums" => $heatinfo[2],
                            "version" => "$heatinfo[1]",
                            "remark" => "$heatinfo[3]",
                            "pro_price" => $heatinfo[4],

                        );
                        Selection_plan::add($heat_Attr);
                    }
                }
                //自定义采暖辅机添加参数
                if ($heat_newStr != "") {
                    $heat_newstr = explode('#', $heat_newStr);
                    foreach ($heat_newstr as $heat_newValue) {
                        $heatnew = explode('-', $heat_newValue);
//   不知道辅机的具体种类与id，只保存文本；设备的名称，规格型号和备注格式是字符串形式。
                        $heat_newAttr = array(
                            "history_id" => $history_id,
                            "equ_name" => "$heatnew[0]",
                            "tab_type" => 1,
                            "nums" => $heatnew[2],
                            "version" => "$heatnew[1]",
                            "remark" => "$heatnew[3]",
                            "pro_price" => $heatnew[4],

                        );
                        Selection_plan::add($heat_newAttr);
                    }
                }
                //自定义热水辅机修改参数
                if ($water_addStr != "") {
                    $water_str = explode('#', $water_addStr);
                    foreach ($water_str as $water_Value) {
                        $waterinfo = explode('-', $water_Value);
//  不知道辅机的具体种类与id，只保存文本;设备的名称，规格型号和备注格式是字符串形式。
                        $water_Attr = array(
                            "history_id" => $history_id,
                            "equ_name" => "$waterinfo[0]",
                            "tab_type" => 2,
                            "nums" => $waterinfo[2],
                            "version" => "$waterinfo[1]",
                            "remark" => "$waterinfo[3]",
                            "pro_price" => $waterinfo[4],

                        );
                        Selection_plan::add($water_Attr);
                    }
                }
                //自定义热水辅机添加参数
                if ($water_newStr != "") {
                    $water_newstr = explode('#', $water_newStr);
                    foreach ($water_newstr as $water_newValue) {
                        $waternew = explode('-', $water_newValue);
//  不知道辅机的具体种类与id，只保存文本;设备的名称，规格型号和备注格式是字符串形式。
                        $water_newAttr = array(
                            "history_id" => $history_id,
                            "equ_name" => "$waternew[0]",
                            "tab_type" => 2,
                            "nums" => $waternew[2],
                            "version" => "$waternew[1]",
                            "remark" => "$waternew[3]",
                            "pro_price" => $waternew[4],

                        );
                        Selection_plan::add($water_newAttr);
                    }
                }
                //自定义其他项修改参数
                if ($other_new_dataStr != "") {
                    $other_str = explode('#', $other_new_dataStr);
                    foreach ($other_str as $other_Value) {
                        $otherinfo = explode('||', $other_Value);
//  不知道辅机的具体种类与id，只保存文本
                        $other_Attr = array(
                            "history_id" => $history_id,
                            "equ_name" => "$otherinfo[0]",
                            "tab_type" => 3,
                            "version" => "$otherinfo[1]",
                            "nums" => $otherinfo[2],
                            "remark" => "$otherinfo[3]",
                            "pro_price" => $otherinfo[4],

                        );
                        Selection_plan::add($other_Attr);
                    }
                }

                //更新selection_history表的状态
                $status_attr = array(
                    "user" => $USERId,
                    "status" => 3,
                    "lastupdate" => time()
                );
                Selection_history::update($history_id, $status_attr);
            }
            $mypdo->pdo->commit();
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'history_id' => $history_id ));
        } catch (MyException $e) {
            $mypdo->pdo->rollBack();
            echo $e->jsonMsg();
        }
        break;

    //----------选型方案二完成提交价格到后台价格表中------------------------------
//将前端的锅炉的报价添加到pricelog表中
    case 'addpricelog':

        $hisid = safeCheck($_POST['id']);
        try{

            $gl_plans=Selection_plan::getListByHidandTabtype($hisid,0);
            foreach ($gl_plans as $guolu_value) {
                if($guolu_value['attrid']){
                    $guoluinfo=Guolu_attr::getInfoById($guolu_value['attrid']);
                    $attrs1 = array(
                        "objectid" => $guoluinfo['proid'],//pricelog表中锅炉的价格是按照proid识别
                        "type" => 1,//产品中心报价
                        "addtype" => 1,//前端报价
                        "price" => $guolu_value['pro_price'],
                        "addtime" => time()
                    );
//                    print_r($attrs1);
//                    exit;
                    Case_pricelog::add($attrs1);
                }
            }


        //将前端的采暖辅机的报价添加到pricelog表中
            $heat_plans=Selection_plan::getListByHidandTabtype($hisid,1);
            if(!empty($heat_plans)) {
                foreach ($heat_plans as $heat_value) {
                    if ($heat_value['attrid']) {
                        $heat_fuji_info = Selection_fuji::getInfoById($heat_value['attrid']);
                        $heat_fuji_data_type=!empty($heat_fuji_info)?$heat_fuji_info['data_type']:'';
                        if(!empty($heat_fuji_data_type)){
                            if ($heat_fuji_info['data_type'] == 1) {
                                $fuji_info = Products_model::getInfoById($heat_value['modelid']);
                                $heat_fuji = $fuji_info['attrname']::getInfoById($heat_fuji_info['value']);
//
                                $attrs1 = array(
                                    "objectid" => $heat_fuji['proid'],//pricelog表中辅机的价格是按照proid识别
                                    "type" => 1,//产品中心报价
                                    "addtype" => 1,//前端报价
                                    "price" => $heat_value['pro_price'],
                                    "addtime" => time()
                                );
                            Case_pricelog::add($attrs1);
                            }
                        }
                    }
                }
            }


           //将前端的热水辅机的报价添加到pricelog表中
           $water_plans=Selection_plan::getListByHidandTabtype($hisid,2);
            if(!empty($water_plans)) {
                foreach ($water_plans as $water_value) {
                    if ($water_value['attrid']) {
                        $water_fuji_info = Selection_fuji::getInfoById($water_value['attrid']);
                        $water_fuji_data_type=!empty($water_fuji_info)?$water_fuji_info['data_type']:'';
                        if(!empty($water_fuji_data_type)) {
                            if ($water_fuji_info['data_type'] == 1) {
                                $fuji_info = Products_model::getInfoById($water_value['modelid']);
                                $water_fuji = $fuji_info['attrname']::getInfoById($water_fuji_info['value']);
                                $attrs1 = array(
                                    "objectid" => $water_fuji['proid'],//pricelog表中辅机的价格是按照proid识别
                                    "type" => 1,//产品中心报价
                                    "addtype" => 1,//前端报价
                                    "price" => $water_value['pro_price'],
                                    "addtime" => time()
                                );
                                Case_pricelog::add($attrs1);
                            }
                        }
                    }
                }
            }

            //将前端的其他项的报价添加到pricelog表中
            $other_plans=Selection_plan::getListByHidandTabtype($hisid,3);
//            print_r($other_plans);
//            exit;
            if(!empty($other_plans)) {
                foreach ($other_plans as $other_value) {
                    if($other_value['attrid']) {
                        $attrs1 = array(
                            "objectid" => $other_value['attrid'],//pricelog表中其他的价格是按照自身的id识别
                            "type" => 2,//产品中心报价
                            "addtype" => 1,//前端报价
                            "price" => $other_value['pro_price'],
                            "addtime" => time()
                        );
                        Case_pricelog::add($attrs1);
                    }
                }
            }

            echo action_msg_data("保存成功！", 1);
            //echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'history_id' => $hisid));
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;

    /**
     * 根据厂家选择锅炉类型
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
     * 根据锅炉id获取锅炉历史价格数据
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

}
