<?php
	/**
	 * 图片列表  picture.php
	 *
	 * @version       v0.03
	 * @create time   2016.8.23
	 * @update time   
	 * @author        cy
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

    $FLAG_TOPNAV	= "webcontent";
	$FLAG_LEFTMENU  = 'indexpic';
	$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
	$type = isset($_GET['type'])? safeCheck($_GET['type']): -1;


	$pagesize = 10;	

	$listcount = Picture::getPageList(1, 10, 0, $type, -1);
	$pagecount = ceil($listcount/$pagesize);
	
	$picturelist = Picture::getPageList($page, $pagesize, 1, $type, -1);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<title>图片 - 图片管理 - 管理系统 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<link href="css/semantic.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/upload.js"></script>
		<script type="text/javascript">
			$(function(){
				if($(".orders").width()>20){  
				    $(".orders").width("20px")  
				}

				$("#search").click(function(){
                    var selecttype = $('#selecttype').val();
                    location.href  = "content_indexpic.php?type="+selecttype;
				});
				$(".orders").blur(function(){
                        var orderlist = new Array();
                        $('.orders').each(function() {
                            orderlist.push({"id": $(this).data('id'), "order": $(this).val()})
                        });
                        $.ajax({
                            type: 'post',
                            data: {"list": orderlist},
                            dataType: 'json',
                            url: 'content_do.php?act=picorder',
                            success: function (data) {
                                var code = data.code;
                                var msg = data.msg;
                                if (code == 1) {
                                        location.reload();
                                } else {
                                    layer.alert(msg, {icon: 5});
                                }
                            }
                        })
                    });

				$("#submit_add").click(function(){
					var url = $('#pic_path0').val();
					// var http = $('#http0').val();
					var type=$('#addtype').val();
					if(type == -1){
						layer.alert('请选择图片类型', {icon: 5});
						return false;
					}
					if(url == ''){
						layer.alert('请上传图片', {icon: 5});
						return false;
					}

					// var reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;

					// if(http == ''){
					// 	layer.alert('网址链接不能为空', {icon: 5});
					// 	return false;
					// }
                    //
					// if(!reg.test(http) && http != '#'){
					// 	layer.alert('请按规定输入正确网址',{icon: 5,shade: false});
					// 	return false;
					// }

                    $.ajax({
						type : 'post',
						data : {
							url : url,
                            type : type
						},
						dataType : 'json',
						url  : 'content_do.php?act=picadd',
						success : function(data){
							var code = data.code;
							var msg  = data.msg;
							if(code > 0){
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
							}else{
								layer.alert(msg, {icon: 5});
							}
						}
					});
					return false;
				});
				
				$("#submit_edit").click(function(){
					var url = $('#pic_path1').val();
                    var upload_file1 = $('#pic_path2').val();
					var pictureid = $('#pictureid').val();
					// var http = $('#http1').val();
					var order = $('#order').val();
                    var type=$('#edittype').val();

					// var reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
					if(type == -1){
						layer.alert('请选择图片类型', {icon: 5});
						return false;
					}
					if(url == ''){
						layer.alert('图片不能为空', {icon: 5});
						return false;
					}

                    if(url != upload_file1){
                        layer.alert('请上传图片', {icon: 5});
                        return false;
                    }
					// if(http == ''){
					// 	layer.alert('网址链接不能为空', {icon: 5});
					// 	return false;
					// }

					// if(!reg.test(http) && http != '#'){
					// 	layer.alert('请按规定输入正确网址',{icon: 5,shade: false});
					// 	return false;
					// }
					$.ajax({
						type : 'post',
						data : {
							id	 : pictureid,
							url : url,
							// http : http,
							order:order,
                            type:type
						},
						dataType : 'json',
						url  : 'content_do.php?act=picedit',
						success : function(data){
							var code = data.code;
							var msg  = data.msg;
							if(code == 1){
								layer.alert(msg, {icon: 6}, function(index){
												location.reload();
											});
							}else{
								layer.alert(msg, {icon: 5});
							}
						}
					});
					return false;
				});
				// 排序
				$('#orderAll').on('click', function() {
					var orderlist = new Array();
					$('.orders').each(function() {
						orderlist.push({"id": $(this).data('id'), "order": $(this).val()})
					})
					$.ajax({
						type : 'post',
						data : {"list": orderlist},
						dataType : 'json',
						url  : 'content_do.php?act=picorder',
						success : function(data){
							var code = data.code;
							var msg  = data.msg;
							if(code == 1){
								layer.alert(msg, {icon: 6}, function(index){
												location.reload();
											});
							}else{
								layer.alert(msg, {icon: 5});
							}
						}
					});
				})
			});

			function delete_picture(id){
				layer.confirm('确认删除该条图片吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									id : id
								},
                                dataType : 'json',
								url : 'content_do.php?act=picdel',
								success : function(data){
									layer.close(index);
									
									var code = data.code;
									var msg  = data.msg;
									switch(code){
										case 1:
											layer.alert(msg, {icon: 6}, function(index){
												location.reload();
											});
											break;
										default:
											layer.alert(msg, {icon: 5});
									}
								}
							});
			            }, function(){}
			        );
			}
			
			function add_picture(){
			  layer.open({
					type: 1,
					title: '添加图片',
					shadeClose: true,
					shade: 0.3,
					area: ['500px', '550px'],
					content: $('#addpicture')
				}); 
			}
			
			function edit_picture(id){
				httppath = '<?php echo $HTTP_PATH;?>';
				$('#upimg1').attr('src',httppath+$('#url'+id).val());
				$('#pic_path1').val($('#url'+id).val());
				// $('#http1').val($('#https'+id).html());
				$('#pictureid').val(id);
				$('#order').val($('#order'+id).val());
                $('#edittype').val($('#type'+id).val());
				layer.open({
					type: 1,
					title: '修改图片',
					shadeClose: true,
					shade: 0.3,
					area: ['500px', '550px'],
					content: $('#editpicture')
				}); 
			}

            function file_upload(value){
                if($('#upload_file'+value).val() == ''){
                    layer.tips('请选择文件', '#upload_file'+value, {tips: 3});
                    return false;
                }
                var uploadUrl = 'all_upload.php?type=indexpic&id='+ value;//处理文件
                $.ajaxFileUpload({
                    url           : uploadUrl,
                    fileElementId : 'upload_file'+value,
                    dataType      : 'json',
                    success       : function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        var http_path = '<?php echo $HTTP_PATH;?>';
                        switch(code){
                            case 1:
                                $("#showimg"+value).css("display","");
                                $('#upimg'+value).attr('src',http_path+msg);
                                $('#pic_path'+value).val(msg);
                                $('#pic_path2').val(msg);
                                layer.msg('上传成功');
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error: function (data, status, e){
                        layer.alert(e);
                    }
                })
                return false;
            }
			
			function selectfile(id){
				return $("#upload_file"+id).click();
			}
			
			function file_selected(id){
				if($('#upload_file'+id).val()){
					$('#btn_selectfile'+id).html($('#upload_file'+id).val());
				}else
					$('#btn_selectfile'+id).html('点击选择图片');
			}
			
			function select_status(id){
				var status = $("#set"+id).val();
				if(status == 1)
					$("#set"+id).val(0);
				else
					$("#set"+id).val(1);
				var ison = $("#set"+id).val();
				$.ajax({
					type : 'post',
					data : {
						id : id,
						status : ison
					},
					url  : 'content_do.php?act=picstatus',
					success : function(data){
					    parent.location.reload();
						return true;
					}
				});
			}
		</script>
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
		<?php include('content_menu.inc.php');?>
			<div id="maincontent" >
				<div id="position">当前位置：<a href="content_indexpic.php">内容管理</a> &gt; 首页 &gt; 轮播图</div>
				<div style="margin:10px auto; float:right;">
					<div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="add_picture()" >添加图片</div>
					<select class="ui dropdown" id="selecttype">
						<option value="-1"> --图片类型--</option>
						<option value="1" <?php if($type == 1) echo 'selected';?>>首页</option>
                        <option value="6" <?php if($type == 6) echo 'selected';?>>公司介绍</option>
						<option value="2" <?php if($type == 2) echo 'selected';?>>新闻中心</option>
                        <option value="3" <?php if($type == 3) echo 'selected';?>>项目案例</option>
                        <option value="4" <?php if($type == 4) echo 'selected';?>>渠道分销</option>
                        <option value="5" <?php if($type == 5) echo 'selected';?>>售后服务</option>
                        <option value="7" <?php if($type == 7) echo 'selected';?>>人才招聘</option>
					</select>
                    <div class="ui mini blue button" style="float:right;margin-left:20px;" id="search">查询</div>
				</div>
				
				<div class="tablelist" >
					<table>
						<tr style="border-top:6px solid #9bccee;">
							<th width="5%">序号</th>
							<th width="10%">排序</th>
                            <th width="15%">图片类型</th>
							<th width="15%">图片</th>
<!--							<th width="15%">链接地址</th>-->
							<th width="15%">是否显示</th>
							<th width="10%">操作</th>
						</tr>
						<?php
							if(!empty($picturelist)){
								$i = ($page-1)*$pagesize+1;
								foreach($picturelist as $list){
									echo '
										<tr class="pictures">
											<td class="center" >'.$i.'</td>
											<td class="center"><a class="ui small input" style="width:35px;"><input type="text" id="order'.$list['id'].'" value="'.$list['order'].'" class="orders" data-id="'.$list['id'].'" style="max-width:55%;" /></a></td>
											<td class="center" >'.$ARRAY_Picture_type[$list['type']].'<input type="hidden" value="'.$list['type'].'" id="type'.$list['id'].'" /></td>
											<td class="center"><img src="'.$HTTP_PATH.$list['url'].'" width="150px" height="100px" alt=""></td>
											<td class="center">
												<input type="hidden" value="'.$list['status'].'" id="set'.$list['id'].'" />
												<div class="ui toggle checkbox" onchange="select_status('.$list['id'].');">
												  <input type="checkbox" ';
												if($list['status'] == 1)
													echo 'checked';
												
										echo	'/>
												  <label id="tips'.$list['id'].'" style="color:#999;">开启</label>
												</div>
											</td>
											<td class="center">
												<input type="hidden" value="'.$list['url'].'" id="url'.$list['id'].'" />
												<a href="javascript:edit_picture('.$list['id'].')" ><img title="修改图片" src="images/edit.png" width="20px;" /></a>
												<a href="javascript:delete_picture('.$list['id'].')" ><img title="删除图片" src="images/del.png" width="20px;" /></a>
											</td>
										</tr>';
									$i++;
								}
							}else{
								echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
							}
						?>
					</table>
				</div>
				<!-- 分页 -->
				<div id="pagelist">
					<!-- 分页信息 -->
					<div class="pageinfo">
							<span>共<?php echo $listcount;?>条，共<?php echo $pagecount;?>页</span>
						</div>
                    <?php
                    if($pagecount>1){
                        echo dspPages(getPageUrl(), $page, $pagesize, $listcount, $pagecount);
                    }
                    ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<!--add picture start-->
		<div id="addpicture" style="display:none; margin-left:20px;margin-top:20px;">
			<div id="formlist">
                <p>
                    <label>图片类型</label>
                    <a class="ui small input">
                       <select class="ui dropdown" id="addtype">
                           <option value="-1"> --图片类型--</option>
                           <option value="1">首页</option>
                           <option value="6">公司介绍</option>
                           <option value="2">新闻中心</option>
                           <option value="3">项目案例</option>
                           <option value="4">渠道分销</option>
                           <option value="5">售后服务</option>
                           <option value="7">人才招聘</option>
                       </select>
                    </a>
                    <a style="color:red;font-weight:bold;">*</a>
                </p>
<!--				<p>-->
<!--					<label>链接地址</label>-->
<!--					<a class="ui small input">-->
<!--						<input type="text" name="http0" id="http0" />-->
<!--					</a>-->
<!--					<a style="color:red;font-weight:bold;">*</a>-->
<!--				</p>-->
<!--				<p  style="text-align:center;color:red;">请以http://或https://开头,若无图片链接,请输入：#</p>-->

				<p>
					<label>图片上传：</label>
					<div style="float:left;width:150px;height:90px; ovarflow:hidden; border: 1px solid #ccc;">
						<div id="showimg0" style="display:none">
                            <img id="upimg0" src=""  width="150px;"/>
						</div>
					</div>
					<div style="float:right;width:150px;">
						<input type="hidden" id="pic_path0" />
						<input type="file" name="upload_file0" id="upload_file0" style="display:none" onchange="file_selected(0);" />        <!-- 添加上传文件 -->
						<button class="ui mini gray button" onclick="selectfile(0)" id="btn_selectfile0">点击选择图片</button>
						<a style="color:red;font-weight:bold;">*</a>
						<input type="button" onclick="file_upload(0);" class="ui mini blue button" style="margin-top:10px;" value="上 传">
					</div>
				</p>
                <p style="text-align:center;color:red;">图片大小：1920px * 500px</p>
				<p>
					<label>&nbsp;</label>
					<input type="submit" id="submit_add" class="ui orange mini button" style="margin-left:30px;" value="提　交" />
				</p>
			</div>
		</div>
		<!--add picture end-->
		
		<!--edit picture start-->
		<div id="editpicture" style="display:none; margin-left:20px;margin-top:20px;">
			<div id="formlist">
                <p>
                    <label>图片类型</label>
                    <a class="ui small input">
                        <select class="ui dropdown" id="edittype">
                            <option value="-1"> --图片类型--</option>
                            <option value="1">首页</option>
                            <option value="6">公司介绍</option>
                            <option value="2">新闻中心</option>
                            <option value="3">项目案例</option>
                            <option value="4">渠道分销</option>
                            <option value="5">售后服务</option>
                            <option value="7">人才招聘</option>
                        </select>
                    </a>
                    <a style="color:red;font-weight:bold;">*</a>
                </p>
<!--				<p>-->
<!--					<label>链接地址</label>-->
<!--					<a class="ui small input">-->
<!--						<input type="text" name="http1" id="http1" />-->
<!--					</a>-->
<!--					<a style="color:red;font-weight:bold;">*</a>-->
<!--				</p>-->
<!--				<p style="text-align:center;color:red;">请以http://或https://开头,若无图片链接,请输入：#</p>-->
				<p>
					<label>图片上传：</label>
					<div style="float:left;width:150px;overflow:hidden;border: 1px solid #ccc;">
						<div id="showimg1">
                            <img id="upimg1" src=""  width="150px;" />
						</div>
					</div>
					<div style="float:right;width:150px;">
						<input type="hidden" id="pic_path1" />
                        <input type="hidden" id="pic_path2" />
						<input type="file" name="upload_file1" id="upload_file1" style="display:none" onchange="file_selected(1);" value=""/>        <!-- 添加上传文件 -->
						<button class="ui mini gray button" onclick="selectfile(1)" id="btn_selectfile1">点击选择图片</button>
						<a style="color:red;font-weight:bold;">*</a>
						<input type="button" onclick="file_upload(1);" class="ui mini blue button" style="margin-top:10px;" value="上 传">
					</div>
				</p>
                <p style="text-align:center;color:red;">图片大小：1920px * 500px</p>
				<p>
					<label>&nbsp;</label>
					<input type="submit" id="submit_edit" class="ui orange mini button" value="提　交" />
					<input type="hidden" id="pictureid" />
				</p>
			</div>
		</div>
		<!--edit picture end-->
		<?php include('footer.inc.php');?>
	</body>
</html>