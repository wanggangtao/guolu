<?php
/**
 * Created by kjb.
 * Date: 2018/12/6
 * Time: 21:18
 */
	require_once('admin_init.php');
	require_once('admincheck.php');

    $FLAG_TOPNAV	= "webcontent";
	$FLAG_LEFTMENU  = 'companyintroduction';
	$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
	$pagesize = 10;

	$listcount = Web_introduction::getPageList(1, 10, 0);
	$pagecount = ceil($listcount/$pagesize);

	$introductionlist = Web_introduction::getPageList($page, $pagesize, 1);
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
			function delete_introduction(id){
				layer.confirm('确认删除该条公司介绍吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									id : id
								},
                                dataType : 'json',
								url : 'content_companyintroduction_do.php?act=introductiondel',
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

			function add_introduction(){
			  layer.open({
					type: 2,
					title: '添加公司介绍',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_companyintroduction_add.php'
				});
			}

			function edit_introduction(id){
				layer.open({
					type: 2,
					title: '修改公司介绍',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_companyintroduction_edit.php?id='+id
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
                var if_top = $("#set"+id).val();
                if(if_top == 1)
                    $("#set"+id).val(0);
                else
                    $("#set"+id).val(1);
                var ison = $("#set"+id).val();
                $.ajax({
                    type : 'post',
                    data : {
                        id : id,
                        if_top : ison
                    },
                    url  : 'content_companysituation_do.php?act=set_if_top',
                    success : function(data){
                        parent.location.reload();
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
				<div id="position">当前位置：<a href="content_indexpic.php">前端内容管理</a> &gt; 公司介绍</div>
				<div style="margin:10px auto; float:right;">
					<div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="add_introduction()" >新增</div>
				</div>

				<div class="tablelist" >
					<table>
						<?php
							if(!empty($introductionlist)){
								foreach($introductionlist as $list){
									echo '
										<tr>
									    <table class="indextable">
									    <tr>
									    <td align="left">栏目：'.$list['kind'].'</td>
									    <td align="right">
									    <a href="javascript:edit_introduction('.$list['id'].')" ><img title="修改公司动态" src="images/edit.png" width="20px;" /></a>
										<a href="javascript:delete_introduction('.$list['id'].')" ><img title="删除公司动态" src="images/del.png" width="20px;" /></a></td>
									    </tr>
									    <tr>
									    <td align="left">内容：'.$list['content'].'</td>
									    </tr>
										</tr>';
								}
							}else{
								echo '<tr><td class="center" colspan="9">没有数据</td></tr>';
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