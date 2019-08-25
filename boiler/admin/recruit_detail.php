<?php
/**
 * 添加
 *
 * @version       v0.01
 * @create time   2018/11/21
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = isset($_GET['id'])? safeCheck($_GET['id']): 0;
$info = array();
if(!empty($id)){
    $info = Web_recruit::getInfoById($id);
}else{
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
        	// 编辑器初始化
            var ckeditor = CKEDITOR.replace('content', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });


            var ckeditor_need = CKEDITOR.replace('need', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });



        });
    </script>
</head>
<body>
<div id="formlist">
    <p>
        <label>招聘岗位</label>
        <input type="text" class="text-input input-length-30" name="station" id="station" value="<?php  echo $info['station']  ?>" readonly/>
<!--        <span class="warn-inline">* </span>-->
    </p>
    <p>
        <label>学历要求</label>
        <input type="text" class="text-input input-length-30" name="education" id="education" value="<?php  echo $ARRAY_Educition_type[$info['education']]  ?>" readonly/>
<!--        <span class="warn-inline">* </span>-->
    </p>
    <p>
        <label>工作经验</label>
        <input type="text" class="text-input input-length-30" name="experience" id="experience" value="<?php  echo $ARRAY_Experience_type[$info['experience']]  ?>" readonly/>
<!--        <span class="warn-inline">* </span>-->
    </p

<br>
    <p>
        <label>招聘人数</label>
        <input type="text" class="text-input input-length-30" name="number" id="number" value="<?php  echo $info['number']  ?>" readonly/>
<!--        <span class="warn-inline">* </span>-->
    </p>

    <p>
        <label>薪资</label>
        <input type="text" class="text-input input-length-30" name="salary" id="salary" value="<?php  echo $ARRAY_Salary_type[$info['salary']]  ?>" readonly/>
<!--        <span class="warn-inline">* </span>-->
    </p
<br>
    <p>
        <label style="width: 140px;">职位描述</label>
	    <div style="margin-top:40px;margin-left:10%">
	        <textarea style="padding:5px;width:60%;height:70px;" readonly name="content" cols=200 id="content" ><?php  echo $info['describe']?></textarea>
	    </div>
    </p>

    <p>
        <label style="width: 140px;">任职要求</label>
        <div style="margin-top:40px;margin-left:10%">
            <textarea style="padding:5px;width:60%;height:70px;" readonly name="need" cols=200 id="need" ><?php  echo $info['need']?></textarea>
        </div>
    </p>

    <p><input type="button" class="btn btn-null-primary" onclick="back()"
           value="返回">
    </p>

    <script>
        function back() {
            parent.location.href="recruit_list.php";
        }
    </script>
<!---->
<!--    <p>-->
<!--        <label>　　</label>-->
<!--        <input type="submit" id="btn_submit" class="btn_submit" value="提　交" />-->
<!--    </p>-->
</div>
</body>
</html>