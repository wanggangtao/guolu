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
        	//编辑器初始化
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



            $('#btn_submit').click(function(){

                var id=<?php echo $id;  ?>;
                var type = $('#type').val();
                var education = $('#education').val();

                var number = $('#number').val();
                var salary = $('#salary').val();

                var content = ckeditor.getData();
                var need= ckeditor_need.getData();
                var experience= $('#experience').val();
                if(type == '' || type == 0){
                    layer.tips('招聘岗位不能为空', '#type');
                    return false;
                }
                if(education == ''||education == 0){
                    layer.tips('学历要求不能为空', '#education');
                    return false;
                }
                if(number == ''){
                    layer.tips('找聘人数不能为空', '#number');
                    return false;
                }
                if(salary == ''||salary == 0){
                    layer.tips('薪资不能为空', '#salary');
                    return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {

                        id:id,
                    	type   : type,
                    	education  : education,
                        number      : number,
                        salary  : salary,
                        need  : need,
                    	content : content,
                        experience:experience
                    },
                    dataType :    'json',
                    url :         'recruit_do.php?act=edit',
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
    </script>
</head>
<body>
<div id="formlist">

<!--    <p>-->
<!--        <label>招聘岗位</label>-->
<!--        <select class="select-option" id="type">-->
<!--			<option value="0"> 招聘岗位</option>-->
<!--	        --><?php //
//			foreach ($ARRAY_Postion_type as $key => $val){
//				$selected = "";
//				if($key == $info['station']){
//					$selected = "selected";
//				}
//				echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
//			}
//			?>
<!--		</select>-->
<!--        <span class="warn-inline">* </span>-->
<!--    </p>-->
    <p>
        <label>招聘岗位</label>
        <input type="text" class="text-input input-length-30" name="type" id="type" value="<?php  echo $info['station']  ?>"/>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label>学历要求</label>
        <select class="select-option" id="education">
            <option value="0"> 学历要求</option>
            <?php
            foreach ($ARRAY_Educition_type as $key => $val){
                $selected = "";
                if($key == $info['education']){
                    $selected = "selected";
                }
                echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
            }
            ?>
        </select>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label>工作经验</label>
        <select class="select-option" id="experience">
            <?php
            foreach ($ARRAY_Experience_type as $key => $val){
                $selected = "";
                if($key == $info['experience']){
                    $selected = "selected";
                }
                echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
            }
            ?>
        </select>
        <span class="warn-inline">* </span>
    </p>


    <p>
        <label>招聘人数</label>
        <input type="text" class="text-input input-length-30" name="number" id="number" value="<?php  echo $info['number']  ?>"/>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label>薪资</label>
        <select class="select-option" id="salary">
            <option value="0"> 薪资</option>
            <?php
            foreach ($ARRAY_Salary_type as $key => $val){
                $selected = "";
                if($key == $info['salary']){
                    $selected = "selected";
                }
                echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
            }
            ?>
        </select>
        <span class="warn-inline">* </span>
    </p>

    <p>
        <label style="width: 140px;">职位描述</label>
	    <div style="margin-top:40px;margin-left:10%">
	        <textarea style="padding:5px;width:60%;height:70px;" name="content" cols=200 id="content" ><?php  echo $info['describe']?></textarea>
	    </div>
    </p>

    <p>
        <label style="width: 140px;">任职要求</label>
        <div style="margin-top:40px;margin-left:10%">
            <textarea style="padding:5px;width:60%;height:70px;" name="need" cols=200 id="need" ><?php  echo $info['need']?></textarea>
        </div>
    </p>


    <p>
        <label>　　</label>
        <input type="submit" id="btn_submit" class="btn_submit" value="提　交" />
    </p>
</div>
</body>
</html>