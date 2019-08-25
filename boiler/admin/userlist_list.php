<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 18:05
 */
require_once('admin_init.php');
require_once('admincheck.php');

$FLAG_LEFTMENU  = 'userlist_list';
$FLAG_TOPNAV    = 'seletion';

//初始化
$page = 1;
$pageSize  = 15;
//默认进来获取锅炉用户的列表，$attrid=20
if(empty($_GET['attrid'])){
    $attrid = 20;
}else{
    $attrid = $_GET['attrid'];
}
//$attrid = safeCheck($_GET['attrid'])?safeCheck($_GET['attrid']):20;



$sel_vender = isset($_GET['sel_vender'])?safeCheck($_GET['sel_vender']):0;
$sel_usertype = isset($_GET['sel_usertype'])?safeCheck($_GET['sel_usertype']):0;
$sel_userstate = isset($_GET['sel_userstate'])?safeCheck($_GET['sel_userstate']):0;
$totalcount  = Case_tpl::getListByAttridandSel($attrid, $sel_vender,$sel_usertype,$sel_userstate,$page, $pageSize, $count = 1);
$pagecount = ceil($totalcount / $pageSize);
$page      = getPage($pagecount);
$rows      = Case_tpl::getListByAttridandSel($attrid,$sel_vender,$sel_usertype,$sel_userstate,$page, $pageSize, $count = 0);

//print_r($rows);
//这里是列表菜单中固定展示的属性对应的id值，因为属性名称可变，每次拿id去取
//$fixedids = array();
//if(20 == $attrid){
//    $fixedids = array(20,21,22);
//}elseif(23 == $attrid){
//    $fixedids = array(23,24,25);
//}

