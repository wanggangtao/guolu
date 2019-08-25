<?php


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
    $userInfo = user::getInfoById($uid);

    $attrsProject = array(
        "lastupdate"=>$nowtime
    );
    $attrsPt = array(
        "project_id" => $project_id,
        "lastupdate"=>$nowtime
    );
    $projecttwoid = 0;
    Project::update($project_id,$attrsProject);
    if(empty($id)){//首次保存
        $attrsPt['addtime'] = $nowtime;
        $rs = Project_two::add($attrsPt);
        $projecttwoid = $rs;
        $bakid = Project_two_bak::add($attrsPt);
        $attrsRecord = array(
            "user"=>$uid,
            "user_name"=>$userInfo['name'],
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
            $link_id =  Project_linkman::add($linkManArray);
            //往备份表加入数据
            $linkManArray['pt_id'] = $bakid;
            Project_linkman_bak::add($linkManArray);
        }
    }else{//之前保存过
        $projecttwoid = $id;
        Project_two::update($id, $attrsPt);
        $attrsPt['addtime'] = $nowtime;
        $beforeid = Project_two_bak::getInfoNewRecodeByPrid($project_id);
        $bakid = Project_two_bak::add($attrsPt);



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
            $content = $userInfo['name'].'对第二阶段的';
            foreach ($linkManListAfter[0] as $key=>$value){
                if ($key=='id'||$key=='pt_id'){
                    continue;
                }else if($value!=$linkManListBefore[0][$key]){
                    if ($key=='isimportant'){
                        $data[$ARRAY_Project_two[$key]]=$ARRAY_isImport[$value];
                    }else{
                        $data[$ARRAY_Project_two[$key]]=$value;
                    }

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
                        socket_message::sendMsgForReport($reportRoomId,$content,socket_message::MSG_TYPE_MODIFY,1,$extra);
                    }
                }
                $attrsRecord = array(
                    "user"=>$uid,
                    "user_name"=>$userInfo['name'],
                    "before_id"=>$beforeid['id'],
                    "after_id"=>$bakid,
                    "project_id"=>$project_id,
                    "addtime"=>$nowtime
                );
                Project_two_record::add($attrsRecord);
            }

        }
    }
    $resultData = array("projectid"=>$project_id,"id"=> $projecttwoid);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}

?>