<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 2019/8/20
 * Time: 2:58 PM
 */

try {

    $type = isset($_POST['type'])? safeCheck($_POST['type']):-1;
    $page = isset($_POST['page'])?safeCheck($_POST['page']):1;
    $pageSize  = isset($_POST['pageSize'])?safeCheck($_POST['pageSize']):15;


    if(empty($type)) throw new MyException("缺少类型参数",101);

      $data = Picture::getPageList($page, $pageSize, 1, $type, 1);

//    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $data);
    /*if(!empty($data)) {
        foreach ($data as $pic_url) {
            echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $HTTP_PATH . $pic_url['url']);
        }
    }*/
    $params = array();
    if(!empty($data)) {
        foreach ($data as $key=>$pic_url) {
            $params[$key] = array(
                'url' => $pic_url["url"],
            );
        }
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $params);


}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
