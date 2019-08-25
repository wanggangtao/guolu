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

$act = safeCheck($_GET['act'], 0);
switch($act){
    case 'project_one_save'://保存
        $id                           = safeCheck($_POST['id']);
        $project_id                   = safeCheck($_POST['project_id']);
        $project_name                 = safeCheck($_POST['project_name'], 0);
        $project_detail               = safeCheck($_POST['project_detail'], 0);
        $project_lat                  = $_POST['project_lat']?safeCheck($_POST['project_lat']):0;
        $project_long                 = $_POST['project_long']?safeCheck($_POST['project_long']):0;
        $project_type                 = $_POST['project_type']?safeCheck($_POST['project_type']):0;
        $project_partya               = safeCheck($_POST['project_partya'], 0);
        $project_partya_address       = safeCheck($_POST['project_partya_address'], 0);
        $project_partya_desc          = HTMLEncode($_POST['project_partya_desc']);
        $project_partya_pic           = safeCheck($_POST['project_partya_pic'], 0);
        $project_linkman              = safeCheck($_POST['project_linkman'], 0);
        $project_linktel              = safeCheck($_POST['project_linktel'], 0);
        $project_linkposition         = safeCheck($_POST['project_linkposition'], 0);
        $project_boiler_num           = $_POST['project_boiler_num']?safeCheck($_POST['project_boiler_num']):0;
        $project_boiler_tonnage       = $_POST['project_boiler_tonnage']?safeCheck($_POST['project_boiler_tonnage']):0;
        $project_wallboiler_num       = $_POST['project_wallboiler_num']?safeCheck($_POST['project_wallboiler_num']):0;
        $project_brand                = safeCheck($_POST['project_brand'], 0);
        $project_xinghao              = safeCheck($_POST['project_xinghao'], 0);
        $project_build_type           = safeCheck($_POST['project_build_type']);
        $project_isnew                = safeCheck($_POST['project_isnew']);
        $project_pre_buildtime        = safeCheck($_POST['project_pre_buildtime'], 0);
        $project_competitive_brand    = safeCheck($_POST['project_competitive_brand'], 0);
        $project_competitive_desc     = HTMLEncode($_POST['project_competitive_desc']);
        $project_desc                 = HTMLEncode($_POST['project_desc']);
//        $project_history              = safeCheck($_POST['project_history'], 0);
        $project_history_attr1         = safeCheck($_POST['project_history_attr1'], 0);
        $project_history_attr2         = safeCheck($_POST['project_history_attr2'], 0);
        $project_history_attr = $project_history_attr1."|".$project_history_attr2;
        $nowtime = time();
        if($project_pre_buildtime){
            $project_pre_buildtime = strtotime($project_pre_buildtime);
        }else{
            $project_pre_buildtime = 0;
        }
        $project_partya_pic = rtrim($project_partya_pic, '|');
        $all_guolu_tonnage    = safeCheck($_POST['all_guolu_tonnage'], 0);
        $all_guolu_num        = safeCheck($_POST['all_guolu_num'], 0);
        try {
            $attrsProject = array(
                "name"=>$project_name,
                "detail"=>$project_detail,
                "type"=>$project_type,
                "status"=>1,
                "user"=>$USERId,
                "lastupdate"=>$nowtime
            );
            $attrsPo = array(
                "project_name"=>$project_name,
                "project_detail"=>$project_detail,
                "project_lat"=>$project_lat,
                "project_long"=>$project_long,
                "project_type"=>$project_type,
                "project_partya"=>$project_partya,
                "project_partya_address"=>$project_partya_address,
                "project_partya_desc"=>$project_partya_desc,
                "project_partya_pic"=>$project_partya_pic,
                "project_linkman"=>$project_linkman,
                "project_linktel"=>$project_linktel,
                "project_linkposition"=>$project_linkposition,
                "project_boiler_num"=>$project_boiler_num,
                "project_boiler_tonnage"=>$project_boiler_tonnage,
                "project_wallboiler_num"=>$project_wallboiler_num,
                "project_brand"=>$project_brand,
                "project_xinghao"=>$project_xinghao,
                "project_build_type"=>$project_build_type,
                "project_isnew"=>$project_isnew,
                "project_pre_buildtime"=>$project_pre_buildtime,
                "project_competitive_brand"=>$project_competitive_brand,
                "project_competitive_desc"=>$project_competitive_desc,
                "project_desc"=>$project_desc,
                "project_lastupdate"=>$nowtime,
//                "project_history"=>$project_history,
                "project_history_attr"=>$project_history_attr
            );
            $projectidr = 0;
            $bakid = 0;
            if(empty($project_id)){//首次保存
                $attrsProject['addtime'] = $nowtime;
                $attrsProject['level'] = 0;
                $code = Code::buildCode(2, date('ymd',$nowtime));
                if(empty($code)){
                    echo action_msg("编号生成失败！请重试", 111);
                    die();
                }else{
                    if($project_type == 1){
                        $code = "SY".$code;
                    }elseif($project_type == 2){
                        $code = "BGL".$code;
                    }elseif($project_type == 3){
                        $code = "RSJ".$code;
                    }
                }
                $attrsProject['code'] = $code;
                $projectid = Project::add($attrsProject);
                if($projectid > 0){
                    $attrsPo['project_id'] = $projectid;
                    $attrsPo['project_addtime'] = $nowtime;
                    $rs = Project_one::add($attrsPo);
                    $bakid = Project_one_bak::add($attrsPo);
                    $attrsRecord = array(
                        "user"=>$USERId,
                        "user_name"=>$USERINFO['name'],
                        "before_id"=>0,
                        "after_id"=>$bakid,
                        "project_id"=>$projectid,
                        "addtime"=>$nowtime
                    );
                    Project_one_record::add($attrsRecord);
                }
                $projectidr = $projectid;
                if($project_type != 2){
                    $all_guolu_tonnage   = trim($all_guolu_tonnage, '||');
                    $all_guolu_tonnageA  = explode('||', $all_guolu_tonnage);

                    $all_guolu_num   = trim($all_guolu_num, '||');
                    $all_guolu_numA  = explode('||', $all_guolu_num);

                    //清除正式表的数据
                    Project_burner_type::delByPoId($project_id);//无效操作
                    for($i = 0; $i < count($all_guolu_tonnageA); $i++){
                        if($all_guolu_tonnageA[$i]){
                            $burnerArray = array(
                                "po_id"=>$projectidr,
                                "guolu_tonnage"=>$all_guolu_tonnageA[$i],
                                "guolu_num"=>$all_guolu_numA[$i]
                            );
                            //往正式表添加数据
                            Project_burner_type::add($burnerArray);
                            //往备份表加入数据
                            $burnerArray['po_id'] = $bakid;
                            Project_burner_type_bak::add($burnerArray);
                        }
                    }
                }
            }else{//之前保存过
                $proinfo = Project::getInfoById($project_id);
                if($proinfo['level'] >= 1){
                    $attrsProject['status'] = $proinfo['status'];
                }
                if(!empty($proinfo['code']) && $proinfo['code'] != $proinfo['type']){
                    if($project_type == 1){
                        $code = "SY".substr($proinfo['code'],-8);
                    }elseif($project_type == 2){
                        $code = "BGL".substr($proinfo['code'],-8);
                    }elseif($project_type == 3){
                        $code = "RSJ".substr($proinfo['code'],-8);
                    }
                    $attrsProject['code'] = $code;
                }
                Project::update($project_id,$attrsProject);
                Project_one::update($id, $attrsPo);
                $beforeid = Project_one_bak::getInfoNewRecodeByPrid($project_id);
                $attrsPo['project_addtime'] = $nowtime;
                $attrsPo['project_id'] = $project_id;
                $bakid = Project_one_bak::add($attrsPo);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );

                $project_before = Project_one_bak::getInfoById($beforeid['id']);
                $project_after = Project_one_bak::getInfoById($bakid);
                foreach ($project_after as $key=>$value){
                    if ($key=='project_addtime'||$key=='project_lastupdate'||$key=='id'){
                        continue;
                    }elseif($value!=$project_before[$key]){
                        $data[$ARRAY_Project_name[$key]]=$value;
                    }
                }
                foreach ($data as $k=>$val){
                    if ($k=='project_pre_buildtime') {
                        $val = date('Y-m-d H:i:s',$val);
                    }
                    $message.=$k.'做了修改,'.'修改内容是:'.$val.';';
                }
                if (!empty($message)){
                    Project_one_record::add($attrsRecord);
                }
                //修改通知
                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
//                    $project_before = Project_one_bak::getInfoById($beforeid['id']);
//                    $project_after = Project_one_bak::getInfoById($bakid);
                    $content = $USERINFO['name'].'对第一阶段的';
//                    foreach ($project_after as $key=>$value){
//                        if ($key=='project_addtime'||$key=='project_lastupdate'||$key=='id'){
//                            continue;
//                        }elseif($value!=$project_before[$key]){
//                            $data[$ARRAY_Project_name[$key]]=$value;
//                        }
//                    }
//                    foreach ($data as $k=>$val){
//                        if ($k=='project_pre_buildtime') {
//                            $val = date('Y-m-d H:i:s',$val);
//                        }
//                        $message.=$k.'做了修改,'.'修改内容是:'.$val.';';
//                    }
                    if (!empty($message)){
                        $content.=$message;
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                        $roomInfo = Chat_room::getInfoByProject($project_id);
                        $projectinfo = Project::getInfoById($project_id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $project_id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)){
                                socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_MODIFY,1,$extra);
                            }
                        }
                    }


                    $attr = array(
                        'name'=>$project_name.'交流群'
                    );
                    Chat_room::update($roomId,$attr);

                }



                $projectidr = $project_id;
                if($project_type != 2){
                    $all_guolu_tonnage   = trim($all_guolu_tonnage, '||');
                    $all_guolu_tonnageA  = explode('||', $all_guolu_tonnage);

                    $all_guolu_num   = trim($all_guolu_num, '||');
                    $all_guolu_numA  = explode('||', $all_guolu_num);

                    //清除正式表的数据
                    Project_burner_type::delByPoId($project_id);//无效操作
                    for($i = 0; $i < count($all_guolu_tonnageA); $i++){
                        if($all_guolu_tonnageA[$i]){
                            $burnerArray = array(
                                "po_id"=>$projectidr,
                                "guolu_tonnage"=>$all_guolu_tonnageA[$i],
                                "guolu_num"=>$all_guolu_numA[$i]
                            );
                            //往正式表添加数据
                            Project_burner_type::add($burnerArray);
                            //往备份表加入数据
                            $burnerArray['po_id'] = $bakid;
                            Project_burner_type_bak::add($burnerArray);
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => 1, 'msg' => "保存成功！", 'projectid' => $projectidr));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_one_submit'://提交
        $id                           = safeCheck($_POST['id']);
        $project_id                   = safeCheck($_POST['project_id']);
        $nowtime = time();

        try {
            $projectinfo = Project::getInfoById($project_id);
            $attrsProject = array(
                "status"=>2,
                "one_status"=>2,
                "reviewopinion"=>'',
                "lastupdate"=>$nowtime
            );
            if($projectinfo['level'] == 0){
                $attrsProject['addtime'] = $nowtime;
            }
            Project::update($project_id, $attrsProject);


            $sockMsg = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理.';

            $msgcontent = $sockMsg.'<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';


            $msgAttrs = array(
                "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                "sender" => $USERId,
                "title" => "项目阶段审核申请-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>$nowtime
            );
            $msgid = Message_info::add($msgAttrs);

            $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);

            //机器人
            $roomId = Chat_room::getRoomIdByProject($project_id);
            if(!empty($roomId)){
                $content = $USERINFO['name'].'已经提交了'.$projectinfo['name'].'，请尽快处理。';
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_APPLY);
                $roomInfo = Chat_room::getInfoByProject($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $extra = $project_id.','.$projectinfo['user'];
                    if (!empty($reportRoomId)) {
                        socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_STEP_APPLY, 1, $extra);
                    }
                }

            }

