<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/7/3
 * Time: 下午7:02
 */

try {
    $project_id = isset($_POST['project_id'])?safeCheck($_POST['project_id'], 0):0;
    $project_name = isset($_POST['project_name'])?safeCheck($_POST['project_name'], 0):'';
    $project_detail = isset($_POST['project_address'])?safeCheck($_POST['project_address'], 0):'';
    $project_partya = isset($_POST['project_partya'])?safeCheck($_POST['project_partya'], 0):'';
    $project_partya_address = isset($_POST['project_partya_address'])?safeCheck($_POST['project_partya_address'], 0):'';
    $project_linktel = isset($_POST['project_linktel'])?safeCheck($_POST['project_linktel'], 0):'';

    $resData = array();
    
    if($project_name){
    	$sameinfo1 = Project_one::getPageSameList(1, 10, 1, $project_name, '', '', '', '', '');
    	if(empty($project_id)){
    		if(!empty($sameinfo1)){
    			$resData['15801']= "已有相似的项目名称存在!";
    		}
    	}else{
    		if(!empty($sameinfo1)){
    			foreach ($sameinfo1 as $thisinfo){
    				if($thisinfo['id'] != $project_id){
    					$resData['15801']= "已有相似的项目名称存在!";
    					break;
    				}
    			}
    		}
    	}
    }
    if($project_detail){
    	$sameinfo2 = Project_one::getPageSameList(1, 10, 1, '', $project_detail, '', '', '', '');
    	if(empty($project_id)){
    		if(!empty($sameinfo2)){
    			$resData['15802']= "已有相似的项目地址存在!";
    		}
    	}else{
    		if(!empty($sameinfo2)){
    			foreach ($sameinfo2 as $thisinfo){
    				if($thisinfo['id'] != $project_id){
    					$resData['15802']= "已有相似的项目地址存在!";
    					break;
    				}
    			}
    		}
    	}
    }

    /* if($project_partya){
    	$sameinfo3 = Project_one::getPageSameList(1, 10, 1, '', '', $project_partya, '', '', '');
    	if(empty($project_id)){
    		if(!empty($sameinfo3)){
    			$resData['15803']= "已有相似的甲方单位存在!";
    		}
    	}else{
    		if(!empty($sameinfo3)){
    			foreach ($sameinfo3 as $thisinfo){
    				if($thisinfo['id'] != $project_id){
    					$resData['15803']= "已有相似的甲方单位存在!";
    					break;
    				}
    			}
    		}
    	}
    }
    
    if($project_partya_address){
    	$sameinfo4 = Project_one::getPageSameList(1, 10, 1, '', '', '', $project_partya_address, '', '');
    	if(empty($project_id)){
    		if(!empty($sameinfo4)){
    			$resData['15804']= "已有相似的甲方地址存在!";
    		}
    	}else{
    		if(!empty($sameinfo4)){
    			foreach ($sameinfo4 as $thisinfo){
    				if($thisinfo['id'] != $project_id){
    					$resData['15804']= "已有相似的甲方地址存在!";
    					break;
    				}
    			}
    		}
    	}
    }
    
    if($project_linktel){
    	$sameinfo5 = Project_one::getPageSameList(1, 10, 0, '', '', '', '', '', $project_linktel);
    	if(empty($project_id)){
    		if(!empty($sameinfo5)){
    			$resData['15805']= "已有相同的联系电话存在!";
    		}
    	}else{
    		if(!empty($sameinfo5)){
    			foreach ($sameinfo5 as $thisinfo){
    				if($thisinfo['id'] != $project_id){
    					$resData['15805']= "已有相同的联系电话存在!";
    					break;
    				}
    			}
    		}
    	}
    } */

    if(empty($resData)){
    	$resData = null;
    }
    echo action_msg_data(API::SUCCESS_MSG, API::SUCCESS, $resData);

}catch (MyException $e){
    $api->ApiError($e->getCode(), $e->getMessage());
}