$nums = $totalcount-($page-1)*$pageSize;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title> 选型方案 - 用户名单 </title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/boxy.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript">
        $(function(){
            //查找
            $('#search').click(function(){
                var sel_vender = $('#sel_vender').val();
                var sel_usertype = $('#sel_usertype').val();
                // alert(usertype);
                // return false;
                var sel_userstate = $('#sel_userstate').val();
                location.href  = "userlist_list.php?attrid="+'<?php echo $attrid;?>'+"&sel_vender="+sel_vender+"&sel_usertype="+sel_usertype+"&sel_userstate="+sel_userstate;
            });
            //添加
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title: '添加',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['950px', '650px'],
                    content: 'userlist_add.php?attrid='+<?php echo $attrid?>//传到userlist_add.php
                });
            });
            //属性编辑
            $('#attradd').click(function(){
                layer.open({
                    type: 2,
                    title: '属性编辑',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['700px', '320px'],
                    content: 'attr_list.php?attrid='+<?php echo $attrid?>//传到userlist_add.php
                });
            });
            //修改
            $(".editinfo").click(function(){
                var thisid = $(this).parent('td').find('#id').val();
                layer.open({
                    type: 2,
                    title: '修改',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['950px', '650px'],
                    content: 'userlist_edit.php?tplid='+thisid
                });
            });
            //详情
            $(".info").click(function(){
                var thisid = $(this).parent('td').find('#id').val();
                layer.open({
                    type: 2,
                    title: '详情',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['700px', '620px'],
                    content: 'userlist_info.php?tplid='+thisid
                });
            });
            //删除
            $(".delete").click(function(){
                var id = $(this).parent('td').find('#id').val();
                layer.confirm('确认删除吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                tplid:id
                            },
                            dataType : 'json',
                            url : 'userlist_do.php?act=del',
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
        });

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('selection_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="userlist_list.php">选型方案</a> &gt;  用户名单</div>
        <div id="handlelist">
            <a href="userlist_list.php?attrid=20"><input type="button" class="btn-handle" <?php if($attrid == 23) echo'style="background-color: white;color: black"'?>  value="厂家用户名单"></a>
            <a href="userlist_list.php?attrid=23"><input type="button" class="btn-handle" <?php if($attrid == 20) echo'style="background-color: white;color: black"'?>   value="公司用户名单"></a>
            <input type="button" class="btn-handle fr" href="javascript:" id="attradd" value="属性编辑">
            <input type="button" class="btn-handle fr" href="javascript:" id="add" value="添加">
                锅炉厂家
            <select id="sel_vender" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = Dict::getListByParentid(1);
                if($list)
                    foreach($list as $thisValue){
                        $selected = '';
                        if($thisValue['id'] == $sel_vender)
                            $selected = 'selected';
                        echo '<option value="'.$thisValue['id'].'" '.$selected.'>'.$thisValue['name'].'</option>';
                    }
                ?>
            </select>
                用户类型
            <select id="sel_usertype" class="select-handle">
                <option value="0">全部</option>
                <?php
                $list = Projectcase_type::getList();
                if($list)
                    foreach($list as $listValue){
                        $selected = '';
                        if($listValue['id'] == $sel_usertype)
                            $selected = 'selected';
                        echo '<option value="'.$listValue['id'].'" '.$selected.'>'.$listValue['name'].'</option>';
                    }
                ?>
            </select>
                是否是典型用户
                <select id="sel_userstate" class="select-handle">
                    <option value="0">全部</option>
                    <?php
                    foreach ($ARRAY_userstate_type as $key => $val)
                    {
                        $selected = "";
                        if($key == $sel_userstate) $selected = "selected='selected'";
                        echo "<option value='{$key}' {$selected} >{$val}</option>";
                    }
                    ?>
                </select>
                <input type="button" class="btn-handle" href="javascript:" id="search" value="查询">
        </div>
        <div class="tablelist">
            <table>
                <tr>

                    <!--<th>锅炉用户名称</th>
                    <th>锅炉类别</th>
                    <th>项目和地址</th>-->
                    <?php
                        if(23 == $attrid) {
//                            $html = '<th>康达用户id</th>';
//                            foreach ($fixedids as $l => $fixedid) {
////                         foreach ($fixedids as $fixedid){
//                                $attrinfo = Case_attr::getInfoById($fixedid);
//                                $html .= '<th>' . $attrinfo[0]["name"] . '</th>';
//                            }
//                            echo $html;
                            echo '<th>公司用户id</th>
                            <th>公司用户名称</th>
                            ';
                        }
                    //-------------锅炉用户页面------------------
                    elseif(20 == $attrid) {
                        echo '<th>厂家用户id</th>
                            <th>厂家用户名称</th>
                            ';
                    }
                        ?>
                    <th>锅炉厂家</th>
                    <th>用户类型</th>
                    <th>是否是典型用户</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!empty($rows)){
                    foreach($rows as $row){
                        //获取管理员账号
                        /*try {
                            $admin       = new Admin($row['operator']);
                            $account     = $admin->getAccount();
                        }catch(MyException $e){
                            $account = '-';
                        }*/
//                     $type = Case_tplcontent::getInfoByAttridandtplid($fixedids[1],$row['id']);
////                    $address = Case_tplcontent::getInfoByAttridandtplid($fixedids[2],$row['id']);

                        $vender=null;
                        $usertype=null;
                        $userstate=null;
                     //锅炉厂家
                        $ven_content=Dict::getInfoById($row['vender']);
                        $vender=$ven_content?$ven_content['name']:'';
                      //用户类型
                        $user_content=Projectcase_type::getInfoById($row['usertype']);
                        $usertype=$user_content?$user_content[0]['name']:'';

                        //用户状态
//                        print_r($ARRAY_userstate_type());
                        $userstate=$ARRAY_userstate_type[$row['userstate']];

                        echo '<tr>
                                <td class="center">'.$nums.'</td>
                                <td class="center">'.$row['name'].'</td>
                                <td class="center">' . $vender . '</td>
                                <td class="center">' . $usertype . '</td>
                                <td class="center">' . $userstate . '</td> 
                                <td class="center">
                                    <a class="info" href="javascript:void(0)">详情</a> 
                                    <a class="editinfo" href="javascript:void(0)">修改</a>
                                    <a class="delete" href="javascript:void(0)">删除</a>
                                    <input type="hidden" id="id" value="'.$row['id'].'"/>                                  
                                </td>
                            </tr>';
                        $nums--;
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
                echo dspPages(getPageUrl(), $page, $pageSize, $totalcount, $pagecount);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.inc.php');?>
</body>
</html>
