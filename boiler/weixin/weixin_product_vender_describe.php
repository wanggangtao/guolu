<?php
/**
 * 微信产品品牌商品列表 weixin_product_vender_describe.php
 *
 * @version       v0.01
 * @create time   2018/3/17
 * @update time
 * @author        ww
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 *  * 产品说明
 */

require_once('admin_init.php');
$name =  isset($_GET["id"])?safeCheck($_GET["name"],0):0;
$id =  isset($_GET["id"])?safeCheck($_GET["id"]):0;
$info = Smallguolu::getInfoByVenderId($id);

$proidArray = array();
foreach ($info as $key => $row){
    array_push($proidArray, $row[$key]['proid']);
}

$pInfoArray = array();
//$sortArray = array();
foreach ($proidArray as $proid){
    $product =  Products::getInfoById($proid);
    array_push($pInfoArray,$product);
//    array_push($sortArray,$product['addtime']);
}
//array_multisort($sortArray,SORT_DESC,$pInfoArray);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>产品型号</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/query.js"></script>
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/common.js"></script>
    <script type="application/javascript">
        $(function () {
           $(document).on('click','.product-item',function () {
               var value = $(this).attr('value');
               if(value != null){
                   var url = 'weixin_product_detail.php?'+value;
                   window.location.href = url;
               }
           });
        });
    </script>
</head>

<body>
<div id="app">
    <div class="product-wrap">
        <div class="product-title"><?php echo $name; ?></div>
        <div class="product-con flex">
            <?php
            if(!empty($pInfoArray)){
                for($index = 0; $index<count($info); $index++){
                    $row = $pInfoArray[$index];
                    $rowInfo = $info[$index][$index];
            ?>
            <a class="product-item" onclick="" value="<?php echo 'id='.$rowInfo['id']; ?>">
                <div class="product-item-img product-model-img">
                    <img src="<?php echo  $HTTP_PATH.$row['img']; ?>" alt="">
                </div>
                <div class="product-item-text product-model-text"><?php echo $rowInfo['version']; ?></div>
            </a>
            <?php }}?>
        </div>
    </div>
</div>
</body>
</html>
