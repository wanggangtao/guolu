<?php
	/**
	 * 项目案例  content_projectcase.php
	 *
	 * @version       v0.01
	 * @create time   2018.11.21
	 * @update time   
	 * @author        dlk
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

    $FLAG_TOPNAV	= "webcontent";
	$FLAG_LEFTMENU  = 'projectcase';
	$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
	$type = isset($_GET['type'])? safeCheck($_GET['type']): 0;
	$pagesize = 10;

	$listcount = Web_projectcase::getPageList(1, 10, 0, $type);
	$pagecount = ceil($listcount/$pagesize);
	
	$caselist = Web_projectcase::getPageList($page, $pagesize, 1, $type);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<title>案例展示 - 项目案例 - 管理系统 </title>
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
                    var type = $('#type').val();
                    location.href  = "content_projectcase.php?type="+type;
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
                        url  : 'content_projectcase_do.php?act=caseorder',
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
						url  : 'content_projectcase_do.php?act=caseorder',
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
				layer.confirm('确认删除该条项目案例吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									id : id
								},
                                dataType : 'json',
								url : 'content_projectcase_do.php?act=casedel',
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
					type: 2,
					title: '添加项目案例',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_projectcase_add.php'
				}); 
			}
			
			function edit_picture(id){
				layer.open({
					type: 2,
					title: '修改项目案例',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_projectcase_edit.php?id='+id
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
						return true;
					}
				});
			}

            function change_switch_status(thisid){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : thisid

                    },
                    dataType :    'json',
                    url :         'content_projectcase_do.php?act=good_status',
                    success :     function(data){
                        //   alert(data);
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error:   function(data){
                        layer.alert("操作失败", {icon: 5});
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
				<div id="position">当前位置：<a href="content_indexpic.php">内容管理</a> &gt; 项目案例 &gt; 案例展示</div>
				<div style="margin:10px auto; float:right;">
					<div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="add_picture()" >新增</div>
<!--					<div class="ui mini blue button" style="float:right;margin-left:20px;" id="orderAll">排序</div>-->
					<select class="ui dropdown" id="type">
						<option value="0"> --类型--</option>
						<?php 
						foreach ($ARRAY_Projectcase_type as $key => $val){
							$selected = "";
							if($key == $type){
								$selected = "selected";
							}
							echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
						}
						?>
					</select>
					<div class="ui mini blue button" style="float:right;margin-left:20px;" id="search">查询</div>
				</div>
				
				<div class="tablelist" >
					<table>
						<tr style="border-top:6px solid #9bccee;">
							<th width="5%">序号</th>
							<th width="10%">排序</th>
							<th width="15%">标题</th>
							<th width="10%">类型</th>
							<th width="15%">发布时间</th>
                            <th width="10%">设为优秀</th>
							<th width="10%">浏览次数</th>
							<th width="15%">图片展示</th>
							<th width="10%">操作</th>
						</tr>
						<?php
							if(!empty($caselist)){
								$i = ($page-1)*$pagesize+1;
								foreach($caselist as $list){
									echo '
										<tr class="pictures">
											<td class="center" >'.$i.'</td>
											<td class="center"><a class="ui small input" style="width:35px;"><input type="text" id="order'.$list['id'].'" value="'.$list['order'].'" class="orders" data-id="'.$list['id'].'" style="max-width:55%;" /></a></td>
											<td>'.$list['title'].'</td>
											<td>'.$ARRAY_Projectcase_type[$list['type']].'</td>
											<td>'.date('Y-m-d H:i:s', $list['addtime']).'</td>
											';?>

                                    <td>
                                        <div class="ui toggle checkbox">
                                            <div onclick="change_switch_status('<?php echo $list["id"]?>')" class="search_checkbox">

                                                <input type="checkbox" value="<?php echo $list["id"];?>" <?php if($list["is_good"]==1) echo 'checked';else echo ''?>>

                                                <label style="color:#999;">优秀</label>
                                            </div>

                                        </div>
                                    </td>

                        <?php echo '
									
											<td>'.$list['count'].'</td>
											<td class="center"><img src="'.$HTTP_PATH.$list['picurl'].'" width="150px" height="100px" alt=""></td>
											<td class="center">
												<a href="javascript:edit_picture('.$list['id'].')" ><img title="修改项目案例" src="images/edit.png" width="20px;" /></a>
												<a href="javascript:delete_picture('.$list['id'].')" ><img title="删除项目案例" src="images/del.png" width="20px;" /></a>
											</td>
										</tr>';
									$i++;
								}
							}else{
								echo '<tr><td class="center" colspan="8">没有数据</td></tr>';
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
		<?php include('footer.inc.php');?>
	</body>
</html>