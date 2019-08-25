<?php


require_once('admin_init.php');
require_once('admincheck.php');
$params=array();
$params['status']=3;

$id=$_GET['id'];
$user_info=User_account::getInfoById($id);
$code=0;
$count=0;
if($user_info){
    $code=$user_info['product_code'];
    if($code){
        $params['code']=$code;
        $count     = repair_order::getCountByCode($params);

    }
}
$pageSize  = 5;

$pagecount = ceil($count / $pageSize);

$page      = getPage($pagecount);

$params['page']=$page;
$params['pageSize']=$pageSize;

if($code){
    $rows      = repair_order::getListByCode($params);
}else{
    $rows=array();
}

//print_r($rows);
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

</head>
<body>
<div id="formlist">
    <div id="maincontent">
        <div id="handlelist">
        </div>
        <div class="tablelist">
            <table>
                <tr>
                    <td width="8%" align="center">服务次数</td>
                    <td width="12%" align="center">客户联系电话</td>
                    <td width="10%" align="center">服务时间</td>
                    <td width="10%" align="center">服务类型</td>
                    <td width="20%" align="center">服务结果</td>
                    <td width="10%" align="center">服务人员</td>
                    <td align="center">备注</td>
                </tr>
                <?php
                if(!empty($rows)){
                    $i = ($page-1)*$pageSize+1;
                    foreach($rows as $row){

                        $name="";
                        $phone="";
                        $person=Repair_person::getInfoById($row['person']);
                        if($person){
                            $name=$person['name'];
                            $phone=$person['phone'];
                        }

                        $service_type = "";
                        if(isset($row['service_type'])){
                            $service_info = Service_type::getInfoById($row['service_type']);
                            if(isset($service_info['name'])){
                                $service_type = $service_info['name'];
                            }
                        }

                        echo '<tr>
                                <td class="center">'.$i.'</td>
                                <td class="center">'.$row['phone'].'</td>
                                <td class="center">'.date("Y-m-d",$row['finish_time']).'</td>
                                <td class="center">'.$service_type.'</td>
                                <td class="center">'.$row['result'].'</td>
                                <td class="center">'.$name."<br>".$phone.'</td>
                               <td class="center">'.$row['remarks'].'</td>
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
</body>
</html>