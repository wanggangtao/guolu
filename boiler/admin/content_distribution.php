<?php
	/**
	 * 渠道分销 content_distribution.php
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
	$FLAG_LEFTMENU  = 'distribution';
	$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
	$pagesize = 10;
    $province_val = isset($_GET['province'])? $_GET['province']: 0;
    $city_val= isset($_GET['city'])? $_GET['city']: 0;
	$listcount = Web_distribution::getPageList(1, 10, 0,$province_val,$city_val);
	$pagecount = ceil($listcount/$pagesize);
	
	$caselist = Web_distribution::getPageList($page, $pagesize, 1,$province_val,$city_val);
//	print_r($caselist);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<title>分销点展示 - 渠道分销 - 管理系统 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<link href="css/semantic.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/upload.js"></script>
		<script type="text/javascript">
			$(function(){
			});
			
			function delete_picture(id){
				layer.confirm('确认删除该条信息吗？', {
		            	btn: ['确认','取消']
			            }, function(){
			            	var index = layer.load(0, {shade: false});
			            	$.ajax({
								type        : 'POST',
								data        : {
									id : id
								},
                                dataType : 'json',
								url : 'content_distribution_do.php?act=del',
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
					title: '添加渠道分销',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_distribution_add.php'
				}); 
			}
			
			function edit_picture(id){
				layer.open({
					type: 2,
					title: '修改渠道分销',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_distribution_edit.php?id='+id
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
		</script>
        <script>
            function selectOnchang(obj){
                var value = obj.options[obj.selectedIndex].value;

                $("#address_two").html("<option value='0'>全部</option>");
                if (value == 0) return false;
                $.ajax({
                    type: 'POST',
                    data: {

                        id: value,
                        type: 0
                    },

                    dataType: 'json',
                    url: 'address_do.php?act=getChild',
                    success: function (data) {

                        var code = data.code;

                        var resultData = data.msg;
                        switch (code) {

                            case 1:

                                var html = "<option value='0'>全部</option>";
                                for (var i = 0; i < resultData.length; i++) {
                                    html += "<option value='" + resultData[i].id + "'>" + resultData[i].name + "</option>";
                                }
                                $("#address_two").html(html);


                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    }
                });


            }
            function search(){
                var value2=document.getElementById("address_two");
                var index2=value2.selectedIndex;
                var city=value2.options[index2].value;

                var value1=document.getElementById("address_one");
                var index1=value1.selectedIndex;
                var province=value1.options[index1].value;
//                if(province==""||province==0){
//                    layer.alert("请选择省份");
//                    return false;
//                }
                location.href  = "content_distribution.php?province="+province+"&&city="+city;
            }

            function weightchange(id,source_value, now) {
                if (source_value != now){
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            id : id,
                            weight: now
                        },
                        dataType :    'json',
                        url :         'content_distribution_do.php?act=order',
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

            }
            function change_switch_status(thisid){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : thisid

                    },
                    dataType :    'json',
                    url :         'content_distribution_do.php?act=good_status',
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
				<div id="position">当前位置：<a href="content_indexpic.php">内容管理</a> &gt; 渠道分销 &gt; 分销点展示</div>
				<div style="margin:10px auto; float:right;">
					<div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="add_picture()" >新增</div>
                    <select class="ui dropdown" onchange="selectOnchang(this)" id="address_one">
                        <?php
                        if($province_val!=0){
                            if($province_val!=27){
                                echo '<option value="0">全部</option>';
                            }
                            else{
                                $province_name=District::getInfoById($province_val);
                                echo '<option value="0">全部</option>';
                                echo '<option value="'.$province_val.'"selected>'.$province_name['name'].'</option>';
                            }

                        }
                        else
                        { echo '<option value="0">全部</option>';

                            $provinces=District::getAddressType(3,0);
                            if(!empty($provinces)){
                                foreach ($provinces as $province){

                                    $upId=$province['id'];
                                    $name=$province['name'];
                                    if($name=="陕西省")
                                        echo '<option value="' . $upId . '">' . $name . '</option>';
                                }
                            }}
                        ?>
                    </select>

                    <select class="ui dropdown" id="address_two">

                        <?php
                        if($province_val!=0&&$city_val!=0){
                            $city_name=district::getInfoById($city_val);
                            echo '<option value="'.$city_val.'">'.$city_name['name'].'</option>';
                            echo '<option value="0">全部</option>';
                            $city_lists=district::getInfoByUpid($province_val);
                            foreach ($city_lists as $city_list){
                                if($city_list['name']!=$city_name['name']){
                                    echo '<option value="' . $city_list['id'] . '">' . $city_list['name']. '</option>';
                                }
                            }}
                        if($province_val!=0&&$city_val==0){
                            echo '<option value="0">全部</option>';
                            $city_lists=district::getInfoByUpid($province_val);
                            foreach ($city_lists as $city_list){
                                echo '<option value="' . $city_list['id'] . '">' . $city_list['name']. '</option>';
                            }
                        }
                        if($province_val==0&&$city_val==0){
                            echo '<option value="0">全部</option>';
                        }
                        ?>

                    </select>
                    <div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="search()">查询</div>
				</div>
				
				<div class="tablelist" >
					<table>
						<tr style="border-top:6px solid #9bccee;">
							<th width="5%">序号</th>
                            <th width="10%">排序</th>
							<th width="10%">标题</th>
<!--							<th width="25%">详情</th>-->
                            <th width="10%">设为优秀</th>
							<th width="10%">地址</th>
							<th width="15%">图片</th>
                            <th width="10%">电话</th>
							<th width="10%">操作</th>
						</tr>
						<?php
							if(!empty($caselist)){
								$i = ($page-1)*$pagesize+1;
								foreach($caselist as $list){
									echo '
										<tr class="pictures">
											<td class="center" >'.$i.'</td>
											<td>
											    <input onchange="weightchange(' . $list['id'] . ','.$list['weight'].',$(this).val())" width="20px;" type="number" placeholder="'.$list['weight'].'">
                                       
                                            </td>
											<td id="t_title'.$list['id'].'">'.$list['title'].'</td>
											
											';?>

                                    <td>
                                        <div class="ui toggle checkbox">
                                            <div onclick="change_switch_status('<?php echo $list["id"]?>')" class="search_checkbox">

                                                <input type="checkbox" value="<?php echo $list["id"];?>" <?php if($list["is_good"]==1) echo 'checked';else echo ''?>>

                                                <label style="color:#999;">优秀</label>
                                            </div>

                                        </div>
                                    </td>

										<?php
										echo '
											<td id="t_address'.$list['id'].'">'.$list['address'].'</td>
											<td class="center"><img src="'.$HTTP_PATH.$list['picurl'].'" width="150px"></td>
											<td id="t_address'.$list['id'].'">'.$list['tel'].'</td>
											<td class="center">
												<input type="hidden" value="'.$list['picurl'].'" id="picurl'.$list['id'].'" />
												<a href="javascript:edit_picture('.$list['id'].')" ><img title="修改" src="images/edit.png" width="20px;" /></a>
												<a href="javascript:delete_picture('.$list['id'].')" ><img title="删除" src="images/del.png" width="20px;" /></a>
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
		<?php include('footer.inc.php');?>
	</body>
</html>