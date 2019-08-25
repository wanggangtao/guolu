<?php
/**
 * 用户列表  user_list.php
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_TOPNAV    = "weixincontent";
$FLAG_LEFTMENU  = 'weixin_customer_info';
$params=array();


//初始化
$count     = weixin_customer::getCount($params);
$pageSize  = 10;
$pagecount = ceil($count / $pageSize);
$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;
$rows      = weixin_customer::getList($params);

$miyuInfo = Baseconfig::getInfoByKey(weixin_customer::BASECONFIG_KEY);
if(empty($miyuInfo)){
    $btn_value = 1;
    $btn = "添加秘语";

}else{
    $btn_value = 2;
    $btn = "修改秘语";
    $miyu = $miyuInfo['value'];

} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>信息管理 - 服务人员管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            layer.config({
                extend: 'extend/layer.ext.js'
            });
            //查找
            //添加
            $('#add').click(function(){
               var btn_value =  $(this).attr("btn_value");
               if(btn_value == 1){
                   layer.open({
                       type: 2,
                       title: '秘语',
                       shadeClose: true,
                       shade: 0.3,
                       area: ['500px', '250px'],
                       content: 'weixin_customer_miyu_add.php'
                   });
               }else{
                   layer.open({
                       type: 2,
                       title: '秘语',
                       shadeClose: true,
                       shade: 0.3,
                       area: ['500px', '250px'],
                       content: 'weixin_customer_miyu_edit.php'
                   });

               }


            });

            $('.editinfo').click(function(){
                var id = $(this).parent('td').find('#cid').val();

                layer.open({
                    type: 2,
                    title: '修改客服信息',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '350px'],
                    content: 'weixin_customer_edit.php?id='+id
                });
        });

            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#cid').val();
                layer.confirm('确认删除该客服吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:id
                            },
                            dataType : 'json',
                            url : 'weixin_customer_do.php?act=dels',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            parent.location.reload();
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

        });

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('weixin_menu_inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="user_list.php">信息管理</a> &gt;客服人员管理</div>
        <div id="handlelist">
            <label><?php echo $miyu?></label>
            <input type="button" btn_value = "<?php echo $btn_value?>" class="btn-handle "  href="javascript:" id="add" value="<?php echo $btn?>">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>昵称</th>
                    <th>备注</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){
                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['nickname'].'</td>
                           
                                <td class="center">'.$row['remark'].'</td>
                            
                                <td class="center">
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="cid" value="'.$row['id'].'"/>
                                </td>
                            </tr>';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="7">没有数据</td></tr>';
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
                echo dspPages(getPageUrl(), $page, $pageSize, $count, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>