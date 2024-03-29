<?php
/**
 * 站内信 message.php
 *
 * @version       v0.01
 * @create time   2018/07/01
 * @update time   2018/07/01
 * @author        dlk
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('init.php');
require_once('usercheck.php');
$pageSize  = 20;
$page = isset($_GET['page'])?safeCheck($_GET['page']):1;
$message_notopen_count = Message_info::getPageList(1, 10, 0, $USERId, 0, '',message_info::UNREAD);

$message_list = Message_info::getPageList($page, $pageSize, 1, $USERId, 0, '',message_info::UNREAD);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>站内信</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/main_two.css">
    <link rel="stylesheet" href="static/css/newlayui.css">
    <link rel="stylesheet" href="static/layui/css/layui.css">
    <link rel="stylesheet" href="static/layui/css/layui.css">
    <script type="text/javascript" src="static/js/jquery-1.4.4.min.js"></script>
</head>
<body class="body_1">
<div class="indexTop_all">
    <div class="indexTop">
        <div class="indexTop_1">
            <a href="home.php"><div class="indexTop_logo">
                    <img src="static/images/top_logo.png" alt="">
                </div></a>
            <a href="message_unread.php">
                <div class="indexTop_2 ">
                    <img src="static/images/emil.png" >
                    <img src="static/images/emil.png">
                    <span>站内信<?php if($message_notopen_count > 0)echo '<div class="num">'.$message_notopen_count.'</div>'; ?></span>
                </div></a>

            <a href="logout.php"><img src="static/images/backlogon.png" class="indexTop_4"><span class="indexTop_6"></span></a>
            <span class="indexTop_7"><?php echo $USERINFO['name']; ?>，欢迎您！</span>
        </div>

    </div>
    <div class="guolumain">
        <div class="guolumain_1">当前位置：<span>站内信</span></div><div class="clear"></div>
    </div>
    <div class="manageHRWJCont_middle">
    <!--有站内信显示一下内容-->

    <?php
    if($message_list){
        ?>

            <div class="manageHRWJCont_middle_left">
                <ul>
                    <a href="message_unread.php"><li class="manage_liCheck"> 未读</li></a>
                    <a href="message_readed.php"><li >已读</li></a>
                </ul>
            </div>


            <div class="manageHRWJCont_middle_middle">
                <div class="message_1">
                    <table>
                        <?php
                        foreach ($message_list as $thismsg){
                            $openstr = '';
                            if($thismsg['openflag'] == 0){
                                $openstr = '<img src="static/images/radio_check.png" class="message_3"/>';
                            }
                            echo '
                <tr>
              <td>
             <div class="message_2">
      
                    <a href="messagedetails.php?id='.$thismsg['id'].'"><div class="message_2_1">
                            '.$openstr.'
                            <div class="message_4">'.$thismsg['title'].'</div>
                            <div class="message_5">'.date('Y年m月d日 H:i:s',$thismsg['addtime']).'</div>
                        </div></a>
                </div>
            </td>
             </tr>
                
            ';
                        }
                        ?>
                    </table>

                    <div id="test1" class="GLthree"></div>
                    <script src="static/layui/layui.js"></script>
                    <script>
                        layui.use('laypage', function(){
                            var laypage = layui.laypage;

                            //执行一个laypage实例
                            laypage.render({
                                elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
                                ,count: <?php echo $message_count; ?> //数据总数，从服务端得到
                                ,curr: <?php echo $page; ?>
                                ,limit:<?php echo $pageSize; ?>
                                ,groups:3
                                ,layout:['count','prev','page','next']
                                ,jump: function(obj, first){
                                    //obj包含了当前分页的所有参数，比如：
                                    //console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                                    //console.log(obj.limit); //得到每页显示的条数
                                    //首次不执行
                                    if(!first){
                                        //do something
                                        location.href  = "message_unread.php?page="+obj.curr+"&pagesize="+obj.limit;
                                    }
                                }

                            });
                        });
                    </script>

                </div>
            </div>
            <div class="clear"></div>


        <?php
    }else{
        ?>
    <div class="manageHRWJCont_middle_left">
        <ul>
            <a href="message_unread.php"><li class="manage_liCheck"> 未读</li></a>
            <a href="message_readed.php"><li >已读</li></a>
        </ul>
    </div>


    <div class="manageHRWJCont_middle_middle">

        <!--没有站内信显示一下内容-->
        <div class="NOmessage" style="margin-left: 0px">
            <img src="static/images/wxj.png" class="NOmessage_1" >
            <div class="NOmessage_2">暂时没有未读消息哦～</div>
        </div>
    </div>
    <div class="clear"></div>
        <?php
    }
    ?>
    </div>
</body>
</html>