<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/12/27 0027
 * Time: 下午 1:11
 */

	require_once('admin_init.php');
	require_once('admincheck.php');

    $FLAG_TOPNAV	= "webcontent";
	$FLAG_LEFTMENU  = 'disver';
//	$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
//	$pagesize = 10;
//
//	$listcount = Web_introduction::getPageList(1, 10, 0);
//	$pagecount = ceil($listcount/$pagesize);

	$main_content = Web_introduction::getList();

    $advantage = Web_intro_advantage::getListByType(2);
//	print_r($main_content);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<title>分销版块 - 公司介绍 - 管理系统 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<link href="css/semantic.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/upload.js"></script>
		<script type="text/javascript">
            function weightchange(id,source_value, now) {
                if (source_value != now){
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            id : id,
                            weight: now
                        },
                        dataType :    'json',
                        url :         'content_comintro_distribution_do.php?act=order',
                        success :     function(data){
                          //  alert(data);
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

			function edit_introduction(id){
				layer.open({
					type: 2,
					title: '修改分销板块内容',
					shadeClose: true,
					shade: 0.3,
					area: ['1000px', '650px'],
					content: 'content_companyintroduction_edit.php?id='+id
				});
			}

            function edit_advantage(id){
                layer.open({
                    type: 2,
                    title: '修改分销版块优势',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['750px', '450px'],
                    content: 'content_comintro_distribution_ad_edit.php?id='+id
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

                <div class="tablelist" >
                    <table>
                        <?php
                        if(!empty($main_content)){
                            echo '
								<table class="indextablalign="right"e">
								<tr style="border-top:6px solid #9bccee;">
									<td align="left">
									    '.$main_content[1]['kind'].'
									    <a align="right" href="javascript:edit_introduction('.$main_content[1]['id'].')" ><img title="修改分销版块内容" src="images/edit.png" width="20px;" /></a>
									</td>
									    
								</tr>
							    <tr style="border-top:6px solid #9bccee;">
						          <td>内容概要：'.$main_content[1]['content'].'</td>
						       </tr>
						       <tr>
						          <td>联系电话：'.$main_content[1]['tel'].'</td>
						       </tr>
							   ';

                        }

                        ?>
                    </table>
                </div>
                <div style="margin:10px auto;">
                </br></br>
				</div>

                <div class="tablelist" >
                    <table>
                        <tr style="border-top:6px solid #9bccee;">
                            <td colspan="6">分销版块-优势</td>
                        </tr>
                        <tr style="border-top:6px solid #9bccee;">
                            <th width="10%">ID</th>
                            <th width="10%">排序</th>
                            <th width="20%">标题</th>
							<th width="20%">图片</th>
                            <th width="30%">内容</th>
                            <th width="10%">操作</th>

                        </tr>

                        </tr>
                        <?php
                        if(!empty($advantage)){
                            $i = 1;
                            foreach($advantage as $ad_value){
                                echo '
							  
									 <tr>
									     <td>'.$i.'</td>
									     <td>
									         <input onchange="weightchange(' . $ad_value['id'] . ','.$ad_value['weight'].',$(this).val())" width="20px;" type="number" placeholder="'.$ad_value['weight'].'">
									     </td>									     
									     <td>'.$ad_value['title'].'</td>	
									     <td><img src="'.$HTTP_PATH.$ad_value['purl'].'" width="135px" height="135px" alt=""></td>
									     <td>'.$ad_value['content'].'</td>
									     <td>
									        <a align="right" href="javascript:edit_advantage('.$ad_value['id'].')" ><img title="修改分销版块优势" src="images/edit.png" width="20px;" /></a>
									     </td>
									 </tr>
									 
							   
							';
                                $i++;
                            }

                        }
                        else{
                            echo '<tr><td class="center" colspan="9">没有数据</td></tr>';
                        }
                        ?>
                    </table>
                </div>


			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>