//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//            }

            //给总经理发相似项目提醒
            if($projectinfo['level'] == 0) {
                $project_one = Project_one::getInfoByProjectId($project_id);
                $samecount = Project_one::getPageSameList(1, 10, 0, $project_one['project_name'], $project_one['project_detail'], '', '', '', '', '');
                if($samecount > 1){
                    //$muserlist = User::getPageList( 0, 10, 1, '', 1, '', 3);
                    //if($muserlist){
                        //foreach ($muserlist as $thism){
                            $mmsgcontent = $USERINFO['name'].'提交的'.$projectinfo['name'].'有'.($samecount-1).'个相似项目,请点击<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_show.php?id='.$project_id.'">马上处理>></a>';
                            $mmsgAttrs = array(
                                "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                                "sender" => $USERId,
                                "title" => "相似项目提醒-".$projectinfo['name'],
                                "content" => $mmsgcontent,
                                "addtime"=>$nowtime
                            );
                            $mmsgid = Message_info::add($mmsgAttrs);

//                            if($jpushuser['register_id'] && $mmsgid > 0){
//                                $mjpushcontent = $USERINFO['name'].'提交的'.$projectinfo['name'].'有'.($samecount-1).'个相似项目。';
//                                JPUSH_send($jpushuser['register_id'], $mjpushcontent, "相似项目提醒-".$projectinfo['name'], $project_id, $mmsgid);
//                            }
                        //}
                    //}
                }
            }

            echo json_encode_cn(array('code' => 1, 'msg' => "提交成功！", 'projectid' => $project_id));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_two_save'://保存
        $id              = safeCheck($_POST['id']);
        $project_id      = safeCheck($_POST['project_id']);
        $all_isim        = safeCheck($_POST['all_isim'], 0);
        $all_name        = safeCheck($_POST['all_name'], 0);
        $all_department  = safeCheck($_POST['all_department'], 0);
        $all_position    = safeCheck($_POST['all_position'], 0);
        $all_duty        = safeCheck($_POST['all_duty'], 0);
        $all_phone       = safeCheck($_POST['all_phone'], 0);

        $nowtime = time();

        try {
            $attrsProject = array(
                "lastupdate"=>$nowtime
            );
            $attrsPt = array(
                "project_id" => $project_id,
                "lastupdate"=>$nowtime
            );

            Project::update($project_id,$attrsProject);
            if(empty($id)){//首次保存
                $attrsPt['addtime'] = $nowtime;
                $rs = Project_two::add($attrsPt);
                $bakid = Project_two_bak::add($attrsPt);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>0,
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );
                Project_two_record::add($attrsRecord);

                $all_isim   = trim($all_isim, '||');
                $all_isimA  = explode('||', $all_isim);

                $all_name   = trim($all_name, '||');
                $all_nameA  = explode('||', $all_name);

                $all_phone   = trim($all_phone, '||');
                $all_phoneA  = explode('||', $all_phone);

                $all_department   = trim($all_department, '||');
                $all_departmentA  = explode('||', $all_department);

                $all_position   = trim($all_position, '||');
                $all_positionA  = explode('||', $all_position);

                $all_duty   = trim($all_duty, '||');
                $all_dutyA  = explode('||', $all_duty);

                //清除正式表的数据
                Project_linkman::delByPtId($project_id);//无效操作
                for($i = 0; $i < count($all_isimA); $i++){
                    $linkManArray = array(
                        "pt_id"=>$project_id,
                        "name"=>$all_nameA[$i],
                        "phone"=>$all_phoneA[$i],
                        "department"=>$all_departmentA[$i],
                        "duty"=>$all_dutyA[$i],
                        "position"=>$all_positionA[$i],
                        "isimportant"=>$all_isimA[$i]
                    );
                    //往正式表添加数据
                    $link_id = Project_linkman::add($linkManArray);
                    //往备份表加入数据
                    $linkManArray['pt_id'] = $bakid;
                    Project_linkman_bak::add($linkManArray);
                }

//                $link_info = Project_linkman::getInfoById($link_id);
//                $roomId = Chat_room::getRoomIdByProject($project_id);
//                if(!empty($roomId)){
//                    $content = $USERINFO['name'].'对第二阶段的';
//                    foreach ($link_info as $k=>$val){
//                        if($k=='id'||$k=='pt_id'){
//                            continue;
//                        } else{
//                            $content.=$ARRAY_Project_two[$k].'做了修改,'.'修改内容是'.$val.';';
//                            }
//
//                    }
//                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//                }
                echo action_msg('保存成功！', 1);
            }else{//之前保存过
                Project_two::update($id, $attrsPt);
                $attrsPt['addtime'] = $nowtime;
                $beforeid = Project_two_bak::getInfoNewRecodeByPrid($project_id);
                $bakid = Project_two_bak::add($attrsPt);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );


                $all_isim   = trim($all_isim, '||');
                $all_isimA  = explode('||', $all_isim);

                $all_name   = trim($all_name, '||');
                $all_nameA  = explode('||', $all_name);

                $all_phone   = trim($all_phone, '||');
                $all_phoneA  = explode('||', $all_phone);

                $all_department   = trim($all_department, '||');
                $all_departmentA  = explode('||', $all_department);

                $all_position   = trim($all_position, '||');
                $all_positionA  = explode('||', $all_position);

                $all_duty   = trim($all_duty, '||');
                $all_dutyA  = explode('||', $all_duty);

                //清除正式表的数据
                Project_linkman::delByPtId($project_id);//无效操作
                for($i = 0; $i < count($all_isimA); $i++){
                    $linkManArray = array(
                        "pt_id"=>$project_id,
                        "name"=>$all_nameA[$i],
                        "phone"=>$all_phoneA[$i],
                        "duty"=>$all_dutyA[$i],
                        "department"=>$all_departmentA[$i],
                        "position"=>$all_positionA[$i],
                        "isimportant"=>$all_isimA[$i]
                    );
                    //往正式表添加数据
                    Project_linkman::add($linkManArray);
                    //往备份表加入数据
                    $linkManArray['pt_id'] = $bakid;
                    Project_linkman_bak::add($linkManArray);
                }

                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
