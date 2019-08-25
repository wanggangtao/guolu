<?php
/**
 * 首页内容  content_indexcon.php
 *
 * @version       v0.01
 * @create time   2018/9/13
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$type = isset($_GET['type'])?safeCheck($_GET['type']):0;

$FLAG_TOPNAV	= "webcontent";
if($type == 1){
    $FLAG_LEFTMENU  = "scale";
    $titlename = '规模实力';
}elseif($type == 2){
    $FLAG_LEFTMENU  = "devhis";
    $titlename = '发展历程';
}

//初始化
$count     = Webcontent::getPageList(1, 10, 0, $type);
$pageSize  = 15;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);
$rows      = Webcontent::getPageList($page, $pageSize, 1, $type);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title><?php echo $titlename; ?> - 首页 - 前端内容管理 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">

        $(function(){
            //添加
            $('#add1').click(function(){

                var type= '<?php echo $type; ?>';
                layer.open({
                    type: 1,
                    title: '添加<?php echo $titlename; ?>',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['650px', '300px'],
                    content: $('#addcontent1')
                });
            });
            $('#add2').click(function(){

                var type= '<?php echo $type; ?>';
                layer.open({
                    type: 1,
                    title: '添加<?php echo $titlename; ?>',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['650px', '300px'],
                    content: $('#addcontent1')
                });
            });
            //修改
            $(".editinfo").click(function(){
                var type= '<?php echo $type; ?>';
                var thisid = $(this).parent('td').find('#aid').val();

                layer.open({
                    type: 2,
                    title: '修改<?php echo $titlename; ?>',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '300px'],
                    content: 'content_indexcon_edit.php?id='+thisid+'&type='+type
               //     content: $('#editcontent')
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'content_do.php?act=content_del',
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
            });

            $("#btn_submit_add1").click(function(){
                var title = $('#title1').val();
                var title_supplement = $('#title_supplement1').val();

                var subtitle = $('#subtitle1').val();
                var content = $('#content1').val();
                if(title == ''){
                    layer.alert('标题不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(title.length >5){
                    layer.alert('标题过长', {icon: 5,shade: false});
                    return false;
                }
                if(title_supplement.length >2){
                    layer.alert('标题备注过长', {icon: 5,shade: false});
                    return false;
                }
                if(subtitle == ''){
                    layer.alert('副标题不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(subtitle.length >12){
                    layer.alert('副标题过长', {icon: 5,shade: false});
                    return false;
                }
                if(content == ''){
                    layer.alert('内容不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(content.length >38){
                    layer.alert('内容过长', {icon: 5,shade: false});
                    return false;
                }
                $.ajax({
                    type : 'post',
                    data : {
                        title : title,
                        title_supplement : title_supplement,

                        subtitle : subtitle,
                        content : content,
                        type : '<?php echo $type;?>'
                    },
                    dataType : 'json',
                    url  : 'content_do.php?act=content_add',
                    success : function(data){
                        var code = data.code;
                        var msg  = data.msg;

                        if(code > 0){
                            layer.alert(msg, {icon: 6,shade: false}, function(index){
                                parent.location.reload();
                            });
                        }else{
                            layer.alert(msg, {icon: 5});
                        }
                    }
                });
                return false;
            });
            $("#btn_submit_add2").click(function(){
                var title = $('#title2').val();
                var content = $('#content2').val();
                if(title == ''){
                    layer.alert('标题不能为空',{icon: 5,shade: false});
                    return false;
                }
                if(title.length >5){
                    layer.alert('标题过长', {icon: 5,shade: false});
                    return false;
                }
                if(content == ''){
                    layer.alert('内容不能为空', {icon: 5,shade: false});
                    return false;
                }
                if(content.length >38){
                    layer.alert('内容过长', {icon: 5,shade: false});
                    return false;
                }
                $.ajax({
                    type : 'post',
                    data : {
                        title : title,
                        content : content,
                        type : '<?php echo $type;?>'
                    },
                    dataType : 'json',
                    url  : 'content_do.php?act=content_add',
                    success : function(data){
                        var code = data.code;
                        var msg  = data.msg;

                        if(code > 0){
                            layer.alert(msg, {icon: 6,shade: false}, function(index){
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
        function weightchange(id,source_value, now) {
            if (source_value != now){
                $.ajax({
                    type        : 'POST',
                    data        : {
                        id : id,
                        order: now
                    },
                    dataType :    'json',
                    url :         'content_do.php?act=content_order',
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

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('content_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="content_indexpic.php">前端内容管理</a> &gt; 首页 &gt; <?php echo $titlename; ?></div>
        <div id="handlelist">
<!--            <input type="button" class="btn-handle fr" href="javascript:" id="orderAll" value="排序">-->
            <?php
            if($type==1){

            ?>
                <input type="button" class="btn-handle fr" href="javascript:" id="add1" value="添加">
            <?php
            }
            else{
            ?>
                <input type="button" class="btn-handle fr" href="javascript:" id="add2" value="添加">
                <?php
            }

            ?>

        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>标题</th>
                    <?php
                    if($type == 1) {
                        ?>
                        <p>
                        <th>标题备注</th>
                        <th>副标题</th>
                        <?php
                    }
                    ?>
                    <th>内容</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    foreach($rows as $row){
                        echo '<tr>
                                <td class="center">'.$row['title'].'</td>
                                ';
                        if($type == 1) {
                            echo'
                       
                                <td class="center">'.$row['title_supplement'].'</td>
                                <td class="center">'.$row['subtitle'].'</td>
                        ';
                        }
                        echo '
                                <td class="center">'.$row['content'].'</td>
                                <td class="center">
                                    <input onchange="weightchange('.$row['id'].','.$row['order'].',$(this).val())" width="10px;" type="number" placeholder="'.$row['order'].'">
                                </td>
                                
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="aid" value="'.$row['id'].'"/>
                                    <input type="hidden" id="subtitle" value="'.$row['subtitle'].'"/>
                                </td>
                            </tr>';
                    }
                }else{
                    echo '<tr><td class="center" colspan="4">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $count;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pagesize, $count, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>

    <!--add content start-->
    <div id="addcontent1" style="display:none; margin-left:20px;margin-top:20px;">
        <div id="formlist">
            <?php
            if($type == 1) {
            ?>
            <p>
                <label>标题</label>
                <input type="text" class="text-input input-length-50" name="title1" id="title1" value=""/>
                <span class="warn-inline">* 不得超过5个字 </span>
            </p>

                <p>
                    <label>标题备注</label>
                    <input type="text" class="text-input input-length-50" name="title_supplement1"
                           id="title_supplement1" value=""/>
                    <span class="warn-inline">  不得超过2个字 </span>
                </p>

                <p class="subtitle">
                    <label>副标题</label>
                    <input type="text" class="text-input input-length-50" name="subtitle0" id="subtitle1" value=""/>
                    <span class="warn-inline">* 不得超过12个字 </span>
                </p>


            <p>
                <label>内容</label>
                <input type="text" class="text-input input-length-50" name="content1" id="content1" value=""/>
                <span class="warn-inline">* 不得超过38个字 </span>
            </p>
            <p>
                <label>&nbsp;</label>
                <input type="submit" id="btn_submit_add1" class="btn_submit" value="确　定" />
            </p>
            <?php
            }
            else {
                ?>
                <p>
                    <label>标题</label>
                    <input type="text" class="text-input input-length-50" name="title2" id="title2" value=""/>
                    <span class="warn-inline">* 不得超过5个字 </span>
                </p>

                <p>
                    <label>内容</label>
                    <input type="text" class="text-input input-length-50" name="content2" id="content2" value=""/>
                    <span class="warn-inline">* 不得超过38个字 </span>
                </p>
                <p>
                    <label>&nbsp;</label>
                    <input type="submit" id="btn_submit_add2" class="btn_submit" value="确　定" />
                </p>
                <?php
            }
            ?>

        </div>
    </div>
    <!--add content end-->


</div>
<?php include('footer.inc.php');?>
</body>
</html>