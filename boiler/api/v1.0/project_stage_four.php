<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/7
 * Time: 下午5:18
 */

$id              = safeCheck($_POST['id']);
$project_id      = safeCheck($_POST['project_id']);
$sucess_company  = safeCheck($_POST['sucess_company'], 0);
$all_name        = safeCheck($_POST['all_name'], 0);
$all_price  = safeCheck($_POST['all_price'], 0);
$all_brand =  isset($_POST['$all_brand'])?safeCheck($_POST['$all_brand'],0):'';
//$all_brand    = safeCheck($_POST['all_brand'], 0);
$project_cid_company = safeCheck($_POST['project_cid_company'], 0);
$project_cid_linkman = safeCheck($_POST['project_cid_linkman'], 0);

$project_cid_linkphone = safeCheck($_POST['project_cid_linkphone'], 0);
$project_cbid_situation = HTMLEncode($_POST['project_cbid_situation']);
//$project_cid_file = safeCheck($_POST['project_cid_file'], 0);
//$project_bid_file = safeCheck($_POST['project_bid_file'], 0);
//$project_cid_ac_file = safeCheck($_POST['project_cid_ac_file'], 0);
//$project_bid_ac_file = safeCheck($_POST['project_bid_ac_file'], 0);

$nowtime = time();

try {
    $projectfourid = 0;
    $userInfo = user::getInfoById($uid);

    $attrsProject = array(
        "lastupdate"=>$nowtime
    );
    $attrsPt = array(
        "project_id" => $project_id,
        "project_cid_company"=>$project_cid_company,
        "project_cid_linkman"=>$project_cid_linkman,
        "project_cid_linkphone"=>$project_cid_linkphone,
        "project_cbid_situation"=>$project_cbid_situation,
        "lastupdate"=>$nowtime
    );

    Project::update($project_id,$attrsProject);
    if(empty($id)){//首次保存
        $attrsPt['addtime'] = $nowtime;
        $rs = Project_four::add($attrsPt);
        $projectfourid = $rs;
        $bakid = Project_four_bak::add($attrsPt);
        $attrsRecord = array(
            "user"=>$uid,
            "user_name"=>$userInfo['name'],
            "before_id"=>0,
            "after_id"=>$bakid,
            "project_id"=>$project_id,
            "addtime"=>$nowtime
        );
        Project_four_record::add($attrsRecord);

        $all_name   = trim($all_name, '||');
        $all_nameA  = explode('||', $all_name);

        $all_price   = trim($all_price, '||');
        $all_priceA  = explode('||', $all_price);

        $all_brand   = trim($all_brand, '||');
        $all_brandA  = explode('||', $all_brand);

        //清除正式表的数据
        Project_bid_company::delByPfId($project_id);//无效操作
        for($i = 0; $i < count($all_nameA); $i++){
            if($all_nameA[$i]){
                $isimportant = 0;
                if($sucess_company == $all_nameA[$i]){
                    $isimportant = 1;
                }
                $companyArray = array(
                    "name"=>$all_nameA[$i],
                    "brand"=>$all_brandA[$i],
                    "pf_id"=>$project_id,
                    "price"=>$all_priceA[$i],
                    "isimportant"=>$isimportant
                );
                //往正式表添加数据
                Project_bid_company::add($companyArray);
                //往备份表加入数据
                $companyArray['pf_id'] = $bakid;
                Project_bid_company_bak::add($companyArray);
            }
        }

//        $four_info = Project_four_bak::getInfoById($bakid);
//        $roomId = Chat_room::getRoomIdByProject($project_id);
//        if(!empty($roomId)){
//            $content = $userInfo['name'].'对第四阶段的';
//            foreach ($four_info as $k=>$val){
//                if($k=='project_cid_company'||$k=='project_cid_linkman'||$k=='project_cid_linkphone'||$k=='project_cbid_situation')
//                {
//                    $content.=$ARRAY_Project_four[$k].'做了修改,'.'修改内容是'.$val.';';
//                }
//
//            }
//            $bid_info = Project_bid_company::getInfoByPfId($project_id);
//            foreach ($bid_info as $item){
//                foreach ($item as $key=>$value){
//                    $content.=$ARRAY_Project_four[$key].'做了修改,'.'修改内容是'.$value.';';
//                }
//            }
//            $content .='<a style="color: blue;" href="'.$HTTP_PATH.'project/project_stage_turn_check.php?id='.$project_id.'">查看详情>></a>';
//            socket_message::sendMsgForRoomId($roomId,$content,socket_message::MSG_TYPE_MODIFY);
//        }
    }else{//之前保存过
        $projectfourid = $id;
        Project_four::update($id, $attrsPt);
        $attrsPt['addtime'] = $nowtime;
        $beforeid = Project_four_bak::getInfoNewRecodeByPrid($project_id);
        $bakid = Project_four_bak::add($attrsPt);

        $all_name   = trim($all_name, '||');
        $all_nameA  = explode('||', $all_name);

        $all_price   = trim($all_price, '||');
        $all_priceA  = explode('||', $all_price);

        $all_brand   = trim($all_brand, '||');
        $all_brandA  = explode('||', $all_brand);

        $projectBefore = Project_bid_company::getInfoByPfId($project_id);
        //清除正式表的数据
        Project_bid_company::delByPfId($project_id);//无效操作
        for($i = 0; $i < count($all_nameA); $i++){
            if($all_nameA[$i]){
                $isimportant = 0;
                if($sucess_company == $all_nameA[$i]){
                    $isimportant = 1;
                }
                $companyArray = array(
                    "pf_id"=>$project_id,
                    "name"=>$all_nameA[$i],
                    "brand"=>$all_brandA[$i],
                    "price"=>$all_priceA[$i],
                    "isimportant"=>$isimportant
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
            $content = $userInfo['name'].'对第四阶段的';
            foreach ($project_four_after as $key=>$value){
                if ($key=='project_cid_company'||$key=='project_cid_linkman'||$key=='project_cid_linkphone'||$key=='project_cbid_situation'){
                    if ($project_four_before[$key]!=$value){
                        $message.=$ARRAY_Project_four[$key].'做了修改,'.'修改内容是'.$value.';';
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
                Project_four_record::add($attrsRecord);
            }

        }
    }
    $resultData = array("projectid"=>$project_id, "id" => $projectfourid);
    echo action_msg_data(API::SUCCESS_MSG,API::SUCCESS,$resultData);
}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}