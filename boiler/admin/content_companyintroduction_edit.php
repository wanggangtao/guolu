<?php
/**
 * Created by kjb.
 * Date: 2018/12/6
 * Time: 22:36
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = Web_introduction::getInfoById($id);
if(empty($info)){
    echo '非法操作';
    die();
}
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
            $('#btn_sumit').click(function(){
              //  var kind = $('#kind').val();
                var tel =$('#tel').val();
                var content = ckeditor.getData();

                var content_length =  content.replace(/<[^>]+>/g,"");
                // alert(content_length);
                // alert(content_length.length);
                var reg1 = (/^400-([0-9]){1}([0-9-]{7})$/);
                var reg2 = (/^800-([0-9]){1}([0-9-]{7})$/);
                var reg3 = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
                var reg4 = /0\d{2,3}-\d{7,8}/;
                var reg5 = /[\u4e00-\u9fa5]/;

//                if(kind == ''){
//                    layer.alert('题目不能为空', {icon:5});
//                    return false;
//                }
                if(content == ''){
                    layer.msg('内容不能为空', {icon:5});
                    return false;
                }
                if(content_length.length> 140){
                    layer.msg('内容过长', {icon:5});
                    return false;
                }
                if(tel ==''){
                    layer.msg('请填写电话！', {icon:5});
                    return false;
                }else if((tel.length !=7) && (!reg1.test(tel)) && (!reg2.test(tel))&& (!reg3.test(tel)) && (reg5.test(tel))){
                    layer.msg('请填写正确的电话',{icon:5});
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        id   : '<?php echo $id;?>',
               //     	kind  : kind,
                    	content : content,
                        tel   :tel
                    },
                    dataType :    'json',
                    url :         'content_companyintroduction_do.php?act=introductionedit',
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
<!--    <p>-->
<!--        <label>标题</label>-->
<!--        <input type="text" class="text-input input-length-30" name="kind" id="kind" value="--><?php //echo $info['kind'];?><!--"/>-->
<!--        <span style="text-align:center;color:red;">*</span>-->
<!--    </p>-->
    <p>
        <label style="width: 140px;">内容</label>
	    <div style="margin-top:40px;margin-left:10%;margin-right:10%">
	        <textarea style="padding:5px;width:60%;height:70px;" name="content" cols=200 id="content" ><?php echo $info['content'];?></textarea>
	    </div>

    </p>
    <p  style="text-align:center;color:red;">* 不得超过140个字</p>
    <p>
        <label>咨询电话</label>
        <input type="text" class="text-input input-length-30" name="tel" id="tel" value="<?php echo $info['tel'];?>"/>
        <span style="text-align:center;color:red;">*</span>
    </p>

    <p>
        <label></label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="保 存" style="display:block;margin:0 auto"/>
    </p>
</div>
</body>
</html>