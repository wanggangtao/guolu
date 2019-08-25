<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19
 * Time: 19:51
 */
include_once '../init.php';

$act = safeCheck($_REQUEST['act'], 0);
switch ($act) {

    //得到问候语
    case 'getBegin':
        $info=Service_problem::getBeginInfo();

        $content="";
        if($info){
            $content=$info['content'];
        }
        echo action_msg($content, $info['type']);  //1 是带图片的
        break;
    //得到用户信息
    case 'getInfo':
        $id = safeCheck($_REQUEST['id'], 0);
        $user_info=User_account::getAllByOpenid($id);
        $values=array();
        if($user_info){
            if($user_info['info_brand']){
                $brand=$user_info['info_brand'];
                $brand_name=Dict::getInfoById($brand)['name'];
                $label=Service_label::getInfoByKeyword($brand_name);
                if($label){
                    $info=Service_problem::getInfoByCode($label['after']);
                    $params=array();
                    $params['before']=$label['after'];
                    $after_array=Service_label::getList($params);
                    $i=0;
                    foreach ($after_array as $value){
                        $values[$i][0]=$value['after'];
                        $values[$i][1]=$value['keyword'];
                        $i++;
                    }
                    $content="";
                    if($info){
                        $content=array(
                            "title" => $info['content'],
                            "description" => $values,
                            "picUrl" => $HTTP_PATH.$info['cover'],
                            "url" => "www.baidu.com",
                            "type" =>1
                        );
                    }
                    echo action_msg($content, $info['type']);  //1 是带图片的
                }else{
                    $label=Service_label::getInfoByKeyword("未登录");
                    $info=Service_problem::getInfoByCode($label['after']);

                    $label_info=Service_label::getInfoByBefore($info['code']);

                    $after_code=$label_info['after'];

                    $params=array();
                    $params["page"]=1;
                    $params["pageSize"]=5;
                    $params['type']=-1;
                    $community_list=Community::getList($params);
                    $i=0;
                    foreach ($community_list as $value){
                        $values[$i][0]=$value['id'];
                        $values[$i][1]=$value['name'];
                        $i++;
                    }
                    $content="";
                    if($info){
                        $content=array(
                            "title" => $info['content'],
                            "description" => $values,
                            "picUrl" => $HTTP_PATH.$info['cover'],
                            "url" => "www.baidu.com",
                            "code" => $after_code,
                            "type" =>0
                        );
                    }
                    echo action_msg($content, $info['type']);  //1 是带图片的
                }

            }else{
                $label=Service_label::getInfoByKeyword("未登录");
                $info=Service_problem::getInfoByCode($label['after']);

                $label_info=Service_label::getInfoByBefore($info['code']);

                $after_code=$label_info['after'];

                $params=array();
                $params['type']=-1;
                $params["page"]=1;
                $params["pageSize"]=5;
                $community_list=Community::getList($params);
                $i=0;
                foreach ($community_list as $value){
                    $values[$i][0]=$value['id'];
                    $values[$i][1]=$value['name'];
                    $i++;
                }
                $content="";
                if($info){
                    $content=array(
                        "title" => $info['content'],
                        "description" => $values,
                        "picUrl" => $HTTP_PATH.$info['cover'],
                        "url" => "www.baidu.com",
                        "code" => $after_code,
                        "type" =>0
                    );
                }
                echo action_msg($content, $info['type']);  //1 是带图片的
            }
        }else{
//            print_r(111);
            $label=Service_label::getInfoByKeyword("未登录");
            $info=Service_problem::getInfoByCode($label['after']);

            $label_info=Service_label::getInfoByBefore($info['code']);

            $after_code=$label_info['after'];

            $params=array();
            $params["page"]=1;
            $params["pageSize"]=5;
            $params['type']=-1;
            $community_list=Community::getList($params);
            $i=0;
            foreach ($community_list as $value){
                $values[$i][0]=$value['id'];
                $values[$i][1]=$value['name'];
                $i++;
            }
            $content="";
            if($info){
                $content=array(
                    "title" => $info['content'],
                    "description" => $values,
                    "picUrl" => $HTTP_PATH.$info['cover'],
                    "url" => "www.baidu.com",
                    "code" => $after_code,
                    "type" =>0
                );
            }
            echo action_msg($content, $info['type']);  //1 是带图片的
        }
        break;
    //得到后置
    case 'getAfter':
        $code= safeCheck($_REQUEST['code'], 0);
        $c_name= safeCheck($_REQUEST['c_name'], 0);
        $openid= safeCheck($_REQUEST['openid'], 0);
        $info= Service_problem::getInfoByCode($code);
        if($c_name){
            //小区添加
            $keyword=Service_label::getInfoByAfter($info['code'])['keyword'];
            $brand=Dict::getInfoByName($keyword)['id'];
            $name  =  $c_name;
            $province_id     =  36;
            $city_id   = 566;
            $area_id      =  45052;
            $province_name     =  "其他";
            $city_name   =  "其他";
            $area_name      =  "其他";
            $params=array();
            $params['brand']=$brand;
            $params['name']=$name;
            $params['provice_id']=$province_id;
            $params['city_id']=$city_id;
            $params['area_id']=$area_id;
            $params['provice_name']=$province_name ;
            $params['city_name']=$city_name;
            $params['area_name']=$area_name;
            $params['type']=1;
            $params['first_charter']=getFirstCharter($name);
            Community::add($params);

            //记录修改
            $param=array();
            $names="";
            $user_info=User_account::getAllByOpenid($openid);
            if($user_info){
                $names=$user_info['account_name'];
            }
            $param['account']=$names;
            $param['community']=$name;
            $param['brand']=$keyword;
            Service_record::update($openid,$param);

        }


        $params=array();
        $params['before']=$info['code'];
        $label_list=Service_label::getList($params);

        $values=array();
        $i=0;

        foreach ($label_list as $value){
            $values[$i][0]=$value['after'];
            $values[$i][1]=$value['keyword'];
            $i++;
        }

        $content="";
        $type=0;
        if($info['url_type']){
            $type=  $info['url_type'];
        }
        else{
            $type=  $info['type'];
        }

        if($info){
            $content=array(
                "title" => $info['content'],
                "description" => $values,
                "picUrl" => $HTTP_PATH.$info['cover'],
                "url" => $HTTP_PATH.$info['url'],
                "videoCoverUrl" => $HTTP_PATH.$info['video_cover'],
                "type" =>1
            );
        }
        echo action_msg($content, $type);  //1 是带图片的
        break;
    //得到品牌
    case 'getBrand':
        //小区id
        $id= safeCheck($_REQUEST['id'], 0);

        //code  锅炉品牌
        $code= safeCheck($_REQUEST['code'], 0);

        $community_brand=0;
        $community_type=1;

        if(is_numeric($id)){
            $community=Community::getInfoById($id);

            if($community){
                if(strstr($community['brand'], ',')){
                    $community_type=-1;
                }else{
                    $community_brand=$community['brand'];
                    $community_brand=Dict::getInfoById($community_brand)['name'];
                    $community_brand=Service_label::getInfoByKeyword($community_brand)['after'];
                }
            }

        }


        if($code){
            $info=Service_problem::getInfoByCode($code);
        }else{
            $info=Service_problem::getInfoByKeyword("锅炉品牌");
        }

        $params=array();
        $params['before']=$info['code'];
        $label_list=Service_label::getList($params);


        $values=array();
        $i=0;


        foreach ($label_list as $value){
            $values[$i][0]=$value['after'];
            $values[$i][1]=$value['keyword'];
            $i++;
        }

        $content="";
        if($info){
            $content=array(
                "title" => $info['content'],
                "description" => $values,
                "picUrl" => $HTTP_PATH.$info['cover'],
                "url" => $HTTP_PATH.$info['url'],
                "community_type" => $community_type,
                "community_brand"=>$community_brand,
                "type" =>1
            );

        }

        echo action_msg($content, 1);  //1 是带图片的
        break;
    //查看更多社区
    case 'getMore':
        $label=Service_label::getInfoByKeyword("未登录");
        $info=Service_problem::getInfoByCode($label['after']);

        $label_info=Service_label::getInfoByBefore($info['code']);

        $after_code=$label_info['after'];
        $params=array();
        $params["type"]=-1;
        $community_list=Community::getListByFC($params);
        $i=0;
        foreach ($community_list as $value){
            $values[$i][0]=$value['id'];
            $values[$i][1]=$value['first_charter']."_".$value['name'];
            $i++;
        }
        $values[count($community_list)][0]=0;
        $values[count($community_list)][1]="_其他";

        $content="";
        if($values){
            $content=array(
                "title" => "小区列表",
                "description" => $values,
//                "picUrl" => $HTTP_PATH.$info['cover'],
//                "url" => "www.baidu.com",
                "after_code"=>$after_code,
                "type" =>0
            );
        }
        echo action_msg($content, 1);
        break;
    //文字回复
    case 'replay':
        $keyword = safeCheck($_REQUEST['keyword'], 0);
        $id = safeCheck($_REQUEST['id'], 0);
        $param=array();
        $record_array=array();
        $param['openid']=$id;
        $infos=Service_record::getListByOpenId($param);

        $user_info=User_account::getAllByOpenid($id);


        if($user_info){
            $record_array['community']="";
            if($user_info['account_community_id']!=0 and $user_info['account_community_id']!=-1){
                $record_array['community']=Community::getInfoById($user_info['account_community_id'])['name'];
            }
            $record_array['brand']="";
            if($user_info['account_product_code']){
                $pro_info=Product_info::getInfoByBarCode($user_info['account_product_code']);
                if($pro_info){
                    if($pro_info['brand']){
                        $record_array['brand']= Dict::getInfoById($pro_info['brand'])['name'];
                    }
                }
            }
            $record_array['account']=$user_info['account_name'];
        }
        if($infos){
            foreach ($infos as $value){
                if($value['community']!=null and $value['brand']!=null){
                    $record_array['community']=$value['community'];
                    $record_array['brand']=$value['brand'];
                    $record_array['account']=$user_info['account_name'];
                    break;
                }

            }
        }


        $record_array['time']=time();
        $record_array['user_openid']=$id;
        $record_array['content']=$keyword;


        $label=Service_label::getInfoByKeyword($keyword);


        $values=array();

        $i=0;

        $content="";

        $type=0;

        if($label){

            if(is_array($label)){
                $info=Service_problem::getEndInfo();
                $record_array['answer']= $info['content'];
                $record_array['status']=-1;
            }else{
                $info=Service_problem::getInfoByCode($label['after']);

                $params=array();
                $params['before']=$info['code'];
                $label_list=Service_label::getList($params);

                foreach ($label_list as $value){
                    $values[$i][0]=$value['after'];
                    $values[$i][1]=$value['keyword'];
                    $i++;
                }
                $record_array['answer']= $info['content'];
                $record_array['status']=1;
            }


        }else{
            $info=Service_problem::getEndInfo();
            $record_array['answer']= $info['content'];
            $record_array['status']=-1;

        }
        if($info['url_type']){
            $type=  $info['url_type'];
        }
        else{
            $type=  $info['type'];
        }

        if($values){

            $content=array(
                "title" => $info['content'],
                "description" => $values,
                "picUrl" => $HTTP_PATH.$info['cover'],
                "url" => $HTTP_PATH.$info['url'],
                "type" =>1
            );
        }else{
            $content=$info['content'];
        }
        Service_record::add($record_array);
        echo action_msg($content, $type);  //1 是带图片的
        break;
    //其他社区回复
    case 'go_Other_Community':
        echo action_msg("请输入你的小区", 2);
        break;

}

?>