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

    $id = isset($_GET['id'])? safeCheck($_GET['id']): 0;
    $info = array();
    $adress = "";
    if(!empty($id)){
        $info = Web_distribution::getInfoById($id);
        $adress = $info['address'];
    }




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
			$(function(){
                $(document).on("click", "#searchXY", function () {

                    var address = $('input[name="address1"]').val();
                    if(address1.length==0){
                        layer.tips("请输入地址后重试","#address1");
                        return false;
                    }

                    layer.open({
                        type: 2,
                        title: '检索地址信息',
                        shadeClose: true,
                        shade: 0.3,
                        area: ['600px', '540px'],
                        content: 'address_search.php?address_detail='+address
                    });
                });

                //编辑器初始化
                var ckeditor = CKEDITOR.replace('detail1', {
                    toolbar: 'Common',
                    forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                    filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                    filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
                });
				$("#submit_edit").click(function(){
				    var address_posttion_currt="<?php echo $info['lat'].','.$info['lng'] ?>";
				    var address_currt="<?php echo $info['address'] ?>";
					var title = $('#title1').val();
					var detail = ckeditor.getData();
					var address = $('#address1').val();
					var picurl = $('#pic_path1').val();
					var tel = $('#tel').val();
                    var address_position = $('#address_position').val();
                    var province = $('#address_one').val();
                    var city = $('#address_two').val();
					//var http = $('#http').val();

					var reg1 = (/^400-([0-9]){1}([0-9-]{7})$/);
					var reg2 = (/^800-([0-9]){1}([0-9-]{7})$/);
					var reg3 = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
					var reg4 = /0\d{2,3}-\d{7,8}/;
//alert(province);
					if(title == ''){
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
					if(address == ''){
						layer.alert('地址不能为空', {icon: 5});
						return false;
					}
                    if(address!=address_currt && address_position==address_posttion_currt){
					    layer.alert('请重新检索地址',{icon:5});
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
                    } else if((tel.length !=7) && (!reg1.test(tel)) && (!reg2.test(tel))&& (!reg3.test(tel)) && (!reg4.test(tel))){
						layer.msg('请填写正确的电话',{icon:5});
						return false;
					}

					// if (http == '') {
					// 	layer.alert('链接地址不能为空', {icon: 5});
					// 	return false;
					// }

					$.ajax({
						type : 'post',
						data : {
							id	 : '<?php echo $id;?>',
							title : title,
							address : address,
                            province:province,
                            city   : city,
							detail : detail,
							// http   : http,
							picurl : picurl,
                            address_position:address_position,
                            tel :tel
						},
						dataType : 'json',
						url  : 'content_distribution_do.php?act=edit',
						success : function(data){
							var code = data.code;
							var msg  = data.msg;
							if(code == 1){
								layer.alert(msg, {icon: 6}, function(index){
									parent.location.reload();
								});
							}else{
								layer.alert(msg, {icon: 5});
							}
						}
					});
					return false;
				});
			});

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
			<!--edit picture start-->
			<div id="editpicture" style="margin-left:20px;margin-top:20px;">
				<div id="formlist">
					<p>
						<label>标题：</label>
						<input type="text" class="text-input input-length-50" name="title1" id="title1" value="<?php echo $info['title'];?>" />
						<span class="warn-inline">* </span>
					</p>
					<div>
                    <p>
                        <label>选择地址：</label>
						<span>
                        <select onchange="selectOnchang(this)" id="address_one">
                            <!--                    <option value="0">请选择省份</option>-->
                            <?php
//                            echo '<option value="0">请选择省份</option>';

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
//							echo '<option value="0">请选择市/区</option>';
                            $current_city_name = District::getAddressNameById($info['city'])["name"];
                            echo '<option value="'.$info['city'].'">'.$current_city_name.'</option>';
                            $childList =Table_district::getAddressType(0,27);
                            foreach ($childList as $city){
                                echo '<option value="' . $city["id"] . '">' . $city["name"] . '</option>';
                            }

                            ?>

                        </select>
						</span>

						<span class="warn-inline" style="position:absolute;margin-left: 775px;">* </span>
				</div>
                    </p>


					<p>
						<label>分销地址：</label>
						<input type="text" class="text-input input-length-50" name="address1" id="address1"  value="<?php echo $info['address'];?>" />
						<span class="warn-inline">* </span>
                        <span style="padding-left:20px"><input type="button" class="btn-handle" href="javascript:" id="searchXY" value="检索坐标"/> </span>

                        <!--						<div id="l-map" style="height: 400px;width: 800px;margin-left: 155px;margin-top: 50px;"></div>-->
					</p>
                    <p>
                        <label>坐标：</label>
                        <input type="text" class="text-input input-length-50" name="address_position" id="address_position" value="<?php
                        echo $info['lat'].",".$info['lng'];
                        ?>" />
						<span class="warn-inline">* </span>

                    </p>

					<p>
						<label>详情：</label>
					<div  style="padding-left:150px;color:red;">建议上传图片大小：500px * 300px</div>
                        <div style="margin-top:40px;margin-left:10%;margin-right:10%">
						    <textarea style="padding:5px;width:60%;height:70px;" name="content" cols=200 name="detail1" id="detail1" ><?php echo $info['detail'];?></textarea>
                        </div>
					    <span class="warn-inline">* </span>
                    </p>


<!--                    <p>-->
<!--                        <label>链接地址：</label>-->
<!--                        <input type="text" class="text-input input-length-50" name="http" id="http" value="--><?php //echo $info['http'];?><!--" />-->
<!--						<span class="warn-inline">* </span>-->
<!--                        <span class="warn-inline">请以http://或https://开头</span>-->
<!--                    </p>-->

					<p>
						<label>图片上传：</label>
                        <input type="hidden" id="pic_path1"  value="<?php echo $info['picurl'];?>" />
                        <input type="file" name="upload_file1" id="upload_file1" style="display:none" onchange="file_selected(1);" />        <!-- 添加上传文件 -->
                        <button class="ui mini gray button" onclick="selectfile(1)" id="btn_selectfile1">点击选择图片</button>
						<span class="warn-inline">* </span>
                        <input type="button" onclick="file_upload(1);" class="ui mini blue button" style="margin-top:10px;" value="上 传">
                    <p  style="padding-left:150px;color:red;">图片大小：300px * 200px</p>

                    <p style="padding-left:150px;"> <img id="upimg1" src="<?php echo $HTTP_PATH.$info['picurl'];?>"   width="300px" height="200px" alt=""  /></p>
                    <p>
                        <label>电话：</label>
                        <input type="text" class="text-input input-length-50" name="tel" id="tel" value="<?php echo $info['tel'];?>" />
                        <span class="warn-inline">* </span>
                    </p>
                    <p>
						<label>&nbsp;</label>
						<input type="submit" id="submit_edit" class="ui orange mini button" value="提　交" />
						<input type="hidden" id="distributionid" />
					</p>
				</div>
			</div>
	</body>
</html>
