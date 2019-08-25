<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/17
 * Time: 20:21
 */

require_once("../init.php");

$weixin = new weixin();

$weixin->createMenu();
print_r($weixin->createMenu());

echo "ok";