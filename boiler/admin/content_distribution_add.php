<?php
	/**
	 * 渠道分销 content_distribution_edit.php
	 *
	 * @version       v0.01
	 * @create time   2018.11.21
	 * @update time   
	 * @author        dlk
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');

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
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript"
                src="http://webapi.amap.com/maps?v=1.3&key=d7c5cdb73a595b9ee6556c08ff37abf9"></script>
		<script type="text/javascript">
			$(function() {
                $(document).on("click", "#searchXY", function () {

                    var address = $('input[name="address0"]').val();
                    if (address.length == 0) {
                        layer.tips("请输入地址后重试", "#address0");
                        return false;
                    }

                    layer.open({
                        type: 2,
                        title: '检索地址信息',
                        shadeClose: true,
                        shade: 0.3,
                        area: ['600px', '540px'],
                        content: 'address_search.php?address_detail=' + address
                    });
                });

                //编辑器初始化
                var ckeditor = CKEDITOR.replace('detail0', {
                    toolbar: 'Common',
                    forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                    filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                    filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
                });
                $("#submit_add").click(function () {
                    var title = $('#title0').val();
                    var detail = ckeditor.getData();
                    var address = $('#address0').val();

                    var picurl = $('#picurl1').val();
                    var tel = $('#tel').val();
                    var address_position = $('#address_position').val();

					var reg1 = (/^400-([0-9]){1}([0-9-]{7})$/);
					var reg2 = (/^800-([0-9]){1}([0-9-]{7})$/);
					var reg3 = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
					var reg4 = /0\d{2,3}-\d{7,8}/;

                    var value2=document.getElementById("address_two");
                    var index2=value2.selectedIndex;
                    var city=value2.options[index2].value;

                    var value1=document.getElementById("address_one");
                    var index1=value1.selectedIndex;
                    var province=value1.options[index1].value;
                    if (title == '') {
                        layer.alert('标题不能为空', {icon: 5});
                        return false;
                    }
					if (province == 0) {
						layer.alert('省份不能为空', {icon: 5});
						return false;
					}
					if (city == 0) {
						layer.alert('城市不能为空', {icon: 5});
						return false;
					}
                    if (address == '') {
                        layer.alert('地址不能为空', {icon: 5});
                        return false;
                    }

					if (address_position == '') {
						layer.alert('坐标不能为空', {icon: 5});
						return false;
					}
					if (detail == '') {
						layer.alert('详情不能为空', {icon: 5});
						return false;
					}
					if (picurl == '') {
						layer.alert('图片不能为空', {icon: 5});
						return false;
					}
					if(tel ==''){
                        tel = "暂无";
					}else if(tel == "暂无"){
                        tel = "暂无";
                    }else if((tel.length !=7) && (!reg1.test(tel)) && (!reg2.test(tel))&& (!reg3.test(tel)) && (!reg4.test(tel))){
						layer.msg('请填写正确的电话',{icon:5});
						return false;
					}


                    $.ajax({
                        type: 'post',
                        data: {
                            title: title,
                            address: address,
                            detail: detail,
                            picurl: picurl,
                            address_position: address_position,
                            province:province,
                            city:city,
                            tel:tel
                        },
                        dataType: 'json',
                        url: 'content_distribution_do.php?act=add',
                        success: function (data) {
                            var code = data.code;
                            var msg = data.msg;
                            if (code > 0) {
                                parent.location.reload();
                            } else {
                                layer.alert(msg, {icon: 5});
                            }
                        }
                    });
                    return false;
                });
            })

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
        <script>
            function selectOnchang(obj){
                var value = obj.options[obj.selectedIndex].value;

                $("#address_two").html("<option value='0'>请选择市/区</option>");
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

                                var html = "<option value='0'>请选择市/区</option>";
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
        </script>
		<style type="text/css">
	        #r-result{
	            width:100%;
	            z-index: 1000;
	            top: 140px;
	            left: 165px;
	            font-size: 14px;
	            background: #fff;
	            width: 300px;
	            height: 24px;
	            border: 1px solid #ddd;
	            padding: 11px;
	        }
	        .tangram-suggestion-main{
	        z-index: 9999999;
	        }
	    </style>
	</head>
	<body>

			<!--add picture start-->
			<div id="addpicture" style=" margin-left:20px;margin-top:20px;">
				<div id="formlist">
					<p>
						<label>标题：</label>
						<input type="text" class="text-input input-length-50" name="title0" id="title0" />
						<span class="warn-inline">* </span>
					</p>
<div>
                   <p>
                       <label>选择地址：</label>
					   <span>
						   <select   onchange="selectOnchang(this)" id="address_one">
                           <!--                    <option value="0">请选择省份</option>-->
							   <?php
							   echo '<option value="0">请选择省份</option>';

							   $provinces=District::getAddressType(3,0);
							   if(!empty($provinces)){
								   foreach ($provinces as $province){

									   $upId=$province['id'];
									   $name=$province['name'];
									   if($name=="陕西省")
										   echo '<option value="' . $upId . '">' . $name . '</option>';
								   }
							   }
							   ?>
                       </select>
                       <select id="address_two">

                           <?php

						   echo '<option value="0">请选择市/区</option>';

						   ?>

                       </select>
					   </span>

						<span class="warn-inline" style="float: none">* </span>
					   </div>

					</p>
					<p>
						<label>分销地址：</label>
						<input type="text" class="text-input input-length-50" name="address0" id="address0" />
						<span class="warn-inline">* </span>
                        <span style="padding-left:20px"><input type="button" class="btn-handle" href="javascript:" id="searchXY" value="检索坐标"/> </span>

<!--						<div id="l-map" style="height: 400px;width: 800px;margin-left: 155px;margin-top: 50px;"></div>-->
					</p>
                    <p>
                        <label>坐标：</label>
                        <input type="text" class="text-input input-length-50" name="address_position" id="address_position"  />
						<span class="warn-inline">* </span>
                    </p>

					<p>
						<label>详情：</label>
					<div  style="padding-left:150px;color:red;">建议上传图片大小：500px * 300px</div>
					    <div style="margin-top:40px;margin-left:10%;margin-right:10%">
						     <textarea style="padding:5px;width:70%;height:70px;" name="detail0" id="detail0" value=""></textarea>
						</div>
						<span class="warn-inline">* </span>
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

                    <p>
                        <label>电话：</label>
                        <input type="text" class="text-input input-length-50" name="tel" id="tel"  />

                    </p>


					<p>
						<label>&nbsp;</label>
						<input type="submit" id="submit_add" class="ui orange mini button" style="margin-left:30px;" value="提　交" />
					</p>
				</div>
			</div>
			<!--add picture end-->

	</body>
</html>
