<?php
	/**
	 * 管理员组  admingroup_do.php
	 *
	 * @version       v0.03
	 * @create time   2014-9-4
	 * @update time   2016/3/26
	 * @author        dxl jt
	 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
	 */

	require_once('admin_init.php');
	require_once('admincheck.php'); 
    
    $POWERID = '7001';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);
    
	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'add'://添加管理员组
			$name=safeCheck($_POST['admingroup_name'], 0);
			$type=safeCheck($_POST['admingroup_type']);

			try {
				$rs = Admingroup::add($name, $type);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'edit'://编辑管理员组
			$g_name = safeCheck(trim($_POST['group_name']), 0);
			$g_type = safeCheck(trim($_POST['group_type']));
			$id     = safeCheck(trim($_POST['id']));
			
			try {
				$rs = Admingroup::edit($id, $g_name, $g_type);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'del'://删除管理员组
			$gid = safeCheck($_POST['id']);
			try {
				$r = Admingroup::del($gid);
				echo $r;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'order'://管理员组排序
			$order = safeCheck($_GET['order'], 0);
			$id    = safeCheck($_GET['id'], 0);

			$order      = trim($order, ',');
			$id         = trim($id, ',');
			$orderlist  = explode(',', $order);
			$idlist     = explode(',', $id);

			$total = count($orderlist);//总数量
			for($i = 0; $i < $total; $i++){
				try {
					Admingroup::updateOrder($idlist[$i], $orderlist[$i]);
				}catch(MyException $e){
					echo $e->jsonMsg();
					exit();
				}
			}

			$msg = '管理员组排序成功!';
            Adminlog::add($msg);
			echo action_msg($msg, 1);
			break;
            
        case 'updateauth'://修改管理员组权限
            $id    = safeCheck($_GET['id']);
            $auth  = trim(safeCheck($_GET['auth'], false), '|');//去掉前后的竖线
			try {
				$r = Admingroup::updateAuth($id, $auth);
				echo $r;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
        break;
	}
?>