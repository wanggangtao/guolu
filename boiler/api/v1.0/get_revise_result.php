<?php
/**
 * Created by PhpStorm.
 * User: ftx
 * Date: 2019/4/23
 * Time: 11:13 AM
 */

try {
    $project_id = isset($_POST['id'])?safeCheck($_POST['id']):'0';
    $project_stage = isset($_POST['stage'])?safeCheck($_POST['stage']):'0';
    if(empty($project_id)) throw new MyException("缺少项目id参数",101);
    if(empty($project_stage)) throw new MyException("缺少项目阶段参数",101);
    $data = array();
    switch ($project_stage){
        case 1:
            $record = Project_one_record::getLastIdByProject($project_id);
            $project_before = Project_one_bak::getInfoById($record['before_id']);
            $project_after = Project_one_bak::getInfoById($record['after_id']);
            foreach ($project_after as $key=>$value){
                if($value!=$project_before[$key]){
                    $data[]=$key;
                }
            }
            break;
        case 2:
            $record = Project_two_record::getLastIdByProject($project_id);
            $project_before = Project_two_bak::getInfoById($record['before_id']);
            $project_one_after = Project_two_bak::getInfoById($record['after_id']);
            foreach ($project_after as $key=>$value){
                if($value!=$project_before[$key]){
                    $data[]=$key;
                }
            }
            break;
        case 3:
            $record = Project_three_record::getLastIdByProject($project_id);
            $project_before = Project_three_bak::getInfoById($record['before_id']);
            $project_one_after = Project_three_bak::getInfoById($record['after_id']);
            foreach ($project_after as $key=>$value){
                if($value!=$project_before[$key]){
                    $data[]=$key;
                }
            }
            break;
        case 4:
            $record = Project_four_record::getLastIdByProject($project_id);
            $project_before = Project_four_bak::getInfoById($record['before_id']);
            $project_one_after = Project_four_bak::getInfoById($record['after_id']);
            foreach ($project_after as $key=>$value){
                if($value!=$project_before[$key]){
                    $data[]=$key;
                }
            }
            break;
        case 5:
            $record = Project_five_record::getLastIdByProject($project_id);
            $project_before = Project_five_bak::getInfoById($record['before_id']);
            $project_one_after = Project_five_bak::getInfoById($record['after_id']);
            foreach ($project_after as $key=>$value){
                if($value!=$project_before[$key]){
                    $data[]=$key;
                }
            }
            break;
    }

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}