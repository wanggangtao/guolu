<?php
/**
 * Created by kjb.
 * Date: 2018/12/6
 * Time: 21:18
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV	= "webcontent";
$FLAG_LEFTMENU  = 'companyintroduction_project';
$page = isset($_GET['page'])? safeCheck($_GET['page']): 1;
$pagesize = 10;

$project_pics_count = Web_intro_projectpic::getPageList(1, $pagesize, 0);
$project_pics = Web_intro_projectpic::getPageList($page, $pagesize, 1);
$introductioncount = Web_introduction::getPageList(1, $pagesize, 0);

$pagecount = ceil($project_pics_count/$pagesize);
$introductionlist = Web_introduction::getPageList($page, $pagesize, 1);
//print_r($project_pics_count);
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
            var uploadUrl = 'all_upload.php?type=projectpic&id='+ value;//处理文件
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

    <script type="text/javascript">
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
                        url : 'content_companyintro_projectpic_do.php?act=del',
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
 //修改图片
        function edit_picture(id){
            layer.open({
                type: 2,
                title: '修改图片',
                shadeClose: true,
                shade: 0.3,
                area: ['750px', '400px'],
                content: 'content_companyintro_projectpic_edit.php?id='+id
            });
        }
//是否显示
        function picchange_switch_status(thisid){
            $.ajax({
                type        : 'POST',
                data        : {
                    id : thisid

                },
                dataType :    'json',
                url :         'content_companyintro_projectpic_do.php?act=display_status',
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

//图片排序
        function picweightchange(id,source_value, now) {
            if (source_value != now){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : id,
                        weight: now
                    },
                    dataType :    'json',
                    url :         'content_companyintro_projectpic_do.php?act=order',
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

        $(function() {
            //添加
            $('#add').click(function () {
                layer.open({
                    type: 1,
                    title: '添加图片',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['700px', '350px'],
                    content: $('#addcontent')
                });
            });

        });

        function ajaxUpload(value){
            if($('#upload_file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=projectpic&id='+ value;//处理文件
            $.ajaxFileUpload({
                url           : uploadUrl,
                fileElementId : 'upload_file'+value,
                dataType      : 'json',
                success       : function(data){
                    var code = data.code;
                    var msg  = data.msg;
                    switch(code){
                        case 1:
                            $('#picurl'+ value).val(msg);
                            $('#val'+ value).attr("src",'<?php echo $HTTP_PATH;?>' + msg);
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

        $(function() {
            $("#btn_submit_add").click(function () {

                var http = $('#http').val();
                var picurl = $('#picurl1').val();
                var reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
                if(http == ''){
                    layer.alert('网址链接不能为空', {icon: 5});
                    return false;
                }
                if(!reg.test(http) && http != '#'){
                    layer.alert('请按规定输入正确网址',{icon: 5,shade: false});
                    return false;
                }
                if(picurl == ''){
                    layer.alert('图片不能为空', {icon: 5});
                    return false;
                }

                $.ajax({
                    type : 'post',
                    data : {
                        http : http,
                        picurl : picurl,
                    },
                    dataType : 'json',
                    url  : 'content_companyintro_projectpic_do.php?act=add',
                    success : function(data){
                        var code = data.code;
                        var msg  = data.msg;

                        if(code == 1){
                            layer.alert(msg, {icon: 6}, function(index){
                                location.reload();
                            })
                        }else{
                            layer.alert(msg, {icon: 5});
                        }
                    }
                });
                return false;
            });
        });

        function advweightchange(id,source_value, now) {
            if (source_value != now){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : id,
                        weight: now
                    },
                    dataType :    'json',
                    url :         'content_companyintro_projectadv_do.php?act=order',
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

        function edit_advantage(id){
            layer.open({
                type: 2,
                title: '修改分销版块优势',
                shadeClose: true,
                shade: 0.3,
                area: ['650px', '550px'],
                content: 'content_companyintro_projectadv_edit.php?id='+id
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
                if(!empty($introductionlist)){
                    foreach($introductionlist as $list){
                        if($list['id']==1)
                        echo '
							<table class="indextable">
								<tr style="border-top:6px solid #9bccee;">	    
									<td align="left">'.$list['kind'].'
									<a href="javascript:edit_introduction('.$list['id'].')" ><img title="修改公司动态" src="images/edit.png" width="20px;" /></a>
									</td>
								</tr>    
							    <tr style="border-top:6px solid #9bccee;">	  
									<td align="left">内容概要：'.$list['content'].'</td>
							    </tr>
							    <tr>
									<td align="left">咨询电话：'.$list['tel'].'</td>
								</tr>
	                 ';
                    }
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
                <td colspan="6">项目版块-优势</td>
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
            $advantage=web_intro_advantage::getListByType(1);
            $i = 1;
            if(!empty($advantage)){

                foreach($advantage as $ad_value){
                    echo '
							  
									 <tr>
									     <td>'.$i.'</td>
									     <td>
									         <input onchange="advweightchange(' . $ad_value['id'] . ','.$ad_value['weight'].',$(this).val())" width="20px;" type="number" placeholder="'.$ad_value['weight'].'">
									     </td>									     
									     <td>'.$ad_value['title'].'</td>		
									     <td><img src="'.$HTTP_PATH.$ad_value['purl'].'" width="135px" height="135px" alt=""></td>
									     <td>'.$ad_value['content'].'</td>
									     <td>
									        <a align="right" href="javascript:edit_advantage('.$ad_value['id'].')" ><img title="修改项目版块优势" src="images/edit.png" width="20px;" /></a>
									     </td>
									 </tr>
																   
							';
                    $i++;
                }
            }
            else{
                echo '<tr><td class="center" colspan="5">没有数据</td></tr>';
            }
            ?>
        </table>
    </div>

    <div  class="tablelist">
        <table>
            <tr>
                <table class="indextable">
                    <tr style="border-top:6px solid #9bccee;">
                        <td align="left">项目板块-图片</td>
                        <div style="margin:10px auto; float:right;">
                            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
                        </div>


                        <table>
                            <tr style="border-top:6px solid #9bccee;">
                                <th width="5%">ID</th>
                                <th width="15%">排序</th>
                                <th width="25%">图片</th>
                                <th width="15%">链接地址</th>
                                <th width="15%">是否显示</th>
                                <th width="15%">操作</th>
                            </tr>
                            <?php
                            $i = ($page-1)*$pagesize+1;
                            foreach ($project_pics as $project_pic){

                                echo '<tr>
                                            <td class="center">'.$i.'</td>
                                            <td>
                                                 <input onchange="picweightchange(' . $project_pic['id'] . ','.$project_pic['weight'].',$(this).val())" width="20px;" type="number" placeholder="'.$project_pic['weight'].'">
                                            </td>		
                                  <td class="center"><img src="'.$HTTP_PATH.$project_pic['picurl'].'" width="150px" height="100px" alt=""></td>
                                  <td class="center">'.$project_pic['http'].'</td>'?>
                                  <td>
                                     <div class="ui toggle checkbox">
                                        <div onclick="picchange_switch_status('<?php echo $project_pic["id"]?>')" class="search_checkbox">
                                            <input type="checkbox" value="<?php echo $project_pic["id"];?>" <?php if($project_pic["display"]==1) echo 'checked';else echo ''?>>
                                            <label style="color:#999;">开启</label>
                                        </div>
                                     </div>
                                  </td>
                                <?php echo '<td class="center">
                                                  <a class="editinfo" href="javascript:edit_picture('.$project_pic['id'].')"><img title="" src="images/edit.png" width="20px;" /></a>
                                                 <a class="delete" href="javascript:delete_picture('.$project_pic['id'].')"><img title="" src="images/del.png" width="20px;" /></a>
                                              </td>
                                </tr>';
                                $i++;
                            } ?>
                        </table>
                    </tr>
        </table>
    </div>
        <!-- 分页 -->
        <div id="pagelist">
            <!-- 分页信息 -->
            <div class="pageinfo">
                <span>共<?php echo $project_pics_count;?>条，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pagesize, $project_pics_count, $pagecount);
            }
            ?>
        </div>


    <!--add content start-->
    <div id="addcontent" style="display:none; margin-left:20px;margin-top:20px;">
        <div id="formlist">
            <p>
                <label>链接地址</label>
                <input  class="text-input input-length-25" name="title0" id="http" value=""/>
                <span class="warn-inline">* 请以http://或https://开头,若无图片链接,请输入：#</span>
            </p>
            <p>
                <label>图片上传</label>
                <input id="picurl1"  name="picurl1" type="hidden"/>
                <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
                <span class="warn-inline">*</span>
                <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
            </p>
            <p style="padding-left:150px;"><img id="val1" src="" width="300px" height="200px" alt="" /></p>
            <p>
                <label>&nbsp;</label>
                <input type="submit" id="btn_submit_add" class="btn_submit" value="确　定" />
            </p>
        </div>

    </div>
    <!--add content end-->
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>