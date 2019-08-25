<?php
/**
 * 微信产品列表 weixin_product_describe.php
 *
 * @version       v0.01
 * @create time   2018/3/17
 * @update time
 * @author        ww
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 *  * 产品说明
 */

require_once('admin_init.php');
//require_once('admincheck.php');

$id =  isset($_GET["id"])?safeCheck($_GET["id"]):1;
$dictinfo = Dict::getInfoById($id);
if(empty($dictinfo)){
    echo '非法操作';
    die();
}
$valuelist = Dict::getListByCat($id,2);
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
        <title>产品说明</title>
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
                    if(value){
                        var url = 'weixin_product_vender_describe.php?'+ value;
                        window.location.href = url;
                    }
                });
            });
        </script>
    </head>
    <body>
    <div id="app">
        <div class="product-wrap">
            <div class="product-title">产品品牌</div>
            <div class="product-con flex">
                <?php
                   if(!empty($valuelist)){
                      foreach($valuelist as $row){ ?>
                <a class="product-item" onclick="" value="<?php echo 'name='.$row['name'].'&&id='.$row['id'] ;?>">
                    <div class="product-item-img">
                        <img src="<?php echo $HTTP_PATH.$row['pic']; ?>" alt="">
                    </div>
                    <div class="product-item-text"><?php echo $row['name'];?></div>
                </a>
                <?php } }?>
            </div>
        </div>
    </div>
    </body>

</html>