//                    $project_before = Project_two_bak::getInfoById($beforeid['id']);
//                    $project_after = Project_two_bak::getInfoById($bakid);
                    $linkManListBefore = Project_linkman_bak::getInfoByPtId($beforeid['id']);
                    $linkManListAfter = Project_linkman_bak::getInfoByPtId($bakid);
                    $content = $USERINFO['name'].'对第二阶段的';
                    foreach ($linkManListAfter[0] as $key=>$value){
                        if ($key=='id'||$key=='pt_id'){
                            continue;
                        }else if($value!=$linkManListBefore[0][$key]){
                            $data[$ARRAY_Project_two[$key]]=$value;
                        }
                    }

                    foreach ($data as $k=>$val){
                        $message.=$k.'做了修改,'.'修改内容是:'.$val.';';
                    }

                    if (!empty($message)){
                        $content.=$message;
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                        $roomInfo = Chat_room::getInfoByProject($project_id);
                        $projectinfo = Project::getInfoById($project_id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $project_id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)){
                            socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_SIMILAR,1,$extra);
                        }
                        }
                        Project_two_record::add($attrsRecord);
                    }

                }

                echo action_msg('保存成功！', 1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_two_submit'://提交
        $id              = safeCheck($_POST['id']);
        $project_id      = safeCheck($_POST['project_id']);

        $nowtime = time();
        $projectinfo = Project::getInfoById($project_id);
        if($projectinfo['one_status'] != 2 && $projectinfo['one_status'] != 3){
            throw new MyException("低星级还未提交，请先提交低星级!",1101);
        }
        try {
            $attrsProject = array(
                "status"=>2,
                "two_status"=>2,
                "reviewopinion"=>'',
                "lastupdate"=>$nowtime
            );
            Project::update($project_id,$attrsProject);
            $msgcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';
            $msgAttrs = array(
                "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                "sender" => $USERId,
                "title" => "项目阶段审核申请-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>$nowtime
            );
            $msgid = Message_info::add($msgAttrs);
//机器人
            $roomId = Chat_room::getRoomIdByProject($project_id);
            if(!empty($roomId)){
                $content = $USERINFO['name'].'已经提交了'.$projectinfo['name'].'，请尽快处理。';
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_APPLY);
                $roomInfo = Chat_room::getInfoByProject($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $extra = $project_id.','.$projectinfo['user'];
                    if (!empty($reportRoomId)) {
                        socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_SIMILAR, 1, $extra);
                    }
                }

            }

