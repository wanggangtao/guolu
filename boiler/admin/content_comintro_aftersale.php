<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/12/27 0027
 * Time: 下午 6:29
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "webcontent";
$FLAG_LEFTMENU  = 'aftersaver';
$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
$pagesize = 10;

$main_content = Web_introduction::getList();

$advantage = Web_intro_advantage::getListByType(3);
$ourpresencecount = Web_intro_aftersale_pic::getPageList(1, $pagesize, 0);
$ourpresence = Web_intro_aftersale_pic::getPageList($page, $pagesize, 1);
$pagecount = ceil($ourpresencecount/$pagesize);

//print_r($ourpresence);
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
        function weightchange_advantage(id,source_value, now) {
            if (source_value != now){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : id,
                        weight: now
                    },
                    dataType :    'json',
                    url :         'content_comintro_aftersale_do.php?act=order_advantage',
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

        function weightchange_ourpresence(id,source_value, now) {
            if (source_value != now){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : id,
                        weight: now
                    },
                    dataType :    'json',
                    url :         'content_comintro_aftersale_do.php?act=order_ourpresence',
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

        function edit_introduction(id){
            layer.open({
                type: 2,
                title: '修改售后板块内容',
                shadeClose: true,
                shade: 0.3,
                area: ['1000px', '650px'],
                content: 'content_companyintroduction_edit.php?id='+id
            });
        }

        function edit_advantage(id){
            layer.open({
                type: 2,
                title: '修改售后版块优势',
                shadeClose: true,
                shade: 0.3,
                area: ['750px', '450px'],
                content: 'content_comintro_aftersale_ad_edit.php?id='+id
            });
        }

        function edit_ourpresence(id){
         //   alert(id);
            layer.open({
                type: 2,
                title: '修改售后版块我们的风采',
                shadeClose: true,
                shade: 0.3,
                area: ['1000px', '650px'],
                content: 'content_comintro_aftersale_pic_edit.php?id='+id
            });
        }

        function change_switch_status(thisid){
            $.ajax({
                type        : 'POST',
                data        : {
                    id : thisid

                },
                dataType :    'json',
                url :         'content_comintro_aftersale_do.php?act=display_status',
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
        function add_aftersale_pic(){
            layer.open({
                type: 2,
                title: '添加图片',
                shadeClose: true,
                shade: 0.3,
                area: ['1000px', '650px'],
                content: 'content_comintro_aftersale_pic_add.php'
            });
        }

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
                        url : 'content_comintro_aftersale_do.php?act=del_pic',
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
                }
            );
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
							    '.$main_content[0]['kind'].'
								<a align="right" href="javascript:edit_introduction('.$main_content[0]['id'].')" ><img title="修改售后版块内容" src="images/edit.png" width="20px;" /></a>
							</td>
									    
						</tr>
						<tr style="border-top:6px solid #9bccee;">
						   <td>内容概要：'.$main_content[0]['content'].'</td>
						</tr>
						<tr>
						   <td>联系电话：'.$main_content[0]['tel'].'</td>
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
                    <td colspan="5">售后版块-优势</td>
                </tr>
                <tr style="border-top:6px solid #9bccee;">
                    <th width="10%">ID</th>
                    <th width="10%">排序</th>
                    <th width="20%">标题</th>
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
									  <input onchange="weightchange_advantage(' . $ad_value['id'] . ','.$ad_value['weight'].',$(this).val())" width="20px;" type="number" placeholder="'.$ad_value['weight'].'">
							     </td>									     
								 <td>'.$ad_value['title'].'</td>									     									     
								 <td>'.$ad_value['content'].'</td>
							     <td>
									  <a align="right" href="javascript:edit_advantage('.$ad_value['id'].')" ><img title="修改售后版块优势" src="images/edit.png" width="20px;" /></a>
								 </td>
							 </tr>  
							';
                        $i++;
                    }

                }
                else{
                    echo '<tr><td class="center" colspan="4">没有数据</td></tr>';
                }
                ?>
            </table>
        </div>


        <div style="margin:10px auto;">
            </br></br>
        </div>

        <div style="margin:10px auto; float:right;">
            <div class="ui mini blue button" style="float:right;margin-left:20px;" onclick="add_aftersale_pic()" >新增</div>
        </div>
        <div class="tablelist" >
            <table>
                <tr style="border-top:6px solid #9bccee;">

                    <td colspan="5">售后版块-我们的风采</td>
                </tr>
                <tr style="border-top:6px solid #9bccee;">
                    <th width="10%">ID</th>
                    <th width="10%">排序</th>
                    <th width="20%">图片</th>
                    <th width="10%">是否显示</th>
                    <th width="10%">操作</th>

                </tr>

                </tr>
                <?php
                if(!empty($ourpresence)){
                    $i = ($page-1)*$pagesize+1;
                    foreach($ourpresence as $op_value){
                        echo '
							  <tr>
								  <td>'.$i.'</td>
								  <td>
									   <input onchange="weightchange_ourpresence(' . $op_value['id'] . ','.$op_value['weight'].',$(this).val())" width="20px;" type="number" placeholder="'.$op_value['weight'].'">
								  </td>			
								  <td ><img src="'.$HTTP_PATH.$op_value['picurl1'].'" width="150px" height="100px" alt=""></td>						     
																     									     
							  ';
                ?>
                        <td>
                            <div class="ui toggle checkbox">
                                <div onclick="change_switch_status('<?php echo $op_value["id"]?>')" class="search_checkbox">

                                    <input type="checkbox" value="<?php echo $op_value["id"];?>" <?php if($op_value["display"]==1) echo 'checked';else echo ''?>>

                                    <label style="color:#999;">开启</label>
                                </div>

                            </div>
                        </td>

				<?php
                        echo '
                              <td>
								<a align="right" href="javascript:edit_ourpresence('.$op_value['id'].')" ><img title="修改售后版块我们的风采" src="images/edit.png" width="20px;" /></a>
								<a class="delete" href="javascript:delete_picture('.$op_value['id'].')"><img title="删除售后版块我们的风采" src="images/del.png" width="20px;" /></a>
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
        <!-- 分页 -->
        <div id="pagelist">
            <!-- 分页信息 -->
            <div class="pageinfo">
                <span>共<?php echo $ourpresencecount;?>条，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pagesize, $ourpresencecount, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>