<?php
	/**
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

		case 'add':

            $address_position = safeCheck($_POST['address_position'], 0);

            $potition=explode(",",$address_position);
            $lat=$potition[0];
            $lng=$potition[1];

			$company = safeCheck($_POST['company'],0);
			$contacter = safeCheck($_POST['contacter'],0);
			$phone = safeCheck($_POST['phone'],0);
            $telephone = safeCheck($_POST['telephone'],0);
            $website = safeCheck($_POST['website'],0);
            $email = safeCheck($_POST['email'],0);
            $hotline = safeCheck($_POST['hotline'],0);
            $address = safeCheck($_POST['address'],0);


            $attrs = array(
                "company"=>$company,
                "contacter"=>$contacter,
                "phone"=>$phone,
                "telephone"=>$telephone,
                "website"=>$website,
                "email"=>$email,
                "address"=>$address,

                "lat"=>$lat,
                "lng"=>$lng,

                "hotline"=>$hotline,
            );
			$rs = Web_contactus::add($attrs);
			if($rs > 0){
			    echo action_msg("保存成功！", 1);
            }else{
                echo action_msg("添加失败！", 101);
            }
		break;

        case 'edit':

            $id=safeCheck($_POST['id'],0);

            $address_position = safeCheck($_POST['address_position'], 0);

            $potition=explode(",",$address_position);
            $lat=$potition[0];
            $lng=$potition[1];


            $company = safeCheck($_POST['company'],0);
            $contacter = safeCheck($_POST['contacter'],0);
            $phone = safeCheck($_POST['phone'],0);
            $telephone = safeCheck($_POST['telephone'],0);
            $website = safeCheck($_POST['website'],0);
            $email = safeCheck($_POST['email'],0);
            $hotline = safeCheck($_POST['hotline'],0);
            $address = safeCheck($_POST['address'],0);
            $picurl1 = safeCheck($_POST['picurl1'],0);
            $picurl2 = safeCheck($_POST['picurl2'],0);

            $attrs = array(
                "company"=>$company,
                "contacter"=>$contacter,
                "phone"=>$phone,
                "telephone"=>$telephone,
                "website"=>$website,
                "email"=>$email,
                "address"=>$address,

                "lat"=>$lat,
                "lng"=>$lng,

                "hotline"=>$hotline,
                'picurl1'=>$picurl1,
                'picurl2'=>$picurl2,
            );
            $rs = Web_contactus::update($id,$attrs);
            if($rs >= 0){
                echo action_msg("修改成功！", 1);
            }else{
                echo action_msg("修改失败！", 101);
            }
            break;



	}
?>