//            $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);
//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//            }

            echo action_msg('提交成功！', 1);
//
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_three_save'://保存
        $id                           = safeCheck($_POST['id']);
        $project_id                   = safeCheck($_POST['project_id']);
        $competitive_brand_situation  = HTMLEncode($_POST['competitive_brand_situation']);
        $progress_situation           = HTMLEncode($_POST['progress_situation']);
        $invitation_situation         = HTMLEncode($_POST['invitation_situation']);
        $other_situation              = HTMLEncode($_POST['other_situation']);
        $nowtime = time();

        try {
            $attrsProject = array(
                "lastupdate"=>$nowtime
            );
            $attrsPt = array(
                "project_id" => $project_id,
                "competitive_brand_situation"=>$competitive_brand_situation,
                "progress_situation"=>$progress_situation,
                "invitation_situation"=>$invitation_situation,
                "other_situation"=>$other_situation,
                "lastupdate"=>$nowtime
            );

            Project::update($project_id,$attrsProject);
            if(empty($id)){//首次保存
                $attrsPt['addtime'] = $nowtime;
                $rs = Project_three::add($attrsPt);
                $bakid = Project_three_bak::add($attrsPt);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>0,
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );
                Project_three_record::add($attrsRecord);

                //修改信息
//                $three_info = Project_three_bak::getInfoById($bakid);
//                $roomId = Chat_room::getRoomIdByProject($project_id);
//                if(!empty($roomId)){
//                    $content = $USERINFO['name'].'对第三阶段的';
//                    foreach ($three_info as $k=>$val){
//                        if($k=='id'||$k=='addtime'||$k=='lastupdate'||$k=='project_id'){
//                            continue;
//                        } else{
//                            $content.=$ARRAY_Project_three[$k].'做了修改,'.'修改内容是'.$val.';';
//                        }
//
//                    }
//                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//                }

                echo action_msg('保存成功！', 1);
            }else{//之前保存过
                Project_three::update($id, $attrsPt);
                $attrsPt['addtime'] = $nowtime;
                $beforeid = Project_three_bak::getInfoNewRecodeByPrid($project_id);
                $bakid = Project_three_bak::add($attrsPt);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );


                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
//                    $project_before = Project_two_bak::getInfoById($beforeid['id']);
//                    $project_after = Project_two_bak::getInfoById($bakid);
                    $project_three_before = Project_three_bak::getInfoById($beforeid['id']);
                    $project_three_after = Project_three_bak::getInfoById($bakid);

                    $content = $USERINFO['name'].'对第三阶段的';
                    foreach ($project_three_after as $key=>$value){
                        if ($key=='id'||$key=='addtime'||$key=='lastupdate'||$key=='project_id'){
                            continue;
                        }else if($value!=$project_three_before[$key]){
                            $data[$ARRAY_Project_three[$key]]=$value;
                        }
                    }

                    foreach ($data as $k=>$val){
                        $message.=$k.'做了修改,'.'修改内容是'.$val.';';
                    }
                    if (!empty($message)){
                        $content.=$message;
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                        $roomInfo = Chat_room::getInfoByProject($project_id);
                        $projectinfo = Project::getInfoById($project_id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $project_id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)) {
                                socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_SIMILAR, 1, $extra);
                            }
                        }
                        Project_three_record::add($attrsRecord);
                    }

                }
                echo action_msg('保存成功！', 1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_three_submit'://提交
        $id                           = safeCheck($_POST['id']);
        $project_id                   = safeCheck($_POST['project_id']);
        $nowtime = time();
        $projectinfo = Project::getInfoById($project_id);
        try {
            if(($projectinfo['one_status'] != 2 && $projectinfo['one_status'] != 3) || ($projectinfo['two_status'] != 2 && $projectinfo['two_status'] != 3)){
                throw new MyException("低星级还未提交，请先提交低星级!",1101);
            }
            $attrsProject = array(
                "status"=>2,
                "three_status"=>2,
                "reviewopinion"=>'',
                "lastupdate"=>$nowtime
            );
            Project::update($project_id,$attrsProject);

            $msgcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';
            $msgAttrs = array(
                "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                "sender" => $USERId,
                "title" => "项目阶段审核申请-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>$nowtime
            );
            $msgid = Message_info::add($msgAttrs);

            //机器人
            $roomId = Chat_room::getRoomIdByProject($project_id);
            if(!empty($roomId)){
                $content = $USERINFO['name'].'已经提交了'.$projectinfo['name'].'，请尽快处理。';
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_APPLY);
                $roomInfo = Chat_room::getInfoByProject($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $extra = $project_id.','.$projectinfo['user'];
                    if (!empty($reportRoomId)) {
                        socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_SIMILAR, 1, $extra);
                    }
                }

            }

