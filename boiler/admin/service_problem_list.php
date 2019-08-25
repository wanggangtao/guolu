<?php
/**
 * 管理员列表  admin_list.php
 *
 * @version       v0.03
 * @create time   2014-8-3
 * @update time   2016/3/26
 * @author        hlc jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$content = "";

if(isset($_POST["content"]))
{
    $content = trim($_POST["content"]);
}



$FLAG_TOPNAV	= "knowledge";
$FLAG_LEFTMENU  = 'service_problem_list';
$params=array();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>管理员 - 管理设置 - 管理系统 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            //添加管理员
            $('#addadmin').click(function(){
                layer.open({
                    type: 2,
                    title: '添加知识点',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '600px'],
                    content: 'service_problem_add.php'
                });
            });


            $('.addAfter').click(function(){

                var code = $(this).parent('td').find('#code').val();

                layer.open({
                    type: 2,
                    title: '添加规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '600px'],
                    content: 'services_label_list_add.php?code='+code
                });
            });



            $('.findAfter').click(function(){

                var code = $(this).parent('td').find('#code').val();

                location.href = "services_label_list.php?before="+code;
            });


            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.open({
                    type: 2,
                    title: '编辑知识点',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['500px', '600px'],
                    content: 'service_problem_edit.php?id='+thisid
                });
            });

            //删除
            $(".delete").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除该知识点吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:thisid
                            },
                            dataType : 'json',
                            url : 'service_problem_do.php?act=del',
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

            $(".editinfo").mouseover(function(){
                layer.tips('修改', $(this), {
                    tips: [4, '#3595CC'],
                    time: 500
                });
            });
            $(".delete").mouseover(function(){
                layer.tips('删除', $(this), {
                    tips: [4, '#3595CC'],
                    time: 500
                });
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
    <?php include('knowledge_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="admingroup.php">知识库管理</a> &gt; 管理员设置</div>
        <div id="handlelist">
            <?php
            //初始化
            //初始化
            $totalcount= Service_problem::getCount($params);
            $shownum   = 15;
            $pagecount = ceil($totalcount / $shownum);
            $page      = getPage($pagecount);
            $params['page']=$page;
            $params['pageSize']=$shownum;
            $rows      = Service_problem::getList($params);
            ?>
            <input type="button" class="btn-handle" href="javascript:" id="addadmin" value="添 加">
            <span class="table_info">共<?php echo count($rows);?>条数据</span>
            <div>
            </div>
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <th>代码</th>
                    <th>关键词</th>
                    <th>内容</th>
                    <th>添加时间</th>
                    <th>规则配置</th>
                    <th>操作</th>
                </tr>
                <?php

                $i=1;
                //如果列表不为空
                if(!empty($rows)){
                    foreach($rows as $row){

                        echo '<tr>
											<td>'.$row['code'].'</td>
											<td class="center">'.$row['keyword'].'</td>
											<td class="center">'.$row['content'].'</td>
											<td class="center">'.date("Y-m-d H:i:s",$row['addtime']).'</td>
											<td class="center">
											    <button class="btn-handle findAfter">查看后置条件</button>
											    <input type="hidden" id="code" value="'.$row['code'].'"/>
                                            </td>
											<td class="center">
												<a class="editinfo" href="javascript:void(0)"><img src="images/dot_edit.png"/></a> 
												<a class="delete" href="javascript:void(0)"><img src="images/dot_del.png"/></a>
												<input type="hidden" id="aid" value="'.$row['id'].'"/>
											</td>
										</tr>
									';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>

        <div id="pagelist">
            <div class="pageinfo">
                <span class="table_info">共<?php echo $totalcount;?>条数据，共<?php echo $pagecount;?>页</span>
            </div>
            <?php
            if($pagecount>1){
                $url = Env::getPageUrl();
                echo dspPages($url, $page, $shownum, $totalcount, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>