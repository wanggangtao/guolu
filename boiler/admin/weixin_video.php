<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-24
 * Time: 12:59
 */

require_once('admin_init.php');
require_once('admincheck.php');

$id = !empty($_GET['id'])?$_GET['id']:die('没有这个产品');
$index = 1;

$page = 1;
$pageSize  = 15;
$count     = weixin_video::getCountByProductId($id,$page,$pageSize);
$pagecount = ceil($count / $pageSize);
//$videoList  = weixin_video::getListByProudctId($id);
$page      = getPage($pagecount);
$videoList  = weixin_video::getListByProudctIdAndPage($id,$page,$pageSize);
//$nums = $count-($page-1)*$pageSize;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>锅炉列表 - 产品管理 - 产品中心 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script>
        $(function () {
            //返回
            $('#back').click(function () {
                window.location.href = 'guolu_list.php?position=2';
            });

            //添加
            $('#add').click(function () {
                layer.open({
                    type: 2,
                    title: '添加视频',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '550px'],
                    content: 'weixin_video_add.php?id=<?php echo $id;?>'
                });
            });


            //修改权重
            var uploadIndex;
            $('.weight').blur(function () {
                var content = $(this).val();
                var id = $(this).parent('td').parent('tr').find('#id').val();
                var old_weight = $(this).parent('td').parent('tr').find('#old_weight').val();
                if(content == ''){
                    layer.close(uploadIndex);
                    layer.tips('权重为空', '.weight');
                    return false;
                }

                if(content != "" && content != old_weight){
                    uploadIndex = layer.load(1);
                    $.ajax({
                        type        : 'POST',
                        data        : {
                            id:id,
                            weight:content
                        },
                        dataType : 'json',
                        url : 'weixin_video_do.php?act=updateWeight',
                        success : function(data){
                            var code = data.code;
                            var msg  = data.msg;
                            switch(code){
                                case 1:
                                    layer.close(uploadIndex);
                                    layer.alert(msg, {icon: 6,shade: false}, function(index){

                                        parent.location.reload()
                                    });
                                    break;
                                default:
                                    layer.alert(msg, {icon: 5});
                            }
                        }
                    });
                }

            });

            //修改
            $('.edit_video').click(function () {
                var id = $(this).parent('td').parent('tr').find('#id').val();
                layer.open({
                    type: 2,
                    title: '添加视频',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '550px'],
                    content: 'weixin_video_edit.php?id='+id
                });
            });


            //删除
            $('.del_video').click(function () {
                var id = $(this).parent('td').parent('tr').find('#id').val();
                layer.confirm('确认删除吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_video_do.php?act=del',
                            success : function(data){
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
                    }, function(){}
                );
            });



        })
    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('products_menu.inc.php');?>
    <div id="maincontent">
        <div id="position" >
            <label style="color: #0c72b8;font-weight: bold">视频管理</label>
            <a id= 'back' href="#" style="font-weight: bold;float: right;">返回</a>
        </div>
        <br/>

        <div id="handlelist">
            <span style="color: #0c72b8"><?php echo $_GET['n']."-".$_GET['v']?></span>
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th width="10%">序号</th>
                    <th width="15%">视频标题</th>
                    <th width="40%">封面/海报</th>
                    <th width="15%">上传时间</th>
                    <th width="10%">排序</th>
                    <th width="10%">操作</th>
                </tr>
                <?php foreach ($videoList as $video){?>
                <tr style="height: 100px">
                    <input type="hidden" id="id" value="<?php echo $video['id']?>"/>
                    <input type="hidden" id="old_weight" value="<?php echo $video['weight']?>"/>
                    <td style="text-align: center"><?php echo $index ?></td>
                    <td style="text-align: center"><?php echo $video['name'] ?></td>
                    <td style="text-align: center"><img style="width: 100%;height: 100px;object-fit: contain;" src="<?php echo $HTTP_PATH.$video['imgpath'] ?>" /></td>
                    <td style="text-align: center"><?php echo date('Y-m-d H:i',$video['add_time']) ?></td>
                    <td style="text-align: center"><input class="weight" type="number"  value="<?php echo $video['weight'];?>"></td>
                    <td style="text-align: center"><a class="edit_video" href="#">修改</a><a class="del_video" href="#">删除</a></td>
                </tr>
                <?php $index++;} ?>
            </table>
        </div>

        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $count;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                echo dspPages(getPageUrl(), $page, $pageSize, $count, $pagecount);
            }
            ?>
        </div>



        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>