//            $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);
//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//            }

            echo action_msg('提交成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_four_save'://保存
        $id              = safeCheck($_POST['id']);
        $project_id      = safeCheck($_POST['project_id']);
        $all_isim        = safeCheck($_POST['all_isim'], 0);
        $all_name        = safeCheck($_POST['all_name'], 0);
        $all_price  = safeCheck($_POST['all_price'], 0);
        $all_brand    = safeCheck($_POST['all_brand'], 0);
        $project_cid_company = safeCheck($_POST['project_cid_company'], 0);
        $project_cid_linkman = safeCheck($_POST['project_cid_linkman'], 0);

        $project_cid_linkphone = safeCheck($_POST['project_cid_linkphone'], 0);
        $project_cbid_situation = HTMLEncode($_POST['project_cbid_situation']);
        $project_cid_file = safeCheck($_POST['project_cid_file'], 0);
        $project_bid_file = safeCheck($_POST['project_bid_file'], 0);
        $project_cid_ac_file = safeCheck($_POST['project_cid_ac_file'], 0);
        $project_bid_ac_file = safeCheck($_POST['project_bid_ac_file'], 0);

        $project_cid_file    = rtrim($project_cid_file, '|');
        $project_bid_file    = rtrim($project_bid_file, '|');
        $project_cid_ac_file = rtrim($project_cid_ac_file, '|');
        $project_bid_ac_file = rtrim($project_bid_ac_file, '|');

        $nowtime = time();

        try {
            $attrsProject = array(
                "lastupdate"=>$nowtime
            );
            $attrsPt = array(
                "project_id" => $project_id,
                "project_cid_company"=>$project_cid_company,
                "project_cid_linkman"=>$project_cid_linkman,
                "project_cid_linkphone"=>$project_cid_linkphone,
                "project_cid_file"=>$project_cid_file,
                "project_bid_file"=>$project_bid_file,
                "project_cid_ac_file"=>$project_cid_ac_file,
                "project_bid_ac_file"=>$project_bid_ac_file,
                "project_cbid_situation"=>$project_cbid_situation,
                "lastupdate"=>$nowtime
            );

            Project::update($project_id,$attrsProject);
            if(empty($id)){//首次保存
                $attrsPt['addtime'] = $nowtime;
                $rs = Project_four::add($attrsPt);
                $bakid = Project_four_bak::add($attrsPt);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>0,
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );
                Project_four_record::add($attrsRecord);

                $all_isim   = trim($all_isim, '||');
                $all_isimA  = explode('||', $all_isim);

                $all_name   = trim($all_name, '||');
                $all_nameA  = explode('||', $all_name);

                $all_price   = trim($all_price, '||');
                $all_priceA  = explode('||', $all_price);

                $all_brand   = trim($all_brand, '||');
                $all_brandA  = explode('||', $all_brand);

                //清除正式表的数据
                Project_bid_company::delByPfId($project_id);//无效操作
                for($i = 0; $i < count($all_isimA); $i++){
                    if($all_nameA[$i]){
                        $companyArray = array(
                            "pf_id"=>$project_id,
                            "name"=>$all_nameA[$i],
                            "brand"=>$all_brandA[$i],
                            "price"=>$all_priceA[$i],
                            "isimportant"=>$all_isimA[$i]
                        );
                        //往正式表添加数据
                        Project_bid_company::add($companyArray);
                        //往备份表加入数据
                        $companyArray['pf_id'] = $bakid;
                        Project_bid_company_bak::add($companyArray);
                    }
                }

//                $four_info = Project_four_bak::getInfoById($bakid);
//                $roomId = Chat_room::getRoomIdByProject($project_id);
//                if(!empty($roomId)){
//                    $content = $USERINFO['name'].'对第四阶段的';
//                    foreach ($four_info as $k=>$val){
//                        if($k=='project_cid_company'||$k=='project_cid_linkman'||$k=='project_cid_linkphone'||$k=='project_cbid_situation')
//                        {
//                            $content.=$ARRAY_Project_four[$k].'做了修改,'.'修改内容是'.$val.';';
//                        }
//
//                    }
//                    $bid_info = Project_bid_company::getInfoByPfId($project_id);
//                    foreach ($bid_info as $item){
//                        foreach ($item as $key=>$value){
//                            $content.=$ARRAY_Project_four[$key].'做了修改,'.'修改内容是'.$value.';';
//                        }
//                    }
//                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//                }


                echo action_msg('保存成功！', 1);
            }else{//之前保存过
                Project_four::update($id, $attrsPt);
                $attrsPt['addtime'] = $nowtime;
                $beforeid = Project_four_bak::getInfoNewRecodeByPrid($project_id);
                $bakid = Project_four_bak::add($attrsPt);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );


                $all_isim   = trim($all_isim, '||');
                $all_isimA  = explode('||', $all_isim);

                $all_name   = trim($all_name, '||');
                $all_nameA  = explode('||', $all_name);

                $all_price   = trim($all_price, '||');
                $all_priceA  = explode('||', $all_price);

                $all_brand   = trim($all_brand, '||');
                $all_brandA  = explode('||', $all_brand);

                $project_four_before = Project_four_bak::getInfoById($beforeid['id']);

                $project_four_after = Project_four_bak::getInfoById($bakid);

                $projectBefore = Project_bid_company::getInfoByPfId($project_id);

                //清除正式表的数据
                Project_bid_company::delByPfId($project_id);//无效操作
                for($i = 0; $i < count($all_isimA); $i++){
                    if($all_nameA[$i]){
                        $companyArray = array(
                            "pf_id"=>$project_id,
                            "name"=>$all_nameA[$i],
                            "brand"=>$all_brandA[$i],
                            "price"=>$all_priceA[$i],
                            "isimportant"=>$all_isimA[$i]
                        );
                        //往正式表添加数据
                        Project_bid_company::add($companyArray);
                        //往备份表加入数据
                        $companyArray['pf_id'] = $bakid;
                        Project_bid_company_bak::add($companyArray);
                    }
                }

                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
                    $data = array();
                    $project_four_before = Project_four_bak::getInfoById($beforeid['id']);

                    $project_four_after = Project_four_bak::getInfoById($bakid);
                    $content = $USERINFO['name'].'对第四阶段的';
                    foreach ($project_four_after as $key=>$value){
                        if ($key=='project_cid_company'||$key=='project_cid_linkman'||$key=='project_cid_linkphone'||$key=='project_cbid_situation'){
                           if ($project_four_before[$key]!=$value){
                               $message.=$ARRAY_Project_four[$key].'做了修改,'.'修改内容是:'.$value.';';
                           }
                        }
                    }



                    $bid_info = Project_bid_company::getInfoByPfId($project_id);

                    foreach ($bid_info as $k=>$val){
                        foreach ($bid_info[$k] as $ka=>$v){
                            if ($ka=='id'||$ka=='pf_id'){
                                continue;
                            }else if($ka=='isimportant'&&$v!=$projectBefore[$k][$ka]){
                                $message.=$ARRAY_Project_four[$ka].'做了修改,'.'修改内容是:'.$ARRAY_isImport[$v].';';
                            }else if($v!=$projectBefore[$k][$ka]){
                                $message.=$ARRAY_Project_four[$ka].'做了修改,'.'修改内容是:'.$v.';';
                            }
                        }
                    }
//                    print_r($message);
                    if (!empty($message)){
                        $content.=$message;
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                        $roomInfo = Chat_room::getInfoByProject($project_id);
                        $projectinfo = Project::getInfoById($project_id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $project_id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)) {
                                socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_MODIFY, 1, $extra);
                            }
                        }
                        Project_four_record::add($attrsRecord);
                    }

                }
                echo action_msg('保存成功！', 1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_four_submit'://提交
        $id              = safeCheck($_POST['id']);
        $project_id      = safeCheck($_POST['project_id']);

        $nowtime = time();
        $projectinfo = Project::getInfoById($project_id);
        try {
            if(($projectinfo['one_status'] != 2 && $projectinfo['one_status'] != 3)
                || ($projectinfo['two_status'] != 2 && $projectinfo['two_status'] != 3)
                || ($projectinfo['three_status'] != 2 && $projectinfo['three_status'] != 3)){
                throw new MyException("低星级还未提交，请先提交低星级!",1101);
            }
            $attrsProject = array(
                "status"=>2,
                "four_status"=>2,
                "reviewopinion"=>'',
                "lastupdate"=>$nowtime
            );

            Project::update($project_id,$attrsProject);

            $msgcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';
            $msgAttrs = array(
                "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                "sender" => $USERId,
                "title" => "项目阶段审核申请-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>$nowtime
            );
            $msgid = Message_info::add($msgAttrs);

            //机器人
            $roomId = Chat_room::getRoomIdByProject($project_id);
            if(!empty($roomId)){
                $content = $USERINFO['name'].'已经提交了'.$projectinfo['name'].'，请尽快处理。';
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_STEP_APPLY);
                $roomInfo = Chat_room::getInfoByProject($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $extra = $project_id.','.$projectinfo['user'];
                    if (!empty($reportRoomId)) {
                        socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_STEP_APPLY, 1, $extra);
                    }
                }

            }

