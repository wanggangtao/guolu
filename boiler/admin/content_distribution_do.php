<?php
	/**
	 * 图片处理  picture_do.php
	 *
	 * @version       v0.01
	 * @create time   2018/09/13
	 * @update time  
	 * @author       dlk
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'del'://删除
			$id = safeCheck($_POST['id'],1);
            $rs = Web_distribution::del($id);
            if($rs >= 0){
                echo action_msg("删除成功！", 1);
            }else{
                echo action_msg("删除失败！", 101);
            }
		break;

		case 'add':
			$title = safeCheck($_POST['title'],0);
            $province = safeCheck($_POST['province'],0);
            $city = safeCheck($_POST['city'],0);
			$address = safeCheck($_POST['address'],0);
            $detail = $_POST['detail'];
			$picurl = safeCheck($_POST['picurl'],0);
            $tel = safeCheck($_POST['tel'],0);
            $address_position = safeCheck($_POST['address_position'], 0);
            $potition=explode(",",$address_position);
            $lat=$potition[0];
            $lng=$potition[1];

            $attrs = array(
                "title"=>$title,
                "detail"=>$detail,
                "address"=>$address,
                "picurl"=>$picurl,
                "time"=>time(),
                "lat"=>$lat,
                "lng"=>$lng,
                "city"=>$city,
                "province"=>$province,
                "tel"=>$tel,
                "detail"=>$detail
            );
			$rs = Web_distribution::add($attrs);
			if($rs > 0){
			    echo action_msg("添加成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
		break;
		
		case 'edit':
			$id = safeCheck($_POST['id']);
			$title = safeCheck($_POST['title'],0);
            $province = safeCheck($_POST['province']);
            $city = safeCheck($_POST['city']);
			$address = safeCheck($_POST['address'],0);
            $detail = $_POST['detail'];
			$picurl = safeCheck($_POST['picurl'],0);
			$tel =safeCheck($_POST['tel'],0);
            $address_position = safeCheck($_POST['address_position'], 0);
            $potition=explode(",",$address_position);
            $lat=$potition[0];
            $lng=$potition[1];
            $attrs = array(
                "title"=>$title,
                "detail"=>$detail,
                "address"=>$address,
                "picurl"=>$picurl,
                "time"=>time(),
                "lat"=>$lat,
                "lng"=>$lng,
                "province"=>$province,
                "city"=>$city,
                "tel"=>$tel,
            );
			$rs = Web_distribution::update($id, $attrs);
			echo $rs;
		break;

        case 'order':
            $id = safeCheck($_POST['id'],1);
            $weight = safeCheck($_POST['weight'],1);
            $attrs = array(
                "weight"=>$weight
            );
            $rs = Web_distribution::update($id, $attrs);
            echo $rs;
            break;

        case 'good_status':
            $id = safeCheck($_POST['id'],1);
            $is_good = Web_distribution::getInfoById($id)["is_good"];
            if($is_good == 1){
                $is_good = -1;
            }
            else{
                $is_good = 1;
            }
            $attrs = array(
                "is_good"=>$is_good
            );
            $rs = Web_distribution::update($id, $attrs);
            echo $rs;
            break;
	}




?>