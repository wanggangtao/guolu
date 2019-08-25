<?php
	/**
	 * 编辑管理员  admin_edit.php
	 *
	 * @version       v0.03
	 * @create time   2014-9-5
	 * @update time   2016-3-26
	 * @author        dxl jt
	 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
	 */

	require_once('admin_init.php');
    require_once('admincheck.php');
    
	$POWERID = '7002';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);
	
	$FLAG_LEFTMENU = 'admin_edit';
    
    //获得参数后，率先检查参数的合法性
    $id = safeCheck(trim($_GET['id']));
	try {
		$admin = new Admin($id);
		$adminId      = $id;
		$adminAccount = $admin->account;
        $adminGroup   = $admin->gid;
	} catch(MyException $e){
		echo $e->getMessage();
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="芝麻开发 http://www.zhimawork.com" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#btn_submit').click(function(){

					var account = $('input[name="account"]').val();
					var group = $('#group').val();
					var aid = <?php echo $adminId;?>;

					if(account == ''){
						layer.tips('账号不能为空', '#s_admin_account');
						return false;
					}
					if(group == ''){
						layer.tips('请选择管理员组', '#s_admin_group');
						return false;
					}
					$.ajax({
						type        : 'POST',
						data        : {
								account  : account,
								id       : aid,
								group    : group
						},
						url :         'admin_do.php?act=edit',
                        dataType:     'json',
						success :     function(data){
                                            var code = data.code;
											var msg  = data.msg;
											switch(code){
												case 1:
													layer.alert(msg, {icon: 6, shade: false}, function(index){
        												parent.location.reload();
        											});
													break;
												default:
													layer.alert(msg, {icon: 5});
											}
									  }
					});
				});
			});
		</script>
	</head>
	<body>
		<div id="formlist">
			<p>
				<label>帐号</label>
				<input type="text" class="text-input input-length-30" name="account" id="account" value="<?php echo $adminAccount;?>" />
				<span class="warn-inline" id="s_admin_account">* </span>
			</p>
			<p>
				<label>管理员所属组</label>
				<select name="admin_group" class="select-option" id="group">
					<?php
						$group = Admingroup::getList();
                        
						foreach($group as $g){
							$gid   = $g['groupid'];
							$gname = $g['name'];
							echo '<option value="'.$gid.'"';
							if($adminGroup == $gid) {
								echo' selected="selected">'.$gname.'</option>';
							}else{
								echo' >'.$gname.'</option>';
							}
						}
					?>
				</select>
				<span class="warn-inline" id="s_admin_group">* </span>
			</p>
			<p>
				<label>　　</label>
				<input type="submit" id="btn_submit" class="btn_submit" value="提　交" />
			</p>
		</div>
	</body>
</html>