//            $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);
//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//            }

            echo action_msg('提交成功！', 1);

        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_five_save'://保存
        $id                        = safeCheck($_POST['id']);
        $project_id                = safeCheck($_POST['project_id']);
        $after_solve               = HTMLEncode($_POST['after_solve']);
        $pay_condition             = HTMLEncode($_POST['pay_condition']);
        $cost_plan                 = HTMLEncode($_POST['cost_plan']);
        $money                     = safeCheck($_POST['money']);
        $pre_build_time            = safeCheck($_POST['pre_build_time'], 0);
        $pre_check_time            = safeCheck($_POST['pre_check_time'], 0);
        $project_contract_file     = safeCheck($_POST['project_contract_file'], 0);
        $project_contract_ac_file  = safeCheck($_POST['project_contract_ac_file'], 0);
        $project_contract_file = rtrim($project_contract_file, '|');
        $project_contract_ac_file = rtrim($project_contract_ac_file, '|');

        $nowtime = time();
        $pre_build_time  = $pre_build_time?strtotime($pre_build_time):0;
        $pre_check_time  = $pre_check_time?strtotime($pre_check_time):0;
        $projectinfo = Project::getInfoById($project_id);
        $first_reward = 0;
        $second_reward = 0;
        $third_reward = 0;
        $first_reward = $money * $projectinfo['bonus'] * $constant_project_first_reward;
        $second_reward = $money * $projectinfo['bonus'] * $constant_project_second_reward;
        $third_reward = $money * $projectinfo['bonus'] * $constant_project_third_reward;
        try {
            $attrsProject = array(
                "lastupdate"=>$nowtime
            );
            $attrsPf = array(
                "project_id" => $project_id,
                "after_solve"=>$after_solve,
                "pay_condition"=>$pay_condition,
                "cost_plan"=>$cost_plan,
                "money"=>$money,
                "first_reward"=>$first_reward,
                "second_reward"=>$second_reward,
                "third_reward"=>$third_reward,
                "pre_build_time"=>$pre_build_time,
                "pre_check_time"=>$pre_check_time,
                "contract_file"=>$project_contract_file,
                "contract_ac_file"=>$project_contract_ac_file ,

                "lastupdate"=>$nowtime
            );


            Project::update($project_id,$attrsProject);
            if(empty($id)){//首次保存
                $attrsPf['addtime'] = $nowtime;
                $rs = Project_five::add($attrsPf);
                $bakid = Project_five_bak::add($attrsPf);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>0,
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );

//                $five_info = Project_five_bak::getInfoById($bakid);
//                $roomId = Chat_room::getRoomIdByProject($project_id);
//                if(!empty($roomId)){
//                    $content = $USERINFO['name'].'对第五阶段的';
//                    foreach ($five_info as $k=>$val){
//                        if($k=='id'||$k=='addtime'||$k=='lastupdate'||$k=='project_id'||$k=='contract_file'||$k=='contract_ac_file'){
//                            continue;
//                        } else{
//                            if($k=='pre_build_time'||$k=='pre_check_time'){
//                                $content.=$ARRAY_Project_five[$k].'做了修改,'.'修改内容是'.date("Y-m-d",$val).';';
//                            }else{
//                                $content.=$ARRAY_Project_five[$k].'做了修改,'.'修改内容是'.$val.';';
//                            }
//                        }
//
//                    }
//                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//                }

                Project_five_record::add($attrsRecord);
                echo action_msg('保存成功！', 1);
            }else{//之前保存过
                Project_five::update($id, $attrsPf);

                $attrsPf['addtime'] = $nowtime;
                $beforeid = Project_five_bak::getInfoNewRecodeByPrid($project_id);
                $bakid = Project_five_bak::add($attrsPf);
                $attrsRecord = array(
                    "user"=>$USERId,
                    "user_name"=>$USERINFO['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );


                $roomId = Chat_room::getRoomIdByProject($project_id);
                if(!empty($roomId)){
                    $data = array();
                    $project_five_before = Project_five_bak::getInfoById($beforeid['id']);
                    $project_five_after = Project_five_bak::getInfoById($bakid);
                    $content = $USERINFO['name'].'对第五阶段的';
                    foreach ($project_five_after as $key=>$value){
                        if ($key=='id'||$key=='addtime'||$key=='lastupdate'||$key=='project_id'||$key=='contract_file'||$key=='contract_ac_file'){
                            continue;
                        }else{
                            if ($project_five_before[$key]!=$value){
                                if($key=='pre_build_time'||$key=='pre_check_time'){
                                    $message.=$ARRAY_Project_five[$key].'做了修改,'.'修改内容是:'.date("Y-m-d",$value).';';
                                }else{
                                    $message.=$ARRAY_Project_five[$key].'做了修改,'.'修改内容是:'.$value.';';
                                }
                            }
                        }
                    }

                    if (!empty($message)){
                        $content.=$message;
                        socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                        $roomInfo = Chat_room::getInfoByProject($project_id);
                        $projectinfo = Project::getInfoById($project_id);
                        if (!empty($roomInfo)){
                            $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                            $extra = $project_id.','.$projectinfo['user'];
                            if (!empty($reportRoomId)) {
                                socket_message::sendMsgForReport($reportRoomId, $content, socket_message::MSG_TYPE_MODIFY, 1, $extra);
                            }
                        }
                        Project_five_record::add($attrsRecord);
                    }
                }
                echo action_msg('保存成功！', 1);
            }
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'summarize_save'://项目沉淀
        $project_id              = safeCheck($_POST['project_id']);
        $summarize               = HTMLEncode($_POST['summarize']);

        try {
            $attrsProject = array(
                "summarize"=>$summarize,
                "lastupdate"=>time()
            );

            Project::update($project_id,$attrsProject);
            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'getProjectreward'://计算提成
        $project_id         = safeCheck($_POST['id']);
        $money              = safeCheck($_POST['money']);
        $first_reward = 0;
        $second_reward = 0;
        $third_reward = 0;
        $code = 1;
        try {
            $projectinfo = Project::getInfoById($project_id);
            if($projectinfo['bonus'] == 0){
                $code = 2;
            }
            $first_reward = $money * $projectinfo['bonus'] * $constant_project_first_reward;
            $second_reward =  $money * $projectinfo['bonus'] * $constant_project_second_reward;
            $third_reward =  $money * $projectinfo['bonus'] * $constant_project_third_reward;
            $first_reward = number_format($first_reward,2);
            $second_reward = number_format($second_reward,2);
            $third_reward = number_format($third_reward,2);
            echo json_encode_cn(array('code' => $code, 'msg' => "保存成功！", 'first_reward' => $first_reward, 'second_reward' => $second_reward, 'third_reward' => $third_reward));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'checkProjectname'://检查项目名
        $project_id         = safeCheck($_POST['id']);
        $project_name       = safeCheck($_POST['project_name'], 0);
        $code = 1;
        $msg = "";
        try {
            $projectinfo = Project_one::getPageSameList(1, 999, 1, $project_name, '', '', '', '', '');
            if(empty($project_id)){
                if(!empty($projectinfo)){
                    $code = 101;
                    $msg = "已有相似的项目名存在!";
                }
            }else{
                if(!empty($projectinfo)){
                    foreach ($projectinfo as $thisinfo){
                        if($thisinfo['id'] != $project_id){
                            $code = 102;
                            $msg = "已有相似的项目名存在!";
                            break;
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'checkProjectaddress'://检查项目地址
        $project_id         = safeCheck($_POST['id']);
        $project_detail       = safeCheck($_POST['project_detail'], 0);
        $code = 1;
        $msg = "";
        try {
            $projectinfo = Project_one::getPageSameList(1, 99, 1, '', $project_detail, '', '', '', '');
            if(empty($project_id)){
                if(!empty($projectinfo)){
                    $code = 101;
                    $msg = "已有相似的项目地址存在!";
                }
            }else{
                if(!empty($projectinfo)){
                    foreach ($projectinfo as $thisinfo){
                        if($thisinfo['id'] != $project_id){
                            $code = 102;
                            $msg = "已有相似的项目地址存在!";
                            break;
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;

    case 'checkProjectpartya'://检查项目地址
        $project_id         = safeCheck($_POST['id']);
        $project_partya       = safeCheck($_POST['project_partya'], 0);
        $code = 1;
        $msg = "";
        try {
            $projectinfo = Project_one::getPageSameList(1, 99, 1, '', '', $project_partya, '', '', '');
            if(empty($project_id)){
                if(!empty($projectinfo)){
                    $code = 101;
                    $msg = "已有相似的甲方单位存在!";
                }
            }else{
                if(!empty($projectinfo)){
                    foreach ($projectinfo as $thisinfo){
                        if($thisinfo['id'] != $project_id){
                            $code = 102;
                            $msg = "已有相似的甲方单位存在!";
                            break;
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'checkPartyaaddress'://检查项目地址
        $project_id         = safeCheck($_POST['id']);
        $partya_address       = safeCheck($_POST['partya_address'], 0);
        $code = 1;
        $msg = "";
        try {
            $projectinfo = Project_one::getPageSameList(1, 99, 1, '', '', '', $partya_address, '', '');
            if(empty($project_id)){
                if(!empty($projectinfo)){
                    $code = 101;
                    $msg = "已有相似的甲方地址存在!";
                }
            }else{
                if(!empty($projectinfo)){
                    foreach ($projectinfo as $thisinfo){
                        if($thisinfo['id'] != $project_id){
                            $code = 102;
                            $msg = "已有相似的甲方地址存在!";
                            break;
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'checkProjectlinkman'://检查联系人
        $project_id         = safeCheck($_POST['id']);
        $project_linkman    = safeCheck($_POST['project_linkman'], 0);
        $code = 1;
        $msg = "";
        try {
            $projectinfo = Project_one::getPageSameList(1, 99, 1, '', '', '', '', $project_linkman, '');
            if(empty($project_id)){
                if(!empty($projectinfo)){
                    $code = 101;
                    $msg = "已有相似的甲方联系人存在!";
                }
            }else{
                if(!empty($projectinfo)){
                    foreach ($projectinfo as $thisinfo){
                        if($thisinfo['id'] != $project_id){
                            $code = 102;
                            $msg = "已有相似的甲方联系人存在!";
                            break;
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'checkProjectlinktel'://检查联系人
        $project_id         = safeCheck($_POST['id']);
        $project_linktel       = safeCheck($_POST['project_linktel'], 0);
        $code = 1;
        $msg = "";
        try {
            $projectinfo = Project_one::getInfoByLinktel($project_linktel);
            if(empty($project_id)){
                if(!empty($projectinfo)){
                    $code = 101;
                    $msg = "联系负责人的电话已经存在报备!";
                }
            }else{
                if(!empty($projectinfo)){
                    foreach ($projectinfo as $thisinfo){
                        if($thisinfo['id'] != $project_id){
                            $code = 102;
                            $msg = "联系负责人的电话已经存在报备!";
                            break;
                        }
                    }
                }
            }
            echo json_encode_cn(array('code' => $code, 'msg' => $msg));
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'stopreason_save'://项目终止原因保存
        $project_id              = safeCheck($_POST['project_id']);
        $stopreason              = HTMLEncode($_POST['stopreason']);

        try {
            $attrsProject = array(
                "stopreason"=>$stopreason,
                "lastupdate"=>time()
            );
            $projectinfo = Project::getInfoById($project_id);
            $roomId = Chat_room::getRoomIdByProject($project_id);
            if(!empty($roomId)){
                $content = $USERINFO['name'].'对项目终止原因做了修改';
                if ($projectinfo['stopreason']!=$stopreason){
                    $message = '修改内容是:'.$stopreason;
                }
                if (!empty($message)){
                    $content.=$message;
                    socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                    $roomInfo = Chat_room::getInfoByProject($project_id);
                    if (!empty($roomInfo)){
                        $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                        $extra = $project_id.','.$projectinfo['user'];
                        if (!empty($reportRoomId)){
                            socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_MODIFY,1,$extra);
                        }
                    }
                }
            }
            Project::update($project_id,$attrsProject);
            echo action_msg('保存成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'stopreason_submit'://项目终止原因提交
        $project_id              = safeCheck($_POST['project_id']);
        $stopreason              = HTMLEncode($_POST['stopreason']);

        try {
            $attrsProject = array(
                "stop_flag"=>1,
                "status"=>2,
                "stopreason"=>$stopreason,
                "lastupdate"=>time()
            );

            Project::update($project_id,$attrsProject);
            $msgcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">马上处理>></a>';
            $msgAttrs = array(
                "recipients" => $USERINFO['parent']?$USERINFO['parent']:$USERId,
                "sender" => $USERId,
                "title" => "项目阶段审核申请-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>time()
            );
            $msgid = Message_info::add($msgAttrs);

//            $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);
//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = $USERINFO['name'].'已经提交了'.$projectinfo['name'].',请尽快处理。';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目阶段审核申请-".$projectinfo['name'], $project_id, $msgid);
//            }

            echo action_msg('提交成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'project_delete'://项目删除
        $project_id              = safeCheck($_POST['id']);
        $del_reason              = HTMLEncode($_POST['del_reason']);


        try {

            $projectinfo = Project::getInfoById($project_id);
            $loginuser = User::getInfoById($USERId);
            if(!($loginuser["role"] == 3 || $loginuser["role"] == 2))
            {
                if ($loginuser['id']!=$projectinfo['user']||$projectinfo['level']!=0){
                    throw new MyException("没有删除权限!",1060);
                }
            }

            $attrsProject = array(
                "del_flag"=>1,
                "del_reason"=>$del_reason,
                "lastupdate"=>time()
            );

            Project::update($project_id,$attrsProject);

            $msgcontent = '您提交的'.$projectinfo['name'].'已被删除';
            if($del_reason){
                $msgcontent .= "，删除原因是".$del_reason;
            }
            $msgcontent .= "。";
            $msgAttrs = array(
                "recipients" => $projectinfo['user'],
                "sender" => $USERId,
                "title" => "项目删除提醒-".$projectinfo['name'],
                "content" => $msgcontent,
                "addtime"=>time()
            );
            $msgid = Message_info::add($msgAttrs);

//            $jpushuser = User::getInfoById($USERINFO['parent']?$USERINFO['parent']:$USERId);
//            if($jpushuser['register_id'] && $msgid > 0){
//                $jpushcontent = '您提交的'.$projectinfo['name'].'已被删除';
//                JPUSH_send($jpushuser['register_id'], $jpushcontent, "项目删除提醒-".$projectinfo['name'], $project_id, $msgid);
//            }

            //删除群
            $chart_room_id = Chat_room::getRoomIdByProject($project_id);
            if(!empty($chart_room_id)){
               $roomAttr = array(
                   "status"=>-1
               );
               Chat_room::update($chart_room_id,$roomAttr);
            }
            echo action_msg('删除成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
    case 'del_sameproject'://移除相似项目
        $project_id          = safeCheck($_POST['id']);
        $sameid              = safeCheck($_POST['sameid']);
        try {

            $projectinfo = Project::getInfoById($project_id);
            $notsame_id = $projectinfo['notsame_id'];

            $sameinfo = Project::getInfoById($sameid);
            $same_id_not = $sameinfo['notsame_id'];

            if(empty($notsame_id)){
                $notsame_id = $sameid;
            }else{
                $notsame_id .= ','.$sameid;
            }
            if(empty($same_id_not)){
                $same_id_not = $project_id;
            }else{
                $same_id_not .= ','.$project_id;
            }

            $attrsProject = array(
                "notsame_id"=>$notsame_id,
                "lastupdate"=>time()
            );

            $attrs = array(
                "notsame_id"=>$same_id_not,
                "lastupdate"=>time()
            );
            Project::update($sameid,$attrs);
            Project::update($project_id,$attrsProject);
            echo action_msg('移除成功！', 1);
        }catch (MyException $e){
            echo $e->jsonMsg();
        }
        break;
}
?>