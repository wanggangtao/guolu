<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午3:40
 */




$id                           = safeCheck($_POST['id']);
$project_id                   = safeCheck($_POST['project_id']);
$project_name                 = safeCheck($_POST['project_name'], 0);
$project_detail               = safeCheck($_POST['project_detail'], 0);
$project_lat                  = (isset($_POST['project_lat']) && !empty($_POST['project_lat']))?safeCheck($_POST['project_lat']):0;
$project_long                 = (isset($_POST['project_long']) && !empty($_POST['project_long']))?safeCheck($_POST['project_long']):0;
$project_type                 = (isset($_POST['project_type']) && !empty($_POST['project_type']))?safeCheck($_POST['project_type']):0;
$project_partya               = safeCheck($_POST['project_partya'], 0);
$project_partya_address       = safeCheck($_POST['project_partya_address'], 0);
$project_partya_desc          = HTMLEncode($_POST['project_partya_desc']);
$project_partya_pic           = isset($_POST['project_partya_pic'])?safeCheck($_POST['project_partya_pic'], 0):'';
$project_linkman              = safeCheck($_POST['project_linkman'], 0);
$project_linktel              = safeCheck($_POST['project_linktel'], 0);
$project_linkposition         = safeCheck($_POST['project_linkposition'], 0);
$project_boiler_num           = (isset($_POST['project_boiler_num']) && !empty($_POST['project_boiler_num']))?safeCheck($_POST['project_boiler_num']):0;
$project_boiler_tonnage       = (isset($_POST['project_boiler_tonnage']) && !empty($_POST['project_boiler_tonnage']))?safeCheck($_POST['project_boiler_tonnage']):0;
$project_wallboiler_num       = (isset($_POST['project_wallboiler_num']) && !empty($_POST['project_wallboiler_num']))?safeCheck($_POST['project_wallboiler_num']):0;
$project_brand                = safeCheck($_POST['project_brand'], 0);
$project_xinghao              = safeCheck($_POST['project_xinghao'], 0);
$project_build_type           = (isset($_POST['project_build_type']) && !empty($_POST['project_build_type']))?safeCheck($_POST['project_build_type']):0;
$project_isnew                = (isset($_POST['project_isnew']) && !empty($_POST['project_isnew']))?safeCheck($_POST['project_isnew']):0;
$project_pre_buildtime        = safeCheck($_POST['project_pre_buildtime'], 0);
$project_competitive_brand    = safeCheck($_POST['project_competitive_brand'], 0);
$project_competitive_desc     = HTMLEncode($_POST['project_competitive_desc']);
$project_desc                 = HTMLEncode($_POST['project_desc']);
//$project_history              = safeCheck($_POST['project_history'], 0);
$project_history_attr1         = isset($_POST['project_history_attr1'])?safeCheck($_POST['project_history_attr1'], 0):"";
$project_history_attr2         = isset($_POST['project_history_attr2'])?safeCheck($_POST['project_history_attr2'], 0):"";





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



    if(empty($project_history_attr1)) throw new MyException("请填写原锅炉功率!",401);

    if(empty($project_history_attr2)) throw new MyException("请填写原锅炉数量!",402);


    $isUpdate = 1;
//    throw new MyException("系统修复中！",101);
    $userInfo = user::getInfoById($uid);
    $attrsProject = array(
        "name"=>$project_name,
        "detail"=>$project_detail,
        "type"=>$project_type,
        "status"=>1,
        "user"=>$uid,
        "lastupdate"=>$nowtime
    );

    if(!empty($project_long))
    {
        $project_long = number_format($project_long, 6, '.', '');
    }


    if(!empty($project_lat))
    {
        $project_lat = number_format($project_lat, 6, '.', '');
    }

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
//        "project_history"=>$project_history,
        "project_history_attr"=>$project_history_attr
    );
    $projectidr = 0;
    $projectoneid = 0;
    $bakid = 0;
    if(empty($project_id)){//首次保存
        $attrsProject['addtime'] = $nowtime;
        $attrsProject['level'] = 0;
        $code = Code::buildCode(2, date('ymd',$nowtime));
        if(empty($code)){
            throw new MyException("项目编号生成失败!",1041);
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
            $projectoneid = $rs;
            $bakid = Project_one_bak::add($attrsPo);
            $attrsRecord = array(
                "user"=>$uid,
                "user_name"=>$userInfo['name'],
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
            $attrsRecord = array(
                "user"=>$uid,
                "user_name"=>$userInfo['name'],
                "before_id"=>$beforeid['id'],
                "after_id"=>$bakid,
                "project_id"=>$project_id,
                "addtime"=>$nowtime
            );
            Project_one_record::add($attrsRecord);
        }


        $roomId = Chat_room::getRoomIdByProject($project_id);
        if(!empty($roomId)){
//            $project_before = Project_one_bak::getInfoById($beforeid['id']);
//            $project_after = Project_one_bak::getInfoById($bakid);
            $content = $userInfo['name'].'对第一阶段的';
//            foreach ($project_after as $key=>$value){
//                if ($key=='project_addtime'||$key=='project_lastupdate'||$key=='id'){
//                    continue;
//                }elseif($value!=$project_before[$key]){
//                    $data[$ARRAY_Project_name[$key]]=$value;
//                }
//            }
//            foreach ($data as $k=>$val){
//                if ($k=='project_pre_buildtime') {
//                    $val = date('Y-m-d H:i:s',$val);
//                }
//                $message.=$k.'做了修改,'.'修改内容是:'.$val.';';
//            }

            if (!empty($message)){
                $content.=$message;
                socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
                $roomInfo = Chat_room::getInfoByProject($project_id);
//                $projectinfo = Project::getInfoById($project_id);
                if (!empty($roomInfo)){
                    $reportRoomId = Chat_room::getRoomByUId($roomInfo['principal_uid']);
                    $extra = $project_id.','.$proinfo['user'];
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
        $projectoneid = $id;
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

    $resultData = array("projectid"=>$projectidr,"id"=> $projectoneid);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
