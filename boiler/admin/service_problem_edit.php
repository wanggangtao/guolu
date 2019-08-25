<?php
/**
 * 修改知识点  service_problem_edit.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/27
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = safeCheck($_GET["id"]);

$problemInfo = Service_problem::getInfoById($id);

$type= $problemInfo['type'];
$url_type= $problemInfo['url_type'];

//echo $type;
//echo $url_type;
//print_r($problemInfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link href="css/semantic.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#video_pic").hide();
            var url_type=<?php echo $url_type;?>;
            if(4==url_type){
                $("#video_pic").show();
                $('#upimg0').hide();
                $('#upvideo0').show();

            }else{
                $('#upvideo0').hide();
                $('#upimg0').show();
            }
            $("#type").change(function () {
                var value = $(this).val();
                if(1==value){
                    $('.rel').each(function () {
                        $(this).show();
                    });
                }else{
                    $('.rel').each(function () {
                        $(this).hide();
                    });
                }
            });

            $("#acc_type").change(function () {
                var value = $(this).val();
                if(4==value){
                    $('.video_pic').show();
                }
                else {
                    $('.video_pic').hide();
                }
            });

            $("#btn_submit").click(function () {

                var id=<?php echo $id; ?>;
                var pic_path0="";
                var pic_path1="";
                var pic_path2="";
                var keyword = $('input[name="keyword"]').val();
                var content = $('#content').val();
                var category = $('#category').val();
                var type = $('#type').val();
                var url_type = 0;
                if(keyword == ''){
                    layer.tips('关键词不能为空', '#keyword');
                    return false;
                }
                if(content == ''){
                    layer.tips('内容不能为空', '#content');
                    return false;
                }
                if(type==1){
                    pic_path0 = $('#pic_path0').val();
                    url_type = $('#acc_type').val();
                    pic_path1= $('#pic_path1').val();
                    pic_path2= $('#pic_path2').val();
                }


                $.ajax({
                    type: 'post',
                    url: 'service_problem_do.php?act=edit',
                    data: {
                        id:id,
                        pic_path0: pic_path0,
                        keyword: keyword,
                        content: content,
                        category: category,
                        type: type,
                        url_type:url_type,
                        pic_path1: pic_path1,
                        video_cover_path:pic_path2
                    },
                    dataType: 'json',
                    success: function (data) {
//                        alert(data);
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
                    error: function () {
                        alert("请求失败");
                    }
                 });
            });

        });
        function file_upload(value){
            if($('#upload_file'+value).val() == ''){
                layer.tips('请选择文件', '#upload_file'+value, {tips: 3});
                return false;
            }
            var uploadUrl = 'all_upload.php?type=service_file&id='+ value;//处理文件
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
                            var types=$("#acc_type").val();
                            if(value==0){
                                if(types==4){
                                    $('#upimg0').hide();
                                    $('#upvideo0').show();
                                    $('#upvideo0').attr('src',http_path+msg);


                                }else  if(types==3){
                                    $('#upvideo0').hide();
                                    $('#upimg0').show();
                                    $('#upimg0').attr('src',http_path+msg);

                                }
                            }else{
                                $('#upimg'+value).attr('src',http_path+msg);
                            }
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
</head>
<body>
<div id="addpicture" >
    <div id="formlist">
        <p>
            <label>关键词</label>
            <input type="text" class="text-input input-length-30" value="<?php echo $problemInfo['keyword']; ?>" name="keyword" id="keyword" />
            <span class="warn-inline" id="s_admin_account">* </span>
        </p>
        <p>
            <label>类别</label>
            <select class="select-option" id="category">
                <?php
                $categoryList = Service_category::getPageList();
                if(!empty($categoryList))
                {
                    $selt = "selected";
                    foreach ($categoryList as $item)
                    {
                        if($item['id'] == $problemInfo['category'])
                            echo "<option value='{$item["id"]}' selected='{$selt}'>{$item["name"]}</option>>";
                        else
                            echo "<option value='{$item["id"]}'>{$item["name"]}</option>";
                    }
                }
                ?>
            </select>
            <span class="warn-inline" id="s_content">* </span>
        </p>
        <p>
            <label>形式</label>
            <select class="select-option" id="type">
                <option value="2" <?php if($type==2) echo "selected=\"selected\""; ?> >文字</option>
                <option value="1" <?php if($type==1) echo "selected=\"selected\""; ?> >其他</option>
            </select>
            <span class="warn-inline" id="s_content">* </span>
        </p>

        <div class="rel" id="accessory_type" style="<?php if($type==2) echo "display:none"; else echo "display:block"; ?>">
            <p>
                <label>附件类型</label>
                <select class="select-option" id="acc_type">
                    <option value="0" <?php if($url_type==0) echo "selected=\"selected\""; ?> >无</option>
                    <option value="3" <?php if($url_type==3) echo "selected=\"selected\""; ?> >图片</option>
                    <option value="4" <?php if($url_type==4) echo "selected=\"selected\""; ?> >视频</option>
                </select>
<!--                <span class="warn-inline" id="s_content">* </span>-->
            </p>
        </div>


        <div class="form-group rel" style="<?php if($type==2) echo "display:none"; else echo "display:block"; ?>" id="rel">
            <p>
                <label>附件上传：</label>
            <div style="float:left;width:150px;height:90px; overflow:hidden; border: 1px solid #ccc;">
                <div id="showimg0" style="display:block">
                        <video id="upvideo0" src="<?php if($problemInfo['url']) echo $HTTP_PATH.$problemInfo['url']; ?>" height="90px" width="150px;" controls loop></video>
                        <img id="upimg0" src="<?php if($problemInfo['url']) echo $HTTP_PATH.$problemInfo['url']; ?>"  width="150px;">
                </div>
            </div>
            <div style="float:right;width:150px;">
                <input type="hidden" id="pic_path0" value="<?php if($problemInfo['url']) echo $problemInfo['url']; ?>" />
                <input type="file" name="upload_file0" id="upload_file0" style="display:none" onchange="file_selected(0);" />        <!-- 添加上传文件 -->
                <button class="ui mini gray button" onclick="selectfile(0)" id="btn_selectfile0"><?php if($problemInfo['url']) echo $problemInfo['url']; else echo "点击选择图片"; ?></button>
<!--                <a style="color:red;font-weight:bold;">*</a>-->
                <input type="button" onclick="file_upload(0);" class="ui mini blue button" style="margin-top:10px;" value="上 传">
            </div>
            </p>
        </div>

        <div class="video_pic" style="<?php if($type==2) echo "display:none"; else echo "display:block"; ?>" id="video_pic">
            <p>
                <label>视频封面图上传：</label>
            <div style="float:left;width:150px;height:90px; overflow:hidden; border: 1px solid #ccc; ">
                <div id="showimg2" style="display:block">
                    <img id="upimg2" height="90px" src="<?php if($problemInfo['video_cover']) echo $HTTP_PATH.$problemInfo['video_cover']; ?>"  width="150px;"/>
                </div>
            </div>
            <div style="float:right;width:150px;">
                <input type="hidden" id="pic_path2" value="<?php if($problemInfo['video_cover']) echo $problemInfo['video_cover']; ?>" />
                <input type="file" name="upload_file2" id="upload_file2" style="display:none" onchange="file_selected(2);" />        <!-- 添加上传文件 -->
                <button class="ui mini gray button" onclick="selectfile(2)" id="btn_selectfile2"><?php if($problemInfo['video_cover']) echo $problemInfo['video_cover']; else echo "点击选择图片"; ?></button>
                <!--                <a style="color:red;font-weight:bold;">*</a>-->
                <input type="button" onclick="file_upload(2);" class="ui mini blue button" style="margin-top:10px;" value="上 传">
            </div>
            </p>
        </div>

        <div class="form-group rel" style="<?php if($type==2) echo "display:none"; else echo "display:block"; ?>" id="cover_div">
            <p>
                <label>封面图上传：</label>
            <div style="float:left;width:150px;height:90px; overflow:hidden; border: 1px solid #ccc;">
                <div id="showimg1" style="display:block">
                    <img id="upimg1" height="90px" src="<?php if($problemInfo['cover']) echo $HTTP_PATH.$problemInfo['cover']; ?>" width="150px"/>
                </div>
            </div>
            <div style="float:right;width:150px;">
                <input type="hidden" id="pic_path1" value="<?php if($problemInfo['cover']) echo $problemInfo['cover']; ?>" />
                <input type="file" name="upload_file1" id="upload_file1" style="display:none" onchange="file_selected(1);" />        <!-- 添加上传文件 -->
                <button class="ui mini gray button" onclick="selectfile(1)" id="btn_selectfile1"><?php if($problemInfo['cover']) echo $problemInfo['cover']; else echo "点击选择图片"; ?></button>
<!--                <a style="color:red;font-weight:bold;">*</a>-->
                <input type="button" onclick="file_upload(1);" class="ui mini blue button" style="margin-top:10px;" value="上 传">
            </div>
            </p>
        </div>
        <p>
            <label>内容</label>
            <textarea rows="10" cols="" style="width:200px;height:150px" id="content" name="content" class="text-area"><?php echo $problemInfo['content']?></textarea>
            <span class="warn-inline" id="s_content">* </span>
        </p>
        <p>
            <label>&nbsp;</label>
            <input type="submit" id="btn_submit" class="ui orange mini button" style="margin-left:30px;" value="提　交" />
        </p>
    </div>
</div>
</body>
</html>