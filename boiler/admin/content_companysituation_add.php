<?php
/**
 * 添加公司动态
 * Created by kjb.
 * Date: 2018/12/5
 * Time: 14:34
 */
require_once('admin_init.php');
require_once('admincheck.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){
        	//编辑器初始化
            var ckeditor = CKEDITOR.replace('content', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });
            // laydate({
            //     elem: '#addtime', //需显示日期的元素选择器
            //     event: 'click', //触发事件
            //     format: 'YYYY-MM-DD hh:mm:ss', //日期格式
            //     max:laydate.now() ,//可选最大日期为当前时间
            //     istime: false, //是否开启时间选择
            //     isclear: true, //是否显示清空
            //     istoday: true, //是否显示今天
            //     issure: true, //是否显示确认
            //     festival: true, //是否显示节日
            //     choose: function(dates){ //选择好日期的回调
            //     }
            // });

            $('#btn_sumit').click(function(){
                var type = $('#type').val();
                var title = $('#title').val();
                var http = $('#http').val();
                var count = $('#count').val();
                var picurl = $('#picurl1').val();
                var if_top=$("input[name='if_top']:checked").val();
                var content = ckeditor.getData();
                if(type == '' || type == 0){
                    layer.alert('类型不能为空',{icon: 5,shade: false});
                    return false;
                }
                if(title == ''){
                    layer.alert('标题不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(picurl==''){
                    layer.alert('请选择图片',{icon: 5,shade: false});
                    return false;
                }
                $.ajax({
                    type        : 'POST',
                    data        : {
                    	type   : type,
                    	title  : title,
                    	http      : http,
                    	picurl   : picurl,
                    	content : content,
                    },
                    dataType :    'json',
                    url :         'content_companysituation_do.php?act=situationadd',
                    success :     function(data){
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
                    }
                });
            });

        });

        function ajaxUpload(value){
            if($('#upload_file'+value).val() == ''){
                layer.tips('请选择文件', '#file'+value, {tips: 3});
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
    </script>
</head>
<body>
<div id="formlist">

    <p>
        <label>类型</label>
        <select class="select-option" id="type">
			<option value="0"> --类型--</option>
	        <?php
			foreach ($ARRAY_Companysituation_type as $key => $val){
				$selected = "";
				if($key == $type){
					$selected = "selected";
				}
				echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
			}
			?>
		</select>
        <span class="warn-inline">* </span>
    </p>
    <p>
        <label>标题</label>
        <input type="text" class="text-input input-length-30" name="title" id="title"/>
        <span class="warn-inline">* </span>
    </p>
    <p>
       <label style="width: 140px;">文章内容</label>
        <div  style="padding-left:150px;color:red;">建议上传图片大小：500px * 300px</div>
        <div style="margin-top:40px;margin-left:10%">
	        <textarea style="padding:5px;width:60%;height:70px;" name="content" cols=200 id="content" ></textarea>
	    </div>
    </p>
    <p>
        <label>链接地址</label>
        <input type="text" class="text-input input-length-30" name="http" id="http"/>
        <span class="warn-inline">请以http://或https://开头</span>
    </p>
    <p>
        <label>图片上传</label>
        <input id="picurl1"  name="picurl1" type="hidden"/>
        <input id="upload_file1" class="upfile_btn" type="file" name="upload_file1" style="height:24px;"/>
        <span class="warn-inline">* </span>
        <input type="button" class="upfile_btn btn-handle" onclick="return ajaxUpload(1);" value="上传"/>
    </p>
    <p  style="padding-left:150px;color:red;">图片大小：300px * 200px</p>
    <p style="padding-left:150px;"><img id="val1" src="" width="300px" height="200px" alt="" /></p>
<!--    <p>-->
<!--        <label>浏览次数</label>-->
<!--        <input type="text" class="text-input input-length-30" name="count" id="count" value="0"/>-->
<!--    </p>-->
<!--    <p>-->
<!--        <label>发布时间</label>-->
<!--        <input type="text" class="text-input input-length-30" name="addtime" id="addtime" readonly/>-->
<!--    </p>-->
<!--    <p>-->
<!--        <label>是否置顶</label>-->
<!--        <div class="warn-inline">-->
<!--        <input type="radio" name="if_top"  value="1">是-->
<!--        <input type="radio" name="if_top"  value="0" id="if_top_id">否-->
<!--        <span style="color:#F00">* </span>-->
<!--        </div>-->
<!--    </p>-->
    <p>
        <label>　　</label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>