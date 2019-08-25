<?php
/**
 * 添加视频  weixin_video_add.php
 *
 * @version       v0.01
 * @create time   2018/5/28
 * @update time
 * @author        wzp
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = !empty($_GET['id'])?$_GET['id']:die('没有这个产品');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/lunbo.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="js/dragMove.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(){


            $('#video_input_click').click(function () {
                $('#video_input').val('');
                $('#video_input').trigger('click');
            });

            $('#placehold_input_click').click(function () {

                $('#placehold_input').val('');
                $('#placehold_input').trigger('click');
            });


            var uploadIndex;
            $(document).off('change','#video_input').on('change','#video_input',function (e) {
                // var $parentDiv = $($(this).parents()[0]);
                uploadIndex = layer.load(1);
                ajaxUploadVideo($(this));
                // $('#placehold_input_click').show();
                // $(this).val('');
            });

            $(document).off('change','#placehold_input').on('change','#placehold_input',function (e) {
                uploadIndex = layer.load(1);
                ajaxUploadVideoPalcehold($(this));
            });

            function videoCreate($src) {
                var video ='<div id="detail_div"><video class="detailVideo" src="../';
                video += $src;
                video += '" controls loop></video>'
                video += '<img class="detail_del" id="delVideo" src="../admin/images/dot_del.png"/></div>'
                return video;
            }

            $(document).on('click','#delVideo',function () {
                $parent = $($(this).parents()[0]);
                if($($parent.parents()[0]).find('div').length > 1){
                    $('#video_input_click').show();
                }
                $('#video_input').val('');
                $parent.remove();
            });


            function ajaxUploadVideoPalcehold($fileVideo){
                if($fileVideo.val() == ''){
                    layer.tips('请选择文件', $fileVideo, {tips: 3});
                    return false;
                }
                var uploadUrl = 'all_upload.php?type=guoluvideoplacehold';//处理文件
                $.ajaxFileUpload({
                    url           : uploadUrl,
                    fileElementId : 'placehold_input',
                    dataType      : 'json',
                    success       : function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.close(uploadIndex);
                                layer.msg('上传成功');
                                var img = placeCreate(msg);
                                $('#placehold_input_click').before(img);
                                // if($parent.find('div').length == 5){
                                $('#placehold_input_click').hide();
                                // }
                                break;
                            default:
                                layer.close(uploadIndex);
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error : function(data, status, e){
                        layer.alert(e);
                    }
                });
            }


            function ajaxUploadVideo($fileVideo){
                if($fileVideo.val() == ''){
                    layer.tips('请选择文件', $fileVideo, {tips: 3});
                    return false;
                }
                var uploadUrl = 'all_upload.php?type=guoluvideodetail';//处理文件
                $.ajaxFileUpload({
                    url           : uploadUrl,
                    fileElementId : 'video_input',
                    dataType      : 'json',
                    success       : function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.close(uploadIndex);
                                layer.msg('上传成功');
                                var video = videoCreate(msg);
                                $('#video_input_click').before(video);
                                // if($parent.find('div').length == 5){
                                $('#video_input_click').hide();
                                // }
                                break;
                            default:
                                layer.close(uploadIndex);
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error : function(data, status, e){
                        layer.alert(e);
                    }
                });
            }


            function placeCreate($src){
                var imageDiv = '<div id="detail_div"><img id="detail_placehold" class="detail_place" src="../';
                imageDiv +=$src;
                imageDiv += '"/><img class="detail_del" id="del_place" src="../admin/images/dot_del.png"/>';
                imageDiv +='<i class="drag"></i></div>';
                return imageDiv;
            }



            $(document).on('click','#del_place',function () {
                $parent = $($(this).parents()[0]);
                if($($parent.parents()[0]).find('div').length > 1){
                    $('#placehold_input_click').show();
                }
                $('#placehold_input').val('');
                $parent.remove();
            });


            function strlen(str){
                var len = 0;
                for (var i=0; i<str.length; i++) {
                    var c = str.charCodeAt(i);
                    //单字节加1
                    if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
                        len++;
                    }
                    else {
                        len+=2;
                    }
                }
                return len;
            }

            $('#btn_sumit').click(function(){


                var title =  $('#title').val();

                var video = $('.detailVideo').attr('src')?$('.detailVideo').attr('src'):'';
                video = video.replace('../','');
                var img =$('#detail_placehold').attr('src')?$('#detail_placehold').attr('src'):'';
                img = img.replace('../','');

                if( title == ''){
                    layer.tips('标题为空', '#title');
                    $("#title").focus();
                    return false;
                }

                if(title.length>5){
                        layer.tips('超过5个字符,', '#title');
                        $("#title").focus();
                        return false;
                }

                $.ajax({
                    type        : 'POST',
                    data        : {
                        product_id:<?php echo $id?>,
                        title:title,
                        video:video,
                        img:img
                    },
                    dataType :    'json',
                    url :         'weixin_video_do.php?act=addvideo',
                    success :     function(data){
                        var code = data.code;
                        var msg  = data.msg;
                        switch(code){
                            case 1:
                                layer.alert(msg, {icon: 6,shade: false}, function(index){
                                    parent.location.reload()
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
<div id="formlist" style="overflow-x: hidden">
    <p style="margin-left:-13%;padding-left: 10%;">
        <label><span style="color: red">*</span> 视频标题:</label>
        <input type="text"  class="text-input input-length-30" name="weight" id="title" value=""/>
    </p>

    <br/>
    <br/>
    <div class="luobo_div" style="padding-left: 10%;">
        <div class="luobo_lable_div">
            <label><b>海报/封面:</b></label>
        </div>

        <div class="luobo_add_div">
            <input id="placehold_input" type="file" accept="image/*" style="display: none" name="placehold_input"/>
            <div id="placehold_input_click" class="input_click"  ><img class="add_img" src="images/add_icon.png" ></div>
        </div>
    </div>

    <br/>
    <div class="tipDiv" style="padding-left: 10%;">
        <span style="color: #D5D5D5;font-size: 12px">建议图片大小375*300,最大不超过1M</span><br/>
    </div>
    <br/>
    <div class="luobo_div" style="padding-left: 10%;">
        <div class="luobo_lable_div">
            <label><b>视频上传:</b></label>
        </div>

        <div class="luobo_add_div">
            <input id="video_input" type="file" accept="video/mp4" style="display: none" name="video_file"/>
            <div id="video_input_click" class="input_click"><img class="add_img" src="images/add_icon.png" ></div>
        </div>
    </div>
    <br/>
    <div class="tipDiv" style="padding-left: 10%;">
        <span style="color: #D5D5D5;font-size: 12px">视频支持MP4,最大不超过20M</span><br/>
    </div>
    <div style="height: 30px"></div>


    <p>
        <label style="width: 200px;"></label>
        <input type="submit" id="btn_sumit" class="btn_submit" value="确　定" />
    </p>
</div>
</body>
</html>