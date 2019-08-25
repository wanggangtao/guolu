<?php
/**
 * Created by PhpStorm.
 * User: dlk
 * Date: 17/7/12
 * Time: 上午9:52
 */



try {
    $data = array();

    $build_type = array();
    foreach ($ARRAY_project_build_type as $key => $value){
        $build_type[] = array(
            'buildingType' => (string)$key,
            'buildingName' => $value
        );
    }
    $data['buildingTypeInfo'] = $build_type;

    $project_type = array();
    $typelist = Project_type::getAllList();

    foreach ($typelist as $thisinfo){
        $project_type[] = array(
            'projectType' => $thisinfo['id'],
            'projectName' => $thisinfo['name']
        );
    }
    $data['projectTypeInfo'] = $project_type;

    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);


}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}


?>
